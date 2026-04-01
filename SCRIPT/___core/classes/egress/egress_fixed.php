<?php

/**
 * This is a class for handling fixed-length output
 * @package MMExtranet
 * @version 1.0
 */

class egress_fixed implements i_egress {

    private static $egressFile = '';
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

        if (file_exists(self::$egressFile) === false || is_readable(self::$egressFile) === false) {
            log::logAlert(errors::$outputFileNotFoundorNotWritable);
            return false;
        } else {}

        if (isset(driver::$currentExport['output']['layout']) === false) {
            driver::$currentExport['output']['layout'] = array();
        } else {}

        if (count(driver::$currentExport['output']['layout']) === 0) {
            log::logAlert(errors::$outputFileNoFixedLayout);
            return false;
        } else {}

        return true;
    }

    /**
     * flushes the output buffer to the destination
     * @access public
     */
    final private static function writeRows() {
        $numRows = count(self::$exportRows);

        if ($numRows > 0) {
            fileio::writeFileData(self::$egressFile, implode("\r\n", self::$exportRows) . "\r\n", FILE_APPEND);
            log::logAlert('Exported ' . $numRows . ' records');
            self::$totalRecords += $numRows;
            self::$exportRows = array();
        } else {}
    }

    /**
     * accepts and logs a row of data to be written out
     * @access public
     * @param array $row accepts a data record from the download handler, converts it to fixed-length format and writes it to memory
     */
    final public static function ingestRow(array $row) {
        $numColumns = count(driver::$currentExport['output']['layout']);
        $columns = array_keys(driver::$currentExport['output']['layout']);
        $lengths = array_values(driver::$currentExport['output']['layout']);

        $newRow = '';
        for ($i = 0; $i < $numColumns; $i++) {
            if (array_key_exists($columns[$i], $row) === true) {
                $data = trim($row[$columns[$i]]);

                if (mb_strlen($data) !== $lengths[$i]) {
                    $data = str_pad($data, $lengths[$i], ' ', STR_PAD_LEFT);
                    $data = substr($data, (0 - $lengths[$i]));
                } else {}
            }
            else {
                $data = str_repeat(' ', $lengths[$i]);
            }

            $newRow .= $data;
        }

        $row = $newRow;

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
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        self::$egressFile = '';
        self::$exportRows = array();
        self::$totalRecords = 0;
    }
}
?>