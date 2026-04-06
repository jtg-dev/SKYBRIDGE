<?php
require(__DIR__ . '/../../___core/init.php');

/* Get CLI options and validate. */
driver::$cliOptions = getopt('', array('dataSource:'));
driver::$cliOptions['dataSource'] = driver::$cliOptions['dataSource'] ?? false;
driver::$cliOptions['dataSource'] = isset(config::$locationProperties[driver::$cliOptions['dataSource']]) ? driver::$cliOptions['dataSource'] : false;
$apiDestination = getopt('', array('api:'));
$apiDestination = $apiDestination["api"] ?? false;

/* Die if no valid data source configuration found. */
if (driver::$cliOptions['dataSource'] === false) {
    die('Invalid --dataSource');
} else {
}

/* Initialize district information. */
custom_locationproperties::init(driver::$cliOptions['dataSource']);

/* Verify API location exists in config. */
if ($apiDestination === false || empty(custom_locationproperties::getConnectionParameters($apiDestination))) {
    die("No API Destination.");
} else {
}

/* Begin lines that are specific to this job */
$snapshotDate = (config::$useHistoricalData) ? config::$historicalSnapshotDate : date('Y-m-d');

// Convert dates to MM/DD/YYYY for Skyward SQL 1/26/26
$sqlSnapshotDate = date('m/d/Y', strtotime($snapshotDate));
// Derive the fiscal start year from currentSY[0], which is always the START year of the school year.
// e.g., historicalYear=2025 → currentSY=[2024,2025] → schoolYear=2024 → SQL builds "07/01/2024"
// In normal mode (March 2026): currentSY=[2025,2026] → schoolYear=2025 → SQL builds "07/01/2025"
$schoolYear = driver::$currentSY[0];

$jobBasePath = __DIR__ . '/../../logs/' . driver::$cliOptions['dataSource'] . '/';
fileio::makePath($jobBasePath);

// Default to empty string for SQL 1/27/26
driver::$sharedBuckets['studentSideStaffTableData'] = array();
driver::$sharedBuckets['odsEntities'] = array();
driver::$sharedBuckets['odsEntitiesString'] = "''"; 


$config = array(
    'logDirectory'                => $jobBasePath,
    'logName'                     => '_EdFi_Staff_Suite3.' . $apiDestination . '.log',
    'jobName'                     => 'Ed-Fi Staff - Suite 3 (' . driver::$cliOptions['dataSource'] . ')',
    'exportDataBufferSize'        => 50,
    'suppressDuplicateLogEntries' => false,
    'exports'                     => array(
        7 => array(
            'name'    => 'Ed-Fi Suite 3 - People',
            'enabled' => false,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["people"],
                'queryParams' => array(
                    'entities'      => &driver::$sharedBuckets['odsEntitiesString'],
                    'staffIdColumn' => '"' . config::$staffUniqueIdTableAlias . '"."' . config::$staffUniqueIdField . '"'
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_people',
                'postCurlRestMilliseconds' => 250
            )
            /*
            'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'people.edFiSuite3.json'
            )*/
        ),

        0 => array(
            'name'    => 'Ed-Fi Suite 3 - Credentials',
            'enabled' => false,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["credentials"],
                'queryParams' => array(
                    'snapshotDate'  => $sqlSnapshotDate,
                    'nameId'        => 'HPMCEM.NAME-ID',
                    'entities'      => &driver::$sharedBuckets['odsEntitiesString'],
                    'staffIdColumn' => '"' . config::$staffUniqueIdTableAlias . '"."' . config::$staffUniqueIdField . '"'
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_credentials',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'credentials.edFiSuite3.json'
            )*/
        ),

        1 => array(
            'name'    => 'Ed-Fi Suite 3 - Staffs',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["staff"],
                'queryParams' => array(
                    'entities'      => &driver::$sharedBuckets['odsEntitiesString'],
                    'snapshotDate'  => $sqlSnapshotDate,
                    'staffIdColumn' => '"' . config::$staffUniqueIdTableAlias . '"."' . config::$staffUniqueIdField . '"'
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_staffs',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'staffs.edFiSuite3.json'
            )*/
        ),

        2 => array(
            'name'    => 'Ed-Fi Suite 3 - StaffEducationOrganizationEmploymentAssociation',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["staffEducationOrganizationEmploymentAssociation"],
                'queryParams' => array(
                    'currentsy'     => driver::$currentSY[0],
                    'entities'      => &driver::$sharedBuckets['odsEntitiesString'],
                    'planName'      => custom_locationproperties::getPlanName(),
                    'snapshotDate'  => $sqlSnapshotDate,
                    'staffIdColumn' => '"' . config::$staffUniqueIdTableAlias . '"."' . config::$staffUniqueIdField . '"'
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_staffEducationOrganizationEmploymentAssociations',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'staffEducationOrganizaitonEmploymentAssociaiton.edFiSuite3.json'
            )*/
        ),

        3 => array(
            'name'    => 'Ed-Fi Suite 3 - StaffEducationOrganizationAssignmentAssociation',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["staffEducationOrganizationAssignmentAssociation"],
                'queryParams' => array(
                    'currentsy'     => driver::$currentSY[0],
                    'entities'      => &driver::$sharedBuckets['odsEntitiesString'],
                    'planName'      => custom_locationproperties::getPlanName(),
                    'snapshotDate'  => $sqlSnapshotDate,
                    'staffIdColumn' => '"' . config::$staffUniqueIdTableAlias . '"."' . config::$staffUniqueIdField . '"'
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_staffEducationOrganizationAssignmentAssociations',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'staffEducationOrganizationAssignmentAssociation.edFiSuite3.json'
            )*/
        ),

        4 => array(
            'name'    => 'Ed-Fi Suite 3 - OpenStaffPosition',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["openStaffPosition"],
                'queryParams' => array(
                    'snapshotDate' => $sqlSnapshotDate,
                    'entities'     => &driver::$sharedBuckets['odsEntitiesString']
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_openStaffPositions',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'openStaffPosition.edFiSuite3.json'
            )*/
        ),

        5 => array(
            'name'    => 'Ed-Fi Suite 3 - StaffAbsenceEvent',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["staffAbsenceEvent"],
                'queryParams' => array(
                    'schoolYear'    => $schoolYear,
                    'snapshotDate'  => $sqlSnapshotDate,
                    'entities'      => &driver::$sharedBuckets['odsEntitiesString'],
                    'staffIdColumn' => '"' . config::$staffUniqueIdTableAlias . '"."' . config::$staffUniqueIdField . '"'
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_staffAbsenceEvents',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'staffAbsenceEvent.edFiSuite3.json'
            )*/
        ),

        6 => array(
            'name'    => 'Ed-Fi Suite 3 - StaffEducationOrganizationAssignmentAssociation (Employees Without Assignments)',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["staffWithoutAssignments"],
                'queryParams' => array(
                    'currentsy'     => driver::$currentSY[0],
                    'snapshotDate'  => $sqlSnapshotDate,
                    'entities'      => &driver::$sharedBuckets['odsEntitiesString'],
                    'staffIdColumn' => '"' . config::$staffUniqueIdTableAlias . '"."' . config::$staffUniqueIdField . '"'
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_staffEducationOrganizationAssignmentAssociations',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'staffEducationOrganizationAssignmentAssociation.edFiSuite3.json'
            )*/
        ),

        8 => array(
            'name'    => 'Ed-Fi Suite 3 - Performance Evaluations',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["performanceEvaluations"],
                'queryParams' => array(
                    'currentsy'    => driver::$currentSY[0],
                    'snapshotDate' => $sqlSnapshotDate,
                    'entities'     => &driver::$sharedBuckets['odsEntitiesString']
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_performanceEvaluations',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'performanceEvaluations.edFiSuite3.json'
            )*/
        ),

        9 => array(
            'name'    => 'Ed-Fi Suite 3 - Performance Evaluation Ratings',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["performanceEvaluationRatings"],
                'queryParams' => array(
                    'currentsy'     => driver::$currentSY[0],
                    'snapshotDate'  => $sqlSnapshotDate,
                    'entities'      => &driver::$sharedBuckets['odsEntitiesString'],
                    'staffIdColumn' => '"' . config::$staffUniqueIdTableAlias . '"."' . config::$staffUniqueIdField . '"'
                )
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'      => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'         => 'o_performanceEvaluationRatings',
                'postCurlRestMilliseconds' => 250
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'performanceEvaluationRatings.edFiSuite3.json'
            )*/
        ),
    )
);

