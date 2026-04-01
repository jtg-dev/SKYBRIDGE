<?php
require('/path/to/___core/init.php');

$config = array (
        'logDirectory' => '/tmp/',
        'jobName' => 'Test Job',
        'exports' => array (
                array (
                        'name' => 'Student Attendance To DB',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'csv',
                                    'location' => '/tmp/input.csv',
                                    'headerRow' => true,
                                    'delimeter' => ',',
                                    'quantifier' => '"'
                                ),
                        'output' => array (
                                    'type' => 'pdo',
                                    'dsn' => config::$databaseDSNArray['localMySQL']['DSN'],
                                    'username' => config::$databaseDSNArray['localMySQL']['username'],
                                    'password' => config::$databaseDSNArray['localMySQL']['password'],
                                    'insertQuery' => 'INSERT INTO
                                                            someTable
                                                        (col1, col2, col3)
                                                        VALUES(:colval1, :colval2, :colval3)'
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
                        ':colval1' => $row['name'],
                        ':colval2' => $row['email'],
                        ':colval3' => $row['gender']
                    );
                break;
        }

        workhorse::routeProcessorToEgressHandler($out);
    }
}

workhorse::processExportJobs();
?>