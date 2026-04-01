<?php
require('E:\_scripts7\___core/init.php');

/* get CLI Options and Validate */
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
driver::$sharedBuckets['studentSideStaffTableData'] = array();
driver::$sharedBuckets['odsEntities'] = array();
/* End lines that are specific to this job */


$config = array(
    'logDirectory'         => $jobBasePath,
    'jobName'              => 'FloridaCodeProduction_InterchangeStaffAssociation_edfi_apiv25_' . driver::$cliOptions['dataSource'],
    'exportDataBufferSize' => 50,
    'exports'              => array(
        0 => array(
            'name'    => 'Staff',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => custom_floridacode_queries_InterchangeStaffAssociation::$shared_Staff,
                'queryParams' => array(
                    'currentsy' => driver::$currentSY[0],
                    //'nameIDs'   => &driver::$sharedBuckets['nameIDs']
                )
            ),
            'output'  => array(
                'type'                     => 'edfi',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'         => 'o_staffs',
                'postCurlRestMilliseconds' => 250
            )
        ),

        1 => array(
            'name'    => 'StaffEducationOrganizationEmploymentAssociation',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => custom_floridacode_queries_InterchangeStaffAssociation::$shared_StaffEducationOrganizationEmploymentAssociation,
                'queryParams' => array(
                    //'nameIDs'  => &driver::$sharedBuckets['nameIDs'],
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                     => 'edfi',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'         => 'o_staffEducationOrganizationEmploymentAssociations',
                'postCurlRestMilliseconds' => 250
            )
        ),

        2 => array(
            'name'    => 'StaffEducationOrganizationAssignmentAssociation',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => custom_floridacode_queries_InterchangeStaffAssociation::$shared_StaffEducationOrganizationAssignmentAssociation,
                'queryParams' => array(
                    'currentsy' => driver::$currentSY[0],
                    //'nameIDs'   => &driver::$sharedBuckets['nameIDs'],
                    'entities'  => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                     => 'edfi',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'         => 'o_staffEducationOrganizationAssignmentAssociations',
                'postCurlRestMilliseconds' => 250
            )
        ),

        /*
        3 => array(
            'name'    => 'StaffSchoolAssociation',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => custom_floridacode_queries_InterchangeStaffAssociation::$shared_StaffSchoolAssociation,
                'queryParams' => array(
                    'currentsy' => driver::$currentSY[0],
                  //'nameIDs'   => &driver::$sharedBuckets['nameIDs']
                )
            ),
            'output'  => array(
                'type'                     => 'edfi',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'         => 'o_staffSchoolAssociations',
                'postCurlRestMilliseconds' => 250
            )
        ),
        */

        4 => array(
            'name'    => 'LeaveEvent',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => custom_floridacode_queries_InterchangeStaffAssociation::$shared_LeaveEvent,
                'queryParams' => array(
                    'currentsy' => driver::$currentSY[0],
                    //'startdate' => driver::$dto->setTimestamp(strtotime('-365 days'))->format('Y-m-d'),
                    'enddate'   => driver::$dto->format('Y-m-d'),
                    //'nameIDs'   => &driver::$sharedBuckets['nameIDs']
                )
            ),
            'output'  => array(
                'type'                     => 'edfi',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'         => 'o_leaveEvents',
                'postCurlRestMilliseconds' => 250
            )
        ),

        5 => array(
            'name'    => 'OpenStaffPosition',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('skyward')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('skyward')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('skyward')['password'],
                'sourceQuery' => custom_floridacode_queries_InterchangeStaffAssociation::$shared_OpenStaffPosition,
                'queryParams' => array(
                    'entities' => &driver::$sharedBuckets['odsEntities']
                )
            ),
            'output'  => array(
                'type'                     => 'edfi',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'         => 'o_openStaffPositions',
                'postCurlRestMilliseconds' => 250
            )
        ),

        /*
        6 => array(
            'name'    => 'StaffSectionAssociation',
            'enabled' => true,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_stu')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_stu')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_stu')['password'],
                'sourceQuery' => custom_floridacode_queries_InterchangeStaffAssociation::$shared_StaffSectionAssociation,
                'queryParams' => array(
                    'currentsy' => driver::$currentSY[1],
                  //'nameIDs' => &driver::$sharedBuckets['nameIDs']
                )
            ),
            'output'  => array(
                'type'                     => 'edfi',
                'edfi_apiUrlBase'          => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'         => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'         => 'o_staffSectionAssociations',
                'postCurlRestMilliseconds' => 250
            )
        )
        */
    )
);

workhorse::init($config);

class jobTransformer implements i_jobTransformation {

