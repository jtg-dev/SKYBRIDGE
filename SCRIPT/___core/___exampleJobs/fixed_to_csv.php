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
                                    'type' => 'fixed',
                                    'location' => '/tmp/input.fixed.text',
                                    'layout' => array (
                                            'name' => 8,
                                            'email' => 15,
                                            'gender' => 2,
                                            'race' => 1
                                        )
                                ),
                        'output' => array (
                                    'type' => 'csv',
                                    'location' => '/tmp/output.fixed.csv',
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