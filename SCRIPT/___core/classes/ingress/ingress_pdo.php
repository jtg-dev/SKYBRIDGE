<?php

/**
 * This is a class for handling PDO inputs
 * @package MMExtranet
 * @version 1.0
 */

class ingress_pdo implements i_ingress {

    private static $db = null;

    /**
     * runs validation checks on csv input parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        self::$db = new database_pdo();
        $ret = self::$db->connect(driver::$currentExport['input']['dsn'], driver::$crypto->decrypt(driver::$currentExport['input']['username']), driver::$crypto->decrypt(driver::$currentExport['input']['password']), false);

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
        $ret = self::$db->justquery(driver::$currentExport['input']['sourceQuery'], driver::$currentExport['input']['queryParams'], false);

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

        if ($ret !== false) {
            if (self::$db->pdoReference->columnCount() > 0){
                while ($row = self::$db->pdoReference->fetch(PDO::FETCH_ASSOC)) {
                    workhorse::routeRowToProcessor($row);
                }

                self::$db->closeCursor();
            }
            else {
                log::logAlert(errors::$noDBResults);

                self::$db->closeCursor();
                return;
            }
        }
        else {
            log::logAlert(errors::$sqlError);
            log::logAlert(end(self::$db->errors));

            self::$db->closeCursor();
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