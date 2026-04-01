<?php
//include the init script, get the relative path right
require(__DIR__ . '/../../___core/init.php');


//get location properties object
custom_locationproperties::init('levy');










/*
    Begin lines that are specific to this job
*/
$jobBasePath = '\\\\sftp.nefec.org\\sftpfolders\\EDFI\\edfi\\QA\\';
fileio::makePath($jobBasePath);
/*
    End lines that are specific to this job
*/

//get encrypted connection parameters for this district and vendor from config
$connectionParameters = custom_locationproperties::getConnectionParameters('vendorXXX_API');





$config = array (
        'logDirectory' => $jobBasePath,
        'jobName' => 'Overall Job Name',
        'exports' => array (
                0 => array (
                        'name' => 'Individual Export Name',
                        'enabled' => true,
                        'input' => array (
                                    'type' => 'vendorXXX_API',
                                    'api_superSecretData0' => $connectionParameters['username'],
                                    'api_superSecretData1' => $connectionParameters['password']
                                ),
                        'output' => array (
                                    'type' => 'csv',
                                    'location' => $jobBasePath . 'output.csv',
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
                do any data transformations to make $row look how you want, such as
                $row = array (
                        'username' => $row['user'],
                        'email' => $row['email']
                    );
                */
                workhorse::routeProcessorToEgressHandler($row);

                //If you don't want to send an individual record to the egress, just don't run the routeProcessorToEgressHandler method for that record
                break;
        }
    }
}

workhorse::processExportJobs();
?>