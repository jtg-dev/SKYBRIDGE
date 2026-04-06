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
//$jobBasePath = '\\\\sftp.nefec.org\sftpfolders\EDFI\edfi\_logs\\_projects\\EdFi\\' . driver::$cliOptions['dataSource'] . '\\';
$jobBasePath = __DIR__ . '/../../logs/' . driver::$cliOptions['dataSource'] . '/';
fileio::makePath($jobBasePath);
driver::$sharedBuckets['studentSideStaffTableData'] = array();
driver::$sharedBuckets['odsEntities'] = array();


$config = array(
    'logDirectory'                => $jobBasePath,
    'logName'                     => '_EdFi_Descriptors_Suite3.' . $apiDestination . '.log',
    'jobName'                     => 'Ed-Fi Descriptors - Suite 3 (' . driver::$cliOptions['dataSource'] . ')',
    'exportDataBufferSize'        => 100,
    'suppressDuplicateLogEntries' => false,
    'exports'                     => array(

        /* ============================================
         * ===   Human Resources (HR) Descriptors   ===
         * ============================================ */

        /* TPDM descriptors that, as of now, are unused. */
        0 => array(
            'name'    => 'Ed-Fi Suite 3 - Certification Field Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_certificationFieldDescriptors'
            )
        ),

        1 => array(
            'name'    => 'Ed-Fi Suite 3 - Certification Level Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_certificationLevelDescriptors'
            )
        ),

        2 => array(
            'name'    => 'Ed-Fi Suite 3 - Staff Identification Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_staffIdentificationSystemDescriptors'
            )
        ),

        3 => array(
            'name'    => 'Ed-Fi Suite 3 - Separation Reason Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_separationReasonDescriptors'
            )
        ),

        4 => array(
            'name'    => 'Ed-Fi Suite 3 - Level of Education Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_levelOfEducationDescriptors'
            )
        ),

        5 => array(
            'name'    => 'Ed-Fi Suite 3 - Teaching Credential Descriptors',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["teachingCredentialDescriptor"],
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_teachingCredentialDescriptors'
            )
        ),

        6 => array(
            'name'    => 'Ed-Fi Suite 3 - Grade Level Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_gradeLevelDescriptors'
            )
        ),

        7 => array(
            'name'    => 'Ed-Fi Suite 3 - Staff Classification Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_staffClassificationDescriptors'
            )
        ),

        8 => array(
            'name'    => 'Ed-Fi Suite 3 - Employment Status Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_employmentStatusDescriptors'
            )
        ),

        9 => array(
            'name'    => 'Ed-Fi Suite 3 - Citizenship Status Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_citizenshipStatusDescriptors'
            )
        ),

        10 => array(
            'name'    => 'Ed-Fi Suite 3 - Electronic Mail Type Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_electronicMailTypeDescriptors'
            )
        ),

        11 => array(
            'name'    => 'Ed-Fi Suite 3 - Sex Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_sexDescriptors'
            )
        ),

        12 => array(
            'name'    => 'Ed-Fi Suite 3 - Race Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_raceDescriptors'
            )
        ),

        13 => array(
            'name'    => 'Ed-Fi Suite 3 - Telephone Number Type Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_telephoneNumberTypeDescriptors'
            )
        ),

        14 => array(
            'name'    => 'Ed-Fi Suite 3 - Address Type Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_addressTypeDescriptors'
            )
        ),

        15 => array(
            'name'    => 'Ed-Fi Suite 3 - Education Organization Category Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_educationOrganizationCategoryDescriptors'
            )
        ),

        16 => array(
            'name'    => 'Ed-Fi Suite 3 - State Abbreviation Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_stateAbbreviationDescriptors'
            )
        ),

        17 => array(
            'name'    => 'Ed-Fi Suite 3 - Operational Status Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_operationalStatusDescriptors'
            )
        ),

        18 => array(
            'name'    => 'Ed-Fi Suite 3 - Absence Event Category Descriptors',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["absenceEventCategoryDescriptor"],
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_absenceEventCategoryDescriptors'
            )
        ),

        19 => array(
            'name'    => 'Ed-Fi Suite 3 - Credential Field Descriptors',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["credentialFieldDescriptor"],
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_credentialFieldDescriptors'
            )
            /*'output'  => array(
                'type'     => 'json',
                'location' => $jobBasePath . 'CredentialFieldDescriptors.json.txt'
            )*/
        ),

        //

        20 => array(
            'name'    => 'Ed-Fi Suite 3 - Credential Type Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_credentialTypeDescriptors'
            )
        ),

        21 => array(
            'name'    => 'Ed-Fi Suite 3 - School Category Type Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_schoolCategoryDescriptors'
            )
        ),

        22 => array(
            'name'    => 'Ed-Fi Suite 3 - Evaluation Period Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_evaluationPeriodDescriptors'
            )
        ),

        23 => array(
            'name'    => 'Ed-Fi Suite 3 - Performance Evaluation Rating Level Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_performanceEvaluationRatingLevelDescriptors'
            )
        ),

        24 => array(
            'name'    => 'Ed-Fi Suite 3 - Performance Evaluation Type Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_performanceEvaluationTypeDescriptors'
            )
        ),

        25 => array(
            'name'    => 'Ed-Fi Suite 3 - Source System Descriptors',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_sourceSystemDescriptors'
            )
        ),

        26 => array(
            'name'    => 'Ed-Fi Suite 3 - Term Descriptors',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["terms"]
            ),
            'output'  => array(
                'type'                  => 'edfiSuite3',
                'edfi_apiUrlBase'       => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'      => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'  => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_yearBeforeData'   => custom_locationproperties::getConnectionParameters($apiDestination)['yearBeforeData'] ?? false,
                'edfi_apiEndpoint'      => 'o_termDescriptors'
            )
        ),
	
        /* ===============================
         * ===   Student Descriptors   ===
         * =============================== */
		/*
        101 => array(
            'name'    => 'Ed-Fi Suite 3 - Program Characteristic Descriptors',
            'enabled' => false,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_stu')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_stu')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_stu')['password'],
                'sourceQuery' => custom_edfiSuite3::$queries["methodsOfInstruction"]
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific'    => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'        => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_apiEndpoint'         => 'o_programCharacteristicDescriptors',
                'postCurlRestMilliseconds' => 250
            ),
        ),
		*/
        /* ==============================
         * ===   One-Time Resources   ===
         * ============================== */

        // WARNING: Do NOT run these without being sure you need them.
        /*
        901 => array(
            'name'    => 'Ed-Fi Suite 3 - State Education Agency',
            'enabled' => false,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_apiEndpoint'         => 'o_stateEducationAgencies',
                'postCurlRestMilliseconds' => 250
            ),
        ),

        902 => array(
            'name'    => 'Ed-Fi Suite 3 - Local Education Agencies',
            'enabled' => false,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_apiEndpoint'         => 'o_localEducationAgencies',
                'postCurlRestMilliseconds' => 250
            ),
        ),

        903 => array(
            'name'    => 'Ed-Fi Suite 3 - Schools',
            'enabled' => false,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                     => 'edfiSuite3',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters($apiDestination)['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters($apiDestination)['apiClientSecret'],
                'edfi_instanceSpecific' => custom_locationproperties::getConnectionParameters($apiDestination)['instanceSpecific'],
                'edfi_databaseUuid'     => custom_locationproperties::getConnectionParameters($apiDestination)['databaseUuid'],
                'edfi_apiEndpoint'         => 'o_schools',
                'postCurlRestMilliseconds' => 250
            ),
        ),
        */
    )
);

