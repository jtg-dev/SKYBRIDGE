<?php

/**
 * This is a class for handling CSV output
 * @package MMExtranet
 * @version 1.0
 */

class egress_csv implements i_egress {

    private static $egressFile = '';
    private static $exportRows = array();
    private static $numWrites = 0;
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

        if (file_exists(self::$egressFile) === true && is_readable(self::$egressFile) === false) {
            log::logAlert(errors::$outputFileNotFoundorNotWritable);
            return false;
        } else {}

        driver::$currentExport['output']['headerRow'] = driver::$currentExport['output']['headerRow'] ?? true;
        driver::$currentExport['output']['delimeter'] = driver::$currentExport['output']['delimeter'] ?? ',';
        driver::$currentExport['output']['quantifier'] = driver::$currentExport['output']['quantifier'] ?? '"';

        return true;
    }

    /**
     * flushes the output buffer to the destination
     * @access public
     */
    final private static function writeRows() {
        //header row if wanted
        if (self::$numWrites === 0 && driver::$currentExport['output']['headerRow'] === true) {
            file_put_contents(self::$egressFile, self::arrayToCSV(array_keys(self::$exportRows[0]), driver::$currentExport['output']['delimeter'], driver::$currentExport['output']['quantifier']) . "\r\n", FILE_APPEND);
        } else {}

        $numRows = count(self::$exportRows);
        if ($numRows > 0) {
            for ($i = 0; $i < $numRows; $i++) {
                self::$exportRows[$i] = self::arrayToCSV(array_values(self::$exportRows[$i]), driver::$currentExport['output']['delimeter'], driver::$currentExport['output']['quantifier']);
            }

            file_put_contents(self::$egressFile, implode("\r\n", self::$exportRows) . "\r\n", FILE_APPEND);

            log::logAlert('Exported ' . $numRows . ' records');
            self::$totalRecords += $numRows;
            self::$exportRows = array();
        } else {}

        ++self::$numWrites;
    }

    /**
     * accepts and logs a row of data to be written out
     * @access public
     * @param array $row accepts a data record from the download handler and writes it to memory
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
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        self::$egressFile = '';
        self::$exportRows = array();
        self::$numWrites = 0;
        self::$totalRecords = 0;
    }

    /**
     * converts a single-dimensional array to csv
     * @access public
     * @param array $array single-dimensional array of data (typically array_values() output)
     * @param string $delimiter the column seperator string, defaults to a comma
     * @param string $quantifier the column data wrapper string, defaults do a double quote
     * @return string
     */
    final public static function arrayToCSV(array $array = array(), string $delimiter = ',', string $quantifier = '"'): string {
        $output = array();
        $limit = count($array);

        for ($i = 0; $i < $limit; $i++) {
            $array[$i] = trim($array[$i]);
            $output[] = $quantifier . str_replace($quantifier, $quantifier . $quantifier, $array[$i]) . $quantifier;
        }

        return implode($delimiter, $output);
    }
}
?>