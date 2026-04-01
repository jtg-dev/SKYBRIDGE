<?php

/**
 * This is a class housing all the logging methods
 * @package MMExtranet
 * @version 1.0
 */

class log {

    public static $alertLogFile = '';
    private static $alertLog = array();
    private static $lastLogEntry = '';
    private static $lastLogEntryCount = 0;

    /**
     * logs a message and writes it to STDOUT
     * @access public
     * @param string $message the message to log and print
     * @param int $trailingReturns the number of \r\n to put after the message
     */
    final public static function logAlert(string $message, int $trailingReturns = 0) {
        $message = trim($message);

        if ($message != '') {
            $dto = new DateTimeImmutable();

            if (config::$suppressDuplicateLogEntries === true) {
                if ($message !== self::$lastLogEntry) {
                    self::$lastLogEntry = $message;

                    if (self::$lastLogEntryCount > 1) {
                        $message = '>>>The above message repeated ' . self::$lastLogEntryCount . ' more times' . "\r\n\r\n" . $dto->format(config::$logDataPrefixDateFormat) . $message;
                        self::$lastLogEntryCount = 1;
                    }
                    else {
                        $message = $dto->format(config::$logDataPrefixDateFormat) . $message;
                    }
                }
                else {
                    ++self::$lastLogEntryCount;
                    return;
                }
            }
            else {
                $message = $dto->format(config::$logDataPrefixDateFormat) . $message;
            }

            unset($dto);

            self::$alertLog[] = $message;
            fwrite(STDOUT, $message . "\r\n");

            for ($i = 0; $i < $trailingReturns; $i++) {
                self::$alertLog[] = '';
                fwrite(STDOUT, "\r\n");
            }

            if (count(self::$alertLog) >= config::$alertLogBufferSize) {
                fileio::writeFileData(self::$alertLogFile, implode("\r\n", self::$alertLog) . "\r\n", FILE_APPEND);
                self::$alertLog = array();
            } else {}
        } else {}
    }

    /**
     * writes any pending logs to file
     * @access public
     */
    final public static function cleanup() {
        if (count(self::$alertLog) > 0) {
            fileio::writeFileData(self::$alertLogFile, implode("\r\n", self::$alertLog), FILE_APPEND);
            self::$alertLog = array();
        } else {}
    }
}
?>