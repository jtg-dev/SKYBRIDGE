<?php

/**
 * This is a utility class with misc homeless code
 * @package MMExtranet
 * @version 1.0
 */

class utility {

    /**
     * parses and returns templated string
     * @access public
     * @param string $template string
     * @param array $params template parameters
     * @return string parsed template
     */
    final public static function parseTemplate(string $template, array $params = array()): string {
        if (empty($template) === false && count($params) > 0) {
            foreach ($params as $key => $val) {
                $template = str_replace('%%' . $key . '%%', $val, $template);
            }
            return $template;
        }
        else {
            return $template;
        }
    }

    /**
     * normalizes phone number based on length of number passed as string, optionally tacks on extension if passed
     *	5555555555 -> (555) 555-5555
     *	5555555555|45 -> (555) 555-5555 x45
     *	5555555 -> 555-5555
     *	5555555|143 -> 555-5555 x143
     *	else -> just returns original number
     * @access public
     * @author Matt Ford
     * @param string $number string phone number from progress database, string may be pipe delimited if phone extension exists
     * @return string normalized phone number and optional extension
     */
    final public static function formatPhoneNumber(string $number = ''): string {
        $number = trim($number);
        $result = '';

        if (strpos($number, '|') !== false) {
            $parts = explode('|', $number);
            $number = $parts[0];
            if ($parts[1] != '') {
                $ext = ' x'.$parts[1];
            }
            else {
                $ext = '';
            }
        } else {}

        switch (strlen($number)) {
            case 10:
                $result = preg_replace('/^([0-9]{3})([0-9]{3})([0-9]{4})/', '($1) $2-$3', $number);
                break;

            case 7:
                $result = preg_replace('/^([0-9]{3})^([0-9]{4})/', '$1-$2', $number);
                break;

            default:
                $result = $number;
                break;
        }

        return $result . $ext;
    }

    /**
     * Normalizes Social Security Number
     *	555555555 -> 555-55-5555
     *	else	-> original SSN
     * @access public
     * @author Matt Ford
     * @param string $ssn string version of SSN from progress database
     * @return string normalized SSN
     */
    public static function formatSSN(string $ssn = ''): string {
        $return = $ssn;
        if (strlen($ssn) == 9) {
            $return = preg_replace('/^([0-9]{3})([0-9]{2})([0-9]{4})/', '$1-$2-$3', $ssn);
        } else {}

        return $return;
    }

    /**
     * Translate school grades to human readable format, optionally zero padded. Takes into account Grade Level Override if passed with grade
     *	-1 -> PK
     *	2|3 -> 3 or 03
     * @access public
     * @author Matt Ford
     * @param string $grade string version of grade from progress database
     * @param bool $padZero whether to left-pad numeric grade with zero (9 or 09)
     * @return string grade
     */
    public static function translateGrade(string $grade = '', bool $padZero = false): string {

        if (strpos($grade, '|') !== false) {
            $parts = explode('|', $grade); // convert "01|-1" to array("01","-1")

            //if GLO exists, use it, else fall back on calc grade
            if (trim($parts[1]) != '') {
                $grade = $parts[1];
            }
            else {
                $grade = $parts[0];
            }
        } else {}

        switch ($grade) {
            case '-3':
            case '-2':
            case '-1':
            case 'pk':
            case 'PK':
                $grade = 'PK';
                break;

            case '0':
            case 'kg':
            case 'KG':
            case 'K':
            case 'k':
                $grade = 'KG';
                break;

            default:
                $grade = intval($grade);
                break;
        }

        if ($padZero === true) { //change '2' to '02' if needed
            $grade = str_pad($grade, 2, '0', STR_PAD_LEFT);
        } else {}

        return $grade;
    }

    /**
     * attempts a socket connection to a host and port, essentially a livelihood check
     * @access public
     * @param string $host hostname or IP address, no protocol
     * @param int $port defaults to 636 since its primary use case in this software is checking LDAP hosts
     * @param int $timeout defaults to 1, don't want code to just sit there forever
     * @return bool
     */
    final public function servicePing(string $host, int $port = 636, int $timeout = 1): bool {
        //SUP BRO
        $PCPrincipal = @fsockopen($host, $port, $errno, $errstr, $timeout);
        if ($PCPrincipal === false) {
            return false;
        }
        else {
            fclose($PCPrincipal); //MY FEELS
            return true;
        }
    }

