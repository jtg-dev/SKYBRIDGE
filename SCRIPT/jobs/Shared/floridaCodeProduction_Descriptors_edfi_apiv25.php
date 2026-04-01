<?php
require('E:\_scripts7\___core/init.php');


/*
    get CLI Options and Validate
*/
driver::$cliOptions = getopt('', array('dataSource:'));
driver::$cliOptions['dataSource'] = driver::$cliOptions['dataSource'] ?? false;
driver::$cliOptions['dataSource'] = isset(config::$locationProperties[driver::$cliOptions['dataSource']]) ? driver::$cliOptions['dataSource'] : false;

if (driver::$cliOptions['dataSource'] === false) {
    die('Invalid --dataSource');
} else {
}

custom_locationproperties::init(driver::$cliOptions['dataSource']);



/* Begin lines that are specific to this job */
$jobBasePath = 'C:\_scripts\_logs\\' . driver::$cliOptions['dataSource'] . '\FLCODE_Prod_v25\\';
fileio::makePath($jobBasePath);
driver::$sharedBuckets['odsEntities'] = array();
/* End lines that are specific to this job */



$config = array(
    'logDirectory'         => $jobBasePath,
    'jobName'              => 'FloridaCodeProduction_Descriptors_edfi_apiv25_' . driver::$cliOptions['dataSource'],
    'exportDataBufferSize' => 50,
    'exports'              => array(
        0 => array(
            'name'    => 'AccountCodeDescriptor_fund',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        "FAM"."FFAMAM-DIM-1" AS DIM_FUND,
                        "FAM"."FFAMAM-START-DATE",
                        "FAM"."FFAMAM-STOP-DATE"
                    FROM
                        "SKYWARD"."PUB"."FFAMAM-ACCT-MST" AS FAM
                    WHERE
                        --"FAM"."FFAMAM-ACTIVE-FLAG" = 1
                        "FAM"."FFAMAM-DIM-5" IN (%%entities%%)
                ',
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_accountCodeDescriptors'
            )
        ),

        1 => array(
            'name'    => 'AccountCodeDescriptor_type',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        "FAM"."FFAMAM-DIM-2" AS DIM_TYPE,
                        "FAM"."FFAMAM-START-DATE",
                        "FAM"."FFAMAM-STOP-DATE"
                    FROM
                        "SKYWARD"."PUB"."FFAMAM-ACCT-MST" AS FAM
                    WHERE
                        --"FAM"."FFAMAM-ACTIVE-FLAG" = 1
                        "FAM"."FFAMAM-DIM-5" IN (%%entities%%)
                ',
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_accountCodeDescriptors'
            )
        ),

        2 => array(
            'name'    => 'AccountCodeDescriptor_function',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        "FAM"."FFAMAM-DIM-3" AS DIM_FUNCTION,
                        "FAM"."FFAMAM-START-DATE",
                        "FAM"."FFAMAM-STOP-DATE"
                    FROM
                        "SKYWARD"."PUB"."FFAMAM-ACCT-MST" AS FAM
                    WHERE
                        --"FAM"."FFAMAM-ACTIVE-FLAG" = 1
                        "FAM"."FFAMAM-DIM-5" IN (%%entities%%)
                ',
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_accountCodeDescriptors'
            )
        ),

        3 => array(
            'name'    => 'AccountCodeDescriptor_object',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        "FAM"."FFAMAM-DIM-4" AS DIM_OBJECT,
                        "FAM"."FFAMAM-START-DATE",
                        "FAM"."FFAMAM-STOP-DATE"
                    FROM
                        "SKYWARD"."PUB"."FFAMAM-ACCT-MST" AS FAM
                    WHERE
                        --"FAM"."FFAMAM-ACTIVE-FLAG" = 1
                        "FAM"."FFAMAM-DIM-5" IN (%%entities%%)
                ',
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_accountCodeDescriptors'
            )
        ),

        4 => array(
            'name'    => 'AccountCodeDescriptor_facility',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        "FAM"."FFAMAM-DIM-5" AS DIM_FACILITY,
                        "FAM"."FFAMAM-START-DATE",
                        "FAM"."FFAMAM-STOP-DATE"
                    FROM
                        "SKYWARD"."PUB"."FFAMAM-ACCT-MST" AS FAM
                    WHERE
                        --"FAM"."FFAMAM-ACTIVE-FLAG" = 1
                        "FAM"."FFAMAM-DIM-5" IN (%%entities%%)
                ',
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_accountCodeDescriptors'
            )
        ),

        5 => array(
            'name'    => 'AccountCodeDescriptor_project',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        "FAM"."FFAMAM-DIM-6" AS DIM_PROJECT,
                        "FAM"."FFAMAM-START-DATE",
                        "FAM"."FFAMAM-STOP-DATE"
                    FROM
                        "SKYWARD"."PUB"."FFAMAM-ACCT-MST" AS FAM
                    WHERE
                        --"FAM"."FFAMAM-ACTIVE-FLAG" = 1
                        "FAM"."FFAMAM-DIM-5" IN (%%entities%%)
                ',
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_accountCodeDescriptors'
            )
        ),

        6 => array(
            'name'    => 'AccountCodeDescriptor_subproject',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        "FAM"."FFAMAM-DIM-7" AS DIM_SUBPROJECT,
                        "FAM"."FFAMAM-START-DATE",
                        "FAM"."FFAMAM-STOP-DATE"
                    FROM
                        "SKYWARD"."PUB"."FFAMAM-ACCT-MST" AS FAM
                    WHERE
                        --"FAM"."FFAMAM-ACTIVE-FLAG" = 1
                        "FAM"."FFAMAM-DIM-5" IN (%%entities%%)
                ',
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_accountCodeDescriptors'
            )
        ),

        7 => array(
            'name'    => 'AccountCodeDescriptor_program',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        "FAM"."FFAMAM-DIM-8" AS DIM_PROGRAM,
                        "FAM"."FFAMAM-START-DATE",
                        "FAM"."FFAMAM-STOP-DATE"
                    FROM
                        "SKYWARD"."PUB"."FFAMAM-ACCT-MST" AS FAM
                    WHERE
                        --"FAM"."FFAMAM-ACTIVE-FLAG" = 1
                        "FAM"."FFAMAM-DIM-5" IN (%%entities%%)
                ',
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_accountCodeDescriptors'
            )
        ),

        8 => array(
            'name'    => 'CredentialFieldDescriptor',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_credentialFieldDescriptors'
            )
        )
    )
);