    private static $tmp_uniqueRecordsHashes = array();
    private static $db = null;
    private static $payRecsUseAcctDistJobCodes;

    final public static function preQueueProcesserLoopHook(array &$config) {
        /*
        //pull SSNs from student staff table
        driver::$sharedBuckets['studentSideStaffTableData'] = array();
        driver::$sharedBuckets['odsEntities'] = array();


        //grab staff data from student DB for name-id translations
        self::$db = new database_odbc();
        $ret = self::$db->connect(custom_locationproperties::getConnectionParameters('prod_skyward_stu')['DSN'], driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('prod_skyward_stu')['username']), driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('prod_skyward_stu')['password']));

        if ($ret !== false) {
            $sql = custom_floridacode_queries_InterchangeStaffAssociation::$shared_pullUserAccountComparisonRecordsFromStudentDB;

            $ret = self::$db->justquery($sql);
            if ($ret !== false) {
                $ssns = $emails = array();
                while ($row = odbc_fetch_array($ret)) {
                    $row['FEDERAL-ID-NO'] = trim($row['FEDERAL-ID-NO']);
                    $row['INTERNET-ADDRESS'] = str_replace('\'', '\'\'', trim($row['INTERNET-ADDRESS']));

                    if ($row['FEDERAL-ID-NO'] != '') {
                        $ssns[] = $row['FEDERAL-ID-NO'];
                    } else {
                    }

                    if ($row['INTERNET-ADDRESS'] != '') {
                        $emails[] = $row['INTERNET-ADDRESS'];
                    } else {
                    }

                    driver::$sharedBuckets['studentSideStaffTableData'][] = array(
                        'name-id' => $row['NAME-ID'],
                        'email'   => $row['INTERNET-ADDRESS'],
                        'ssn'     => $row['FEDERAL-ID-NO']
                    );
                }

                odbc_free_result($ret);
                self::$db->closeConnection();

                self::$db = new database_odbc();
                $ret = self::$db->connect(custom_locationproperties::getConnectionParameters('skyward')['DSN'], driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('skyward')['username']), driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('skyward')['password']));

                if ($ret !== false) {
                    $sql = 'SELECT
                                        N."NAME-ID",
                                        N."INTERNET-ADDRESS",
                                        N."FEDERAL-ID-NO"
                                    FROM
                                        pub."HAAPRO-PROFILE" AS HP
                                        INNER JOIN pub."NAME" AS N
                                            ON N."NAME-ID" = HP."NAME-ID"
                                    WHERE
                                        N."INTERNET-ADDRESS" IN(%%emails%%)
                                        OR N."FEDERAL-ID-NO" IN(%%ssns%%)';
                    $params = array(
                        'emails' => '\'' . implode('\',\'', $emails) . '\'',
                        'ssns'   => '\'' . implode('\',\'', $ssns) . '\''
                    );
                    $sql = utility::parseTemplate($sql, $params);
                    unset($emails, $ssns);

                    $ret = self::$db->justquery($sql);
                    if ($ret !== false) {
                        while ($row = odbc_fetch_array($ret)) {
                            $row['FEDERAL-ID-NO'] = trim($row['FEDERAL-ID-NO']);
                            $row['INTERNET-ADDRESS'] = trim($row['INTERNET-ADDRESS']);

                            //first try match via ssn
                            $key = array_search($row['FEDERAL-ID-NO'], array_column(driver::$sharedBuckets['studentSideStaffTableData'], 'ssn'));
                            if ($key === false) {
                                //next try match on email
                                $key = array_search($row['INTERNET-ADDRESS'], array_column(driver::$sharedBuckets['studentSideStaffTableData'], 'email'));
                            } else {
                            }


                            if ($key !== false) {
                                driver::$sharedBuckets['nameIDMap'][$row['NAME-ID']] = array(
                                    'stu-name-id' => driver::$sharedBuckets['studentSideStaffTableData'][$key]['name-id']
                                );
                                driver::$sharedBuckets['nameIDs'][] = $row['NAME-ID'];
                            } else {
                            }
                        }

                        unset(driver::$sharedBuckets['studentSideStaffTableData']);
                        driver::$sharedBuckets['nameIDs'] = implode(',', driver::$sharedBuckets['nameIDs']);
                    } else {
                        log::logAlert(errors::$sqlError);
                        die();
                    }
                } else {
                    log::logAlert(errors::$databaseCantConnect);
                    die();
                }
            } else {
                log::logAlert(errors::$sqlError);
                die();
            }
        } else {
            log::logAlert(errors::$databaseCantConnect);
            die();
        }
        */


        //get entities from student, will only pull records for entities that exist in ODS already.
        $edfiPointer = new edfi();
        $ret = $edfiPointer->init(custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'], driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID']), driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret']));
        if ($ret !== false) {
            $edfiPointer->loopDataWithCallback('o_schools', array('offset' => 0, 'localEducationAgencyId' => custom_locationproperties::get_districtID()), array(), function ($record) {
                driver::$sharedBuckets['odsEntities'][] = substr($record['schoolId'], -4);
            });
            driver::$sharedBuckets['odsEntities'][] = '0000';
            driver::$sharedBuckets['odsEntities'] = '\'' . implode('\',\'', driver::$sharedBuckets['odsEntities']) . '\'';
        } else {
            log::logAlert(errors::$edfi_apiFailure);
            die();
        }

