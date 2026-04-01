<?php
require(__DIR__ . '/../../../../___core/init.php');


/*
    ATTENTION!!!
        This is designed to be used as a "FIRST RUN" to initialize some default descriptors for a district in EdFi. Once this has been run against a district
        it is advisable to not run it again unless you talk with the programmer AND someone familiar with that districts EdFi implimentation as you MIGHT
        be overriding some post-init-config.
*/

/* get CLI Options and Validate */
driver::$cliOptions = getopt('', array('dataSource:', 'path::'));
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
            'name'    => 'CredentialFieldDescriptor',
            'enabled' => false,
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
        ),

        1 => array(
            'name'    => 'SeparationReasonDescriptor',
            'enabled' => false,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_separationReasonDescriptors'
            )
        ),

        2 => array(
            'name'    => 'EmploymentStatusDescriptor',
            'enabled' => false,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => '
                    SELECT DISTINCT
                        HJL."HAPJBL-FULL-TIME-IND"
                    FROM
                        pub."HAPJBL-JOB-LISTING" AS HJL
                    WHERE
                        HJL."HAPJBL-STATUS" = \'O\'
                ',
                'queryParams' => array()
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_employmentStatusDescriptors'
            )
        ),

        3 => array(
            'name'    => 'LevelDescriptor',
            'enabled' => false,
            'input'   => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_loopURLKey'      => 'o_levelDescriptors'
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_levelDescriptors'
            )
        ),

        4 => array(
            'name'    => 'TeachingCredentialDescriptor',
            'enabled' => false,
            'input'   => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_loopURLKey'      => 'o_teachingCredentialDescriptors'
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_teachingCredentialDescriptors'
            )
        ),

        5 => array(
            'name'    => 'StaffClassificationDescriptors',
            'enabled' => false,
            'input'   => array(
                'type'        => 'odbc',
                'dsn'         => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['DSN'],
                'username'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['username'],
                'password'    => custom_locationproperties::getConnectionParameters('prod_skyward_fin')['password'],
                'sourceQuery' => '
                    SELECT
                        SC."CODE-ID",
                        SC."CODE-SDESC",
                        SC."CODE-LDESC",
                        SC."START-YEAR",
                        SC."END-YEAR"
                    FROM
                        pub."SYS-CTD" AS SC
                    WHERE
                        SC."TABLE-ID" = \'HR-FL-ACCT-ST-RPT-FLD\'
                        --AND SC."START-YEAR" <= %%currentsy%%
                        --AND SC."END-YEAR" >= %%currentsy%%
                ',
                'queryParams' => array(
                  //'currentsy' => driver::$currentSY[0]
                )
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_staffClassificationDescriptors'
            )
        ),

        6 => array(
            'name'    => 'StaffIdentificationDescriptors',
            'enabled' => false,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_staffIdentificationSystemDescriptors'
            )
        ),

        7 => array(
            'name'    => 'GradeLevelDescriptors',
            'enabled' => false,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_gradeLevelDescriptors'
            )
        ),

        8 => array(
            'name'    => 'LevelOfEducationDescriptors',
            'enabled' => false,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                 => 'edfi',
                'edfi_apiUrlBase'      => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'     => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiEndpoint'     => 'o_levelOfEducationDescriptors'
            )
        ),

        9 => array(
            'name'    => 'StaffClassificationDescriptors (Custom)',
            'enabled' => true,
            'input'   => array(
                'type' => 'noop'
            ),
            'output'  => array(
                'type'                    => 'edfi',
                'edfi_apiUrlBase'         => custom_locationproperties::getConnectionParameters('edfiConfig')['apiUrlBase'],
                'edfi_apiClientID'        => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientID'],
                'edfi_apiClientSecret'    => custom_locationproperties::getConnectionParameters('edfiConfig')['apiClientSecret'],
                'edfi_apiSubscriptionKey' => custom_locationproperties::getConnectionParameters('edfiConfig')['apiSubscriptionKey'],
                'edfi_apiEndpoint'        => 'o_staffClassificationDescriptors'
            )
        )
    )
);

workhorse::init($config);

class jobTransformer implements i_jobTransformation
{

    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

