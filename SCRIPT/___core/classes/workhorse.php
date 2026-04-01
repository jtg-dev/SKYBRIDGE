<?php

/**
 * This is the workhorse class, primary data router
 * @package MMExtranet
 * @version 1.0
 */

class workhorse {

    private static $config = array();
    public static $currentExportInt = 0;
    private static $overallTimer = null;
    private static $hasStartupError = false;
    private static $egressRecordsBetweenExportFileReminder = 0;

    /**
     * does some overall validation of the job
     * @access public
     * @param array $config array of config for the current job
     */
    final public static function init(array $config) {
        self::$overallTimer = new timer();
        log::logAlert('Data Migration starting');
        self::$config = $config;

        /*
            Sanity Checks
        */

        //verify we have a job name
        if (isset(self::$config['jobName']) === true) {
            self::$config['jobName'] = trim(self::$config['jobName']);
        }
        else {
            self::$config['jobName'] = '';
        }
        self::$config['jobName'] = (self::$config['jobName'] !== '') ? self::$config['jobName'] : 'Generic_Export_Job_' . driver::$now;

        //verify we have a base path
        if (isset(self::$config['logDirectory']) === true) {
            self::$config['logDirectory'] = trim(self::$config['logDirectory']);
        }
        else {
            self::$config['logDirectory'] = '';
        }


        //check we have a trailing slash
        $temp = substr(self::$config['logDirectory'], -1);
        if ($temp !== '/' && $temp !== '\\') {
            log::logAlert(errors::$noTrailingBasepathSlash);
            self::$hasStartupError = true;
        } else {}

        //verify path exists
        if (is_dir(self::$config['logDirectory']) === false) {
            log::logAlert(errors::$logDirectoryIsNotDir);
            self::$hasStartupError = true;
        } else {}

        //verify it's writable
        /*if (is_writable(self::$config['logDirectory']) === false) {
            log::logAlert(errors::$logDirectoryNotWritable);
            self::$hasStartupError = true;
        } else {}*/

        //verify we have an array of exports
        if (isset(self::$config['exports']) === false) {
            self::$config['exports'] = array();
        } else {}

        //honor a valid exportDataBufferSize override if present
        if (isset(self::$config['exportDataBufferSize']) === true) {
            self::$config['exportDataBufferSize'] = intval(self::$config['exportDataBufferSize']);
            if (self::$config['exportDataBufferSize'] > 0) {
                config::$exportDataBufferSize = self::$config['exportDataBufferSize'];
            }
            else {
                unset(self::$config['exportDataBufferSize']);
            }
        } else {}



        //continue
        if (self::$hasStartupError === false) {
            //create logfile
            log::$alertLogFile = self::$config['logDirectory'] . self::$config['jobName'] . '_log.txt';
            fileio::createEmptyFile(log::$alertLogFile);
        }
        else {
            log::logAlert(errors::$hasStartupErrors);
        }

        log::logAlert('Configuration Initialized', 1);
    }

    /**
     * loops over the export jobs and processes them
     * @access public
     */
    final public static function processExportJobs() {
        if (self::$hasStartupError === false) {




            //save bools about existence of classes and methods
            if (class_exists('jobTransformer') === true) {
                driver::$sharedBools['job_jobTransformer'] = true;

                if (in_array('i_jobTransformation', class_implements('jobTransformer')) === true) {
                    driver::$sharedBools['job_useInterface_jobTransformer'] = true;
                } else {}

                if (method_exists('jobTransformer', 'rowProcessor') === true) {
                    driver::$sharedBools['job_rowProcessor'] = true;
                } else {}

                if (method_exists('jobTransformer', 'preQueueProcesserLoopHook') === true) {
                    driver::$sharedBools['job_preQueueProcesserLoopHook'] = true;
                } else {}

                if (method_exists('jobTransformer', 'preQueueRecordProcessHook') === true) {
                    driver::$sharedBools['job_preQueueRecordProcessHook'] = true;
                } else {}

                if (method_exists('jobTransformer', 'postQueueRecordProcessHook') === true) {
                    driver::$sharedBools['job_postQueueRecordProcessHook'] = true;
                } else {}

                if (method_exists('jobTransformer', 'postQueueProcesserLoopHook') === true) {
                    driver::$sharedBools['job_postQueueProcesserLoopHook'] = true;
                } else {}
            } else {}


            if (driver::$sharedBools['job_jobTransformer'] === true && driver::$sharedBools['job_preQueueProcesserLoopHook'] === true) {
                log::logAlert('executing pre-process-loop code', 1);
                jobTransformer::preQueueProcesserLoopHook(self::$config);
            } else {}

            //loop over export jobs
            $exportKeys = array_keys(self::$config['exports']);
            $numFiles = count($exportKeys);

            for ($i = 0; $i < $numFiles; $i++) {
                $fileTimer = new timer();
                self::$currentExportInt = $exportKeys[$i];
                driver::$currentExport = self::$config['exports'][self::$currentExportInt];
                self::$egressRecordsBetweenExportFileReminder = config::$egressRecordsBetweenExportFileReminder;
                log::logAlert('=========================================================================================');
                log::logAlert('BEGIN Processing export #' . $exportKeys[$i] . ' (' . driver::$currentExport['name'] . ')');

                if (isset(driver::$currentExport['enabled']) && driver::$currentExport['enabled'] !== false) {
                    self::processExport();
                }
                else {
                    log::logAlert('This export is disabled');
                }
                log::logAlert('END Processing export');
                $fileTimer->stopTimer();
                log::logAlert('File Runtime: ' . $fileTimer->getResults());

                //get filesize if applicable
                if (isset(driver::$currentExport['output']['location']) === true && file_exists(driver::$currentExport['output']['location']) === true) {
                    $filesize = fileio::getFilesize(driver::$currentExport['output']['location']);
                    log::logAlert('File Size: ' . $filesize['formatted']);
                } else {}

                log::logAlert('Queue Record Completed', 3);

                //reset
                self::$currentExportInt = 0;
                driver::$currentExport = array();
                driver::$ingressClassName = '';
                driver::$egressClassName = '';
            }

            if (driver::$sharedBools['job_jobTransformer'] === true && driver::$sharedBools['job_postQueueProcesserLoopHook'] === true) {
                log::logAlert('executing post-process-loop code');
                jobTransformer::postQueueProcesserLoopHook();
            } else {}
        } else {}





        log::logAlert('/////////////////////////////////////////////////////////////////////////////////////////');
        log::logAlert('/////////////////////////////////////////////////////////////////////////////////////////');
        log::logAlert('/////////////////////////////////////////////////////////////////////////////////////////');
        log::logAlert('Data Migration ending');

        //save overall timer stats
        self::$overallTimer->stopTimer();
        log::logAlert('Overall Runtime: ' . self::$overallTimer->getResults());
		//echo "Connection failed: " . odbc_errormsg();

        //save any unsaved alerts
        log::cleanup();
    }

