<?php
require(__DIR__ . '/init.php');

/*
    just to get a new encryption key
*/
$key = Defuse\Crypto\Key::createNewRandomKey()->saveToAsciiSafeString();
echo $key;


//spacing
echo "\n\n";



/*
    To encrypt a string value
    (Only runs if an encryption key is already set in config)
*/
if (driver::$crypto !== null) {
    $stringToEncrypt = 'RvcohKz9zHI4';
    $encrypted = driver::$crypto->encrypt($stringToEncrypt);
    $decrypted = driver::$crypto->decrypt($encrypted);

    echo 'Raw String: ' . $stringToEncrypt . "\n\n";
    echo 'Encrypted: ' . $encrypted . "\n\n";
    echo 'Decrypted: ' . $decrypted . "\n\n";
} else {
    echo "No encryption key set yet. Copy the key above into config.TODO.php \$encrytionKey, then re-run this script to test encrypt/decrypt.\n\n";
}
?>