workhorse::init($config);

class jobTransformer implements i_jobTransformation {
    private static $tmp_uniqueRecordsHashes = array();
    private static $db = null;
    private static $payRecsUseAcctDistJobCodes;

    /**
     * Returns the configured staff unique ID for a row.
     * Uses config::$staffUniqueIdField as a true open input — whatever column name
     * is configured will be selected by the SQL query and read here.
     * Falls back to HAAPRO-OTHER-ID if the configured field is empty or not found.
     * Fires a log::logAlert() warning whenever the configured field is not 'HAAPRO-OTHER-ID'
     * and the primary value is empty, regardless of whether a fallback value exists.
     */
    private static function getStaffId(array $row): string {
        $field    = config::$staffUniqueIdField;
        $primary  = trim($row['STAFF-UNIQUE-ID'] ?? '');
        $fallback = trim($row['HAAPRO-OTHER-ID'] ?? '');
        if (!empty($primary)) {
            return $primary;
        }
        if ($field !== 'HAAPRO-OTHER-ID') {
            log::logAlert('WARNING: ' . $field . ' is empty or not found for NAME-ID ' . ($row['NAME-ID'] ?? 'unknown') . ' (HAAPRO-OTHER-ID: ' . $fallback . '). Falling back to HAAPRO-OTHER-ID.');
        }
        return $fallback;
    }

    final public static function preQueueProcesserLoopHook(array &$config) {
        global $apiDestination;

        //get entities from student, will only pull records for entities that exist in ODS already.
        $apiConnParams = custom_locationproperties::getConnectionParameters($apiDestination);
        $edfiPointer = new edfiSuite3();
        $ret = $edfiPointer->init(
            $apiConnParams['apiUrlBase'],
            driver::$crypto->decrypt($apiConnParams['apiClientID']),
            driver::$crypto->decrypt($apiConnParams['apiClientSecret']),
            "",
            $apiConnParams['instanceSpecific']   ?? false,
            driver::$crypto->decrypt($apiConnParams['databaseUuid'] ?? ''),
            $apiConnParams['yearBeforeData']     ?? false
        );

        if ($ret !== false) {
            $edfiPointer->loopDataWithCallback('o_schools', array('offset' => 0, 'localEducationAgencyId' => custom_locationproperties::get_districtID()), array(), function ($record) {
                $code = substr($record['schoolId'], -4);
                if (!empty($code)) {
                    driver::$sharedBuckets['odsEntities'][] = $code;
                }
                return $record;
            });
            driver::$sharedBuckets['odsEntities'][] = '0000';
            
            // Save the SQL string version into the new variable 1/27/26 ==================================================================
            driver::$sharedBuckets['odsEntitiesString'] = '\'' . implode('\',\'', driver::$sharedBuckets['odsEntities']) . '\'';
            
            // Debug to confirm it worked
            //var_dump(driver::$sharedBuckets['odsEntitiesString']);
            // Update END =================================================================================================================

        } else {
            log::logAlert(errors::$edfi_apiFailure);
            die();
        }

        //for debug
        //var_dump(driver::$sharedBuckets['odsEntities']);

        /* Get flag for whether or not pay record account distributions use job codes. */
        self::$db = new database_odbc();
        $ret = self::$db->connect(
            custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
            driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username']),
            driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'])
        );

        if ($ret !== false) {
            $sql = custom_edfiSuite3::$subqueries["getPayRecsUseAcctDistJobCode"];
            $ret = self::$db->justquery($sql);

            if ($ret !== false) {
                $row = odbc_fetch_array($ret);
                self::$payRecsUseAcctDistJobCodes = ($row["FFAACT-AcctStRptFld-X"] === "1" ? true : false);
            } else {
                log::logAlert(errors::$sqlError);
                die();
            }

            odbc_free_result($ret);
            self::$db->closeConnection();
        } else {
            log::logAlert(errors::$databaseCantConnect);
            die();
        }
    }

    final public static function preQueueRecordProcessHook(int $exportIndex) {
        self::$tmp_uniqueRecordsHashes = array();

        switch ($exportIndex) {
            case 2: {
                log::logAlert("ODS Entities: " . implode(", ", driver::$sharedBuckets['odsEntities']));
                break;
            }
            case 3: {
                log::logAlert("Reading assignment records from Skyward plan \"" . custom_locationproperties::getPlanName() . "\"");
                break;
            }
            case 6: {
                if (self::$payRecsUseAcctDistJobCodes) {
                    log::logAlert("INFORMATION: Pay record account distributions *DO* use job codes.");
                } else {
                    log::logAlert("INFORMATION: Pay record account distributions *DO NOT* use job codes.");
                }
                break;
            }
        }
    }

    final public static function postQueueRecordProcessHook(int $exportIndex) {
        self::$tmp_uniqueRecordsHashes = array();
    }

    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

