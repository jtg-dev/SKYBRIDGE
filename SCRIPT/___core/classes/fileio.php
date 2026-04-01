<?php

/**
 * This is a class housing all the file IO methods
 * @package MMExtranet
 * @version 1.0
 */

class fileio {

    /**
     * recursively check and create a folder structure
     * @access public
     * @param string $pathname absolute path withOUT filename, PATH ONLY
     * @return bool
     * @example makePath('/web/track2-fordm/private/files/some/dir/structure/');
     */
    final public static function makePath($pathname) {
        if (file_exists($pathname) === false) {
            /*
                4 = read
                2 = write
                1 = execute
                0 = no access

                Octal|Owner|Group|Others

                We default to 0770
                so the user/group owner has read/write/execute and everyone else has no access
            */
            return @mkdir($pathname, 0770, true);
        }
        else {
            return true;
        }
    }

    /**
     * attempts to create, or blank out, an existing file
     * @access public
     * @param string $absolutepath The full path to the file, including the filename
     * @return bool
     */
    final public static function createEmptyFile(string $absolutepath): bool {
        return self::writeFileData($absolutepath, '');
    }

    /**
     * attempts to create, or blank out, an existing file
     * @access public
     * @param string $absolutepath The full path to the file, including the filename
     * @param string $data the data to be written to the file
     * @param int $flags the flags parameters to be passed to the file_put_contents, such as FILE_APPEND
     * @return bool
     */
    final public static function writeFileData(string $absolutepath, string $data, int $flags = 0): bool {
        $ret = file_put_contents($absolutepath, $data, $flags);

        if ($ret !== false) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * This method calculates, converts, and reports filesize information
     * @access public
     * @author Matt Ford
     * @param string $file absolute path to a file
     * @param string $units abbreviation of units to report formatted size in, defaults to kb
     * @param integer $rounding number of decimal places to round to, defaults to 2
     * @param string $seperator string seperator to place between in the formatted output between the numerical size and the units, defaults to one space
     * @return array array of filesize information along with units and a per-formatted string output
     */
    final public static function getFilesize($file, $units = 'kb', $rounding = 2, $seperator = ' ') {
        $lcUnits = mb_strtolower($units);
        $fileSize = $returnSize = $returnSize_formatted = '';

        if (file_exists($file)) {
            $fileSize = filesize($file);

            if ($fileSize !== false) {
                switch ($lcUnits) {
                    case 'bits':
                        $returnSize = $fileSize * 8;
                        break;

                    default:
                    case 'b':
                        $returnSize = $fileSize;
                        $units = 'b';
                        break;

                    case 'kb':
                        $returnSize = $fileSize / 1024;
                        break;

                    case 'mb':
                        $returnSize = $fileSize / 1048576;
                        break;

                    case 'gb':
                        $returnSize = $fileSize / 1073741824;
                        break;

                    case 'tb':
                        $returnSize = $fileSize / 1099511627776;
                        break;
                }

                $returnSize_formatted = number_format($returnSize, $rounding, '.', ',');
            } else {}
        } else {}

        $return = array (
                'rawBytes' => $fileSize,
                'convertedSize' => $returnSize,
                'convertedSize_formatted' => $returnSize_formatted,
                'units' => $units,
                'formatted' => $returnSize_formatted . $seperator . $units
            );

        return $return;
    }

    /**
     * This method returns an array representation of a directory listing
     * @access public
     * @author Matt Ford
     * @param string $path base directory to perform the listing
     * @param boolean $onlyFiles whether or not to only output files
     * @param boolean $onlyDirectories whether or not to only output directories
     * @return array directory listing
     */
    final public static function getFileListing($path, $onlyFiles = false, $onlyDirectories = false) {
        $entries = array();
        $folder = $path;
        if ($dh = opendir($folder)) {
            while (($file = readdir($dh)) !== false) {
                $filePath = $folder . '/' . $file;
                if (
                        (($onlyFiles === true && is_file($filePath) === true)
                            ||
                        ($onlyDirectories === true && is_dir($filePath) === true)
                            ||
                        ($onlyFiles !== true && $onlyDirectories !== true))
                            &&
                        ($file != '.' && $file != '..')
                    ) {
                    $entries[] = $file;
                }
                else {

                }
            }
        } else {}

        return $entries;
    }

    /**
     * This will trigger a forced file download for the client, provided the file exists
     * @access public
     * @param string $internalFile absolute path to the filename, including the filename
     * @param string $friendlyFilename The filename to present to the download client
     * @return array
     */
    public static function forceFileDownload($internalFile, $friendlyFilename) {
        if (file_exists($internalFile) === true) {
            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: Binary');
            header('Content-Length: ' . filesize($internalFile));
            header('Content-disposition: attachment; filename="'.$friendlyFilename.'"');
            readfile($internalFile);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * get file contents and return
     * @access public
     * @param string $file absolute path to a file
     * @return string
     */
    public static function getFileContents($file) {
        if (file_exists($file) === true) {
            return file_get_contents($file);
        }
        else {
            return '';
        }
    }

    /**
     * simply performs a touch() on a file to update the last mod time, typically for lock files
     * @access public
     * @param string $file absolute path to a file
     * @return bool
     */
    final public static function updateFileLastMod($file) {
        return touch($file);
    }

    /**
     * deletes a file
     * @access public
     * @param string $file absolute path to a file
     * @return bool
     */
    final public static function deleteFile($file) {
        return unlink($file);
    }
}
?>