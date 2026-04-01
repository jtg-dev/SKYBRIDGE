<?php
//include the init script
require(__DIR__ . '/../../___core/init.php');

/*
    get CLI Options and Validate
*/
/*driver::$cliOptions = getopt('', array('dataSource:', 'studentDataSource:'));
driver::$cliOptions['dataSource'] = driver::$cliOptions['dataSource'] ?? false;
driver::$cliOptions['dataSource'] = isset(config::$locationProperties[driver::$cliOptions['dataSource']]) ? driver::$cliOptions['dataSource'] : false;

if (driver::$cliOptions['dataSource'] === false) {
    die('Invalid --dataSource');
} else {}
*/

//get location properties object
custom_locationproperties::init('baker');
$jobBasePath = 'C:\\Users\\fordm.c\\Desktop\\genericdatamigrator\\'; //trailing slash required
fileio::makePath($jobBasePath);








//job config parameters
$config = array (
        'logDirectory' => $jobBasePath,
        'jobName' => 'Overall Name Of Job',
        'exports' => array (
                0 => array (
                        'name' => 'Queue Record Name',
                        'enabled' => true,
                        'input' => array (

                                ),
                        'output' => array (

                                )
                    )
            )
    );

//validate config parameters and initialize core
workhorse::init($config);

//optional, yet recommended, job processor
class jobTransformer implements i_jobTransformation {

    final public static function preQueueProcesserLoopHook(array &$config) {
        //This will be executed, once, before the first queue record
        /*
            $config pointer to the overall config parameter array
        */
    }

    final public static function preQueueRecordProcessHook(int $exportIndex) {
        //This will be executed, once, before each queue record is started
        /*
            $exportIndex = the array index number of the current queue record being processed
        */
    }

    final public static function postQueueRecordProcessHook(int $exportIndex) {
        //This will be executed, once, after each queue record is completed
        /*
            $exportIndex = the array index number of the current queue record being processed
        */
    }

    final public static function postQueueProcesserLoopHook() {
        //This will be executed, once, after the last queue record
        /*
            $config pointer to the overall config parameter array
        */
    }

    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {
        //this will be fed the input data, one row at a time
        /*
            $row = associative array of input data, one row
            $exportIndex = the array index number of the current queue record being processed
            $parseOptions = optional array of parse options from the queue record

            workhorse::routeProcessorToEgressHandler(array $outputRow);
        */
    }
}


//start going through the queue
workhorse::processExportJobs();
?>