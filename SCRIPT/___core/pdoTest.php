<?php
require(__DIR__ . '/init.php');

$db = new database_pdo();

$ret = $db->connect(
            config::$databaseDSNArray['localMySQL']['DSN'],
            driver::$crypto->decrypt(config::$databaseDSNArray['localMySQL']['username']),
            driver::$crypto->decrypt(config::$databaseDSNArray['localMySQL']['password'])
        );

if ($ret === false) {
    die(errors::$databaseCantConnect);
} else {}




$sql = 'SELECT
            SSR.roleID,
            SSR.roleName,
            SSR.roleDescription
        FROM
            sys_security_roles AS SSR
        WHERE
            SSR.enabled = :enabled
        ORDER BY
            SSR.roleName ASC';

$params = array (
        ':enabled' => 1
    );


$ret = $db->justquery($sql, $params, false);

if ($ret !== false) {
    if ($db->pdoReference->columnCount() > 0){
        while ($row = $db->pdoReference->fetch(PDO::FETCH_ASSOC)) {
            var_export($row);
        }
    }
    else {
        echo 'no results';
    }

    $db->pdoReference->closeCursor();
    $db->pdoReference = null;
}
else {
    die(errors::$sqlError);
}
?>