    /**
     * processes an individual export job
     * @access private
     */
    final private static function processExport() {
        if (isset(driver::$currentExport['input']['type']) === true) {
            if (isset(driver::$currentExport['output']['type']) === true) {

                driver::$ingressClassName = 'ingress_' . strtolower(driver::$currentExport['input']['type']);
                driver::$egressClassName = 'egress_' . strtolower(driver::$currentExport['output']['type']);


                if (class_exists(driver::$ingressClassName) === true) {
                    if (in_array('i_ingress', class_implements(driver::$ingressClassName)) === true) {
                        if (driver::$ingressClassName::init() === false) {
                            return;
                        } else {}
                    }
                    else {
                        log::logAlert(errors::$ingressClassNoInterface);
                    }
                }
                else {
                    log::logAlert(errors::$jobInputTypeNotRecognized);
                    return;
                }

                if (class_exists(driver::$egressClassName) === true) {
                    if (in_array('i_egress', class_implements(driver::$egressClassName)) === true) {
                        if (driver::$egressClassName::init() === false) {
                            return;
                        } else {}
                    }
                    else {
                        log::logAlert(errors::$egressClassNoInterface);
                    }
                }
                else {
                    log::logAlert(errors::$jobOutputTypeNotRecognized);
                    return;
                }
            }
            else {
                log::logAlert(errors::$noJobOutputType);
                return;
            }
        }
        else {
            log::logAlert(errors::$noJobInputType);
            return;
        }

        if (driver::$sharedBools['job_jobTransformer'] === true && driver::$sharedBools['job_preQueueRecordProcessHook'] === true) {
            jobTransformer::preQueueRecordProcessHook(self::$currentExportInt);
        } else {}

        driver::$ingressClassName::beginIteration();
        driver::$egressClassName::writeUnsavedRows();

        if (driver::$sharedBools['job_jobTransformer'] === true && driver::$sharedBools['job_postQueueRecordProcessHook'] === true) {
            jobTransformer::postQueueRecordProcessHook(self::$currentExportInt);
        } else {}

        log::logAlert('Total Records: ' . driver::$egressClassName::$totalRecords);

        driver::$ingressClassName::cleanup();
        driver::$egressClassName::cleanup();
    }

    /**
     * accepts a row from the input source and routes it to the job's processor class
     * @access public
     * @param array $row the multi-dimensional array of data from the ingress handler to the job class
     */
    final public static function routeRowToProcessor(array $row) {
        if (driver::$sharedBools['job_jobTransformer'] === true && driver::$sharedBools['job_rowProcessor'] === true && driver::$sharedBools['job_useInterface_jobTransformer'] === true) {
            $parseOptions = (isset(driver::$currentExport['parseOptions']) && is_array(driver::$currentExport['parseOptions'])) ? driver::$currentExport['parseOptions'] : array();
            jobTransformer::rowProcessor($row, self::$currentExportInt, $parseOptions);
        }
        else {
            //send to egress handler
            self::routeProcessorToEgressHandler($row);
        }
    }

    /**
     * accepts a row from the input source and routes it to the job's processor class
     * @access public
     * @param array $row the multi-dimensional array of data from the row processor of the job class to the egress handler
     */
    final public static function routeProcessorToEgressHandler(array $data, bool $isMultipleRecords = false) {
        //send to egress handler
        if ($isMultipleRecords === true) {
            $limit = count($data);
            self::$egressRecordsBetweenExportFileReminder -= $limit;
            for ($i = 0; $i < $limit; $i++) {
                driver::$egressClassName::ingestRow($data[$i]);
            }
        }
        else {
            --self::$egressRecordsBetweenExportFileReminder;
            driver::$egressClassName::ingestRow($data);
        }

        if (self::$egressRecordsBetweenExportFileReminder <= 0) {
            self::$egressRecordsBetweenExportFileReminder = config::$egressRecordsBetweenExportFileReminder;
            log::logAlert('**********');
            log::logAlert('*sigh*, still running the extract: ' . driver::$currentExport['name']);
            log::logAlert('**********');
        } else {}
    }

    /**
     * returns the export config array
     * @access public
     * @return array
     */
    final public static function getConfig() {
        return self::$config;
    }
}
?>