<?php

/**
 * This is a class for handling ODBC inputs
 * ODBC DOESN'T REALLY SUPPORT PARAMETERIZED QUERIES, BE CAREFUL ABOUT SQL INJECTION
 * @package MMExtranet
 * @version 1.0
 */

class ingress_odbc implements i_ingress {

    private static $db = null;

    /**
     * runs validation checks on csv input parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        self::$db = new database_odbc();
        $ret = self::$db->connect(driver::$currentExport['input']['dsn'], driver::$crypto->decrypt(driver::$currentExport['input']['username']), driver::$crypto->decrypt(driver::$currentExport['input']['password']));

        if ($ret === false) {
            log::logAlert(errors::$databaseCantConnect);
            return false;
        } else {}

        return true;
    }

    /**
     * cycles through the input data
     * @access public
     */
    final public static function beginIteration() {
        if (isset(driver::$currentExport['input']['queryParams']) === true && is_array(driver::$currentExport['input']['queryParams']) === true) {
            driver::$currentExport['input']['sourceQuery'] = utility::parseTemplate(driver::$currentExport['input']['sourceQuery'], driver::$currentExport['input']['queryParams']);
        } else {}

        if (isset(driver::$currentExport['input']['dumpSQL']) === true && driver::$currentExport['input']['dumpSQL'] === true) {
            if (isset(driver::$currentExport['input']['sourceQuery']) === true) {
                log::logAlert('===SQL===');
                log::logAlert(driver::$currentExport['input']['sourceQuery']);
            } else {}

            if (isset(driver::$currentExport['input']['queryParams']) === true && is_array(driver::$currentExport['input']['queryParams']) === true) {
                log::logAlert('===Params===');
                log::logAlert(var_export(driver::$currentExport['input']['queryParams'], true));
            } else {}
        } else {}


        $ret = self::$db->justquery(driver::$currentExport['input']['sourceQuery']);

        if ($ret !== false) {
            //if (odbc_num_rows($ret) > 0){
                while ($row = odbc_fetch_array($ret)) {
                    workhorse::routeRowToProcessor($row);
                }

                odbc_free_result($ret);
            /*}
            else {
                log::logAlert(errors::$noDBResults);
                return;
            }*/
        }
        else {
            log::logAlert(errors::$sqlError);
            return;
        }
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
    }
}
?>