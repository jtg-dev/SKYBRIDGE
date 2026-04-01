<?php

/**
 * This is the interfaces file
 * @package MMExtranet
 */

interface i_jobTransformation {
    public static function rowProcessor(array $row, int $exportIndex, array $parseOptions);
}

interface i_ingress {
    public static function init();
    public static function beginIteration();
    public static function cleanup();
}

interface i_egress {
    public static function init();
    public static function ingestRow(array $row);
    public static function writeUnsavedRows();
    public static function cleanup();
}
?>