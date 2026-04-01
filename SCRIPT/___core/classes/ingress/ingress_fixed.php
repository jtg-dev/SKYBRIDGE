<?php

/**
 * This is a class for handling CSV inputs
 * @package MMExtranet
 * @version 1.0
 */

class ingress_fixed implements i_ingress {

    private static $ingestFile = '';
    private static $fp = null;

    /**
     * runs validation checks on csv input parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        $config = workhorse::getConfig();

        self::$ingestFile = driver::$currentExport['input']['location'];

        if (file_exists(self::$ingestFile) === true && is_readable(self::$ingestFile) === true) {
            self::$fp = fopen(self::$ingestFile, 'r');

            if (self::$fp === false) {
                log::logAlert(errors::$inputFileNotFoundorNotReadable);
                return false;
            } else {}
        }
        else {
            log::logAlert(errors::$inputFileNotFoundorNotReadable);
            return false;
        }

        if (isset(driver::$currentExport['input']['layout']) === false) {
            driver::$currentExport['input']['layout'] = array();
        } else {}

        if (count(driver::$currentExport['input']['layout']) === 0) {
            log::logAlert(errors::$inputFileNoFixedLayout);
            return false;
        } else {}

        return true;
    }

    /**
     * cycles through the input data
     * @access public
     */
    final public static function beginIteration() {
        $numColumns = count(driver::$currentExport['input']['layout']);
        $columns = array_keys(driver::$currentExport['input']['layout']);
        $lengths = array_values(driver::$currentExport['input']['layout']);

        while (($row = fgets(self::$fp)) !== false) {
            $out = array();
            $index = 0;

            for ($i = 0; $i < $numColumns; $i++) {
                $data = substr($row, $index, $lengths[$i]);
                $index += $lengths[$i];

                $out[$columns[$i]] = trim($data);
            }

            workhorse::routeRowToProcessor($out);
        }
    }

    /**
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        fclose(self::$fp);
        self::$ingestFile = '';
    }
}
?>