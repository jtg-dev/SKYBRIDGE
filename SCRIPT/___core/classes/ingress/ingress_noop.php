<?php

/**
 * This is a class for handling a NOOP input
 * @package MMExtranet
 * @version 1.0
 */

class ingress_noop implements i_ingress {

    /**
     * runs validation checks on csv input parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        return true;
    }

    /**
     * cycles through the input data
     * @access public
     */
    final public static function beginIteration() {
        workhorse::routeRowToProcessor(array());
    }

    /**
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {}
}
?>