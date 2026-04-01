<?php
require('../___core/init.php');

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
                /*
                    In the event that one incoming record might require more than one output.
                    You can do this by sending an array of output arrays and specifying a second
                    boolean argument of TRUE, or simply by calling the
                    workhorse::routeProcessorToEgressHandler(array $array); multiple times
                    without the second parameter.
                */

                $out = array ();

                $out[] = array (
                        'name' => $row['name']
                    );

                $out[] = array (
                        'name' => $row['name2']
                    );
                break;
        }

        workhorse::routeProcessorToEgressHandler($out, true);

        //OR

        workhorse::routeProcessorToEgressHandler($record);
        workhorse::routeProcessorToEgressHandler($record);
        workhorse::routeProcessorToEgressHandler($record);
    }
}

workhorse::processExportJobs();
?>