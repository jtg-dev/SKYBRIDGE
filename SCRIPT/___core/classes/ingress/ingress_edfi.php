<?php

/**
 * This is a class for handling PDO inputs
 * @package MMExtranet
 * @version 1.0
 */

class ingress_edfi implements i_ingress {

    private static $edfi_apiClientID = '';
    private static $edfi_apiClientSecret = '';
    private static $edfi_apiSubscriptionKey = '';
    private static $edfi_pointer = '';

    /**
     * runs validation checks on input parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        self::$edfi_apiClientID = driver::$currentExport['input']['edfi_apiClientID'] ?? '';
        self::$edfi_apiClientSecret = driver::$currentExport['input']['edfi_apiClientSecret'] ?? '';
        self::$edfi_apiSubscriptionKey = driver::$currentExport['output']['edfi_apiSubscriptionKey'] ?? '';

        driver::$currentExport['input']['edfi_loopURLQueryStringParams'] = driver::$currentExport['input']['edfi_loopURLQueryStringParams'] ?? array();
        driver::$currentExport['input']['edfi_loopURLQueryStringParams']['offset'] = driver::$currentExport['input']['edfi_loopURLQueryStringParams']['offset'] ?? 0;
        driver::$currentExport['input']['edfi_loopURLParams'] = driver::$currentExport['input']['edfi_loopURLParams'] ?? array();
        driver::$currentExport['input']['edfi_loopRecordLimit'] = driver::$currentExport['input']['edfi_loopRecordLimit'] ?? 0;

        self::$edfi_apiClientID = driver::$crypto->decrypt(self::$edfi_apiClientID);
        self::$edfi_apiClientSecret = driver::$crypto->decrypt(self::$edfi_apiClientSecret);
        self::$edfi_apiSubscriptionKey = driver::$crypto->decrypt(self::$edfi_apiSubscriptionKey);

        if (isset(driver::$currentExport['input']['edfi_apiUrlBase']) === false) {
            log::logAlert(errors::$edfi_noAPIBaseURL);
            return false;
        } else {}

        /* Warn if an API subscription key is configured. */
        if (isset(driver::$currentExport['output']['edfi_apisubscriptionKey'])) {
            log::logAlert(errors::$edfi_apiSubscriptionKey);
        } else {}

        if (isset(driver::$currentExport['input']['edfi_loopURLKey']) === false) {
            log::logAlert(errors::$generic_requiredFieldMising);
            return false;
        } else {}

        if (isset(driver::$currentExport['input']['edfi_loopFilterFunction']) === false || is_callable(driver::$currentExport['input']['edfi_loopFilterFunction']) === false) {
            driver::$currentExport['input']['edfi_loopFilterFunction'] = false;
        } else {}

        if (self::$edfi_apiClientID === false) {
            log::logAlert(errors::$edfi_noAPIClientID);
            return false;
        } else {}

        if (self::$edfi_apiClientSecret === false) {
            log::logAlert(errors::$edfi_noAPIClientSecret);
            return false;
        } else {}

        self::$edfi_pointer = new edfi();
        $ret = self::$edfi_pointer->init(driver::$currentExport['input']['edfi_apiUrlBase'], self::$edfi_apiClientID, self::$edfi_apiClientSecret, self::$edfi_apiSubscriptionKey);

        if ($ret === false) {
            log::logAlert(errors::$edfi_apiFailure);
            return false;
        } else {}

        return true;
    }

    /**
     * cycles through the input data
     * @access public
     */
    final public static function beginIteration() {
        driver::$currentExport['input']['edfi_loopURLQueryStringParams']['offset'] = driver::$currentExport['input']['edfi_loopURLQueryStringParams']['offset'] ?? 0;
        $break = false;
        $recordCount = 0;

        while($break === false) {
            $url = self::$edfi_pointer->generateURL(driver::$currentExport['input']['edfi_loopURLKey'], driver::$currentExport['input']['edfi_loopURLQueryStringParams'], driver::$currentExport['input']['edfi_loopURLParams']);
            $records = self::$edfi_pointer->makeCURLRequest('GET', $url);

            if ($records !== false) {
                $limit = count($records);

                if ($limit > 0) {
                    for ($i = 0; $i < $limit; $i++) {
                        workhorse::routeRowToProcessor($records[$i]);

                        ++$recordCount;
                        if (driver::$currentExport['input']['edfi_loopRecordLimit'] > 0 && driver::$currentExport['input']['edfi_loopRecordLimit'] === $recordCount) {
                            $break = true;
                            break(2);
                        } else {}
                    }

                    driver::$currentExport['input']['edfi_loopURLQueryStringParams']['offset'] += $limit;
                }
                else {
                    $break = true;
                    break(1);
                }
            }
            else {
                $break = true;
                break(1);
            }
        }
    }

    /**
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        self::$edfi_apiClientID = '';
        self::$edfi_apiClientSecret = '';
        self::$edfi_pointer = null;
    }
}
?>