        switch ($exportIndex) {
            case 0: // Credential
                self::processRow_Credential($row, $exportIndex, $parseOptions);
                break;

            case 1: // Staff
                self::processRow_Staff($row, $exportIndex, $parseOptions);
                break;

            case 2: // Staff Education Organization Employment Association
                self::processRow_StaffEducationOrganizationEmploymentAssociation($row, $exportIndex, $parseOptions);
                break;

            case 3: // Staff Education Organization Assignment Association
                self::processRow_StaffEducationOrganizationAssignmentAssociation($row, $exportIndex, $parseOptions);
                break;

            case 4: // Open Staff Position
                self::processRow_OpenStaffPosition($row, $exportIndex, $parseOptions);
                break;

            case 5: // Staff Absence Event
                self::processRow_StaffAbsenceEvent($row, $exportIndex, $parseOptions);
                break;

            case 6: // Staff Education Organization Assignment Association (Alternate, Staff Without Assignments)
                self::processRow_EmployeesWithoutAssignments($row, $exportIndex, $parseOptions);
                break;

            case 7: // People
                self::processRow_Person($row, $exportIndex, $parseOptions);
                break;

            case 8: // Performance Evaluations
                self::processRow_PerformanceEvaluation($row, $exportIndex, $parseOptions);
                break;

            case 9: // Performance Evaluation Ratings
                self::processRow_PerformanceEvaluationRating($row, $exportIndex, $parseOptions);
                break;
        }
    }

    /* === Export #0 === */
    final private static function processRow_Credential(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Validate subject code values (replace slashes and hash symbols with underscores). */
        $row["HPMCEM-CERT-NBR"] = trim($row["HPMCEM-CERT-NBR"]);
        $row["HPMCEM-STATE"] = (empty($row["HPMCEM-STATE"]) ? "FL" : $row["HPMCEM-STATE"]);
        $row["HAADSC-CODE-SubjArea"] = str_replace(array("/","#"), "_", $row["HAADSC-CODE-SubjArea"]);

        /* Build initial export record. */
        $out = array(
            "credentialIdentifier"                    => $row["HPMCEM-CERT-NBR"] . "_" . $row["HAADSC-ID-CERT2"] . "_" . $row["HAADSC-ID-CERT1"], // Cert Num + Subj Area ID + Level ID
            "gradeLevels"                             => array(),
            "stateOfIssueStateAbbreviationDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "StateAbbreviationDescriptor") . "#" . $row["HPMCEM-STATE"],
            "credentialFieldDescriptor"               => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "CredentialFieldDescriptor") . "#" . $row["HAADSC-CODE-SubjArea"],
            "credentialTypeDescriptor"                => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "CredentialTypeDescriptor") . "#Certificate",
            "expirationDate"                          => $row["HPMCEM-EXP-DATE"],
            "issuanceDate"                            => $row["HPMCEM-ISSUE-DATE"],
            "namespace"                               => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "Credential"),
            "teachingCredentialDescriptor"            => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "TeachingCredentialDescriptor") . "#" . $row["HAADSC-CODE-CertType"],
            "_ext"                                    => array(
                "TPDM" => array(
                    "certificationTitle" => $row["HPMCEM-CERT-NBR"] . "_" . $row["HAADSC-ID-CERT2"] . "_" . $row["HAADSC-ID-CERT1"],
                    "personReference" => array(
                        "personId" => self::getStaffId($row),
                        "sourceSystemDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "SourceSystemDescriptor") . "#Skyward"
                    )
                )
            )
        );

        /* Determine grade levels by certificate level type. */
        switch ($row["HAADSC-CODE-Level"]) {
            case "1": { // "1" => "6-12"
                $grades = array("06", "07", "08", "09", "10", "11", "12");
                break;
            }
            case "2": { // "2" => "Adult"
                $grades = array("30", "31");
                break;
            }
            case "3": { // "3" => "1-6"
                $grades = array("01", "02", "03", "04", "05", "06");
                break;
            }
            case "4": { // "4" => "7-12"
                $grades = array("07", "08", "09", "10", "11", "12");
                break;
            }
            case "5": { // "5" => "K-8"
                $grades = array("KG", "01", "02", "03", "04", "05", "06", "07", "08");
                break;
            }
            case "6": { // "6" => "K-12"
                $grades = array("KG", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                break;
            }
            case "7": { // "7" => "Career and Technical Education"
                $grades = array("Ungraded");
                break;
            }
            case "A": { // "A" => "Birth to Age 4"
                $grades = array("PK");
                break;
            }
            case "B": { // "B" => "K-3"
                $grades = array("KG", "01", "02", "03");
                break;
            }
            case "C": { // "C" => "5-9"
                $grades = array("05", "06", "07", "08", "09");
                break;
            }
            case "D": { // "D" => "PK-12"
                $grades = array("PK", "KG", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                break;
            }
            case "E": { // "E" => "Endorsement"
                $grades = array("Ungraded");
                break;
            }
            case "F": { // "F" => "All Levels"
                $grades = array("PK", "KG", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "30", "31");
                break;
            }
            case "G": { // "G" => "District-Issued"
                $grades = array("Ungraded");
                break;
            }
            case "H": { // "H" => "PK-3"
                $grades = array("PK", "KG", "01", "02", "03");
                break;
            }
            case "K": { // "K" => "K-6"
                $grades = array("KG", "01", "02", "03", "04", "05", "06");
                break;
            }
            case "L": { // "L" => "0-4 Yrs"
                $grades = array("PK", "KG");
                break;
            }
            default: { // Unknown
                $grades = array("Other");
                break;
            }
        }

        /* Loop through grades and add grade descriptors to export record. */
        foreach ($grades as $g) {
            $out["gradeLevels"][] = array("gradeLevelDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "GradeLevelDescriptor") . "#" . $g);
        }

        /* Send credential record to the ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => $out));
    }

    /* === Export #4 === */
    final private static function processRow_OpenStaffPosition(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Crosswalk full time indicator field to a descriptor description. */
        switch ($row["HAPJBL-FULL-TIME-IND"]) {
            case "F": {
                $row["HAPJBL-FULL-TIME-IND"] = "Tenured or permanent";
                break;
            }
            case "O": {
                $row["HAPJBL-FULL-TIME-IND"] = "Other";
                break;
            }
            case "P": {
                $row["HAPJBL-FULL-TIME-IND"] = "Employed part-time";
                break;
            }
        }

        /* Build initial export record. */
        $out = array(
            "requisitionNumber"              => $row["HAPJBL-JOB-LISTING-ID"],
            "educationOrganizationReference" => array(
                "educationOrganizationId" => intval(custom_locationproperties::get_districtID()) . $row["HAABLD-BLD-CODE"]
            ),
            "datePosted"                     => $row["HAPJBL-POST-INT-BEGIN-DATE"],
            "datePostingRemoved"             => ($row["HAPJBL-STATUS"] === "C" ? (empty($row["HAPJBL-CLOSE-DATE"]) ? $row["HAPJBL-POST-INT-BEGIN-DATE"] : $row["HAPJBL-CLOSE-DATE"]) : $row["HAPJBL-CLOSE-DATE"]),
            "employmentStatusDescriptor"     => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "EmploymentStatusDescriptor") . "#" . $row["HAPJBL-FULL-TIME-IND"],
            "positionTitle"                  => $row["HAPJBL-ASN-DESC"],
            "staffClassificationDescriptor"  => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "StaffClassificationDescriptor") . "#" . $row["HAADSC-CODE"]
        );

        /* Send open staff position record to the ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => $out));
    }

    final private static function processRow_Person(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Build initial export record. */
        $out = array(
            "personId" => self::getStaffId($row),
            "sourceSystemDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'SourceSystemDescriptor') . "#Skyward"
        );

        /* Ship to ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => $out));
    }

    final private static function processRow_PerformanceEvaluation(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Convert Evaluation Type code to description. */
        switch ($row["HAAEVL-EVAL-TYPE"]) {
            case "E": { $row["HAAEVL-EVAL-TYPE"] = "End-of-Year"; break; }
            case "M": { $row["HAAEVL-EVAL-TYPE"] = "Mid-Year"; break; }
        }

        /* Convert Evaluation Class code to description. */
        switch ($row["HAAEVL-EVAL-CLASS"]) {
            case "A": { $row["HAAEVL-EVAL-CLASS"] = "Administrative"; break; }
            case "I": { $row["HAAEVL-EVAL-CLASS"] = "Instructional"; break; }
            case "O": { $row["HAAEVL-EVAL-CLASS"] = "Other"; break; }
        }

        /* Populate evaluation period with Default if empty. */
        if (empty($row["HAAEVL-EVAL-PERIOD"])) {
            $row["HAAEVL-EVAL-PERIOD"] = "Default";
        }

        /* Build initial export record. */
        $out = array(
            "evaluationPeriodDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'EvaluationPeriodDescriptor') . "#" . $row["HAAEVL-EVAL-TYPE"],
            "performanceEvaluationTitle" => $row["HAAEVL-EVAL-TYPE"] . " " . $row["HAAEVL-EVAL-CLASS"] . " Evaluation",
            "performanceEvaluationTypeDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'PerformanceEvaluationTypeDescriptor') . "#" . $row["HAAEVL-EVAL-CLASS"],
            "termDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TermDescriptor') . "#" . $row["HAAEVL-EVAL-PERIOD"],
            "educationOrganizationReference" => array(
                "educationOrganizationId" => intval(custom_locationproperties::get_districtID()) . $row["HAABLD-BLD-CODE"]
            ),
            "schoolYearTypeReference" => array(
                "schoolYear" => utility::determineSchoolYear($row["HAAEVL-EVALUATION-DATE"])[1]
            )
        );

        /* Ship to ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => $out));
    }

    final private static function processRow_PerformanceEvaluationRating(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Convert Evaluation Type code to description. */
        switch ($row["HAAEVL-EVAL-TYPE"]) {
            case "E": { $row["HAAEVL-EVAL-TYPE"] = "End-of-Year"; break; }
            case "M": { $row["HAAEVL-EVAL-TYPE"] = "Mid-Year"; break; }
        }

        /* Convert Evaluation Class code to description. */
        switch ($row["HAAEVL-EVAL-CLASS"]) {
            case "A": { $row["HAAEVL-EVAL-CLASS"] = "Administrative"; break; }
            case "I": { $row["HAAEVL-EVAL-CLASS"] = "Instructional"; break; }
            case "O": { $row["HAAEVL-EVAL-CLASS"] = "Other"; break; }
        }

        /* Populate evaluation period with Default if empty. */
        if (empty($row["HAAEVL-EVAL-PERIOD"])) {
            $row["HAAEVL-EVAL-PERIOD"] = "Default";
        }

        /* Build initial export record. */
        $out = array(
            "performanceEvaluationReference" => array(
                "educationOrganizationId" => intval(custom_locationproperties::get_districtID()) . $row["HAABLD-BLD-CODE"],
                "evaluationPeriodDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'EvaluationPeriodDescriptor') . "#" . $row["HAAEVL-EVAL-TYPE"],
                "performanceEvaluationTitle" => $row["HAAEVL-EVAL-TYPE"] . " " . $row["HAAEVL-EVAL-CLASS"] . " Evaluation",
                "performanceEvaluationTypeDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'PerformanceEvaluationTypeDescriptor') . "#" . $row["HAAEVL-EVAL-CLASS"],
                "schoolYear" => utility::determineSchoolYear($row["HAAEVL-EVALUATION-DATE"])[1],
                "termDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TermDescriptor') . "#" . $row["HAAEVL-EVAL-PERIOD"],
            ),
            "personReference" => array(
                "personId" => self::getStaffId($row),
                "sourceSystemDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'SourceSystemDescriptor') . "#Skyward",
            ),
            "actualDate" => $row["HAAEVL-EVALUATION-DATE"],
            "performanceEvaluationRatingLevelDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'PerformanceEvaluationRatingLevelDescriptor') . "#" . $row["HAAEVL-EVALUATION-STATUS"],
        );

        /* Ship to ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => $out));
    }

    /* === Export #1 === */
    final private static function processRow_Staff(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* ===========================
         * ===   Skip Conditions   ===
         * =========================== */
        /* Do not process record if one of these conditions is met. */

        if (empty($row["HAABLD-BLD-CODE"])) {
            log::logAlert("ERROR: Blank Building Code for " . $row["LAST-NAME"] . ", " . $row["FIRST-NAME"] . " (" . self::getStaffId($row) . "). Skipping record...");
            return false;
        }


        /* =============================
         * ===   Staff Main Record   ===
         * ============================= */

        /* Build initial output record. */
        $out = array(
            "staffUniqueId"                              => self::getStaffId($row),
            "birthDate"                                  => date("Y-m-d", strtotime($row["BIRTHDATE"])),
            "citizenshipStatusDescriptor"                => "", // Set later.
            "firstName"                                  => $row["FIRST-NAME"],
            "generationCodeSuffix"                       => $row["NAME-SUFFIX-ID"],
            "highestCompletedLevelOfEducationDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'LevelOfEducationDescriptor') . "#", // Set later.
            "highlyQualifiedTeacher"                     => false,
            "hispanicLatinoEthnicity"                    => (empty($row["ETHNICITY-HISP-X"]) ? false : ($row["ETHNICITY-HISP-X"] === "1" ? true : false)),
            "lastSurname"                                => $row["LAST-NAME"],
            "loginId"                                    => trim($row['INTERNET-ADDRESS']),
            "maidenName"                                 => $row["HAAPRO-MAIDEN-NAME"],
            "middleName"                                 => $row["MIDDLE-NAME"],
            "personalTitlePrefix"                        => $row["SALUTATION-SDESC"],
            "sexDescriptor"                              => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'SexDescriptor') . "#", // Set later.
            "yearsOfPriorProfessionalExperience"         => 0,  // Set later.
            "yearsOfPriorTeachingExperience"             => 0,  // Set later.
            "addresses"                                  => array(),
            "credentials"                                => array(),
            "electronicMails"                            => array(),
            "identificationCodes"                        => array(),
            "races"                                      => array(),
            "telephones"                                 => array(),
        );

        /* Determine citizenship status. */
        $row["HAAPRO-US-CITIZEN-X"] = (empty($row["HAAPRO-US-CITIZEN-X"]) ? "Non-resident alien" : ($row["HAAPRO-US-CITIZEN-X"] === "1" ? "US Citizen" : "Non-resident alien"));
        $out["citizenshipStatusDescriptor"] = custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'CitizenshipStatusDescriptor') . "#" . $row["HAAPRO-US-CITIZEN-X"];

        /* Determine highest completed level of education. */
        /* If the local degree code is "H" or "HS", assume "High School Diploma" because FLDOE does not have a state code for it. */
        if (in_array($row["HAADEG-CODE"], array("H", "HS"))) {
            $out["highestCompletedLevelOfEducationDescriptor"] .= "High School Diploma";
        } else {
            $out["highestCompletedLevelOfEducationDescriptor"] .= custom_floridacode::highestEducationCode(trim($row['HAADEG-STATE-CODE']));
        }

        /* Determine sex. */
        if (empty($row["GENDER"])) {
            $row["GENDER"] = "Not Selected";
        } else {
            switch ($row["GENDER"]) {
                case "F": {
                    $row["GENDER"] = "Female";
                    break;
                }
                case "M": {
                    $row["GENDER"] = "Male";
                    break;
                }
                default: {
                    $row["GENDER"] = "Not Selected";
                    break;
                }
            }
        }
        $out["sexDescriptor"] .= $row["GENDER"];

        /* Determine years of experience. */
        $yrsTeachInDistr = intval($row["HAAPRO-YRS-TEACH-IN-DISTRICT"]);
        $yrsTeachFlPublic = intval($row["HAAPRO-YRS-TEACH-FL-PUBLIC"]);
        $yrsTeachFlNonPublic = intval($row["HAAPRO-YRS-TEACH-FL-NON-PUBLIC"]);
        $yrsTeachOtherPublic = intval($row["HAAPRO-YRS-TEACH-OTHER-PUBLIC"]);
        $yrsTeachOtherNonPublic = intval($row["HAAPRO-YRS-TEACH-OTHER-NON-PUBLIC"]);
        $yrsAdminExp = intval($row["HAAPRO-YRS-ADMIN-EXP"]);
        $yrsMilitary = intval($row["HAAPRO-YRS-MILITARY-SERVICE"]);
        $yrsNonInstrInDistr = intval($row["HAAPRO-YRS-NONINST-IN-DISTRICT"]);
        $yrsNonInstrOther = intval($row["HAAPRO-YRS-NONINST-OTHER"]);
        $yrsVocational = intval($row["HAAPRO-YRS-VOCATIONAL"]);
        $out["yearsOfPriorProfessionalExperience"] = $yrsAdminExp + $yrsMilitary;
        $out["yearsOfPriorTeachingExperience"] = $yrsTeachInDistr + $yrsTeachFlPublic + $yrsTeachFlNonPublic + $yrsTeachOtherPublic + $yrsTeachOtherNonPublic;


        /* =====================
         * ===   Addresses   ===
         * ===================== */

        /* Add physical address to the record. */
        if ($row["ADDRESS-ID"] !== "0" && !empty(trim($row["STREET-NAME"]))) {
            $out["addresses"][] = array(
                "addressTypeDescriptor"       => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'AddressTypeDescriptor') . "#Physical",
                "stateAbbreviationDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StateAbbreviationDescriptor') . "#" . $row["ZIP-STATE"],
                "city"                        => $row["ZIP-CITY"],
                "postalCode"                  => $row["ZIP-CODE"],
                "streetNumberName"            => (!empty($row["PO-BOX"]) ? "PO BOX " . $row["PO-BOX"] : utility::addressBuilder($row["STREET-NAME"], $row["STREET-NUMBER"], $row["STREET-DIR"])),
                "apartmentRoomSuiteNumber"    => $row["STREET-APPT"],
                "nameOfCounty"                => $row["COUNTY-LDESC"],
            );
        }



        /* =======================
         * ===   Credentials   ===
         * ======================= */

        /* Look for any employee credentials. */
        /* Look for any employee credentials. */
        // Call the helper function we just created, passing the name ID and the formatted date
        // The function will now auto-detect the correct date from config
        $credentials = custom_edfiSuite3::shared_getCredentials($row['NAME-ID']);

        foreach($credentials as $c) {
            /* Populate "FL" as default state, if needed. */
            $c["HPMCEM-CERT-NBR"] = trim($c["HPMCEM-CERT-NBR"]);
            $c["HPMCEM-STATE"] = (empty($c["HPMCEM-STATE"]) ? "FL" : $c["HPMCEM-STATE"]);

            /* Add credential reference to export record. */
            $out["credentials"][] = array(
                "credentialReference" => array(
                    "credentialIdentifier"                    => $c["HPMCEM-CERT-NBR"] . "_" . $c["HAADSC-ID-CERT2"] . "_" . $c["HAADSC-ID-CERT1"], // Cert Num + Subj Area ID + Level ID
                    "stateOfIssueStateAbbreviationDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "StateAbbreviationDescriptor") . "#" . $c["HPMCEM-STATE"]
                )
            );
        }



        /* ============================
         * ===   Electronic Mails   ===
         * ============================ */

        /* Add school email address to the record. */
        if (!empty($row["INTERNET-ADDRESS"])) {
            $out["electronicMails"][] = array(
                "electronicMailTypeDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'ElectronicMailTypeDescriptor') . "#Work",
                "electronicMailAddress"        => $row["INTERNET-ADDRESS"]
            );
        }



        /* ================================
         * ===   Identification Codes   ===
         * ================================ */

        /* Add Skyward Business Name ID to the record. */
        if (!empty(trim($row["NAME-ID"]))) {
            $out["identificationCodes"][] = array(
                "assigningOrganizationIdentificationCode" => "Skyward",
                "staffIdentificationSystemDescriptor"     => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StaffIdentificationSystemDescriptor') . "#Business Name ID",
                "identificationCode"                      => $row["NAME-ID"]
            );
        }

        /* Add SSN to the record. */
        if (!empty(trim($row["FEDERAL-ID-NO"]))) {
            $out["identificationCodes"][] = array(
                "assigningOrganizationIdentificationCode" => "SSN",
                "staffIdentificationSystemDescriptor"     => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StaffIdentificationSystemDescriptor') . "#SSN",
                "identificationCode"                      => $row["FEDERAL-ID-NO"]
            );
        }

        /* Add Employee ID to the record.
         * ALTERNATE-ID is only present in the row when config::$staffUniqueIdField = 'ALTERNATE-ID'.
         * Guard with isset() so no notice is thrown when a different staffUniqueIdField is configured. */
        if (isset($row["ALTERNATE-ID"]) && !empty(trim($row["ALTERNATE-ID"]))) {
            /* Pad employee ID if ID is numeric with less than the minimum length. */
            if ($row["FFAACT-EmpIDSetup-NumLtr-Opt"] === "N" && strlen($row["ALTERNATE-ID"]) < intval($row["FFAACT-EmpIDSetup-Length-Min"])) {
                $row["ALTERNATE-ID"] = str_pad($row["ALTERNATE-ID"], intval($row["FFAACT-EmpIDSetup-Length-Min"]), "0", STR_PAD_LEFT);
            }

            $out["identificationCodes"][] = array(
                "assigningOrganizationIdentificationCode" => "District",
                "staffIdentificationSystemDescriptor"     => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StaffIdentificationSystemDescriptor') . "#Employee ID",
                "identificationCode"                      => $row["ALTERNATE-ID"]
            );
        }

        /* Add FLEID to the record. */
        if (!empty(self::getStaffId($row))) {
            $out["identificationCodes"][] = array(
                "assigningOrganizationIdentificationCode" => "State",
                "staffIdentificationSystemDescriptor"     => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StaffIdentificationSystemDescriptor') . "#FLEID",
                "identificationCode"                      => self::getStaffId($row)
            );
        }

        // TODO: Professional Certificate

        /* Add Skyward Business Alphakey to the record. */
        if (!empty(trim($row["NALPHAKEY"]))) {
            $out["identificationCodes"][] = array(
                "assigningOrganizationIdentificationCode" => "Skyward",
                "staffIdentificationSystemDescriptor"     => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StaffIdentificationSystemDescriptor') . "#Business Alphakey",
                "identificationCode"                      => $row["NALPHAKEY"]
            );
        }

        /* Add Skyward Business Username to the record. */
        if (!empty(trim($row["DUSER-ID"]))) {
            $out["identificationCodes"][] = array(
                "assigningOrganizationIdentificationCode" => "District",
                "staffIdentificationSystemDescriptor"     => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StaffIdentificationSystemDescriptor') . "#Business Username",
                "identificationCode"                      => $row["DUSER-ID"]
            );
        }



        /* =================
         * ===   Races   ===
         * ================= */

        /* Add "Choose Not to Respond" flag to the record for unknown federal races. */
        if (!empty($row["FED-RACE-FLAGS"]) && $row["FED-RACE-FLAGS"] === "00000") {
            $out["races"][] = array("raceDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'RaceDescriptor') . "#Choose Not to Respond");
        }

        /* Add American Indian/Alaskan Native flag to the record. */
        if (!empty($row["FED-RACE-FLAGS"]) && substr($row["FED-RACE-FLAGS"], 0, 1) === "1") {
            $out["races"][] = array("raceDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'RaceDescriptor') . "#American Indian - Alaska Native");
        }

        /* Add Asian flag to the record. */
        if (!empty($row["FED-RACE-FLAGS"]) && substr($row["FED-RACE-FLAGS"], 1, 1) === "1") {
            $out["races"][] = array("raceDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'RaceDescriptor') . "#Asian");
        }

        /* Add African-American/Black flag to the record. */
        if (!empty($row["FED-RACE-FLAGS"]) && substr($row["FED-RACE-FLAGS"], 2, 1) === "1") {
            $out["races"][] = array("raceDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'RaceDescriptor') . "#Black - African American");
        }

        /* Add Native Hawaiian/Pacific Islander flag to the record. */
        if (!empty($row["FED-RACE-FLAGS"]) && substr($row["FED-RACE-FLAGS"], 3, 1) === "1") {
            $out["races"][] = array("raceDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'RaceDescriptor') . "#Native Hawaiian - Pacific Islander");
        }

        /* Add White flag to the record. */
        if (!empty($row["FED-RACE-FLAGS"]) && substr($row["FED-RACE-FLAGS"], 4, 1) === "1") {
            $out["races"][] = array("raceDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'RaceDescriptor') . "#White");
        }



        /* =====================
         * ===   Telephones   ===
         * ===================== */

        /* Add primary phone number to the record. */
        if (!empty($row["PRIMARY-PHONE"]) && strlen($row["PRIMARY-PHONE"]) === 10) {
            $out["telephones"][] = array(
                "telephoneNumberTypeDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TelephoneNumberTypeDescriptor') . "#Home",
                "telephoneNumber"               => $row["PRIMARY-PHONE"],
                "doNotPublishIndicator"         => (empty($row["CONFIDENTIAL-CODE"]) ? false : ($row["CONFIDENTIAL-CODE"] === "1" ? true : false)),
                "orderOfPriority"               => 1
            );
        }



        /* ============================
         * ===   Final Operations   ===
         * ============================ */

        /* Send staff record to the ODS. */
        workhorse::routeProcessorToEgressHandler(array("data" => $out));


        // ==================================== OLD CODE ====================================
        // TODO: Build Credentials Export. Redo credentials lookup here for Suite 3.
        /*
        //add credentials if any
        $db = driver::$ingressClassName::getDBPointer();
        $sql = custom_floridacode_queries_InterchangeStaffAssociation::$shared_getAllCredentials;
        $params = array('nameid' => $row['NAME-ID']);
        $sql = utility::parseTemplate($sql, $params);

        $credentialsRecords = $db->query($sql);
        $numCredentials = count($credentialsRecords);
        $profCertNum = "";
        $profCertType = "";

        if ($numCredentials > 0) {
            for ($credentialIndex = 0; $credentialIndex < $numCredentials; $credentialIndex++) {
                /* Get teaching certificate number.
                if (in_array(trim($credentialsRecords[$credentialIndex]["HAADSC-CODE"]), array("AC", "NP", "RG", "SB", "TB", "TC", "TD", "TM"))) {
                    /* If:
                     *     - A certificate number hasn't been read in at all.
                     *     - A certificate number has been read but this certificate is a non-renewable professional,
                     *       regular professional, or temporary three-year certificate AND the current certificate I have
                     *       is not a regular professional certificate.
                     *     (OR)
                     *     - A certificate number has been read but this certificate is a regular professional.
                    if (empty($profCertNum)) {
                        /* Use the first certificate found, always.
                        $profCertNum = trim($credentialsRecords[$credentialIndex]["HPMCEM-CERT-NBR"]);
                        $profCertType = trim($credentialsRecords[$credentialIndex]["HAADSC-CODE"]);
                    } else {
                        if ($profCertType !== "RG") {
                            if (in_array(trim($credentialsRecords[$credentialIndex]["HAADSC-CODE"]), array("NP", "RG", "TD"))) {
                                $profCertNum = trim($credentialsRecords[$credentialIndex]["HPMCEM-CERT-NBR"]);
                                $profCertType = trim($credentialsRecords[$credentialIndex]["HAADSC-CODE"]);
                            }
                        } elseif (trim($credentialsRecords[$credentialIndex]["HAADSC-CODE"]) === "RG") {
                            $profCertNum = trim($credentialsRecords[$credentialIndex]["HPMCEM-CERT-NBR"]);
                            $profCertType = trim($credentialsRecords[$credentialIndex]["HAADSC-CODE"]);
                        }
                    }
                }

                //get cert areas and grade levels
                $sql = custom_floridacode_queries_InterchangeStaffAssociation::$shared_getCredentialCertAreasAndGradeLevels;
                $params = array('credid' => $credentialsRecords[$credentialIndex]['HPMCEM-ID']);
                $sql = utility::parseTemplate($sql, $params);
                $credentialDetails = $db->query($sql);
                $numDetails = count($credentialDetails);


                if ($numDetails > 0) {
                    for ($detailIndex = 0; $detailIndex < $numDetails; $detailIndex++) {
                        $credentialDetails[$detailIndex] = array_map('trim', $credentialDetails[$detailIndex]);

                        /* Populate issue date with derived date if not populated.
                        if (empty($credentialsRecords[$credentialIndex]['HPMCEM-ISSUE-DATE'])) {
                            $credentialsRecords[$credentialIndex]['HPMCEM-ISSUE-DATE'] = custom_floridacode::deriveCertIssueDateFromExpDate(new DateTime($credentialsRecords[$credentialIndex]['HPMCEM-EXP-DATE']));
                        }

                        //filter out records that aren't grade ranges
                        if (!in_array($credentialDetails[$detailIndex]['GCERT1CODE'], array("7","E")) && !empty($credentialsRecords[$credentialIndex]['HPMCEM-ISSUE-DATE'])) {
                            $recordOut['credentials'][] = array(
                                'credentialFieldDescriptor'         => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'CredentialFieldDescriptor') . '/' . custom_floridacode::attemptConvertCredentialFieldToFloridaCodeEnumeration($credentialDetails[$detailIndex]['GCERT2DESC']),
                                'credentialType'                    => custom_floridacode::attemptConvertCredentialTypeToFloridaCodeEnumeration($credentialsRecords[$credentialIndex]['HAADSC-DESC']),
                                'levelDescriptor'                   => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'LevelDescriptor') . '/' . custom_floridacode::attemptConvertCredentialLevelToFloridaCodeEnumeration($credentialDetails[$detailIndex]['GCERT1DESC']),
                                'teachingCredentialDescriptor'      => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TeachingCredentialDescriptor') . '/' . custom_floridacode::attemptConvertTeachingCredentialTypeToFloridaCodeEnumeration($credentialsRecords[$credentialIndex]['HAADSC-CODE']),
                                'credentialIssuanceDate'            => $credentialsRecords[$credentialIndex]['HPMCEM-ISSUE-DATE'],
                                'credentialExpirationDate'          => $credentialsRecords[$credentialIndex]['HPMCEM-EXP-DATE'],
                                'stateOfIssueStateAbbreviationType' => $credentialsRecords[$credentialIndex]['HPMCEM-STATE']
                            );
                        } else {
                        }

                        $credentialDetails[$detailIndex]['HPMCED-HIGHLY-QUALIFIED-X'] = intval($credentialDetails[$detailIndex]['HPMCED-HIGHLY-QUALIFIED-X']);
                        if ($credentialDetails[$detailIndex]['HPMCED-HIGHLY-QUALIFIED-X'] === 1) {
                            $recordOut['highlyQualifiedTeacher'] = true;
                        } else {
                        }
                    }
                } else {
                }
            }
        } else {
        }*/
    }

    /* === Export #5 === */
    final private static function processRow_StaffAbsenceEvent(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* If hours is negative, this is an allocation record credited back to the employee's bank.
         * Send as an allocation descriptor so the ODS is able to differentiate between the two. */
        if (boolval($row["X-IS-ALLOCATION"])) {
            $row["HTODCD-TOF-CODES"] .= "-A";
        }

        /* Build initial export record. */
        $out = array(
            "absenceEventCategoryDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "AbsenceEventCategoryDescriptor") . "#" . $row["HTODCD-TOF-CODES"],
            "eventDate"                      => $row["HTOTRN-TRANS-DATE"],
            "staffReference"                 => array(
                "staffUniqueId" => self::getStaffId($row)
            ),
            "absenceEventReason"             => substr(utility::stripHTML_trim($row['HTODRS-DESC']), 0, 40),
            "hoursAbsent"                    => floatval($row["HTOTRN-HRS"])
        );

        /* Ship to ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => $out));
    }

    /* === Export #2 === */
    final private static function processRow_StaffEducationOrganizationEmploymentAssociation(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* ===========================================================================
         * ===   Staff Education Organization Employment Association Main Record   ===
         * =========================================================================== */

        /* Build initial export record. */
        $out = array(
            "employmentStatusDescriptor"     => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "EmploymentStatusDescriptor") . "#Contractual",
            "hireDate"                       => $row["HAAPRO-HIRE-DTE"],
            "educationOrganizationReference" => array(
                "educationOrganizationId" => intval(custom_locationproperties::get_districtID()) . $row["HAABLD-BLD-CODE"]
            ),
            "staffReference"                 => array(
                "staffUniqueId" => self::getStaffId($row),
            ),
            "endDate"                        => ($row["HAAPRO-ACTIVE"] === "0" && empty($row["HAAPRO-TERM-DTE"]) ? "1900-01-01" : $row["HAAPRO-TERM-DTE"])
        );

        /* Add separation reason if one is found for the employee. */
        if (trim($row["HPETER-TERM-CODE"]) !== "") {
            if (trim($row["HPETER-STATE-CODE"]) !== "") {
                $out["separationReasonDescriptor"] = custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "SeparationReasonDescriptor") . "#" . $row["HPETER-STATE-CODE"];
            } else {
                $out["separationReasonDescriptor"] = custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "SeparationReasonDescriptor") . "#Z";
            }
        }

        /* If calculated FTE is populated, add to export record. */
        if (!empty($row["SUM-HPMASN-FTE-CALC"] && floatval($row["SUM-HPMASN-FTE-CALC"]) > 0)) {
            $out["fullTimeEquivalency"] = floatval($row["SUM-HPMASN-FTE-CALC"]);
        }


        /* ============================
         * ===   Final Operations   ===
         * ============================ */

        /* Ship to ODS. */
        workhorse::routeProcessorToEgressHandler(array("data" => $out));
    }

    /* === Export #3 === */
    final private static function processRow_StaffEducationOrganizationAssignmentAssociation(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* ===========================
         * ===   Skip Conditions   ===
         * =========================== */
        /* Do not process record if one of these conditions is met. */

        if (empty($row["HAABLD-BLD-CODE"])) {
            log::logAlert("ERROR: Blank Building Code for " . $row["LAST-NAME"] . ", " . $row["FIRST-NAME"] . " (" . self::getStaffId($row) . "). Skipping record...");
            return false;
        }


        /* ===========================================================================
         * ===   Staff Education Organization Assignment Association Main Record   ===
         * =========================================================================== */

        /* Build initial export record. */
        $out = array(
            "beginDate"                                                          => $row["HPMASN-START-DATE"],
            "staffClassificationDescriptor"                                      => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "StaffClassificationDescriptor") . "#" . $row["JOBCODE"],
            "educationOrganizationReference"                                     => array(
                "educationOrganizationId" => intval(custom_locationproperties::get_districtID()) . $row["HAABLD-BLD-CODE"]
            ),
            "employmentStaffEducationOrganizationEmploymentAssociationReference" => array(
                "educationOrganizationId"    => intval(custom_locationproperties::get_districtID()) . $row["HAAPRO-HAABLD-BLD-CODE"],
                "employmentStatusDescriptor" => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, "EmploymentStatusDescriptor") . "#Contractual",
                "hireDate"                   => $row["HAAPRO-HIRE-DTE"],
                "staffUniqueId"              => self::getStaffId($row),
            ),
            "staffReference"                                                     => array(
                "staffUniqueId" => self::getStaffId($row),
            ),
            "endDate"                                                            => $row["HPMASN-END-DATE"],
            "positionTitle"                                                      => $row["HAADSC-DESC-POS"],
            "orderOfAssignment"                                                  => (strpos(strtoupper($row["HAADSC-DESC-POS"]), "SUPPLEMENT") !== false ? 2 : 1)
        );


        /* ============================
         * ===   Final Operations   ===
         * ============================ */

        // Check for duplicate records.
        $hash = md5(json_encode($out));
        if (in_array($hash, self::$tmp_uniqueRecordsHashes) === true) {
            log::logAlert("ERROR: Found duplicate assignment record. Skipping...");
            return false;
        } else {
            self::$tmp_uniqueRecordsHashes[] = $hash;
            workhorse::routeProcessorToEgressHandler(array('data' => $out));
        }
    }

    /* === Export #6 === */
    final private static function processRow_EmployeesWithoutAssignments(array $row, int $exportIndex, array $parseOptions) {
        /* Workflow:
         *     * Look for job code by account distribution (use code with highest percentage total or all in case of tie).
         *     * Look for job code by most recent check (use code with highest percentage total or all in case of tie).
         *     * Look for job code by state reporting parameter set.
         * */

        /* Compute the snapshot date in MM/DD/YYYY for Skyward SQL comparisons. */
        $sqlSnapshotDate = date('m/d/Y', strtotime((config::$useHistoricalData) ? config::$historicalSnapshotDate : date('Y-m-d')));
 
        /* Do pay record account distributions include job codes? No need to read them if they're not used. */
        if (self::$payRecsUseAcctDistJobCodes) {
            /* First, look for job code by pay record account distribution. */
            $db = driver::$ingressClassName::getDBPointer();
            $sql = custom_edfiSuite3::$subqueries["getJobCodeByAccountDistribution"];
            $params = array('nameId' => $row['NAME-ID'], 'snapshotDate' => $sqlSnapshotDate);
            $sql = utility::parseTemplate($sql, $params);
            $acctDists = $db->query($sql);

            /* If we pulled any account distributions, loop through them to generate SEOAA records. */
            if (!empty($acctDists)) {
                $numAcctDists = count($acctDists);
                foreach ($acctDists as $a) {
                    /* If pay record has no start and end dates, use pay control file start and end dates. */
                    if (empty($a["HPAPRM-START-DATE"]) && empty($a["HPAPRM-STOP-DATE"])) {
                        $payStartDate = $a["HPACFP-START-DATE"];
                        $payStopDate = $a["HPACFP-STOP-DATE"];
                    } else {
                        $payStartDate = $a["HPAPRM-START-DATE"];
                        $payStopDate = $a["HPAPRM-STOP-DATE"];
                    }

                    /* Build assignment row record to send to SEOAA function. */
                    $asstRecord = array(
                        "HAABLD-BLD-CODE"        => $row["HAABLD-BLD-CODE"],
                        "HAADSC-DESC-POS"        => custom_floridacode::getStateCodeIndexValueByJobcode($a["HPAPRA-DEPT"], 1),
                        "HAAPRO-HAABLD-BLD-CODE" => $a["HAAPRO-HAABLD-BLD-CODE"],
                        "HAAPRO-HIRE-DTE"        => $a["HAAPRO-HIRE-DTE"],
                        "HAAPRO-OTHER-ID"        => self::getStaffId($row),
                        "HPMASN-END-DATE"        => $payStopDate,
                        "HPMASN-START-DATE"      => $payStartDate,
                        "JOBCODE"                => substr($a["HPAPRA-DEPT"], 0, 5),
                        "NAME-ID"                => $row["NAME-ID"]
                    );

                    /* Send to SEOAA row processor because I'm not rewriting that code here.
                     * (This also will call the "routeProcessorToEgressHandler" handler for me.) */
                    self::processRow_StaffEducationOrganizationAssignmentAssociation($asstRecord, $exportIndex, $parseOptions);
                }

                /* Do not move onto the next check (check history) if we've found job codes in pay records. */
                return;
            }
        }

        /* If no pay record account distributions found, move on to the most recent check. */
        $db = driver::$ingressClassName::getDBPointer();
        $sql = custom_edfiSuite3::$subqueries["getJobCodeByCheckHistory"];
        $params = array('nameId' => $row['NAME-ID'], 'snapshotDate' => $sqlSnapshotDate);
        $sql = utility::parseTemplate($sql, $params);
        $checkInfo = $db->query($sql);

        if (!empty($checkInfo)) {
            foreach ($checkInfo as $c) {
                /* Build assignment row record to send to SEOAA function. */
                $asstRecord = array(
                    "HAABLD-BLD-CODE"        => $row["HAABLD-BLD-CODE"],
                    "HAADSC-DESC-POS"        => custom_floridacode::getStateCodeIndexValueByJobcode($c["HPAHDP-DEPT"], 1),
                    "HAAPRO-HAABLD-BLD-CODE" => $c["HAAPRO-HAABLD-BLD-CODE"],
                    "HAAPRO-HIRE-DTE"        => $c["HAAPRO-HIRE-DTE"],
                    "HAAPRO-OTHER-ID"        => self::getStaffId($row),
                    "HPMASN-END-DATE"        => (!empty($c["HPAPRM-STOP-DATE"]) ? $c["HPAPRM-STOP-DATE"] : $c["HPAHDC-CHK-DTE"]),
                    "HPMASN-START-DATE"      => (!empty($c["HPAPRM-START-DATE"]) ? $c["HPAPRM-START-DATE"] : $c["HPAHDC-CHK-DTE"]),
                    "JOBCODE"                => substr($c["HPAHDP-DEPT"], 0, 5),
                    "NAME-ID"                => $row["NAME-ID"]
                );

                /* Send to SEOAA row processor because I'm not rewriting that code here.
                 * (This also will call the "routeProcessorToEgressHandler" handler for me.) */
                self::processRow_StaffEducationOrganizationAssignmentAssociation($asstRecord, $exportIndex, $parseOptions);
            }

            /* Do not move onto the next check (parameter set) if we've found job codes in the most recent check. */
            return;
        }

        /* Finally, look for a parameter set for the current year to use. */
        $paramSet = self::GetParameterSetsFromSkyward();

        if (!empty($paramSet)) {
            foreach ($paramSet as $p) {
                if (in_array($row["HAAETY-EMP-TYPE-CODE"], $p["includes"]["employeeTypes"]) || in_array($row["HPADCP-PAY-CODE"], $p["includes"]["payCodes"])) {
                    if ($row["HPADCP-PAY-CODE"] === $row["X-REF-HPADCP"]) {
                        
                        // HISTORICAL MODE FIX 1/26/26 ===========================================================================
                        $defaultDate = (config::$useHistoricalData) ? config::$historicalSnapshotDate : date("Y-m-d");
                        // HISTORICAL MODE END ===================================================================================

                        /* Build assignment row record to send to SEOAA function. */
                        $asstRecord = array(
                            "HAABLD-BLD-CODE"   => $row["HAABLD-BLD-CODE"],
                            "HAADSC-DESC-POS"   => custom_floridacode::getStateCodeIndexValueByJobcode($p["X-REF-HAADSC"], 1),
                            "HAAPRO-OTHER-ID"   => self::getStaffId($row),

                            // HISTORICAL MODE FIX 1/26/26 =======================================================================
                            "HPMASN-END-DATE"   => (!empty($row["HPAPRM-STOP-DATE"]) ? $row["HPAPRM-STOP-DATE"] : $defaultDate),
                            "HPMASN-START-DATE" => (!empty($row["HPAPRM-START-DATE"]) ? $row["HPAPRM-START-DATE"] : $defaultDate),
                            // HISTORICAL MODE END ===============================================================================

                            "JOBCODE"           => $p["X-REF-HAADSC"],
                            "NAME-ID"           => $row["NAME-ID"]
                        );

                        /* Send to SEOAA row processor because I'm not rewriting that code here.
                         * (This also will call the "routeProcessorToEgressHandler" handler for me.) */
                        self::processRow_StaffEducationOrganizationAssignmentAssociation($asstRecord, $exportIndex, $parseOptions);

                        /* We done. */
                        return;
                    }
                }
            }
        }

        /* We ain't found nothing. */
        return;
    }

    final private static function GetParameterSetsFromSkyward() {
        $db = driver::$ingressClassName::getDBPointer();
        $sql = custom_edfiSuite3::$subqueries["getJobCodeByStateReportingParameterSet"];
        
        // HISTORICAL MODE FIX 1/26/26 =======================================================================
        // Use the historical snapshot date if we are in historical mode
        $calcDate = (config::$useHistoricalData) ? config::$historicalSnapshotDate : date("Y-m-d");
        // HISTORICAL MODE END ===============================================================================

        $sy = utility::determineSchoolYear($calcDate, 0);
        $params = array('schoolYearXXYY' => substr($sy[0], -2) . substr($sy[1], -2));
        $sql = utility::parseTemplate($sql, $params);
        $return = $db->query($sql);
        $paramSet = array();

        if (!empty($return)) {
            /* Split PARAM-SET-HAAETY and PARAM-SET-HPADCP by CHR(21). */
            foreach ($return as $r) {
                if ($r["X-REF-METHOD"] === "F") {
                    log::logAlert("WARNING: Parameter Set Cross-Ref Method \"F\" found (not implemented)!");
                } else {
                    $paramSet[] = array(
                        //"survey"     => $r["PARAM-SET-SUBM"],
                        "includes"   => array(
                            "employeeTypes" => explode(chr(21), $r["PARAM-SET-HAAETY"]),
                            "payCodes"      => explode(chr(21), $r["PARAM-SET-HPADCP"])
                        ),
                        "xrefMethod" => $r["X-REF-METHOD"],
                        "xref"       => array(
                            "payCode" => $r["X-REF-HPADCP"],
                            "jobCode" => $r["X-REF-HAADSC"]
                        )
                    );
                }
            }
        } else {
            return false;
        }
    }
}

workhorse::processExportJobs();
?>
