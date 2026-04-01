<?php

/**
 * This is the error class of error messages
 * @package MMExtranet
 */

class errors {
    public static $noTrailingBasepathSlash = 'I did not detect a trailing slash in your job logDirectory.';

    public static $logDirectoryIsNotDir = 'The job logDirectory is not a directory. Does it even exist?';

    public static $logDirectoryNotWritable = 'The job logDirectory is not writable, permission denied.';

    public static $hasStartupErrors = 'Malformed $config variable. Please review your script configuration for errors and try again.';

    public static $noJobInputType = 'This export job has no input type';

    public static $noJobOutputType = 'This export job has no output type';

    public static $jobInputTypeNotRecognized = 'This export job has an unrecognized input type';

    public static $jobOutputTypeNotRecognized = 'This export job has an unrecognized output type';

    public static $inputFileNotFoundorNotReadable = 'The ingest file is not found or we do not have permission to read from it.';

    public static $outputFileNotFoundorNotWritable = 'The output file could not be created or is not writable.';

    public static $outputFileNoFixedLayout = 'The output file is missing a valid file layout.';

    public static $inputFileNoFixedLayout = 'The input file is missing a valid file layout.';

    public static $databaseCantConnect = 'Database connect failure';

    public static $sqlError = 'There was a problem with your query and/or parameters';

    public static $noDBResults = 'There were no DB results from your query';

    public static $dbInsertFailed = 'The DB Insert query failed';

    public static $dbColumnCountWrong = 'The necessary Insert parameter names could not be round in the record row';

    public static $ingressClassNoInterface = 'The ingress handler does not follow the ingress coding standard';

    public static $egressClassNoInterface = 'The egress handler does not follow the ingress coding standard';

    public static $ingressClassNoXMLDataRegion = 'The ingress handler does not have your dataRegion parameter';

    public static $edfi_noAPIBaseURL = 'There was no Edfi API Base URL';

    public static $edfi_noAPIClientID = 'There was no Edfi API oAuth Client ID';

    public static $edfi_noAPIClientSecret = 'There was no Edfi API oAuth Client Secret';

    public static $edfi_apiFailure = 'There was a problem communicating with the Edfi API';

    public static $edfi_apiSubscriptionKey = 'INFORMATION: A subscription key is configured for this export.';

    public static $generic_requiredFieldMising = 'There was a missing required field';
}
?>