workhorse::init($config);

class jobTransformer implements i_jobTransformation {

    final public static function postQueueRecordProcessHook(int $exportIndex) {
        //This will be executed, once, after each queue record is completed
        /*
            $exportIndex = the array index number of the current queue record being processed
        */
        global $apiDestination;

        switch ($exportIndex) {
            case 26: {
                /* Push a default descriptor. */
                workhorse::routeProcessorToEgressHandler(array('data' => array(
                    'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TermDescriptor'),
                    'codeValue'        => "Default",
                    'shortDescription' => "Default",
                    'description'      => "Default"
                )));
            }
        }
    }

    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

        switch ($exportIndex) {
            case 0:
                self::processRow_CertificationFieldDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 1:
                self::processRow_CertificationLevelDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 2:
                self::processRow_StaffIdentificationDescriptors($row, $exportIndex, $parseOptions);
                break;

            case 3:
                self::processRow_SeparationReasonDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 4:
                self::processRow_LevelOfEducationDescriptors($row, $exportIndex, $parseOptions);
                break;

            case 5:
                self::processRow_TeachingCredentialDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 6:
                self::processRow_GradeLevelDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 7:
                self::processRow_StaffClassificationDescriptors($row, $exportIndex, $parseOptions);
                break;

            case 8:
                self::processRow_EmploymentStatusDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 9:
                self::processRow_CitizenshipStatusDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 10:
                self::processRow_ElectronicMailTypeDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 11:
                self::processRow_SexDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 12:
                self::processRow_RaceDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 13:
                self::processRow_TelephoneNumberTypeDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 14:
                self::processRow_AddressTypeDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 15:
                self::processRow_EducationOrganizationCategoryDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 16:
                self::processRow_StateAbbreviationDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 17:
                self::processRow_OperationalStatusDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 18:
                self::processRow_AbsenceEventCategoryDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 19:
                self::processRow_CredentialFieldDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 20:
                self::processRow_CredentialTypeDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 21:
                self::processRow_SchoolCategoryTypeDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 22:
                self::processRow_EvaluationPeriodDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 23:
                self::processRow_PerformanceEvaluationRatingLevelDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 24:
                self::processRow_PerformanceEvaluationTypeDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 25:
                self::processRow_SourceSystemDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 26:
                self::processRow_TermDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 101:
                self::processRow_ProgramCharacteristicDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 901:
                self::processRow_StateEducationAgency($row, $exportIndex, $parseOptions);
                break;

            case 902:
                self::processRow_LocalEducationAgency($row, $exportIndex, $parseOptions);
                break;

            case 903:
                self::processRow_School($row, $exportIndex, $parseOptions);
                break;
        }
    }

    /* === Export #0 === */
    final private static function processRow_CertificationFieldDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through DOE Academic Coverage codes and ship to ODS. */
        foreach (custom_EdFiSuite3::$certificateSubjectAreas as $code => $desc) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'CertificationFieldDescriptor'),
                'codeValue'        => $code,
                'shortDescription' => $desc,
                'description'      => $desc
            )));
        }
    }

    /* === Export #1 === */
    final private static function processRow_CertificationLevelDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through DOE Academic Coverage Grade Level codes and ship to ODS. */
        foreach (custom_EdFiSuite3::$certificateLevelCodes as $code => $desc) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'CertificationLevelDescriptor'),
                'codeValue'        => $code,
                'shortDescription' => $desc,
                'description'      => $desc
            )));
        }
    }

    /* === Export #2 ===*/
    final private static function processRow_StaffIdentificationDescriptors(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through custom Staff Identification codes and ship to ODS. */
        foreach (custom_EdFiSuite3::$staffIdentificationDescriptors as $sic) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StaffIdentificationSystemDescriptor'),
                'codeValue'        => $sic[0],
                'shortDescription' => $sic[1],
                'description'      => $sic[2]
            )));
        }
    }

    /* === Export #3 === */
    final private static function processRow_SeparationReasonDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through DOE separation reason codes and ship to ODS. */
        foreach (custom_EdFiSuite3::$separationReasonCodes as $code => $desc) {
            if (is_array($desc)) {
                /* Handler for separate short and full descriptions. */
                workhorse::routeProcessorToEgressHandler(array('data' => array(
                    'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'SeparationReasonDescriptor'),
                    'codeValue'        => $code,
                    'shortDescription' => $desc["short"],
                    'description'      => $desc["long"]
                )));
            } else {
                /* Handler for one description. */
                workhorse::routeProcessorToEgressHandler(array('data' => array(
                    'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'SeparationReasonDescriptor'),
                    'codeValue'        => $code,
                    'shortDescription' => $desc,
                    'description'      => $desc
                )));
            }
        }
    }

    /* === Export #4 === */
    final private static function processRow_LevelOfEducationDescriptors(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through level of education descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$levelOfEducationDescriptors as $l) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'LevelOfEducationDescriptor'),
                'codeValue'        => $l,
                'shortDescription' => $l,
                'description'      => $l
            )));
        }
    }

    /* === Export #5 === */
    final private static function processRow_TeachingCredentialDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Format credential field descriptor and ship to ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => array(
            'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TeachingCredentialDescriptor'),
            'codeValue'        => $row["HAADSC-CODE"],
            'shortDescription' => substr(utf8_encode($row["HAADSC-DESC"]), 0, 75),
            'description'      => utf8_encode($row["HAADSC-DESC"]),
        )));

        /* Loop through certificate type codes and ship to ODS. */
      //foreach (custom_EdFiSuite3::$certificateTypeCodes as $code => $desc) {
      //    workhorse::routeProcessorToEgressHandler(array('data' => array(
      //        'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TeachingCredentialDescriptor'),
      //        'codeValue'        => $code,
      //        'shortDescription' => $desc,
      //        'description'      => $desc
      //    )));
      //}
    }

    /* === Export #6 === */
    final private static function processRow_GradeLevelDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through grade levels and ship to ODS. */
        foreach (custom_EdFiSuite3::$gradeLevels as $code => $desc) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'GradeLevelDescriptor'),
                'codeValue'        => $code,
                'shortDescription' => $desc,
                'description'      => $desc
            )));
        }
    }

    /* === Export #7 === */
    final private static function processRow_StaffClassificationDescriptors(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through grade levels and ship to ODS. */
        foreach (custom_EdFiSuite3::$jobCodes as $code => $desc) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StaffClassificationDescriptor'),
                'codeValue'        => $code,
                'shortDescription' => $desc[0],
                'description'      => $desc[1]
            )));
        }
    }

    /* === Export #8 === */
    final private static function processRow_EmploymentStatusDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through grade levels and ship to ODS. */
        foreach (custom_EdFiSuite3::$employmentStatusCodes as $e) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'EmploymentStatusDescriptor'),
                'codeValue'        => $e,
                'shortDescription' => $e,
                'description'      => $e
            )));
        }
    }

    /* === Export #9 === */
    final private static function processRow_CitizenshipStatusDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through citizenship status descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$citizenshipStatuses as $c) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'CitizenshipStatusDescriptor'),
                'codeValue'        => $c,
                'shortDescription' => $c,
                'description'      => $c
            )));
        }
    }

    /* === Export #10 === */
    final private static function processRow_ElectronicMailTypeDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through electronic mail type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$electronicMailTypes as $e) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'ElectronicMailTypeDescriptor'),
                'codeValue'        => $e,
                'shortDescription' => $e,
                'description'      => $e
            )));
        }
    }

    /* === Export #11 === */
    final private static function processRow_SexDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through sex type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$sexTypes as $s) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'SexDescriptor'),
                'codeValue'        => $s,
                'shortDescription' => $s,
                'description'      => $s
            )));
        }
    }

    /* === Export #12 === */
    final private static function processRow_RaceDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through race type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$raceTypes as $r) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'RaceDescriptor'),
                'codeValue'        => $r,
                'shortDescription' => $r,
                'description'      => $r
            )));
        }
    }

    /* === Export #13 === */
    final private static function processRow_TelephoneNumberTypeDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through race type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$telephoneNumberTypes as $t) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TelephoneNumberTypeDescriptor'),
                'codeValue'        => $t,
                'shortDescription' => $t,
                'description'      => $t
            )));
        }
    }

    /* === Export #14 === */
    final private static function processRow_AddressTypeDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through race type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$addressTypes as $a) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'AddressTypeDescriptor'),
                'codeValue'        => $a,
                'shortDescription' => $a,
                'description'      => $a
            )));
        }
    }

    /* === Export #15 === */
    final private static function processRow_EducationOrganizationCategoryDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through education organization category descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$educationOrganizationCategories as $eoc) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'EducationOrganizationCategoryDescriptor'),
                'codeValue'        => $eoc,
                'shortDescription' => $eoc,
                'description'      => $eoc
            )));
        }
    }

    /* === Export #16 === */
    final private static function processRow_StateAbbreviationDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through state abbreviation codes and ship to ODS. */
        foreach (custom_EdFiSuite3::$stateAbbreviationCodes as $code => $desc) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'StateAbbreviationDescriptor'),
                'codeValue'        => $code,
                'shortDescription' => $code,
                'description'      => $desc
            )));
        }
    }

    /* === Export #17 === */
    final private static function processRow_OperationalStatusDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through education organization category descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$operationalStatusDescriptors as $code => $desc) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'OperationalStatusDescriptor'),
                'codeValue'        => $code,
                'shortDescription' => $desc,
                'description'      => $desc
            )));
        }
    }

    /* === Export #18 === */
    final private static function processRow_AbsenceEventCategoryDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Format absence event category descriptor and ship to ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => array(
            'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'AbsenceEventCategoryDescriptor'),
            'codeValue'        => $row["HTODCD-TOF-CODE"],
            'shortDescription' => $row["HTODCD-TOF-CODE"],
            'description'      => $row["HTODCD-DESC"],
        )));

        /* Once more, with feeling. Add a second descriptor for leave allocations. */
        workhorse::routeProcessorToEgressHandler(array('data' => array(
            'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'AbsenceEventCategoryDescriptor'),
            'codeValue'        => $row["HTODCD-TOF-CODE"] . "-A",
            'shortDescription' => $row["HTODCD-TOF-CODE"] . "-A",
            'description'      => $row["HTODCD-DESC"] . " (Allocation)",
        )));
    }

    /* === Export #19 === */
    final private static function processRow_CredentialFieldDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Format credential field descriptor and ship to ODS. */
        workhorse::routeProcessorToEgressHandler(array('data' => array(
            'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'CredentialFieldDescriptor'),
            'codeValue'        => $row["HAADSC-CODE"],
            'shortDescription' => substr(utf8_encode($row["HAADSC-DESC"]), 0, 75),
            'description'      => utf8_encode($row["HAADSC-DESC"]),
        )));
    }

    /* === Export #20 === */
    final private static function processRow_CredentialTypeDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Format credential type descriptor and ship to ODS. */
        foreach (custom_EdFiSuite3::$credentialTypes as $t) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'CredentialTypeDescriptor'),
                'codeValue'        => $t,
                'shortDescription' => $t,
                'description'      => $t
            )));
        }
    }

    /* === Export #21 === */
    final private static function processRow_SchoolCategoryTypeDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through school category type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$schoolCategories as $s) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'SchoolCategoryDescriptor'),
                'codeValue'        => $s,
                'shortDescription' => $s,
                'description'      => $s
            )));
        }
    }

    /* === Export #22 === */
    final private static function processRow_EvaluationPeriodDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through school category type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$evaluationPeriods as $e) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'EvaluationPeriodDescriptor'),
                'codeValue'        => $e,
                'shortDescription' => $e,
                'description'      => $e
            )));
        }
    }

    /* === Export #23 === */
    final private static function processRow_PerformanceEvaluationRatingLevelDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through school category type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$performanceEvaluationRatingLevels as $perl => $v) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'PerformanceEvaluationRatingLevelDescriptor'),
                'codeValue'        => $perl,
                'shortDescription' => $v["shortDescription"],
                'description'      => $v["description"]
            )));
        }
    }

    /* === Export #24 === */
    final private static function processRow_PerformanceEvaluationTypeDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through school category type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$performanceEvaluationTypes as $pet) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'PerformanceEvaluationTypeDescriptor'),
                'codeValue'        => $pet,
                'shortDescription' => $pet,
                'description'      => $pet
            )));
        }
    }

    /* === Export #25 === */
    final private static function processRow_SourceSystemDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Loop through school category type descriptors and ship to ODS. */
        foreach (custom_EdFiSuite3::$sourceSystems as $ss) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'SourceSystemDescriptor'),
                'codeValue'        => $ss,
                'shortDescription' => $ss,
                'description'      => $ss
            )));
        }
    }

    /* === Export #26 === */
    final private static function processRow_TermDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Nothing special. Just shoot it to the egress handler. */
        workhorse::routeProcessorToEgressHandler(array('data' => array(
            'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'TermDescriptor'),
            'codeValue'        => $row["CodeValue"],
            'shortDescription' => $row["Description"],
            'description'      => $row["Description"]
        )));
    }

    /* === Export #101 === */
    final private static function processRow_ProgramCharacteristicDescriptor(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Nothing special. Just shoot it to the egress handler. */
        workhorse::routeProcessorToEgressHandler(array('data' => array(
            'namespace'        => custom_locationproperties::GetDescriptorUrlBaseSuite3($apiDestination, 'ProgramCharacteristicDescriptor'),
            'codeValue'        => $row["CodeValue"],
            'shortDescription' => $row["ShortDescription"],
            'description'      => $row["Description"]
        )));
    }

    /* === Export #901 === */
    final private static function processRow_StateEducationAgency(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;

        /* Shoot it to the egress handler. */
        workhorse::routeProcessorToEgressHandler(array('data' => array(
            "stateEducationAgencyId"      => 12, // ANSI Numeric State Code for Florida (https://www.census.gov/library/reference/code-lists/ansi.html#par_textimage_3)
            "categories"                  => array(
                array(
                    "educationOrganizationCategoryDescriptor" => "uri://ed-fi.org/EducationOrganizationCategoryDescriptor/State Education Agency"
                ),
            ),
            "nameOfInstitution"           => "Florida Department of Education",
            "operationalStatusDescriptor" => "uri://ed-fi.org/OperationalStatusDescriptor/Active",
            "shortNameOfInstitution"      => "FDOE"
        )));
    }

    /* === Export #902 === */
    final private static function processRow_LocalEducationAgency(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;
    }

    /* === Export #903 === */
    final private static function processRow_School(array $row, int $exportIndex, array $parseOptions) {
        global $apiDestination;
    }
}

workhorse::processExportJobs();
?>