    /**
     * Useful for rekeying SQL query results array. Say you have a result-set as an array of arrays, each array is
     * array('locationID' => 2, 'locationName' => 'whatever')
     * where the locationID is a primary key of the results and you want a quick way to know if a record exists
     * in the results with locationID of 2.
     * This will rekey the results array so the results are keyed to the specified object key..
     * Instead of this
     * array(
     *   0 => array('locationID' => 4, 'name' => 'whatever'),
     *   1 => array('locationID' => 7, 'name' => 'whatever')
     * )
     * You have this
     * array(
     *   4 => array('locationID' => 4, 'name' => 'whatever'),
     *   7 => array('locationID' => 7, 'name' => 'whatever')
     * )
     * So to see if a result exists with locationID 4, instead of having to loop over the array of results, you just check
     * if array_key_exists(4, $array)
     * @access public
     * @param array $arrayOfObjects self explanatory. Most commonly used for SQL results
     * @param string $objectKey the object key to get data from to use as the rekeying data.
     * @return mixed rekeyed array or original data
     */
    final public function reKeyArrayByObjectKey($arrayOfObjects, $objectKey) {
        //check that it is indeed an array
        if (is_array($arrayOfObjects) === true) {
            //check that the array has records
            $limit = count($arrayOfObjects);

            if ($limit > 0) {
                //check that it is indeed an array of objects
                if (is_object($arrayOfObjects[0]) === true) {
                    //check that the first record has the specified objectKey
                    if (isset($arrayOfObjects[0]->$objectKey) === true) {
                        //okay we should be safe
                        $out = array();
                        for ($i = 0; $i < $limit; $i++) {
                            $out[$arrayOfObjects[$i]->$objectKey] = $arrayOfObjects[$i];
                        }

                        return $out;
                    }
                    else {
                        return $arrayOfObjects;
                    }
                }
                else {
                    return $arrayOfObjects;
                }
            }
            else {
                return $arrayOfObjects;
            }
        }
        else {
            return $arrayOfObjects;
        }
    }

    /**
     * determines the school year based on an optional specified date, defaults to current date
     * @access public
     * @param string $referenceDate optional date string to use instead of current date
     * @param int $returnFormat the format of the return data
     * @return array low,high
     */
    final public static function determineSchoolYear($referenceDate = false, int $returnFormat = 0) {
        $date = driver::$dto;
        if ($referenceDate !== false) {
            $referenceDate = strtotime($referenceDate);
            if ($referenceDate !== false) {
                $date = driver::$dto->setTimestamp($referenceDate);
            } else {}
        } else {}

        $endYear = intval($date->format('Y'));

        switch ($returnFormat) {
            default:
            case 0:
                if (intval($date->format('n')) >= 7) { //assuming SY starting in July
                    return array($endYear, ($endYear + 1));
                }
                else {
                    return array(($endYear - 1), $endYear);
                }
                break;
        }
    }

    /**
     * This method generates a token
     * @access public
     * @return string token
     */
    final public static function generateToken(int $length = 25): string {
        $string = bin2hex(openssl_random_pseudo_bytes($length));

        if (mb_strlen($string) > $length) {
            return mb_substr($string, 0, $length);
        }
        else if (mb_strlen($string) < $length) {
            $string .= bin2hex(openssl_random_pseudo_bytes($length));
            return mb_substr($string, 0, $length);
        }
        else {
            return $string;
        }
    }

    /**
     * converts string via htmlentities, removes HTML, compresses multiple spaces, trims
     * @access public
     * @param string $rawString raw string to convert
     * @return string
     */
    final public static function stripHTML_trim(string $rawString): string {
        $rawString = strip_tags($rawString);
        $rawString = htmlentities($rawString);
        $rawString = mb_ereg_replace('&([#0-9a-zA-Z]+);', '', $rawString);
        $rawString = mb_ereg_replace('\s{2,}', ' ', $rawString);
        $rawString = trim($rawString);

        return $rawString;
    }

    /**
     * checks parameters json file for core version and dies on failure
     * @access public
     * @param float $minimumVersion minimum software version, such as 20180618.01
     * @return void
     */
    final public static function requireMinimumSoftwareVersion(float $minimumVersion) {
        $json = file_get_contents(config::$path . '/parameters.json');

        if ($json === false) {
            die('Missing parameters.json file');
        }
        else {
            $json = json_decode($json);

            if ($json === null) {
                die('damaged parameters.json file');
            }
            else {
                if (empty($json->coreVersion) === true) {
                    die('missing coreVersion parameter');
                }
                else {
                    if ($minimumVersion > $json->coreVersion) {
                        die('Minimum required version: ' . $minimumVersion . '; current core version: ' . $json->coreVersion);
                    }
                    else {
                        //continue
                    }
                }
            }
        }
    }

    /**
     * Method to build a home address, line 1. Accounts for blank values.
     * "1", "N", "Infinite Loop" -> "1 N Infinite Loop"
     * "108", "", "Cinnamon Trail" -> "108 Cinnamon Trail"
     * @access public
     * @author Michael V. Preslar
     * @param string $num Address Number
     * @param string $dir Street Directional
     * @param string $street Street Name
     * @return string Formatted address line 1
     */
    final public static function addressBuilder(string $street, string $num = "", string $dir = "") {
        $address = "";

        // Trim whitespace
        $street = trim($street);
        $num = trim($num);
        $dir = trim($dir);

        if ($num != "") { $address = $address . $num . " "; }
        if ($dir != "") { $address = $address . $dir . " "; }
        if ($street != "") { $address = $address . $street; }

        return $address;
    }
}
?>