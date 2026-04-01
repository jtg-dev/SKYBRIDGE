<?php

/**
 * This is a class for handling a NOOP output
 * @package MMExtranet
 * @version 1.0
 */

class egress_noop implements i_egress {

    public static $totalRecords = 0;

    /**
     * runs validation checks on csv output parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        return true;
    }

    /**
     * flushes the output buffer to the destination
     * @access public
     */
    final private static function writeRows() {}

    /**
     * accepts and logs a row of data to be written out
     * @access public
     * @param array $row accepts a data record from the download handler and writes it to memory
     */
    final public static function ingestRow(array $row) {}

    /**
     * flushes the unsaved data to the destination
     * @access public
     */
    final public static function writeUnsavedRows() {}

    /**
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        self::$totalRecords = 0;
    }
}
?>