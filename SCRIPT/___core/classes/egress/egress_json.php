<?php

/**
 * This is a class for handling JSON output
 * Due to the nature of the JSON structure, the file is first created with a single [ character
 * Then the json records are concatenated
 * Then a trailing ] character is appended.
 * This is to prevent having the hold the entire output dataset in memory
 * @package MMExtranet
 * @version 1.0
 */

class egress_json implements i_egress {

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
        file_put_contents(self::$egressFile, '[');

        if (file_exists(self::$egressFile) === true && is_readable(self::$egressFile) === false) {
            log::logAlert(errors::$outputFileNotFoundorNotWritable);
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
            for ($i = 0; $i < $numRows; $i++) {
                self::$exportRows[$i] = json_encode(self::$exportRows[$i]);
            }

            if (self::$numWrites === 0) {
                file_put_contents(self::$egressFile, implode(',', self::$exportRows), FILE_APPEND);
            }
            else {
                file_put_contents(self::$egressFile, ',' . implode(',', self::$exportRows), FILE_APPEND);
            }

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
        file_put_contents(self::$egressFile, ']', FILE_APPEND);
        self::$egressFile = '';
        self::$exportRows = array();
        self::$numWrites = 0;
        self::$totalRecords = 0;
    }
}
?>