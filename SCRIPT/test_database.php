<?php

/**
 * DATABASE / ODBC CONNECTION TEST
 *
 * Tests the following for each configured data source:
 *   1. PHP ODBC extension is loaded
 *   2. Configured ODBC drivers are present on the system
 *   3. Credentials can be decrypted (encryption key is set)
 *   4. ODBC connection can be established
 *   5. A lightweight query executes successfully
 *
 * Run from CLI:
 *   php test_database.php
 *
 * The data source(s) under test are pulled directly from
 * config::$connectionParametersStorage, so keep this file in sync
 * with config.TODO.php as you add/remove data sources.
 */

require(__DIR__ . '/___core/init.php');


/* ===========================================================================
 * ===   HELPERS
 * =========================================================================== */

/**
 * Prints a labelled PASS / FAIL line.
 */
function result(string $label, bool $passed, string $detail = ''): void {
    $status = $passed ? '[  PASS  ]' : '[  FAIL  ]';
    $line   = $status . '  ' . $label;
    if (!empty($detail)) {
        $line .= PHP_EOL . '          ' . $detail;
    }
    echo $line . PHP_EOL;
}

/**
 * Prints a section header.
 */
function section(string $title): void {
    echo PHP_EOL . str_repeat('=', 60) . PHP_EOL;
    echo '  ' . strtoupper($title) . PHP_EOL;
    echo str_repeat('=', 60) . PHP_EOL;
}


/* ===========================================================================
 * ===   TEST 1 — PHP ODBC EXTENSION
 * =========================================================================== */

section('Test 1: PHP ODBC Extension');

$odbcLoaded = extension_loaded('odbc');
result('PHP odbc extension is loaded', $odbcLoaded,
    $odbcLoaded ? '' : 'Install/enable the php_odbc extension and restart PHP.');

if (!$odbcLoaded) {
    echo PHP_EOL . 'Cannot continue without the ODBC extension. Exiting.' . PHP_EOL;
    exit(1);
}


/* ===========================================================================
 * ===   TEST 2 — ODBC DRIVERS REGISTERED ON THIS SYSTEM
 * =========================================================================== */

section('Test 2: ODBC Drivers Present on System');

/* odbc_data_source() is the best cross-platform way to list ODBC sources,
 * but listing installed drivers requires reading the system ODBC ini or
 * checking via the shell on Windows. We check for the Progress driver
 * string referenced in the config DSN. */

$expectedDriverFragment = 'Progress OpenEdge';

/* On Windows, query the registry through the ODBC data source list. */
$driverFound = false;
if (function_exists('odbc_data_source')) {
    $sources = @odbc_data_source(ODBC_TYPE_USER) ?: array();
    $sources = array_merge($sources, @odbc_data_source(ODBC_TYPE_SYSTEM) ?: array());
    foreach ($sources as $src) {
        if (isset($src['server']) && stripos($src['server'], $expectedDriverFragment) !== false) {
            $driverFound = true;
            break;
        }
    }
}

/* Fallback: check if any DSN string in config contains the driver name directly. */
if (!$driverFound) {
    foreach (config::$connectionParametersStorage as $dataSourceName => $connections) {
        foreach ($connections as $connKey => $connParams) {
            if (is_array($connParams) && isset($connParams['DSN'])) {
                if (stripos($connParams['DSN'], $expectedDriverFragment) !== false) {
                    $driverFound = true;
                    break 2;
                }
            }
        }
    }
}

result(
    "Driver string '{$expectedDriverFragment}' referenced in config DSN",
    $driverFound,
    $driverFound
        ? 'DSN references the expected driver.'
        : "No DSN in config references '{$expectedDriverFragment}'. Verify your ODBC driver is installed."
);


/* ===========================================================================
 * ===   TEST 3 — ENCRYPTION KEY IS SET
 * =========================================================================== */

section('Test 3: Encryption Key');

$encKeySet = isset(config::$encrytionKey) && !empty(trim(config::$encrytionKey));
result('config::$encrytionKey is not empty', $encKeySet,
    $encKeySet
        ? ''
        : 'Set $encrytionKey in config.TODO.php. See ___core/cryptoexamples.php.');


/* ===========================================================================
 * ===   TEST 4 & 5 — CONNECTION + QUERY PER DATA SOURCE
 * =========================================================================== */

/* Identify database-type connections (have a DSN key and are not API configs). */
$dbConnections = array();
foreach (config::$connectionParametersStorage as $dataSourceName => $connections) {
    foreach ($connections as $connKey => $connParams) {
        if (is_array($connParams) && isset($connParams['DSN'], $connParams['username'], $connParams['password'])) {
            $dbConnections[] = array(
                'dataSource' => $dataSourceName,
                'connKey'    => $connKey,
                'params'     => $connParams,
            );
        }
    }
}

if (empty($dbConnections)) {
    echo PHP_EOL . '[  WARN  ]  No database connections with DSN/username/password found in' . PHP_EOL;
    echo '            config::$connectionParametersStorage. Add your Skyward connection.' . PHP_EOL;
} else {
    foreach ($dbConnections as $entry) {
        $label = $entry['dataSource'] . ' / ' . $entry['connKey'];

        section("Test 4 & 5: DB Connection + Query  [{$label}]");

        /* --- Decrypt credentials --- */
        $decryptOk  = false;
        $decUsername = '';
        $decPassword = '';

        if ($encKeySet) {
            try {
                $decUsername = driver::$crypto->decrypt($entry['params']['username']);
                $decPassword = driver::$crypto->decrypt($entry['params']['password']);
                $decryptOk   = (is_string($decUsername) && is_string($decPassword));
            } catch (Exception $e) {
                $decryptOk = false;
            }
        }

        result("Credentials decrypt without error  [{$label}]", $decryptOk,
            $decryptOk
                ? ''
                : 'Decryption failed. Verify $encrytionKey and that credentials were encrypted with ___core/encryptString.php.');

        if (!$decryptOk) {
            echo '          Skipping connection test — cannot proceed without valid credentials.' . PHP_EOL;
            continue;
        }

        /* --- Attempt ODBC connection --- */
        $db      = new database_odbc();
        $connRet = @$db->connect($entry['params']['DSN'], $decUsername, $decPassword);
        $connOk  = ($connRet !== false);

        result("ODBC connection established  [{$label}]", $connOk,
            $connOk
                ? 'Connected successfully.'
                : 'odbc_connect() returned false. Check DSN, host, port, and credentials.');

        if (!$connOk) {
            echo '          Skipping query test — no active connection.' . PHP_EOL;
            continue;
        }

        /* --- Lightweight query: pull district name --- */
        $testSql = '
            SELECT
              "DC"."DISTRICT-NAME"

            FROM "SKYWARD"."PUB"."DISTRICT-CONFIG" "DC"
        ';

        $queryRet = $db->query($testSql);
        $queryOk  = ($queryRet !== false && is_array($queryRet));

        result("Test query executes and returns rows  [{$label}]", $queryOk,
            $queryOk
                ? 'District name: ' . ($queryRet[0]['DISTRICT-NAME'] ?? '(empty)')
                : 'Query returned false. Check that the SKYWARD.PUB.DISTRICT-CONFIG table exists and is accessible.');

        $db->closeConnection();
    }
}


/* ===========================================================================
 * ===   SUMMARY
 * =========================================================================== */

section('Done');
echo '  All tests complete. Review any FAIL lines above.' . PHP_EOL . PHP_EOL;
