<?php
//include the init script
require(__DIR__ . '/../../___core/init.php');


//get location properties object
custom_locationproperties::init('baker');










/*
    Begin lines that are specific to this job
*/
$dto = new DateTimeImmutable();
//$jobBasePath = '\\\\sftp.nefec.org\\sftpfolders\\EDFI\\edfi\\QA\\StaffFiles-SkywardBusiness\\' . $ymd . '\\';
$jobBasePath = 'C:\\Users\\fordm.c\\Desktop\\genericdatamigrator\\';
fileio::makePath($jobBasePath);
driver::$sharedBuckets['odsEntities'] = array();
config::$exportDataBufferSize = 80;
/*
    End lines that are specific to this job
*/








$config = array (
        'logDirectory' => $jobBasePath,
        'jobName' => 'FloridaCode_Descriptors_edfi_apiv20',
        'exports' => array (
                0 => array (
                        'name' => 'LevelOfEducationDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_levelOfEducationDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_levelOfEducationDescriptors'
                                )
                    ),

                1 => array (
                        'name' => 'CredentialFieldDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_credentialFieldDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_credentialFieldDescriptors'
                                )
                    ),

                2 => array (
                        'name' => 'GradeLevelDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_gradeLevelDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_gradeLevelDescriptors'
                                )
                    ),

                3 => array (
                        'name' => 'TeachingCredentialDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_teachingCredentialDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_teachingCredentialDescriptors'
                                )
                    ),

                4 => array (
                        'name' => 'SeparationReasonDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_separationReasonDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_separationReasonDescriptors'
                                )
                    ),

                5 => array (
                        'name' => 'StaffClassificationDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_staffClassificationDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_staffClassificationDescriptors'
                                )
                    ),

                6 => array (
                        'name' => 'ProgramAssignmentDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_programAssignmentDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_programAssignmentDescriptors'
                                )
                    ),

                7 => array (
                        'name' => 'EmploymentStatusDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_employmentStatusDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_employmentStatusDescriptors'
                                )
                    ),

                8 => array (
                        'name' => 'AccountCodeDescriptor_fund',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['bakerFinTrn']['DSN'],
                                    'username' => config::$databaseDSNArray['bakerFinTrn']['username'],
                                    'password' => config::$databaseDSNArray['bakerFinTrn']['password'],
                                    'sourceQuery' => 'SELECT DISTINCT
                                                            FAM."FFAMAM-DIM-1" AS DIM_FUND,
                                                            FAM."FFAMAM-START-DATE",
                                                            FAM."FFAMAM-STOP-DATE"
                                                        FROM
                                                            pub."FFAMAM-ACCT-MST" AS FAM
                                                        WHERE
                                                            --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                                            FAM."FFAMAM-DIM-5" IN (%%entities%%)',
                                    'queryParams' => array (
                                            'entities' => &driver::$sharedBuckets['odsEntities']
                                        )
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_accountCodeDescriptors'
                                )
                    ),

                9 => array (
                        'name' => 'AccountCodeDescriptor_type',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['bakerFinTrn']['DSN'],
                                    'username' => config::$databaseDSNArray['bakerFinTrn']['username'],
                                    'password' => config::$databaseDSNArray['bakerFinTrn']['password'],
                                    'sourceQuery' => 'SELECT DISTINCT
                                                            FAM."FFAMAM-DIM-2" AS DIM_TYPE,
                                                            FAM."FFAMAM-START-DATE",
                                                            FAM."FFAMAM-STOP-DATE"
                                                        FROM
                                                            pub."FFAMAM-ACCT-MST" AS FAM
                                                        WHERE
                                                            --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                                            FAM."FFAMAM-DIM-5" IN (%%entities%%)',
                                    'queryParams' => array (
                                            'entities' => &driver::$sharedBuckets['odsEntities']
                                        )
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_accountCodeDescriptors'
                                )
                    ),

                10 => array (
                        'name' => 'AccountCodeDescriptor_function',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['bakerFinTrn']['DSN'],
                                    'username' => config::$databaseDSNArray['bakerFinTrn']['username'],
                                    'password' => config::$databaseDSNArray['bakerFinTrn']['password'],
                                    'sourceQuery' => 'SELECT DISTINCT
                                                            FAM."FFAMAM-DIM-3" AS DIM_FUNCTION,
                                                            FAM."FFAMAM-START-DATE",
                                                            FAM."FFAMAM-STOP-DATE"
                                                        FROM
                                                            pub."FFAMAM-ACCT-MST" AS FAM
                                                        WHERE
                                                            --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                                            FAM."FFAMAM-DIM-5" IN (%%entities%%)',
                                    'queryParams' => array (
                                            'entities' => &driver::$sharedBuckets['odsEntities']
                                        )
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_accountCodeDescriptors'
                                )
                    ),

                11 => array (
                        'name' => 'AccountCodeDescriptor_object',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['bakerFinTrn']['DSN'],
                                    'username' => config::$databaseDSNArray['bakerFinTrn']['username'],
                                    'password' => config::$databaseDSNArray['bakerFinTrn']['password'],
                                    'sourceQuery' => 'SELECT DISTINCT
                                                            FAM."FFAMAM-DIM-4" AS DIM_OBJECT,
                                                            FAM."FFAMAM-START-DATE",
                                                            FAM."FFAMAM-STOP-DATE"
                                                        FROM
                                                            pub."FFAMAM-ACCT-MST" AS FAM
                                                        WHERE
                                                            --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                                            FAM."FFAMAM-DIM-5" IN (%%entities%%)',
                                    'queryParams' => array (
                                            'entities' => &driver::$sharedBuckets['odsEntities']
                                        )
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_accountCodeDescriptors'
                                )
                    ),

                12 => array (
                        'name' => 'AccountCodeDescriptor_facility',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['bakerFinTrn']['DSN'],
                                    'username' => config::$databaseDSNArray['bakerFinTrn']['username'],
                                    'password' => config::$databaseDSNArray['bakerFinTrn']['password'],
                                    'sourceQuery' => 'SELECT DISTINCT
                                                            FAM."FFAMAM-DIM-5" AS DIM_FACILITY,
                                                            FAM."FFAMAM-START-DATE",
                                                            FAM."FFAMAM-STOP-DATE"
                                                        FROM
                                                            pub."FFAMAM-ACCT-MST" AS FAM
                                                        WHERE
                                                            --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                                            FAM."FFAMAM-DIM-5" IN (%%entities%%)',
                                    'queryParams' => array (
                                            'entities' => &driver::$sharedBuckets['odsEntities']
                                        )
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_accountCodeDescriptors'
                                )
                    ),

                13 => array (
                        'name' => 'AccountCodeDescriptor_project',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['bakerFinTrn']['DSN'],
                                    'username' => config::$databaseDSNArray['bakerFinTrn']['username'],
                                    'password' => config::$databaseDSNArray['bakerFinTrn']['password'],
                                    'sourceQuery' => 'SELECT DISTINCT
                                                            FAM."FFAMAM-DIM-6" AS DIM_PROJECT,
                                                            FAM."FFAMAM-START-DATE",
                                                            FAM."FFAMAM-STOP-DATE"
                                                        FROM
                                                            pub."FFAMAM-ACCT-MST" AS FAM
                                                        WHERE
                                                            --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                                            FAM."FFAMAM-DIM-5" IN (%%entities%%)',
                                    'queryParams' => array (
                                            'entities' => &driver::$sharedBuckets['odsEntities']
                                        )
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_accountCodeDescriptors'
                                )
                    ),

                14 => array (
                        'name' => 'AccountCodeDescriptor_subproject',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['bakerFinTrn']['DSN'],
                                    'username' => config::$databaseDSNArray['bakerFinTrn']['username'],
                                    'password' => config::$databaseDSNArray['bakerFinTrn']['password'],
                                    'sourceQuery' => 'SELECT DISTINCT
                                                            FAM."FFAMAM-DIM-7" AS DIM_SUBPROJECT,
                                                            FAM."FFAMAM-START-DATE",
                                                            FAM."FFAMAM-STOP-DATE"
                                                        FROM
                                                            pub."FFAMAM-ACCT-MST" AS FAM
                                                        WHERE
                                                            --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                                            FAM."FFAMAM-DIM-5" IN (%%entities%%)',
                                    'queryParams' => array (
                                            'entities' => &driver::$sharedBuckets['odsEntities']
                                        )
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_accountCodeDescriptors'
                                )
                    ),

                15 => array (
                        'name' => 'AccountCodeDescriptor_program',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['bakerFinTrn']['DSN'],
                                    'username' => config::$databaseDSNArray['bakerFinTrn']['username'],
                                    'password' => config::$databaseDSNArray['bakerFinTrn']['password'],
                                    'sourceQuery' => 'SELECT DISTINCT
                                                            FAM."FFAMAM-DIM-8" AS DIM_PROGRAM,
                                                            FAM."FFAMAM-START-DATE",
                                                            FAM."FFAMAM-STOP-DATE"
                                                        FROM
                                                            pub."FFAMAM-ACCT-MST" AS FAM
                                                        WHERE
                                                            --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                                            FAM."FFAMAM-DIM-5" IN (%%entities%%)',
                                    'queryParams' => array (
                                            'entities' => &driver::$sharedBuckets['odsEntities']
                                        )
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_accountCodeDescriptors'
                                )
                    ),

                16 => array (
                        'name' => 'LevelDescriptor',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_loopURLKey' => 'o_levelDescriptors'
                                ),
                        'output' => array (
                                    'type' => 'edfi',
                                    'edfi_apiUrlBase' => custom_locationproperties::get_edfi_apiBaseURL(),
                                    'edfi_apiClientID' => custom_locationproperties::get_edfi_clientID(),
                                    'edfi_apiClientSecret' => custom_locationproperties::get_edfi_clientSecret(),
                                    'edfi_apiEndpoint' => 'o_levelDescriptors'
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
        $ret = $edfiPointer->init(custom_locationproperties::get_edfi_apiBaseURL(), driver::$crypto->decrypt(custom_locationproperties::get_edfi_clientID()), driver::$crypto->decrypt(custom_locationproperties::get_edfi_clientSecret()));
        if ($ret !== false) {
            $edfiPointer->loopDataWithCallback('o_schools', array('offset' => 0), array(), function($record) {
                    driver::$sharedBuckets['odsEntities'][] = substr($record['schoolId'], -4);
                });
            driver::$sharedBuckets['odsEntities'][] = '0000';
            driver::$sharedBuckets['odsEntities'] = '\'' . implode('\',\'', driver::$sharedBuckets['odsEntities']) . '\'';
        }
        else {
            log::logAlert(errors::$edfi_apiFailure);
            die();
        }
    }

    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

        switch ($exportIndex) {
            case 0:
                self::processRow_LevelOfEducationDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 1:
                self::processRow_CredentialFieldDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 2:
                self::processRow_GradeLevelDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 3:
                self::processRow_TeachingCredentialDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 4:
                self::processRow_SeparationReasonDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 5:
                self::processRow_StaffClassificationDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 6:
                self::processRow_ProgramAssignmentDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 7:
                self::processRow_EmploymentStatusDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 8:
                self::processRow_AccountCodeDescriptor_fund($row, $exportIndex, $parseOptions);
                break;

            case 9:
                self::processRow_AccountCodeDescriptor_type($row, $exportIndex, $parseOptions);
                break;

            case 10:
                self::processRow_AccountCodeDescriptor_function($row, $exportIndex, $parseOptions);
                break;

            case 11:
                self::processRow_AccountCodeDescriptor_object($row, $exportIndex, $parseOptions);
                break;

            case 12:
                self::processRow_AccountCodeDescriptor_facility($row, $exportIndex, $parseOptions);
                break;

            case 13:
                self::processRow_AccountCodeDescriptor_project($row, $exportIndex, $parseOptions);
                break;

            case 14:
                self::processRow_AccountCodeDescriptor_subproject($row, $exportIndex, $parseOptions);
                break;

            case 15:
                self::processRow_AccountCodeDescriptor_program($row, $exportIndex, $parseOptions);
                break;

            case 16:
                self::processRow_LevelDescriptor($row, $exportIndex, $parseOptions);
                break;
        }
    }

    final private static function processRow_LevelOfEducationDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('LevelOfEducationDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description'],
                    'levelOfEducationType' => $row['levelOfEducationType']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }

    final private static function processRow_CredentialFieldDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('CredentialFieldDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }

    final private static function processRow_GradeLevelDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('GradeLevelDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description'],
                    'gradeLevelType' => $row['gradeLevelType']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }

    final private static function processRow_TeachingCredentialDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('TeachingCredentialDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description'],
                    'teachingCredentialType' => $row['teachingCredentialType']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }

    final private static function processRow_SeparationReasonDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('SeparationReasonDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description'],
                    'separationReasonType' => $row['separationReasonType']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }

    final private static function processRow_StaffClassificationDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('StaffClassificationDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description'],
                    'staffClassificationType' => $row['staffClassificationType']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }

    final private static function processRow_ProgramAssignmentDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('ProgramAssignmentDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description'],
                    'programAssignmentType' => $row['programAssignmentType']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }

    final private static function processRow_EmploymentStatusDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('EmploymentStatusDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description'],
                    'employmentStatusType' => $row['employmentStatusType']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }

    final private static function processRow_AccountCodeDescriptor_fund(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array (
                'namespace' => custom_locationproperties::get_descriptorURLBase('AccountCodeDescriptor'),
                'codeValue' => 'FUND_' . $row['DIM_FUND'],
                'shortDescription' => 'FUND_' . $row['DIM_FUND'],
                'description' => 'FUND_' . $row['DIM_FUND'],
                'accountCodeCategory' => 'FUND_' . $row['DIM_FUND'],
                'beginDate' => $row['FFAMAM-START-DATE'],
                'endDate' => $row['FFAMAM-STOP-DATE']
            );

        workhorse::routeProcessorToEgressHandler($recordOut);
    }

    final private static function processRow_AccountCodeDescriptor_type(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array (
                'namespace' => custom_locationproperties::get_descriptorURLBase('AccountCodeDescriptor'),
                'codeValue' => 'TYPE_' . $row['DIM_TYPE'],
                'shortDescription' => 'TYPE_' . $row['DIM_TYPE'],
                'description' => 'TYPE_' . $row['DIM_TYPE'],
                'accountCodeCategory' => 'TYPE_' . $row['DIM_TYPE'],
                'beginDate' => $row['FFAMAM-START-DATE'],
                'endDate' => $row['FFAMAM-STOP-DATE']
            );

        workhorse::routeProcessorToEgressHandler($recordOut);
    }

    final private static function processRow_AccountCodeDescriptor_function(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array (
                'namespace' => custom_locationproperties::get_descriptorURLBase('AccountCodeDescriptor'),
                'codeValue' => 'FUNCTION_' . $row['DIM_FUNCTION'],
                'shortDescription' => 'FUNCTION_' . $row['DIM_FUNCTION'],
                'description' => 'FUNCTION_' . $row['DIM_FUNCTION'],
                'accountCodeCategory' => 'FUNCTION_' . $row['DIM_FUNCTION'],
                'beginDate' => $row['FFAMAM-START-DATE'],
                'endDate' => $row['FFAMAM-STOP-DATE']
            );

        workhorse::routeProcessorToEgressHandler($recordOut);
    }

    final private static function processRow_AccountCodeDescriptor_object(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array (
                'namespace' => custom_locationproperties::get_descriptorURLBase('AccountCodeDescriptor'),
                'codeValue' => 'OBJECT_' . $row['DIM_OBJECT'],
                'shortDescription' => 'OBJECT_' . $row['DIM_OBJECT'],
                'description' => 'OBJECT_' . $row['DIM_OBJECT'],
                'accountCodeCategory' => 'OBJECT_' . $row['DIM_OBJECT'],
                'beginDate' => $row['FFAMAM-START-DATE'],
                'endDate' => $row['FFAMAM-STOP-DATE']
            );

        workhorse::routeProcessorToEgressHandler($recordOut);
    }

    final private static function processRow_AccountCodeDescriptor_facility(array $row, int $exportIndex, array $parseOptions) {
        $row['DIM_FACILITY'] = ($row['DIM_FACILITY'] === '0000') ? intval(custom_locationproperties::get_districtID()) : intval(custom_locationproperties::get_districtID()) . $row['DIM_FACILITY'];

        $recordOut = array (
                'namespace' => custom_locationproperties::get_descriptorURLBase('AccountCodeDescriptor'),
                'codeValue' => 'FACILITY_' . $row['DIM_FACILITY'],
                'shortDescription' => 'FACILITY_' . $row['DIM_FACILITY'],
                'description' => 'FACILITY_' . $row['DIM_FACILITY'],
                'accountCodeCategory' => 'FACILITY_' . $row['DIM_FACILITY'],
                'beginDate' => $row['FFAMAM-START-DATE'],
                'endDate' => $row['FFAMAM-STOP-DATE']
            );

        workhorse::routeProcessorToEgressHandler($recordOut);
    }

    final private static function processRow_AccountCodeDescriptor_project(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array (
                'namespace' => custom_locationproperties::get_descriptorURLBase('AccountCodeDescriptor'),
                'codeValue' => 'PROJECT_' . $row['DIM_PROJECT'],
                'shortDescription' => 'PROJECT_' . $row['DIM_PROJECT'],
                'description' => 'PROJECT_' . $row['DIM_PROJECT'],
                'accountCodeCategory' => 'PROJECT_' . $row['DIM_PROJECT'],
                'beginDate' => $row['FFAMAM-START-DATE'],
                'endDate' => $row['FFAMAM-STOP-DATE']
            );

        workhorse::routeProcessorToEgressHandler($recordOut);
    }

    final private static function processRow_AccountCodeDescriptor_subproject(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array (
                'namespace' => custom_locationproperties::get_descriptorURLBase('AccountCodeDescriptor'),
                'codeValue' => 'SUBPROJECT_' . $row['DIM_SUBPROJECT'],
                'shortDescription' => 'SUBPROJECT_' . $row['DIM_SUBPROJECT'],
                'description' => 'SUBPROJECT_' . $row['DIM_SUBPROJECT'],
                'accountCodeCategory' => 'SUBPROJECT_' . $row['DIM_SUBPROJECT'],
                'beginDate' => $row['FFAMAM-START-DATE'],
                'endDate' => $row['FFAMAM-STOP-DATE']
            );

        workhorse::routeProcessorToEgressHandler($recordOut);
    }

    final private static function processRow_AccountCodeDescriptor_program(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array (
                'namespace' => custom_locationproperties::get_descriptorURLBase('AccountCodeDescriptor'),
                'codeValue' => 'PROGRAM_' . $row['DIM_PROGRAM'],
                'shortDescription' => 'PROGRAM_' . $row['DIM_PROGRAM'],
                'description' => 'PROGRAM_' . $row['DIM_PROGRAM'],
                'accountCodeCategory' => 'PROGRAM_' . $row['DIM_PROGRAM'],
                'beginDate' => $row['FFAMAM-START-DATE'],
                'endDate' => $row['FFAMAM-STOP-DATE']
            );

        workhorse::routeProcessorToEgressHandler($recordOut);
    }

    final private static function processRow_LevelDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            $recordOut = array (
                    'namespace' => custom_locationproperties::get_descriptorURLBase('LevelDescriptor'),
                    'codeValue' => $row['codeValue'],
                    'shortDescription' => $row['shortDescription'],
                    'description' => $row['description'],
                    'gradeLevels' => $row['gradeLevels']
                );

            workhorse::routeProcessorToEgressHandler($recordOut);
        } else {}
    }
}

workhorse::processExportJobs();
?>