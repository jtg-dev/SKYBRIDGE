<?php

/**
 * This is a class for handling CSV output
 * @package MMExtranet
 * @version 1.0
 */

class egress_edfiSuite3 implements i_egress {

    private static $edfi_apiClientID = '';
    private static $edfi_apiClientSecret = '';
    private static $edfi_apiSubscriptionKey = '';
    private static $edfi_apiInstanceSpecific = '';
    private static $edfi_databaseUuid = '';
    private static $edfi_pointer = '';
    private static $httpReturnCodeStats = array();
    private static $httpRequestTimeStats = array();
    private static $exportRows = array();
    public static $totalRecords = 0;
    public static $postCurlRestDuration = 500000; //microseconds; 1 second = 1000 milliseconds = 1,000,000 microseconds

    /**
     * runs validation checks on csv output parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        self::$edfi_apiClientID = driver::$currentExport['output']['edfi_apiClientID'] ?? '';
        self::$edfi_apiClientSecret = driver::$currentExport['output']['edfi_apiClientSecret'] ?? '';
        self::$edfi_apiSubscriptionKey = driver::$currentExport['output']['edfi_apiSubscriptionKey'] ?? '';
        self::$edfi_apiInstanceSpecific = driver::$currentExport['output']['edfi_instanceSpecific'] ?? false;
        self::$edfi_databaseUuid = driver::$currentExport['output']['edfi_databaseUuid'] ?? '';
        driver::$currentExport['output']['edfi_apiEndpoint'] = driver::$currentExport['output']['edfi_apiEndpoint'] ?? '';

        //calculate rest period in microseconds
        driver::$currentExport['output']['postCurlRestMilliseconds'] = driver::$currentExport['output']['postCurlRestMilliseconds'] ?? 500;
        self::$postCurlRestDuration = intval(driver::$currentExport['output']['postCurlRestMilliseconds']) * 1000;

        self::$edfi_apiClientID = driver::$crypto->decrypt(self::$edfi_apiClientID);
        self::$edfi_apiClientSecret = driver::$crypto->decrypt(self::$edfi_apiClientSecret);
        self::$edfi_apiSubscriptionKey = driver::$crypto->decrypt(self::$edfi_apiSubscriptionKey);
        self::$edfi_databaseUuid = driver::$crypto->decrypt(self::$edfi_databaseUuid);

        if (isset(driver::$currentExport['output']['edfi_apiUrlBase']) === false) {
            log::logAlert(errors::$edfi_noAPIBaseURL);
            return false;
        } else {}

        if (isset(driver::$currentExport['output']['edfi_apiEndpoint']) === false) {
            log::logAlert(errors::$generic_requiredFieldMising);
            return false;
        } else {}

        /* Warn if an API subscription key is configured. */
        if (isset(driver::$currentExport['output']['edfi_apiSubscriptionKey'])) {
            log::logAlert(errors::$edfi_apiSubscriptionKey);
        } else {}

        /* Warn if a database UUID is configured. */
        if (isset(driver::$currentExport['output']['edfi_apiSubscriptionKey'])) {
            log::logAlert(errors::$edfi_databaseUuid);
        } else {}

        if (self::$edfi_apiClientID === false) {
            log::logAlert(errors::$edfi_noAPIClientID);
            return false;
        } else {}

        if (self::$edfi_apiClientSecret === false) {
            log::logAlert(errors::$edfi_noAPIClientSecret);
            return false;
        } else {}

        self::$edfi_pointer = new edfiSuite3();
        $ret = self::$edfi_pointer->init(
            driver::$currentExport['output']['edfi_apiUrlBase'],
            self::$edfi_apiClientID,
            self::$edfi_apiClientSecret,
            self::$edfi_apiSubscriptionKey,
            self::$edfi_apiInstanceSpecific,
            self::$edfi_databaseUuid
        );

        if ($ret === false) {
            log::logAlert(errors::$edfi_apiFailure);
            return false;
        } else {}


