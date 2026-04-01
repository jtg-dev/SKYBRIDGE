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
                                    'type' => 'odbc',
                                    'dsn' => config::$databaseDSNArray['odbcsource']['DSN'],
                                    'username' => config::$databaseDSNArray['odbcsource']['username'],
                                    'password' => config::$databaseDSNArray['odbcsource']['password'],
                                    'insertQuery' => 'INSERT INTO
                                                            someTable
                                                        (col1, col2, col3)
                                                        VALUES(\'%%colval1%%\', \'%%colval2%%\', \'%%colval3%%\')'
                                )
                    )
            )
    );

workhorse::init($config);

class jobTransformer implements i_jobTransformation {
    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

        switch ($exportIndex) {
            case 0:
                $out = array (
                        'colval1' => $row['name'],
                        'colval2' => $row['email'],
                        'colval3' => $row['gender']
                    );
                break;
        }

        workhorse::routeProcessorToEgressHandler($out);
    }
}

workhorse::processExportJobs();
?>