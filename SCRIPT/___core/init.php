<?php

/**
 * This is the common init script that enables an entrypoint to use the framework
 * @package MMExtranet
 */

if (php_sapi_name() === 'cli') {
    error_reporting(E_ALL);
    ini_set('include_path', '.');
    ini_set('default_charset', 'UTF-8');
    mb_internal_encoding('UTF-8');
    mb_regex_encoding('UTF-8');
    set_time_limit(0);
    ini_set('MAX_EXECUTION_TIME', -1);

    $path = __DIR__;
    require($path . '/classes/config.TODO.php');
    config::$path = $path;
    require(config::$path . '/interfaces.php');
    require(config::$path . '/autoload.php');
    config::init();
    driver::init();
}
else {
    die('CLI only');
}
?>