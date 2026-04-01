<?php

/**
 * This is a class for handling XML inputs
 * @package MMExtranet
 * @version 1.0
 */

class ingress_xml implements i_ingress {

    private static $ingestFile = '';
    private static $xmlReader = null;
    private static $recordNum = 0;

    /**
     * runs validation checks on csv input parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        $config = workhorse::getConfig();

        self::$ingestFile = driver::$currentExport['input']['location'];

        if (file_exists(self::$ingestFile) === false || is_readable(self::$ingestFile) === false) {
            log::logAlert(errors::$inputFileNotFoundorNotReadable);
            return false;
        } else {}

        if (isset(driver::$currentExport['input']['dataRegion']) === false) {
            log::logAlert(errors::$ingressClassNoXMLDataRegion);
            return false;
        }
        else {
            driver::$currentExport['input']['dataRegion'] = trim(driver::$currentExport['input']['dataRegion']);
            if (empty(driver::$currentExport['input']['dataRegion']) === true) {
                log::logAlert(errors::$ingressClassNoXMLDataRegion);
                return false;
            } else {}
        }

        self::$xmlReader = new XMLReader();

        $ret = self::$xmlReader->open(self::$ingestFile);
        if ($ret === false) {
            log::logAlert(errors::$inputFileNotFoundorNotReadable);
            return false;
        } else {}

        while(self::$xmlReader->read() && self::$xmlReader->name !== driver::$currentExport['input']['dataRegion']);

        return true;
    }

    /**
     * cycles through the input data
     * @access public
     */
    final public static function beginIteration() {
        while (self::$xmlReader->name === driver::$currentExport['input']['dataRegion']) {
            $node = json_decode(json_encode(new SimpleXMLElement(self::$xmlReader->readOuterXML())), true);
            workhorse::routeRowToProcessor($node);
            ++self::$recordNum;
            self::$xmlReader->next(driver::$currentExport['input']['dataRegion']);
        }
    }

    /**
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        self::$ingestFile = '';
        self::$xmlReader = array();
        self::$recordNum = 0;
    }
}
?>