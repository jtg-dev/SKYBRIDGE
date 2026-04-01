<?php

/**
 * This is a class to keep properties about a district in memory, such as district ID and such.
 * @package MMExtranet
 * @version 1.0
 */

class custom_locationproperties {

    private static $properties = array();
    private static $connectionParametersCache = array();
    public static $districtName = '';

    /**
     * init the static class
     * @access public
     * @param string $districtName nameID of district in config class.
     * @return void
     */
    final public static function init(string $districtName) {
        self::$districtName = $districtName;
        if (array_key_exists($districtName, config::$locationProperties) === true && is_array(config::$locationProperties[$districtName]) === true) {
            self::$properties = config::$locationProperties[$districtName];
        }
        else {
            self::$properties = array();
        }
    }

    /**
     * This method returns the 2 character district ID with leading zeros
     * @access public
     * @return string
     */
    final public static function get_districtID(): string {
        if (isset(self::$properties['districtCode']) === true) {
            return self::$properties['districtCode'];
        }
        else {
            return '';
        }
    }

    /**
     * This method returns the current Skyward HR plan name.
     * @access public
     * @return string
     */
    final public static function getPlanName(): string {
        if (isset(self::$properties["planName"]) === true) {
            return self::$properties["planName"];
        }
        else {
            return '';
        }
    }

    /**
     * This method returns the common url base for a districts descriptor XML files
     * @access public
     * @return string
     */
    final public static function get_descriptorURLBase(string $connectionParametersKey, string $descriptorDocument = '', $descriptorURLParams = array()): string {
        $descriptorDocument = trim($descriptorDocument);

        $descriptorURLBase = self::getConnectionParameters($connectionParametersKey)['descriptorURLBase'];
        if (isset($descriptorURLBase) === true) {
            $url = $descriptorURLBase;
        }
        else {
            $url = 'http://www.ed-fi.org/Descriptor';
        }

        //set default vendor to skyward
        $descriptorURLParams['vendor'] = $descriptorURLParams['vendor'] ?? 'Skyward';

        $url = utility::parseTemplate($url, $descriptorURLParams);

        if ($descriptorDocument !== '') {
            $url .= $descriptorDocument . '.xml';
        } else {}

        return $url;
    }

    /**
     * This method returns the common url base for a district's descriptors (Ed-Fi Suite 3)
     * @access public
     * @return string
     */
    final public static function GetDescriptorUrlBaseSuite3(string $connectionParametersKey, string $descriptorDocument = '', $descriptorURLParams = array()): string {
        $descriptorDocument = trim($descriptorDocument);

        $descriptorURLBase = self::getConnectionParameters($connectionParametersKey)['descriptorURLBase'];
        if (isset($descriptorURLBase) === true) {
            $url = $descriptorURLBase;
        }
        else {
            $url = 'http://www.ed-fi.org/Descriptor';
        }

        //set default vendor to skyward
        $descriptorURLParams['vendor'] = $descriptorURLParams['vendor'] ?? 'Skyward';

        $url = utility::parseTemplate($url, $descriptorURLParams);

        if ($descriptorDocument !== '') {
            $url .= $descriptorDocument;
        } else {}

        return $url;
    }

    /**
     * This method returns the requested connection parameters with caching
     * @access public
     * @return array
     */
    final public static function getConnectionParameters(string $connectionParametersKey): array {
        if (isset(self::$connectionParametersCache[self::$districtName][$connectionParametersKey]) === false) {
            if (isset(config::$connectionParametersStorage[self::$districtName][$connectionParametersKey]) === true) {
                self::$connectionParametersCache[self::$districtName][$connectionParametersKey] = config::$connectionParametersStorage[self::$districtName][$connectionParametersKey];
            }
            else {
                self::$connectionParametersCache[self::$districtName][$connectionParametersKey] = array();
            }
        } else {}

        return self::$connectionParametersCache[self::$districtName][$connectionParametersKey];
    }
}
?>