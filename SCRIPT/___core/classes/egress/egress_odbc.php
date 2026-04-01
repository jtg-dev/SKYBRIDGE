<?php

/**
 * This is a class for handling odbc output
 * ODBC DOESN'T REALLY SUPPORT PARAMETERIZED QUERIES, BE CAREFUL ABOUT SQL INJECTION
 * This egress is untested and purely theoretically ready
 * @package MMExtranet
 * @version 1.0
 */

class egress_odbc implements i_egress {

    private static $db = null;
    private static $exportRows = array();
    public static $totalRecords = 0;

    /**
     * runs validation checks on csv output parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        self::$db = new database_odbc();
        $ret = self::$db->connect(driver::$currentExport['output']['dsn'], driver::$crypto->decrypt(driver::$currentExport['output']['username']), driver::$crypto->decrypt(driver::$currentExport['output']['password']));

        if ($ret === false) {
            log::logAlert(errors::$databaseCantConnect);
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
            $exported = 0;

            for ($i = 0; $i < $numRows; $i++) {
                $sql = driver::$currentExport['output']['insertQuery'];
                $sql = utility::parseTemplate($sql, self::$exportRows[$i]);

                $ret = self::$db->justquery($sql);

                if ($ret !== false) {
                    ++$exported;
                }
                else {
                    log::logAlert(errors::$dbInsertFailed);
                }
            }

            log::logAlert('Exported ' . $exported . ' records');
            self::$totalRecords += $exported;
            self::$exportRows = array();
        } else {}
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
     * a method of sharing the DB connection
     * @access public
     */
    final public static function getDBPointer() {
        return self::$db;
    }

    /**
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        self::$db->closeConnection();
        self::$exportRows = array();
        self::$totalRecords = 0;
    }
}
?>