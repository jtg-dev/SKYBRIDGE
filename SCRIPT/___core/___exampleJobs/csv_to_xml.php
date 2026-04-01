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
                                    'type' => 'xml',
                                    'location' => '/tmp/output.xml'
                                )
                    )
            )
    );

workhorse::init($config);

class jobTransformer implements i_jobTransformation {
    final public static function preQueueRecordProcessHook(int $exportIndex) {
        switch ($exportIndex) {
            case 0:
                driver::$egressClassName::$xmlWriter->startElement('Users');
                break;
        }
    }

    final public static function postQueueRecordProcessHook(int $exportIndex) {
        switch ($exportIndex) {
            case 0:
                driver::$egressClassName::$xmlWriter->endElement();
                driver::$egressClassName::writeRows();
                break;
        }
    }

    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

        switch ($exportIndex) {
            case 0:
                //build an XML array using the data from each $row
                $out = array (
                        'name' => 'User',
                        'attributes' => array (
                                'id' => 2
                            ),
                        'children' => array (
                                array(
                                        'name' => 'lastName',
                                        'value' => 'ford'
                                    ),

                                array (
                                        'name' => 'races',
                                        'children' => array (
                                                array (
                                                        'name' => 'race',
                                                        'value' => 'w'
                                                    ),
                                                array (
                                                        'name' => 'race',
                                                        'value' => 'h'
                                                    )
                                            )
                                    ),

                                array(
                                        'name' => 'gender',
                                        'value' => 'm'
                                    )
                            )
                    );
                break;
        }

        workhorse::routeProcessorToEgressHandler($out);
    }
}

workhorse::processExportJobs();
?>