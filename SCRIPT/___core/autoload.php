<?php

/**
 * This is the autoloader
 * @package MMExtranet
 */

function mmFrameworkAutoloader(string $className) {
    $className = mb_ereg_replace('[^a-zA-Z0-9_\\\]', '', $className);

    switch ($className) {
        /*
            DefusePHP Crypto
        */
        case 'Defuse\Crypto\Encoding':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/Encoding.php';
            break;

        case 'Defuse\Crypto\File':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/File.php';
            break;

        case 'Defuse\Crypto\Exception\IOException':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/Exception/IOException.php';
            break;

        case 'Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/Exception/WrongKeyOrModifiedCiphertextException.php';
            break;

        case 'Defuse\Crypto\Exception\CryptoException':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/Exception/CryptoException.php';
            break;

        case 'Defuse\Crypto\Exception\BadFormatException':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/Exception/BadFormatException.php';
            break;

        case 'Defuse\Crypto\Core':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/Core.php';
            break;

        case 'Defuse\Crypto\DerivedKeys':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/DerivedKeys.php';
            break;

        case 'Defuse\Crypto\Crypto':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/Crypto.php';
            break;

        case 'Defuse\Crypto\KeyOrPassword':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/KeyOrPassword.php';
            break;

        case 'Defuse\Crypto\Key':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/Key.php';
            break;

        case 'Defuse\Crypto\KeyProtectedByPassword':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/KeyProtectedByPassword.php';
            break;

        case 'Defuse\Crypto\RuntimeTests':
            $fileName = config::$path . '/lib/encryption/defuse/2.0.0/src/RuntimeTests.php';
            break;



        default:
            if (strpos($className, 'ingress_') === 0) {
                $fileName = config::$path . '/classes/ingress/' . $className . '.php';
            }
            elseif (strpos($className, 'egress_') === 0) {
                $fileName = config::$path . '/classes/egress/' . $className . '.php';
            }
            elseif (strpos($className, 'custom_') === 0) {
                $fileName = config::$path . '/classes/custom/' . $className . '.php';
            }
            else {
                $fileName = config::$path . '/classes/' . $className . '.php';
            }
        break;
    }

    if (empty($fileName) === false && file_exists($fileName) === true) {
        require_once($fileName);
    } else {}
}
spl_autoload_register('mmFrameworkAutoloader', true, true);
?>