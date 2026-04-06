<?php
require(__DIR__ . '/init.php');

echo 'Encrypted: ' . driver::$crypto->encrypt('') . "\n\n";
echo 'Encrypted: ' . driver::$crypto->encrypt('') . "\n\n";
?>

