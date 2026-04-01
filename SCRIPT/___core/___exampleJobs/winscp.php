<?php
require('/path/to/___core/init.php');

$config = array (
        'logDirectory' => '/tmp/',
        'jobName' => 'Test Job',
        'exports' => array ()
    );

workhorse::init($config);

class jobTransformer implements i_jobTransformation {

    final public static function postQueueProcesserLoopHook() {
        global $config;

        winscp::useSession('sessionName');
        winscp::setLogfile($config['logDirectory'] . 'winscp.txt');
        winscp::setCLIParameter('optionname');
        winscp::setCLIParameter('optionname2', 'value');

        $batchCommands = array (
            'option batch on',
            'option confirm off',
            'lcd "/local/directory/with/files/"',
            'cd /subdirectory',
            'mput *.*',
            'dir',
            'exit'
        );
        winscp::setCLIParameter('command', $batchCommands);

        winscp::processSetup();
    }

    final public static function rowProcessor(array $row, int $exportIndex, array $parseOptions) {

    }
}

workhorse::processExportJobs();
?>