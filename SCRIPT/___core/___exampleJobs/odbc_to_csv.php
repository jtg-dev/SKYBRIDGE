<?php
/*
* ODBC DOESN'T REALLY SUPPORT PARAMETERIZED QUERIES, BE CAREFUL ABOUT SQL INJECTION
*/

require('/path/to/___core/init.php');

$config = array (
        'logDirectory' => '/tmp/',
        'jobName' => 'Test Job',
        'exports' => array (
                array (
                        'name' => 'Job Name',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['odbcsource']['DSN'],
                                    'username' => config::$databaseDSNArray['odbcsource']['username'],
                                    'password' => config::$databaseDSNArray['odbcsource']['password'],
                                    'sourceQuery' => 'SELECT
                                                            COUNT(*) AS NUM
                                                        FROM
                                                            pub."HAAPRO-PROFILE" AS HP
                                                        WHERE
                                                            HP."HAAPRO-ACTIVE" = %%active%%',
                                    'queryParams' => array (
                                            'active' => 1
                                        )
                                ),
                        'output' => array (
                                    'type' => 'csv',
                                    'location' => '/tmp/pdotocsv.csv',
                                    'headerRow' => true,
                                    'delimeter' => ',',
                                    'quantifier' => '"'
                                )
                    )
            )
    );

workhorse::init($config);

class jobTransformer implements i_jobTransformation {
    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

        switch ($exportIndex) {
            case 0:
                $out = $row;
                break;
        }

        workhorse::routeProcessorToEgressHandler($out);
    }
}

workhorse::processExportJobs();
?>