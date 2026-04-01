<?php
require('/path/to/___core/init.php');

$config = array (
        'logDirectory' => '/tmp/',
        'jobName' => 'Test Job',
        'exports' => array (
                array (
                        'name' => 'Job Name',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'pdo',
                                    'dsn' => config::$databaseDSNArray['localMySQL']['DSN'],
                                    'username' => config::$databaseDSNArray['localMySQL']['username'],
                                    'password' => config::$databaseDSNArray['localMySQL']['password'],
                                    'sourceQuery' => 'SELECT
                                                            SSR.roleID,
                                                            SSR.roleName,
                                                            SSR.roleDescription
                                                        FROM
                                                            sys_security_roles AS SSR
                                                        WHERE
                                                            SSR.enabled = :enabled
                                                        ORDER BY
                                                            SSR.roleName ASC',
                                    'queryParams' => array (
                                            ':enabled' => 1
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