        return true;
    }

    /**
     * flushes the output buffer to the destination
     * @access public
     */
    final private static function writeRows() {
        $limit = count(self::$exportRows);
        if ($limit > 0) {
            $counter = 0;
            $stats = array();
            $requests = array();

            for ($i = 0; $i < $limit; $i++) {
                $requests[] = array (
                        'method' => 'POSTJSON',
                        'url' => self::$edfi_pointer->generateURL(driver::$currentExport['output']['edfi_apiEndpoint'], array(), self::$exportRows[$i]['urlParams']),
                        'data' => self::$exportRows[$i]['data']
                    );
            }

            $responses = self::$edfi_pointer->makeCURLParallelRequests($requests);

            $limit = count($responses);
            for ($i = 0; $i < $limit; $i++) {
                $stats[curl::$debugInfos[$i]['http_code']] = (isset($stats[curl::$debugInfos[$i]['http_code']]) === true) ? ++$stats[curl::$debugInfos[$i]['http_code']] : 1;
                self::$httpReturnCodeStats[curl::$debugInfos[$i]['http_code']] = (isset(self::$httpReturnCodeStats[curl::$debugInfos[$i]['http_code']]) === true) ? ++self::$httpReturnCodeStats[curl::$debugInfos[$i]['http_code']] : 1;
                self::$httpRequestTimeStats[] = curl::$debugInfos[$i]['total_time'];

                if (curl::$debugInfos[$i]['http_code'] >= 0 && curl::$debugInfos[$i]['http_code'] >= 200 && curl::$debugInfos[$i]['http_code'] <= 299) {
                    ++$counter;
                }
                else {
                    $url = self::$edfi_pointer->generateURL(driver::$currentExport['output']['edfi_apiEndpoint'], array(), self::$exportRows[$i]['urlParams']);
                    log::logAlert('=========================================');
                    log::logAlert('Import Error: ' . var_export($responses[$i], true) . "\n\n" . $url . "\n\n" . json_encode(curl::$debugInfos[$i]['__payload']), 2);
                    log::logAlert('Import Error: ' . var_export($responses[$i], true), 2);
                    log::logAlert('=========================================');
                }
            }

            log::logAlert('Exported ' . $limit . ' records');
            log::logAlert('Buffer Return Codes: ' . json_encode($stats));

            if (count(self::$httpRequestTimeStats) > 0) {
                log::logAlert('Average API Response Time: ' . array_sum(self::$httpRequestTimeStats) / count(self::$httpRequestTimeStats) . ' seconds');
            } else {}

            self::$totalRecords += $counter;
            self::$exportRows = array();
            $stats = array();

            //sleep
            usleep(self::$postCurlRestDuration);
        } else {}
    }

    /**
     * accepts and logs a row of data to be written out
     * Rows from the edfi jobs will me multidimensional with the keys 'urlParams' and 'data'
     * urlParams will be a multi-dimensional array of named parameters to dynamically build an endpoint url that contains placeholders
     * data is the usual array of named data points for the row itself
     * @access public
     */
    final public static function ingestRow(array $row) {
        if (isset($row['urlParams']) === false || is_array($row['urlParams']) === false) {
            $row['urlParams'] = array();
        } else {}

        if (isset($row['data']) === false || is_array($row['data']) === false) {
            $row['data'] = array();
        } else {}

        if (count($row['data']) > 0) {
            self::$exportRows[] = $row;
            $numRows = count(self::$exportRows);

            if ($numRows >= config::$exportDataBufferSize) {
                self::writeRows();
            } else {}
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
        self::$edfi_apiClientID = '';
        self::$edfi_apiClientSecret = '';
        self::$exportRows = array();
        self::$totalRecords = 0;

        log::logAlert('Export Return Codes: ' . json_encode(self::$httpReturnCodeStats));

        self::$httpReturnCodeStats = array();
        self::$httpRequestTimeStats = array();
        self::$edfi_pointer = null;
    }
}
?>