        switch ($exportIndex) {
            case 0:
                self::processRow_CredentialFieldDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 1:
                self::processRow_SeparationReasonDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 2:
                self::processRow_EmploymentStatusDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 3:
                self::processRow_LevelDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 4:
                self::processRow_TeachingCredentialDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 5:
                self::processRow_StaffClassificationDescriptors($row, $exportIndex, $parseOptions);
                break;

            case 6:
                self::processRow_StaffIdentificationDescriptors($row, $exportIndex, $parseOptions);
                break;

            case 7:
                self::processRow_GradeLevelDescriptor($row, $exportIndex, $parseOptions);
                break;

            case 8:
                self::processRow_levelOfEducationDescriptors($row, $exportIndex, $parseOptions);
                break;

            case 9:
                self::processRow_StaffClassificationDescriptorsCustom($row, $exportIndex, $parseOptions);
                break;
        }
    }

    final private static function processRow_StaffIdentificationDescriptors(array $row, int $exportIndex, array $parseOptions) {

        $array = array(
            array('Business Name ID', 'Business Name ID', 'Business Name Identifier', 'District'),
            array('Employee ID', 'Employee ID', 'Employee Identifier', 'District'),
            array('FLEID', 'FLEID', 'Florida Education Identifier', 'State'),
            array('SSN', 'SSN', 'Social Security Number', 'SSN')
        );

        $limit = count($array);
        for ($i = 0; $i < $limit; $i++) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'                     => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffIdentificationSystemDescriptor'),
                'codeValue'                     => $array[$i][0],
                'shortDescription'              => $array[$i][1],
                'description'                   => $array[$i][2],
                'staffIdentificationSystemType' => $array[$i][3]
            )));
        }
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
                'namespace'                 => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'CredentialFieldDescriptor'),
                'codeValue'                 => $array[$i],
                'shortDescription'          => $array[$i],
                'description'               => $array[$i],
                'academicSubjectDescriptor' => $array[$i]
            )));
        }
    }

    final private static function processRow_SeparationReasonDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $array = array(
            'A' => array(
                'short' => 'Retirement',
                'long'  => 'Retirement',
                'type'  => 'Retirement'
            ),
            'B' => array(
                'short' => 'Resign for Emp FLEDU',
                'long'  => 'Resignation for employment in education in Florida',
                'type'  => 'Employment elsewhere'
            ),
            'C' => array(
                'short' => 'Resign for Non-EDU',
                'long'  => 'Resignation for employment outside of education',
                'type'  => 'Employment elsewhere'
            ),
            'D' => array(
                'short' => 'Resign w/ prejudice',
                'long'  => 'Resignation with prejudice',
                'type'  => 'Employment elsewhere'
            ),
            'E' => array(
                'short' => 'Resign Other',
                'long'  => 'Resignation for other personal reasons',
                'type'  => 'Other'
            ),
            'F' => array(
                'short' => 'Staff reduction',
                'long'  => 'Staff reduction',
                'type'  => 'Layoff'
            ),
            'G' => array(
                'short' => 'Dismissed, Board Charges',
                'long'  => 'Dismissal due to findings by the board related to charges',
                'type'  => 'Discharge'
            ),
            'H' => array(
                'short' => 'Death',
                'long'  => 'Death',
                'type'  => 'Illness/disability/death'
            ),
            'I' => array(
                'short' => 'Contract expired',
                'long'  => 'Contract expired',
                'type'  => 'Other'
            ),
            'J' => array(
                'short' => 'Unknown',
                'long'  => 'Reason not known',
                'type'  => 'Unknown'
            ),
            'K' => array(
                'short' => 'Disabled',
                'long'  => 'Disabled',
                'type'  => 'Illness/disability/death'
            ),
            'L' => array(
                'short' => 'Resign for EDU',
                'long'  => 'Resignation for employment in education outside Florida',
                'type'  => 'Employment elsewhere'
            ),
            'M' => array(
                'short' => 'Contract Ended Non-Perform',
                'long'  => 'Contract not renewed, due to less than satisfactory performance',
                'type'  => 'Discharge'
            ),
            'N' => array(
                'short' => 'Probationary Dismissal',
                'long'  => 'Dismissal during probationary period.',
                'type'  => 'Discharge'
            ),
            'O' => array(
                'short' => 'Job Abandonment',
                'long'  => 'Job Abandonment',
                'type'  => 'Other'
            ),
            'P' => array(
                'short' => 'Ineffective Performance',
                'long'  => 'Classroom teachers or principals who were dismissed for ineffective performance as demonstrated through the district\'s evaluation system.',
                'type'  => 'Discharge'
            ),
            'Z' => array(
                'short' => 'N/A',
                'long'  => 'Not applicable.  Include temporary employees here.',
                'type'  => 'Other')
        );

        foreach ($array as $code => $params) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'            => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'SeparationReasonDescriptor'),
                'codeValue'            => $code,
                'shortDescription'     => $params['short'],
                'description'          => $params['long'],
                'separationReasonType' => $params['type']
            )));
        }
    }

    final private static function processRow_EmploymentStatusDescriptor(array $row, int $exportIndex, array $parseOptions) {

        switch ($row['HAPJBL-FULL-TIME-IND']) {
            case 'F':
                workhorse::routeProcessorToEgressHandler(array('data' => array(
                    'namespace'            => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'EmploymentStatusDescriptor'),
                    'codeValue'            => 'F',
                    'shortDescription'     => 'F',
                    'description'          => 'F',
                    'employmentStatusType' => 'Tenured or permanent'
                )));
                break;

            case 'P':
                workhorse::routeProcessorToEgressHandler(array('data' => array(
                    'namespace'            => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'EmploymentStatusDescriptor'),
                    'codeValue'            => 'P',
                    'shortDescription'     => 'P',
                    'description'          => 'P',
                    'employmentStatusType' => 'Probationary'
                )));
                break;

            default:
                workhorse::routeProcessorToEgressHandler(array('data' => array(
                    'namespace'            => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'EmploymentStatusDescriptor'),
                    'codeValue'            => $row['HAPJBL-FULL-TIME-IND'],
                    'shortDescription'     => $row['HAPJBL-FULL-TIME-IND'],
                    'description'          => $row['HAPJBL-FULL-TIME-IND'],
                    'employmentStatusType' => 'Other'
                )));
                break;
        }
    }

    final private static function processRow_LevelDescriptor(array $row, int $exportIndex, array $parseOptions) {
        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'LevelDescriptor'),
                'codeValue'        => $row['codeValue'],
                'shortDescription' => $row['shortDescription'],
                'description'      => $row['description'],
                'gradeLevels'      => $row['gradeLevels']
            )));
        } else {
        }
    }

    final private static function processRow_TeachingCredentialDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $row['namespace'] = mb_strtolower($row['namespace']);
        if (mb_strpos($row['namespace'], '//ed-fi.org') !== false) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'              => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'TeachingCredentialDescriptor'),
                'codeValue'              => $row['codeValue'],
                'shortDescription'       => $row['shortDescription'],
                'description'            => $row['description'],
                'teachingCredentialType' => $row['teachingCredentialType']
            )));
        } else {
        }
    }

    final private static function processRow_StaffClassificationDescriptors(array $row, int $exportIndex, array $parseOptions) {
        $type = custom_floridacode::getStateCodeIndexValueByJobcode($row['CODE-ID'], 6);
        $row['START-YEAR'] = ($row['START-YEAR'] < 1) ? 1970 : $row['START-YEAR'];

        if ($type !== '') {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'               => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffClassificationDescriptor'),
                'codeValue'               => $row['CODE-ID'],
                'shortDescription'        => $row['CODE-SDESC'],
                'description'             => $row['CODE-LDESC'],
                'effectiveBeginDate'      => $row['START-YEAR'] . '/07/01',
                'effectiveEndDate'        => $row['END-YEAR'] . '/06/30',
                'staffClassificationType' => $type
            )));
        } else {
            print_r($row);
        }
    }

    final private static function processRow_StaffClassificationDescriptorsCustom(array $row, int $exportIndex, array $parseOptions) {
        $array = array(
            "99999" => array(
                "code"      => "99999",
                "shortDesc" => "UNDEFINED, OPEN POS",
                "longDesc"  => "Undefined, Open Position",
                "startYear" => "1970",
                "endYear"   => "9999",
                "type"      => "Other"
            )
        );

        foreach ($array as $code => $params) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'               => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'StaffClassificationDescriptor'),
                'codeValue'               => $params["code"],
                'shortDescription'        => $params["shortDesc"],
                'description'             => $params["longDesc"],
                'effectiveBeginDate'      => $params["startYear"] . '/07/01',
                'effectiveEndDate'        => $params["endYear"] . '/06/30',
                'staffClassificationType' => $params["type"]
            )));
        }
    }

    final private static function processRow_GradeLevelDescriptor(array $row, int $exportIndex, array $parseOptions) {

        $array = array(
            "01"       => array(
                "short"          => "First Grade",
                "desc"           => "First Grade",
                "gradeLevelType" => "First grade"
            ),
            "02"       => array(
                "short"          => "Second Grade",
                "desc"           => "Second Grade",
                "gradeLevelType" => "Second grade"
            ),
            "03"       => array(
                "short"          => "Third Grade",
                "desc"           => "Third Grade",
                "gradeLevelType" => "Third grade"
            ),
            "04"       => array(
                "short"          => "Fourth Grade",
                "desc"           => "Fourth Grade",
                "gradeLevelType" => "Fourth grade"
            ),
            "05"       => array(
                "short"          => "Fifth Grade",
                "desc"           => "Fifth Grade",
                "gradeLevelType" => "Fifth grade"
            ),
            "06"       => array(
                "short"          => "Sixth Grade",
                "desc"           => "Sixth Grade",
                "gradeLevelType" => "Sixth grade"
            ),
            "07"       => array(
                "short"          => "Seventh Grade",
                "desc"           => "Seventh Grade",
                "gradeLevelType" => "Seventh grade"
            ),
            "08"       => array(
                "short"          => "Eighth Grade",
                "desc"           => "Eighth Grade",
                "gradeLevelType" => "Eighth grade"
            ),
            "09"       => array(
                "short"          => "Ninth Grade",
                "desc"           => "Ninth Grade",
                "gradeLevelType" => "Ninth grade"
            ),
            "10"       => array(
                "short"          => "Tenth Grade",
                "desc"           => "Tenth Grade",
                "gradeLevelType" => "Tenth grade"
            ),
            "11"       => array(
                "short"          => "Eleventh Grade",
                "desc"           => "Eleventh Grade",
                "gradeLevelType" => "Eleventh grade"
            ),
            "12"       => array(
                "short"          => "Twelfth Grade",
                "desc"           => "Twelfth Grade",
                "gradeLevelType" => "Twelfth grade"
            ),
            "30"       => array(
                "short"          => "Nonhigh Grad",
                "desc"           => "Adult, Nonhigh School graduate",
                "gradeLevelType" => "Postsecondary"
            ),
            "31"       => array(
                "short"          => "High Schl Grad",
                "desc"           => "Adult, High School graduate",
                "gradeLevelType" => "Postsecondary"
            ),
            "KG"       => array(
                "short"          => "Kindergarten",
                "desc"           => "Kindergarten",
                "gradeLevelType" => "Kindergarten"
            ),
            "PK"       => array(
                "short"          => "Pre-K",
                "desc"           => "Prekindergarten",
                "gradeLevelType" => "Preschool/Prekindergarten"
            ),
            "Ungraded" => array(
                "short"          => "Ungraded",
                "desc"           => "Ungraded",
                "gradeLevelType" => "Ungraded"
            ),
            "Other"    => array(
                "short"          => "Other",
                "desc"           => "Other",
                "gradeLevelType" => "Other"
            )
        );

        foreach ($array as $code => $params) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'        => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'GradeLevelDescriptor'),
                'codeValue'        => $code,
                'shortDescription' => $params['short'],
                'description'      => $params['desc'],
                'gradeLevelType'   => $params['gradeLevelType']
            )));
        }
    }

    final private static function processRow_levelOfEducationDescriptors(array $row, int $exportIndex, array $parseOptions) {

        $array = array(
            "Associate's Degree (two years or more)" => array(
                "short"                => "Associate's Degree (two years or more)",
                "desc"                 => "Associate's Degree (two years or more)",
                "levelOfEducationType" => "Associate's Degree (two years or more)"
            ),
            "Bachelor's"                             => array(
                "short"                => "Bachelor's",
                "desc"                 => "Bachelor's",
                "levelOfEducationType" => "Bachelor's"
            ),
            "Did Not Graduate High School"           => array(
                "short"                => "Did Not Graduate High School",
                "desc"                 => "Did Not Graduate High School",
                "levelOfEducationType" => "Did Not Graduate High School"
            ),
            "Doctorate"                              => array(
                "short"                => "Doctorate",
                "desc"                 => "Doctorate",
                "levelOfEducationType" => "Doctorate"
            ),
            "High School Diploma"                    => array(
                "short"                => "High School Diploma",
                "desc"                 => "High School Diploma",
                "levelOfEducationType" => "High School Diploma"
            ),
            "Master's"                               => array(
                "short"                => "Master's",
                "desc"                 => "Master's",
                "levelOfEducationType" => "Master's"
            ),
            "Some College No Degree"                 => array(
                "short"                => "Some College No Degree",
                "desc"                 => "Some College No Degree",
                "levelOfEducationType" => "Some College No Degree"
            ),
            "Specialist"                             => array(
                "short"                => "Specialist",
                "desc"                 => "Specialist",
                "levelOfEducationType" => "Specialist")
        );

        foreach ($array as $code => $params) {
            workhorse::routeProcessorToEgressHandler(array('data' => array(
                'namespace'            => custom_locationproperties::get_descriptorURLBase('edfiConfig', 'LevelOfEducationDescriptor'),
                'codeValue'            => $code,
                'shortDescription'     => $params['short'],
                'description'          => $params['desc'],
                'levelOfEducationType' => $params['levelOfEducationType']
            )));
        }
    }
}

workhorse::processExportJobs();
?>