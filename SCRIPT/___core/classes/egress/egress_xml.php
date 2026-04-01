<?php

/**
 * This is a class for handling XML output
 * @package MMExtranet
 * @version 1.0
 */

class egress_xml implements i_egress {

    private static $egressFile = '';
    public static $xmlWriter = null;
    private static $exportRows = array();
    public static $totalRecords = 0;

    /**
     * runs validation checks on csv output parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        $config = workhorse::getConfig();

        self::$egressFile = driver::$currentExport['output']['location'];

        if (file_exists(self::$egressFile) === true) {
            unlink(self::$egressFile);
        } else {}

        fileio::createEmptyFile(self::$egressFile);

        if (file_exists(self::$egressFile) === true && is_readable(self::$egressFile) === true) {
            self::$xmlWriter = new XMLWriter();
            self::$xmlWriter->openMemory();
            self::$xmlWriter->setIndent(true);
            self::$xmlWriter->setIndentString('    ');
            self::$xmlWriter->startDocument('1.0', 'UTF-8');
        }
        else {
            log::logAlert(errors::$outputFileNotFoundorNotWritable);
            return false;
        }

        return true;
    }

    /**
     * flushes the output buffer to the destination
     * @access public
     */
    final public static function writeRows() {
        $numRows = count(self::$exportRows);

        if ($numRows > 0) {
            for ($i = 0; $i < $numRows; $i++) {
                self::traverseRowStructure(self::$exportRows[$i]);
            }
        } else {}

        file_put_contents(self::$egressFile, self::$xmlWriter->flush(true), FILE_APPEND);

        log::logAlert('Exported ' . $numRows . ' records');
        self::$totalRecords += $numRows;
        self::$exportRows = array();
    }

    /**
     * accepts and logs a row of data to be written out
     * @access public
     */
    final public static function ingestRow(array $row) {
        self::$exportRows[] = $row;
        $numRows = count(self::$exportRows);

        if ($numRows >= config::$exportDataBufferSize) {
            self::writeRows();
        } else {}
    }

    /**
     * flushes the unsaved data to the destination
     * @access public
     */
    final public static function writeUnsavedRows() {
        $numRows = count(self::$exportRows);

        if ($numRows > 0) {
            self::writeRows();
        } else {}
    }

    /**
     * flushes the unsaved data to the destination
     * @access public
     * @param array $array iterates over return data from download handler and writes XML structure to memory
     */
    final public static function traverseRowStructure(array $array) {
        $hasChildren = (isset($array['children']) && is_array($array['children']));

        if ($hasChildren === true) {
            self::$xmlWriter->startElement($array['name']);

            if (isset($array['attributes']) && is_array($array['attributes']) === true) {
                $temp = count($array['attributes']);
                $keys = array_keys($array['attributes']);
                $vals = array_values($array['attributes']);
                for ($i = 0; $i < $temp; $i++) {
                    $vals[$i] = trim($vals[$i]);
                    if ($vals[$i] !== '' && $vals[$i] !== null) {
                        self::$xmlWriter->writeAttribute($keys[$i], $vals[$i]);
                    } else {}
                }
            } else {}

            $temp = count($array['children']);
            for ($i = 0; $i < $temp; $i++) {
                self::traverseRowStructure($array['children'][$i]);
            }

            self::$xmlWriter->endElement();
        }
        else {
            $array['value'] = trim($array['value']);

            if ($array['value'] !== '' && $array['value'] !== null) {
                self::$xmlWriter->writeElement($array['name'], $array['value']);

                if (isset($array['attributes']) && is_array($array['attributes']) === true) {
                    $temp = count($array['attributes']);
                    $keys = array_keys($array['attributes']);
                    $vals = array_values($array['attributes']);
                    for ($i = 0; $i < $temp; $i++) {
                        $vals[$i] = trim($vals[$i]);
                        if ($vals[$i] !== '' && $vals[$i] !== null) {
                            self::$xmlWriter->writeAttribute($keys[$i], $vals[$i]);
                        } else {}
                    }
                } else {}
            } else {}
        }
    }

    /**
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        self::$xmlWriter = null;
        self::$egressFile = '';
        self::$exportRows = array();
        self::$totalRecords = 0;
    }
}
?>