        /* Get flag for whether or not pay record account distributions use job codes. */
        self::$db = new database_odbc();
        $ret = self::$db->connect(
            custom_locationproperties::getConnectionParameters('skyward')['DSN'],
            driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('skyward')['username']),
            driver::$crypto->decrypt(custom_locationproperties::getConnectionParameters('skyward')['password'])
        );

        if ($ret !== false) {
            $sql = custom_floridacode_queries_InterchangeStaffAssociation::$shared_GetPayRecsUseAcctDistJobCode;
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
            case 7: {
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
            case 0:
                self::processRow_Staff($row, $exportIndex, $parseOptions);
                break;

            case 1:
                self::processRow_StaffEducationOrganizationEmploymentAssociation($row, $exportIndex, $parseOptions);
                break;

            case 2:
                self::processRow_StaffEducationOrganizationAssignmentAssociation($row, $exportIndex, $parseOptions);
                break;

            /*case 3:
                self::processRow_StaffSchoolAssociation($row, $exportIndex, $parseOptions);
                break;*/

            case 4:
                self::processRow_LeaveEvent($row, $exportIndex, $parseOptions);
                break;

            case 5:
                self::processRow_OpenStaffPosition($row, $exportIndex, $parseOptions);
                break;

            /*case 6:
                self::processRow_StaffSectionAssociation($row, $exportIndex, $parseOptions);
                break;*/

            case 7: {
                self::processRow_EmployeesWithoutAssignments($row, $exportIndex, $parseOptions);
                break;
            }
        }
    }

    final private static function processRow_Staff(array $row, int $exportIndex, array $parseOptions) {
        $tmp = intval($row['HAABLD-BLD-CODE']);
        if ($tmp <= 0) {
            log::logAlert('Invalid Building Code: ' . $row['HAABLD-BLD-CODE'] . ' for ' . $row['FIRST-NAME'] . ' ' . $row['LAST-NAME'] . ', skipping...');

            return false;
        }

        /* If the local degree code is "H" or "HS", assume "High School Diploma" because FLDOE does not have a state code for it. */
        if (in_array($row["HAADEG-CODE"], array("H", "HS"))) {
            $levelOfEducation = "High School Diploma";
        } else {
            $levelOfEducation = custom_floridacode::highestEducationCode(trim($row['HAADEG-STATE-CODE']));
        }

        $recordOut = array(
            //'StaffUniqueId'                              => custom_locationproperties::get_districtID() . '_' . driver::$sharedBuckets['nameIDMap'][$row['NAME-ID']]['stu-name-id'],
            'StaffUniqueId'                              => (!empty($row["HAAPRO-OTHER-ID"]) ? $row["HAAPRO-OTHER-ID"] : custom_locationproperties::get_districtID() . '_' . $row["NAME-ID"]),
            'personalTitlePrefix'                        => $row['SALUTATION-SDESC'],
            'FirstName'                                  => $row['FIRST-NAME'],
            'middleName'                                 => $row['MIDDLE-NAME'],
            'LastSurname'                                => $row['LAST-NAME'],
            'generationCodeSuffix'                       => $row['NAME-SUFFIX-ID'],
            'maidenName'                                 => $row['HAAPRO-MAIDEN-NAME'],
            'sexType'                                    => custom_floridacode::genderCodeToDescription($row['GENDER']),
            'birthDate'                                  => $row['BIRTHDATE'],
            'hispanicLatinoEthnicity'                    => (boolval($row['ETHNICITY-HISP-X']) === true) ? true : false,
            'highestCompletedLevelOfEducationDescriptor' => custom_locationproperties::get_descriptorURLBase('prod_edfi_v25', 'LevelOfEducationDescriptor') . '/' . $levelOfEducation,
            'yearsOfPriorProfessionalExperience'         => intval($row['YPPE']),
            'yearsOfPriorTeachingExperience'             => intval($row['YPTE']),
            'highlyQualifiedTeacher'                     => false,
            'loginId'                                    => trim($row['INTERNET-ADDRESS']),//$row['DUSER-ID'],
            'addresses'                                  => array(),
            'credentials'                                => array(),
            'electronicMails'                            => array(),
            'races'                                      => array(),
            'telephones'                                 => array()
        );

        //street address
        if ($row["ADDRESS-ID"] !== "0") { /* Skip address processing if no address found. */
            $streetNumberName = trim(trim($row['STREET-NUMBER']) . ' ' . $row['STREET-DIR'] . ' ' . $row['STREET-NAME']);
            if (mb_strlen($streetNumberName) > 0) {
                $recordOut['addresses'][] = array(
                    'addressType'              => 'Home',
                    'streetNumberName'         => $streetNumberName,
                    'apartmentRoomSuiteNumber' => $row['STREET-APPT'],
                    'city'                     => $row['ZIP-CITY'],
                    'stateAbbreviationType'    => $row['ZIP-STATE'],
                    'postalCode'               => $row['ZIP-CODE'],
                    'nameOfCounty'             => $row['COUNTY-LDESC'],
                    'latitude'                 => $row['LATITUDE'],
                    'longitude'                => $row['LONGITUDE']
                );
            } else {
            }
        }

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
                /* Get teaching certificate number. */
                if (in_array(trim($credentialsRecords[$credentialIndex]["HAADSC-CODE"]), array("AC", "NP", "RG", "SB", "TB", "TC", "TD", "TM"))) {
                    /* If:
                     *     - A certificate number hasn't been read in at all.
                     *     - A certificate number has been read but this certificate is a non-renewable professional,
                     *       regular professional, or temporary three-year certificate AND the current certificate I have
                     *       is not a regular professional certificate.
                     *     (OR)
                     *     - A certificate number has been read but this certificate is a regular professional. */
                    if (empty($profCertNum)) {
                        // Use the first certificate found, always.
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

                        //filter out records that aren't grade ranges
                        if (!in_array($credentialDetails[$detailIndex]['GCERT1CODE'], array("7","E")) && !empty($credentialsRecords[$credentialIndex]['HPMCEM-ISSUE-DATE'])) {
                            $recordOut['credentials'][] = array(
                                'credentialFieldDescriptor'         => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'CredentialFieldDescriptor') . '/' . custom_floridacode::attemptConvertCredentialFieldToFloridaCodeEnumeration($credentialDetails[$detailIndex]['GCERT2DESC']),
                                'credentialType'                    => custom_floridacode::attemptConvertCredentialTypeToFloridaCodeEnumeration($credentialsRecords[$credentialIndex]['HAADSC-DESC']),
                                'levelDescriptor'                   => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'LevelDescriptor') . '/' . custom_floridacode::attemptConvertCredentialLevelToFloridaCodeEnumeration($credentialDetails[$detailIndex]['GCERT1DESC']),
                                'teachingCredentialDescriptor'      => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'TeachingCredentialDescriptor') . '/' . custom_floridacode::attemptConvertTeachingCredentialTypeToFloridaCodeEnumeration($credentialsRecords[$credentialIndex]['HAADSC-CODE']),
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
        }

        //electronic mail
        $electronicMail = trim($row['INTERNET-ADDRESS']);
        if (mb_strlen($electronicMail) > 0) {
            $recordOut['electronicMails'][] = array(
                'electronicMailType'           => 'Work',
                'electronicMailAddress'        => $electronicMail,
                'primaryEmailAddressIndicator' => true
            );
        } else {
        }

        //identificationCodes
        $recordOut['identificationCodes'] = array();
        $row['FEDERAL-ID-NO'] = trim($row['FEDERAL-ID-NO']);
        $row['ALTERNATE-ID'] = trim($row['ALTERNATE-ID']);
        $row['HAAPRO-OTHER-ID'] = trim($row['HAAPRO-OTHER-ID']);

        /* Pad employee ID if ID is numeric with less than the minimum length. */
        if ($row["FFAACT-EmpIDSetup-NumLtr-Opt"] === "N" && strlen($row["ALTERNATE-ID"]) < intval($row["FFAACT-EmpIDSetup-Length-Min"])) {
            $row["ALTERNATE-ID"] = str_pad($row["ALTERNATE-ID"], intval($row["FFAACT-EmpIDSetup-Length-Min"]), "0", STR_PAD_LEFT);
        }

        /* Skyward Business Internal ID */
        $recordOut['identificationCodes'][] = array(
            'staffIdentificationSystemDescriptor'     => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffIdentificationSystemDescriptor') . '/Business Name ID',
            'assigningOrganizationIdentificationCode' => 'Skyward',
            'identificationCode'                      => $row['NAME-ID']
        );

        /* Social Security Number (SSN) */
        if (mb_strlen($row['FEDERAL-ID-NO']) > 0) {
            $recordOut['identificationCodes'][] = array(
                'staffIdentificationSystemDescriptor'     => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffIdentificationSystemDescriptor') . '/SSN',
                'assigningOrganizationIdentificationCode' => 'SSN',
                'identificationCode'                      => $row['FEDERAL-ID-NO']
            );
        } else {
        }

        /* Skyward HR Employee ID */
        if (mb_strlen($row['ALTERNATE-ID']) > 0) {
            $recordOut['identificationCodes'][] = array(
                'staffIdentificationSystemDescriptor'     => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffIdentificationSystemDescriptor') . '/Employee ID',
                'assigningOrganizationIdentificationCode' => 'District',
                'identificationCode'                      => $row['ALTERNATE-ID']
            );
        } else {
        }

        /* Skyward HR Florida Education ID (FLEID) */
        if (mb_strlen($row['HAAPRO-OTHER-ID']) > 0) {
            $recordOut['identificationCodes'][] = array(
                'staffIdentificationSystemDescriptor'     => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffIdentificationSystemDescriptor') . '/FLEID',
                'assigningOrganizationIdentificationCode' => 'State',
                'identificationCode'                      => $row['HAAPRO-OTHER-ID']
            );
        } else {
        }

        /* Certificate Number */
        if (mb_strlen($profCertNum) > 0) {
            $recordOut['identificationCodes'][] = array(
                'staffIdentificationSystemDescriptor'     => custom_locationproperties::get_descriptorURLBase('prod_edfi_v25', 'StaffIdentificationSystemDescriptor') . '/Professional Certificate',
                'assigningOrganizationIdentificationCode' => 'State',
                'identificationCode'                      => $profCertNum
                //'identificationCode'                      => str_pad($profCertNum, 10, "0", STR_PAD_LEFT)
            );
        } else {
        }

        /* Skyward HR Alphakey */
        if (mb_strlen($row["NALPHAKEY"]) > 0) {
            $recordOut['identificationCodes'][] = array(
                'staffIdentificationSystemDescriptor'     => custom_locationproperties::get_descriptorURLBase('prod_edfi_v25', 'StaffIdentificationSystemDescriptor') . '/Business Alphakey',
                'assigningOrganizationIdentificationCode' => 'District',
                'identificationCode'                      => $row["NALPHAKEY"]
            );
        } else {
        }

        /* Skyward HR Secured Username k*/
        if (mb_strlen($row["DUSER-ID"]) > 0) {
            $recordOut['identificationCodes'][] = array(
                'staffIdentificationSystemDescriptor'     => custom_locationproperties::get_descriptorURLBase('prod_edfi_v25', 'StaffIdentificationSystemDescriptor') . '/Business Username',
                'assigningOrganizationIdentificationCode' => 'District',
                'identificationCode'                      => $row["DUSER-ID"]
            );
        } else {
        }

        // Races, using federal race flags.
        $recordOut["races"] = custom_floridacode::fedRaceFlagsToDescription($row["FED-RACE-FLAGS"]);

        //telephones //remove invalid phone numbers, must be 10 digit
        $row['PRIMARY-PHONE'] = intval($row['PRIMARY-PHONE']);
        if (strlen($row['PRIMARY-PHONE']) === 10) {
            $recordOut['telephones'][] = array(
                'telephoneNumberType' => 'Mobile',
                'orderOfPriority'     => 1,
                'telephoneNumber'     => $row['PRIMARY-PHONE']
            );
        } else {
        }

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_StaffEducationOrganizationEmploymentAssociation(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'educationOrganizationReference' => array(
                'educationOrganizationId' => intval(custom_locationproperties::get_districtID()) . $row['HAABLD-BLD-CODE']
            ),
            'staffReference'                 => array(
                //'staffUniqueId' => custom_locationproperties::get_districtID() . '_' . driver::$sharedBuckets['nameIDMap'][$row['NAME-ID']]['stu-name-id']
                'staffUniqueId' => (!empty($row["HAAPRO-OTHER-ID"]) ? $row["HAAPRO-OTHER-ID"] : custom_locationproperties::get_districtID() . '_' . $row["NAME-ID"]),
            ),
            'employmentStatusDescriptor'     => 'Contractual',
            'hireDate'                       => $row['HAAPRO-HIRE-DTE'],
            'endDate'                        => ($row["HAAPRO-ACTIVE"] === "0" && empty($row['HAAPRO-TERM-DTE']) ? "1900-01-01" : $row['HAAPRO-TERM-DTE'])
        );

        if (trim($row['HPETER-TERM-CODE']) !== '') {
            if (trim($row['HPETER-STATE-CODE']) !== '') {
                $recordOut['separationReasonDescriptor'] = custom_locationproperties::get_descriptorURLBase('edfiConfig', 'SeparationReasonDescriptor') . '/' . $row['HPETER-STATE-CODE'];
            } else {
                $recordOut['separationReasonDescriptor'] = custom_locationproperties::get_descriptorURLBase('edfiConfig', 'SeparationReasonDescriptor') . '/Z';
            }
        } else {
        }

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    final private static function processRow_StaffEducationOrganizationAssignmentAssociation(array $row, int $exportIndex, array $parseOptions) {
        $temp = intval($row['HAABLD-BLD-CODE']);
        if ($temp <= 0) {
            log::logAlert('Invalid Building Code: ' . $row['HAABLD-BLD-CODE']);

            return false;
        } else {
        }

        $recordOut = array(
            'educationOrganizationReference' => array(
                'educationOrganizationId' => intval(custom_locationproperties::get_districtID()) . $row['HAABLD-BLD-CODE']
            ),
            'staffReference'                 => array(
                //'staffUniqueId' => custom_locationproperties::get_districtID() . '_' . driver::$sharedBuckets['nameIDMap'][$row['NAME-ID']]['stu-name-id']
                'staffUniqueId' => (!empty($row["HAAPRO-OTHER-ID"]) ? $row["HAAPRO-OTHER-ID"] : custom_locationproperties::get_districtID() . '_' . $row["NAME-ID"]),
            ),
            'staffClassificationDescriptor'  => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffClassificationDescriptor') . '/' . $row['JOBCODE'],
            'beginDate'                      => $row['HPMASN-START-DATE'],
            'positionTitle'                  => $row['HAADSC-DESC-POS'],
            'endDate'                        => $row['HPMASN-END-DATE']
        );

        //remove dupes
        $hash = md5(json_encode($recordOut));
        if (in_array($hash, self::$tmp_uniqueRecordsHashes) === true) {
            log::logAlert('Duplicate Record');

            return false;
        } else {
            self::$tmp_uniqueRecordsHashes[] = $hash;
            workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
        }
    }

    /*
    final private static function processRow_StaffSchoolAssociation(array $row, int $exportIndex, array $parseOptions) {

        $recordOut = array(
            'schoolReference'             => array(
                'schoolId' => intval(custom_locationproperties::get_districtID()) . $row['HAABLD-BLD-CODE']
            ),
            'staffReference'              => array(
                'staffUniqueId' => custom_locationproperties::get_districtID() . '_' . driver::$sharedBuckets['studentSideStaffTableData'][$row['FEDERAL-ID-NO']]['name-id']
            ),
            'schoolYearTypeReference'     => array(
                'schoolYear' => ($row['HPMASN-FIS-YEAR'] - 1) . '-' . $row['HPMASN-FIS-YEAR']
            ),
            'programAssignmentDescriptor' => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'ProgramAssignmentDescriptor') . '/' . custom_floridacode::attemptConvertProgramAssignmentToFloridaCodeEnumeration($row['HAADSC-DESC-POS']),
            'academicSubjects'            => array(),
            'gradeLevels'                 => array()
        );

        $db = driver::$ingressClassName::getDBPointer();
        $sql = custom_floridacode_queries_InterchangeStaffAssociation::$shared_getGradeRangeBreakdowns;
        $params = array('asnid' => $row['HPMASN-ID']);
        $sql = utility::parseTemplate($sql, $params);

        $breakdowns = $db->query($sql);
        $numBreakdowns = count($breakdowns);

        if ($numBreakdowns > 0) {
            for ($ii = 0; $ii < $numBreakdowns; $ii++) {
                $breakdowns[$ii]['HAADSC-ID-GRADE-TO'] = intval($breakdowns[$ii]['HAADSC-ID-GRADE-TO']);
                $breakdowns[$ii]['HAADSC-ID-GRADE-FROM'] = intval($breakdowns[$ii]['HAADSC-ID-GRADE-FROM']);
                $gradeLevelDesc = (($breakdowns[$ii]['HAADSC-ID-GRADE-TO'] + $breakdowns[$ii]['HAADSC-ID-GRADE-FROM']) === 0) ? 'Unknown' : $breakdowns[$ii]['HAADSC-ID-GRADE-FROM'] . ' - ' . $breakdowns[$ii]['HAADSC-ID-GRADE-TO'];

                $recordOut['gradeLevels'][] = array(
                    'gradeLevelDescriptor' => $gradeLevelDesc
                );

                $recordOut['academicSubjects'][] = array(
                    'academicSubjectDescriptor' => $breakdowns[$ii]['HAADSC-DESC-ASN']
                );
            }
        } else {
            //log::logAlert('No Breakdowns');
            //return false;
        }

        //remove dupes
        $hash = md5(json_encode($recordOut));
        if (in_array($hash, self::$tmp_uniqueRecordsHashes) === true) {
            log::logAlert('Duplicate Record');

            return false;
        } else {
            self::$tmp_uniqueRecordsHashes[] = $hash;
            workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
        }
    }
    */

    final private static function processRow_LeaveEvent(array $row, int $exportIndex, array $parseOptions) {
        $row['HTOTRN-SUB-NAME-ID'] = intval($row['HTOTRN-SUB-NAME-ID']);
        $row['HTODRS-DESC'] = utility::stripHTML_trim($row['HTODRS-DESC']);

        $recordOut = array(
            'staffReference'     => array(
                //'staffUniqueId' => custom_locationproperties::get_districtID() . '_' . driver::$sharedBuckets['nameIDMap'][$row['NAME-ID']]['stu-name-id']
                'staffUniqueId' => (!empty($row["HAAPRO-OTHER-ID"]) ? $row["HAAPRO-OTHER-ID"] : custom_locationproperties::get_districtID() . '_' . $row["NAME-ID"]),
            ),
            'eventDate'          => $row['HTOTRN-TRANS-DATE'],
            'categoryType'       => custom_floridacode::stateHRLeaveCodeToFloridaCodeEnumeration($row['HTODRS-ABSENCE-TYPE']),
            'reason'             => substr($row['HTODRS-DESC'], 0, 40),
            'hoursOnLeave'       => floatval($row['HTOTRN-HRS']),
            'substituteAssigned' => (intval($row['HTOTRN-SUB-NAME-ID']) > 0) ? true : false
        );

        //remove dupes
        $hash = md5(json_encode($recordOut));
        if (in_array($hash, self::$tmp_uniqueRecordsHashes) === true) {
            log::logAlert('Duplicate Record');

            return false;
        } else {
            self::$tmp_uniqueRecordsHashes[] = $hash;
            workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
        }
    }

    final private static function processRow_OpenStaffPosition(array $row, int $exportIndex, array $parseOptions) {
        $recordOut = array(
            'educationOrganizationReference' => array(
                'educationOrganizationId' => intval(custom_locationproperties::get_districtID()) . $row['HAABLD-BLD-CODE']
            ),
            'employmentStatusDescriptor'     => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'EmploymentStatusDescriptor') . '/' . $row['HAPJBL-FULL-TIME-IND'],
            'staffClassificationDescriptor'  => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffClassificationDescriptor') . '/' . $row['HAADSC-CODE'],
            'requisitionNumber'              => $row['HAPJBL-JOB-LISTING-ID'],
            'datePosted'                     => $row['HAPJBL-POST-INT-BEGIN-DATE'],
            'positionTitle'                  => $row['HAPJBL-ASN-DESC'],
            'datePostingRemoved'             => ($row["HAPJBL-STATUS"] === "C" ? (empty($row["HAPJBL-CLOSE-DATE"]) ? $row["HAPJBL-POST-INT-BEGIN-DATE"] : $row["HAPJBL-CLOSE-DATE"]) : $row["HAPJBL-CLOSE-DATE"])
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }

    /*
    final private static function processRow_StaffSectionAssociation(array $row, int $exportIndex, array $parseOptions) {

        $recordOut = array(
            'sectionReference'            => array(
                'schoolId'                    => intval(custom_locationproperties::get_districtID()) . $row['ENTITY-ID'],
                'classPeriodName'             => str_pad($row['DSP-PERIOD'], 2, '0', STR_PAD_LEFT) . ' - Traditional',
                'classroomIdentificationCode' => $row['BUILDING-ID'] . '-' . $row['ROOM-NUMBER'],
                'localCourseCode'             => $row['COR-ALPHAKEY'],
                'termDescriptor'              => custom_floridacode::termCodeToDescription($row['CONTROL-SET-ID']),
                'schoolYear'                  => ($row['SCHOOL-YEAR'] - 1) . '-' . $row['SCHOOL-YEAR'],
                'uniqueSectionCode'           => intval(custom_locationproperties::get_districtID()) . $row['ENTITY-ID'] . $row['COR-ALPHAKEY'] . ($row['SCHOOL-YEAR'] - 1) . '-' . $row['SCHOOL-YEAR'] . $row['CONTROL-SET-ID'],
                'sequenceOfCourse'            => 1
            ),
            'staffReference'              => array(
                'staffUniqueId' => custom_locationproperties::get_districtID() . '_' . driver::$sharedBuckets['studentSideStaffTableData'][$row['FEDERAL-ID-NO']]['name-id']
            ),
            'classroomPositionDescriptor' => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'ClassroomPositionDescriptor') . '/' . custom_floridacode::classMeetTeacherTypeCodeToDescription($row['TCHR-PRIME-FLAG']),
            'beginDate'                   => $row['TDSTART'],
            'endDate'                     => $row['TDSTOP'],
            'highlyQualifiedTeacher'      => custom_floridacode::teacherHighlyQualifiedCodeToBoolString($row['HIGHLY-QUALIFIED']),
            'percentageContribution'      => intval($row['AIDE-PERCENTAGE'])
        );

        workhorse::routeProcessorToEgressHandler(array('data' => $recordOut));
    }*/

    final private static function processRow_EmployeesWithoutAssignments(array $row, int $exportIndex, array $parseOptions) {
        /* Workflow:
         *     * Look for job code by account distribution (use code with highest percentage total or all in case of tie).
         *     * Look for job code by most recent check (use code with highest percentage total or all in case of tie).
         *     * Look for job code by state reporting parameter set.
         * */

        /* Do pay record account distributions include job codes? No need to read them if they're not used. */
        if (self::$payRecsUseAcctDistJobCodes) {
            /* First, look for job code by pay record account distribution. */
            $db = driver::$ingressClassName::getDBPointer();
            $sql = custom_floridacode_queries_InterchangeStaffAssociation::$shared_GetJobCodeByAccountDistribution;
            $params = array('nameId' => $row['NAME-ID']);
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
                        "HAABLD-BLD-CODE"   => $row["HAABLD-BLD-CODE"],
                        "HAADSC-DESC-POS"   => custom_floridacode::getStateCodeIndexValueByJobcode($a["HPAPRA-DEPT"], 1),
                        "HAAPRO-OTHER-ID"   => $row["HAAPRO-OTHER-ID"],
                        "HPMASN-END-DATE"   => $payStopDate,
                        "HPMASN-START-DATE" => $payStartDate,
                        "JOBCODE"           => $a["HPAPRA-DEPT"],
                        "NAME-ID"           => $row["NAME-ID"]
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
        $sql = custom_floridacode_queries_InterchangeStaffAssociation::$shared_GetJobCodeByCheckHistory;
        $params = array('nameId' => $row['NAME-ID']);
        $sql = utility::parseTemplate($sql, $params);
        $checkInfo = $db->query($sql);

        if (!empty($checkInfo)) {
            foreach ($checkInfo as $c) {
                /* Build assignment row record to send to SEOAA function. */
                $asstRecord = array(
                    "HAABLD-BLD-CODE"   => $row["HAABLD-BLD-CODE"],
                    "HAADSC-DESC-POS"   => custom_floridacode::getStateCodeIndexValueByJobcode($c["HPAHDP-DEPT"], 1),
                    "HAAPRO-OTHER-ID"   => $row["HAAPRO-OTHER-ID"],
                    "HPMASN-END-DATE"   => (!empty($c["HPAPRM-STOP-DATE"]) ? $c["HPAPRM-STOP-DATE"] : $c["HPAHDC-CHK-DTE"]),
                    "HPMASN-START-DATE" => (!empty($c["HPAPRM-START-DATE"]) ? $c["HPAPRM-START-DATE"] : $c["HPAHDC-CHK-DTE"]),
                    "JOBCODE"           => $c["HPAHDP-DEPT"],
                    "NAME-ID"           => $row["NAME-ID"]
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
                        /* Build assignment row record to send to SEOAA function. */
                        $asstRecord = array(
                            "HAABLD-BLD-CODE"   => $row["HAABLD-BLD-CODE"],
                            "HAADSC-DESC-POS"   => custom_floridacode::getStateCodeIndexValueByJobcode($p["X-REF-HAADSC"], 1),
                            "HAAPRO-OTHER-ID"   => $row["HAAPRO-OTHER-ID"],
                            "HPMASN-END-DATE"   => (!empty($row["HPAPRM-STOP-DATE"]) ? $row["HPAPRM-STOP-DATE"] : date("Y-m-d")),
                            "HPMASN-START-DATE" => (!empty($row["HPAPRM-START-DATE"]) ? $row["HPAPRM-START-DATE"] : date("Y-m-d")),
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
        $sql = custom_floridacode_queries_InterchangeStaffAssociation::$shared_GetJobCodeByStateReportingParameterSet;
        $params = array('schoolYearXXYY' => utility::determineSchoolYear(date("Y-m-d"), 1));
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