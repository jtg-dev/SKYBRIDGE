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
                                    'type' => 'csv',
                                    'location' => '/tmp/input.csv',
                                    'headerRow' => true,
                                    'delimeter' => ',',
                                    'quantifier' => '"'
                                ),
                        'output' => array (
                                    'type' => 'csv',
                                    'location' => '/tmp/output.csv',
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
                if ($row['somefield'] < 10) {
                    //if you want to omit a specific row from the output, just never call the workhorse::routeProcessorToEgressHandler(); method
                    log::logAlert('optional log message');
                    return;
                }
                else {
                    workhorse::routeProcessorToEgressHandler($out);
                }
                break;
        }
    }
}

workhorse::processExportJobs();
?>