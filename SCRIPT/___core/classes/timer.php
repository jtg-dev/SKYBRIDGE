<?php

/**
 * This is a timer class, solely for tracking runtimes for the framework
 * @package MMExtranet
 */

class timer {

    /**
     * The container for the execution timer datum
     * @var array
     */
    private $scriptExecutionTracker = array();

    public function __construct() {
        $this->scriptExecutionTracker[0] = microtime(true);
    }

    /**
     * outputs the generated result text from the timer
     * @access public
     * @return string
     */
    public function getResults() {
        return $this->scriptExecutionTracker[2];
    }

    /**
     * stops the timer
     * @access public
     */
    public function stopTimer() {
        $this->scriptExecutionTracker[1] = microtime(true);
        $this->scriptExecutionTracker[2] = array ();

        $this->scriptExecutionTracker[2] = floatval($this->scriptExecutionTracker[1] - $this->scriptExecutionTracker[0]) . ' seconds';
    }
}
?>