workhorse::init($config);

class jobTransformer implements i_jobTransformation {

    final public static function preQueueProcesserLoopHook(array &$config) {
        driver::$sharedBuckets['odsEntities'] = array();

        //get entities from student, will only pull records for entities that exist in ODS already.
        $edfiPointer = new edfi();
        $ret = $edfiPointer->init(custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'], driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID']), driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret']));
        if ($ret !== false) {
            $edfiPointer->loopDataWithCallback('o_schools', array('offset' => 0), array(), function ($record) {
                driver::$sharedBuckets['odsEntities'][] = substr($record['schoolId'], -4);
            });
            driver::$sharedBuckets['odsEntities'][] = '0000';
            driver::$sharedBuckets['odsEntities'] = '\'' . implode('\',\'', driver::$sharedBuckets['odsEntities']) . '\'';
        } else {
            log::logAlert(errors::$edfi_apiFailure);
            die();
        }
    }

    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

        switch ($exportIndex) {
            case 0:
                self::processRow_AccountCodeDescriptor_fund($row, $exportIndex, $parseOptions);
                break;

            case 1:
                self::processRow_AccountCodeDescriptor_type($row, $exportIndex, $parseOptions);
                break;

            case 2:
                self::processRow_AccountCodeDescriptor_function($row, $exportIndex, $parseOptions);
                break;

            case 3:
                self::processRow_AccountCodeDescriptor_object($row, $exportIndex, $parseOptions);
                break;

            case 4:
                self::processRow_AccountCodeDescriptor_facility($row, $exportIndex, $parseOptions);
                break;

            case 5:
                self::processRow_AccountCodeDescriptor_project($row, $exportIndex, $parseOptions);
                break;

            case 6:
                self::processRow_AccountCodeDescriptor_subproject($row, $exportIndex, $parseOptions);
                break;

            case 7:
                self::processRow_AccountCodeDescriptor_program($row, $exportIndex, $parseOptions);
                break;

            case 8:
                self::processRow_CredentialFieldDescriptor($row, $exportIndex, $parseOptions);
                break;
        }
    }

    final private static function processRow_AccountCodeDescriptor_fund(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'namespace'           => custom_locationproperties::get_descriptorURLBase('edfi', 'AccountCodeDescriptor'),
            'codeValue'           => 'FUND_' . strtoupper($row['DIM_FUND']),
            'shortDescription'    => 'FUND_' . strtoupper($row['DIM_FUND']),
            'description'         => 'FUND_' . strtoupper($row['DIM_FUND']),
            'accountCodeCategory' => 'FUND_' . strtoupper($row['DIM_FUND']),
            'beginDate'           => $row['FFAMAM-START-DATE'],
            'endDate'             => $row['FFAMAM-STOP-DATE']
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_AccountCodeDescriptor_type(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'namespace'           => custom_locationproperties::get_descriptorURLBase('edfi', 'AccountCodeDescriptor'),
            'codeValue'           => 'TYPE_' . strtoupper($row['DIM_TYPE']),
            'shortDescription'    => 'TYPE_' . strtoupper($row['DIM_TYPE']),
            'description'         => 'TYPE_' . strtoupper($row['DIM_TYPE']),
            'accountCodeCategory' => 'TYPE_' . strtoupper($row['DIM_TYPE']),
            'beginDate'           => $row['FFAMAM-START-DATE'],
            'endDate'             => $row['FFAMAM-STOP-DATE']
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_AccountCodeDescriptor_function(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'namespace'           => custom_locationproperties::get_descriptorURLBase('edfi', 'AccountCodeDescriptor'),
            'codeValue'           => 'FUNCTION_' . strtoupper($row['DIM_FUNCTION']),
            'shortDescription'    => 'FUNCTION_' . strtoupper($row['DIM_FUNCTION']),
            'description'         => 'FUNCTION_' . strtoupper($row['DIM_FUNCTION']),
            'accountCodeCategory' => 'FUNCTION_' . strtoupper($row['DIM_FUNCTION']),
            'beginDate'           => $row['FFAMAM-START-DATE'],
            'endDate'             => $row['FFAMAM-STOP-DATE']
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_AccountCodeDescriptor_object(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'namespace'           => custom_locationproperties::get_descriptorURLBase('edfi', 'AccountCodeDescriptor'),
            'codeValue'           => 'OBJECT_' . strtoupper($row['DIM_OBJECT']),
            'shortDescription'    => 'OBJECT_' . strtoupper($row['DIM_OBJECT']),
            'description'         => 'OBJECT_' . strtoupper($row['DIM_OBJECT']),
            'accountCodeCategory' => 'OBJECT_' . strtoupper($row['DIM_OBJECT']),
            'beginDate'           => $row['FFAMAM-START-DATE'],
            'endDate'             => $row['FFAMAM-STOP-DATE']
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_AccountCodeDescriptor_facility(array $row, int $exportIndex, array $parseOptions) {
        $row['DIM_FACILITY'] = ($row['DIM_FACILITY'] === '0000') ? intval(custom_locationproperties::get_districtID()) : intval(custom_locationproperties::get_districtID()) . $row['DIM_FACILITY'];

        $recordOut = array(
            'namespace'           => custom_locationproperties::get_descriptorURLBase('edfi', 'AccountCodeDescriptor'),
            'codeValue'           => 'FACILITY_' . strtoupper($row['DIM_FACILITY']),
            'shortDescription'    => 'FACILITY_' . strtoupper($row['DIM_FACILITY']),
            'description'         => 'FACILITY_' . strtoupper($row['DIM_FACILITY']),
            'accountCodeCategory' => 'FACILITY_' . strtoupper($row['DIM_FACILITY']),
            'beginDate'           => $row['FFAMAM-START-DATE'],
            'endDate'             => $row['FFAMAM-STOP-DATE']
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_AccountCodeDescriptor_project(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'namespace'           => custom_locationproperties::get_descriptorURLBase('edfi', 'AccountCodeDescriptor'),
            'codeValue'           => 'PROJECT_' . strtoupper($row['DIM_PROJECT']),
            'shortDescription'    => 'PROJECT_' . strtoupper($row['DIM_PROJECT']),
            'description'         => 'PROJECT_' . strtoupper($row['DIM_PROJECT']),
            'accountCodeCategory' => 'PROJECT_' . strtoupper($row['DIM_PROJECT']),
            'beginDate'           => $row['FFAMAM-START-DATE'],
            'endDate'             => $row['FFAMAM-STOP-DATE']
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_AccountCodeDescriptor_subproject(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'namespace'           => custom_locationproperties::get_descriptorURLBase('edfi', 'AccountCodeDescriptor'),
            'codeValue'           => 'SUBPROJECT_' . strtoupper($row['DIM_SUBPROJECT']),
            'shortDescription'    => 'SUBPROJECT_' . strtoupper($row['DIM_SUBPROJECT']),
            'description'         => 'SUBPROJECT_' . strtoupper($row['DIM_SUBPROJECT']),
            'accountCodeCategory' => 'SUBPROJECT_' . strtoupper($row['DIM_SUBPROJECT']),
            'beginDate'           => $row['FFAMAM-START-DATE'],
            'endDate'             => $row['FFAMAM-STOP-DATE']
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_AccountCodeDescriptor_program(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'namespace'           => custom_locationproperties::get_descriptorURLBase('edfi', 'AccountCodeDescriptor'),
            'codeValue'           => 'PROGRAM_' . strtoupper($row['DIM_PROGRAM']),
            'shortDescription'    => 'PROGRAM_' . strtoupper($row['DIM_PROGRAM']),
            'description'         => 'PROGRAM_' . strtoupper($row['DIM_PROGRAM']),
            'accountCodeCategory' => 'PROGRAM_' . strtoupper($row['DIM_PROGRAM']),
            'beginDate'           => $row['FFAMAM-START-DATE'],
            'endDate'             => $row['FFAMAM-STOP-DATE']
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_CredentialFieldDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $array = array(
            'English Language Arts',
            'Reading',
            'Mathematics',
            'Life and Physical Sciences',
            'Social Sciences and History',
            'Social Studies',
            'Science',
            'Fine and Performing Arts',
            'Foreign Language and Literature',
            'Writing',
            'Physical, Health, and Safety Education',
            'Career and Technical Education',
            'Religious Education and Theology',
            'Military Science',
            'Other'
        );

        $limit = count($array);
        for ($i = 0; $i < $limit; $i++) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'                 => custom_locationproperties::get_descriptorURLBase('edfi', 'credentialFieldDescriptors'),
                'codeValue'                 => $array[$i],
                'shortDescription'          => $array[$i],
                'description'               => $array[$i],
                'academicSubjectDescriptor' => $array[$i]
            )));
        }
    }
}

workhorse::processExportJobs();
?>