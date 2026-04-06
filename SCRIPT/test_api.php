<?php

/**
 * ED-FI SUITE 3 API CONNECTION TEST
 *
 * Tests the following for each configured EdFiSuite3 connection:
 *   1. PHP cURL extension is loaded
 *   2. API connection parameters are present and non-empty
 *   3. Credentials can be decrypted (encryption key is set)
 *   4. OAuth token request succeeds and returns a valid access_token
 *   5. Authenticated GET to a lightweight endpoint returns HTTP 200
 *
 * Run from CLI:
 *   php test_api.php                              — tests ALL EdFiSuite3 connections
 *   php test_api.php --api=prod_edfi              — tests all connections with connKey "prod_edfi"
 *   php test_api.php --dataSource=district        — tests all connections for data source "district"
 *   php test_api.php --dataSource=district --api=prod_edfi  — tests one specific connection
 *
 * The API connection(s) under test are pulled directly from
 * config::$connectionParametersStorage, so keep this file in sync
 * with config.TODO.php as you add/remove API destinations.
 */

require(__DIR__ . '/___core/init.php');


/* ===========================================================================
 * ===   CLI FILTERS
 * =========================================================================== */

$cliOpts        = getopt('', array('dataSource::', 'api::'));
$filterSource   = isset($cliOpts['dataSource']) ? trim($cliOpts['dataSource']) : '';
$filterApi      = isset($cliOpts['api'])        ? trim($cliOpts['api'])        : '';

if (!empty($filterSource) || !empty($filterApi)) {
    $parts = array();
    if (!empty($filterSource)) { $parts[] = 'dataSource=' . $filterSource; }
    if (!empty($filterApi))    { $parts[] = 'api='        . $filterApi;    }
    echo PHP_EOL . '[  INFO  ]  Filtering connections: ' . implode(', ', $parts) . PHP_EOL;
} else {
    echo PHP_EOL . '[  INFO  ]  No filter specified — testing ALL EdFiSuite3 connections.' . PHP_EOL;
}


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

/**
 * Performs a raw cURL request and returns array('body' => ..., 'http_code' => ...).
 * Used for the token request where we need the raw HTTP status code.
 */
function rawCurlPost(string $url, array $postFields): array {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,            $url);
    curl_setopt($ch, CURLOPT_POST,           true);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     http_build_query($postFields));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT,        30);
    $body     = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);
    return array('body' => $body, 'http_code' => $httpCode, 'curl_error' => $curlErr);
}

/**
 * Performs a raw cURL GET with a Bearer token. Returns array('body' => ..., 'http_code' => ...).
 */
function rawCurlGet(string $url, string $token): array {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,            $url);
    curl_setopt($ch, CURLOPT_HTTPGET,        true);
    curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Authorization: Bearer ' . $token));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT,        30);
    $body     = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);
    return array('body' => $body, 'http_code' => $httpCode, 'curl_error' => $curlErr);
}


/* ===========================================================================
 * ===   TEST 1 — PHP cURL EXTENSION
 * =========================================================================== */

section('Test 1: PHP cURL Extension');

$curlLoaded = extension_loaded('curl');
result('PHP curl extension is loaded', $curlLoaded,
    $curlLoaded ? '' : 'Install/enable the php_curl extension and restart PHP.');

if (!$curlLoaded) {
    echo PHP_EOL . 'Cannot continue without the cURL extension. Exiting.' . PHP_EOL;
    exit(1);
}


/* ===========================================================================
 * ===   TEST 2 — ENCRYPTION KEY IS SET
 * =========================================================================== */

section('Test 2: Encryption Key');

$encKeySet = isset(config::$encrytionKey) && !empty(trim(config::$encrytionKey));
result('config::$encrytionKey is not empty', $encKeySet,
    $encKeySet
        ? ''
        : 'Set $encrytionKey in config.TODO.php. See ___core/cryptoexamples.php.');


/* ===========================================================================
 * ===   FIND EdFiSuite3 CONNECTIONS IN CONFIG
 * =========================================================================== */

$apiConnections    = array();
$allApiConnections = array();
foreach (config::$connectionParametersStorage as $dataSourceName => $connections) {
    foreach ($connections as $connKey => $connParams) {
        /* Identify Ed-Fi Suite 3 connections by the expected keys. */
        if (is_array($connParams) &&
            isset($connParams['apiUrlBase'], $connParams['apiClientID'], $connParams['apiClientSecret'])) {
            $allApiConnections[] = array(
                'dataSource' => $dataSourceName,
                'connKey'    => $connKey,
                'params'     => $connParams,
            );
        }
    }
}

/* Apply --dataSource and/or --api filters if provided. */
foreach ($allApiConnections as $entry) {
    $sourceMatch = empty($filterSource) || $entry['dataSource'] === $filterSource;
    $apiMatch    = empty($filterApi)    || $entry['connKey']    === $filterApi;
    if ($sourceMatch && $apiMatch) {
        $apiConnections[] = $entry;
    }
}

if (empty($allApiConnections)) {
    echo PHP_EOL . '[  WARN  ]  No EdFiSuite3 connections found in config::$connectionParametersStorage.' . PHP_EOL;
    echo '            Add an "EdFiSuite3" block with apiUrlBase, apiClientID, apiClientSecret.' . PHP_EOL;
    exit(0);
}

if (empty($apiConnections)) {
    echo PHP_EOL . '[  WARN  ]  No connections matched the filter.' . PHP_EOL;
    echo '            Available connections:' . PHP_EOL;
    foreach ($allApiConnections as $entry) {
        echo '              --dataSource=' . $entry['dataSource'] . ' --api=' . $entry['connKey'] . PHP_EOL;
    }
    exit(0);
}


/* ===========================================================================
 * ===   TESTS 3–5 PER API CONNECTION
 * =========================================================================== */

foreach ($apiConnections as $entry) {
    $label      = $entry['dataSource'] . ' / ' . $entry['connKey'];
    $params     = $entry['params'];
    $apiUrlBase = rtrim($params['apiUrlBase'], '/');

    /* -----------------------------------------------------------------------
     * TEST 3 — Connection parameters are populated
     * --------------------------------------------------------------------- */

    section("Test 3: API Parameters Populated  [{$label}]");

    $urlOk    = !empty($apiUrlBase);
    $idOk     = !empty(trim($params['apiClientID']));
    $secretOk = !empty(trim($params['apiClientSecret']));

    result("apiUrlBase is set  [{$label}]",      $urlOk,    $urlOk    ? $apiUrlBase       : 'apiUrlBase is empty in config.');
    result("apiClientID is set  [{$label}]",     $idOk,     $idOk     ? '(value present)' : 'apiClientID is empty in config.');
    result("apiClientSecret is set  [{$label}]", $secretOk, $secretOk ? '(value present)' : 'apiClientSecret is empty in config.');

    if (!$urlOk || !$idOk || !$secretOk) {
        echo '          Skipping further tests — missing required connection parameters.' . PHP_EOL;
        continue;
    }

    /* -----------------------------------------------------------------------
     * TEST 4 — Decrypt credentials and obtain OAuth token
     * --------------------------------------------------------------------- */

    section("Test 4: OAuth Token  [{$label}]");

    /* Decrypt credentials. */
    $decryptOk = false;
    $clientId  = '';
    $clientSec = '';

    if ($encKeySet) {
        try {
            $clientId  = driver::$crypto->decrypt($params['apiClientID']);
            $clientSec = driver::$crypto->decrypt($params['apiClientSecret']);
            $decryptOk = (is_string($clientId) && !empty($clientId) &&
                          is_string($clientSec) && !empty($clientSec));
        } catch (Exception $e) {
            $decryptOk = false;
        }
    } else {
        /* No encryption key — treat values as plain text for testing. */
        $clientId  = $params['apiClientID'];
        $clientSec = $params['apiClientSecret'];
        $decryptOk = (!empty($clientId) && !empty($clientSec));
    }

    result("Credentials decrypt without error  [{$label}]", $decryptOk,
        $decryptOk
            ? ''
            : 'Decryption failed. Verify $encrytionKey matches the key used in ___core/encryptString.php.');

    if (!$decryptOk) {
        echo '          Skipping token + GET tests — cannot proceed without valid credentials.' . PHP_EOL;
        continue;
    }

    /* Decrypt databaseUuid if present — mirrors how edfiSuite3::init() receives it. */
    $databaseUuid   = '';
    $rawUuid        = trim($params['databaseUuid'] ?? '');
    $instanceSpec   = $params['instanceSpecific'] ?? false;
    /* yearBeforeData — set true in config for Ed-Fi v7.x SaaS instances where the year
     * appears between the UUID and the verb path, e.g.:
     *   token:    /{uuid}/{year}/oauth/token
     *   resource: /{uuid}/{year}/data/v3/                                        */
    $yearBeforeData = !empty($params['yearBeforeData']);

    if (!empty($rawUuid)) {
        if ($encKeySet) {
            try {
                $databaseUuid = trim(driver::$crypto->decrypt($rawUuid));
            } catch (Exception $e) {
                $databaseUuid = $rawUuid; // fall back to raw if decrypt fails
            }
        } else {
            $databaseUuid = $rawUuid;
        }
    }

    /* Year used in URL paths — same source as edfiSuite3::generateURL(). */
    $year = driver::$currentSY[1];

    /* Build token URL.
     *   Standard (no UUID):                /oauth/token
     *   UUID (apiInstYearSpecUrls style):   /{uuid}/oauth/token
     *   UUID + yearBeforeData (v7.x SaaS):  /{uuid}/{year}/oauth/token           */
    if (!empty($databaseUuid) && $yearBeforeData) {
        $tokenPath = '/' . $databaseUuid . '/' . $year . '/oauth/token';
    } elseif (!empty($databaseUuid)) {
        $tokenPath = '/' . $databaseUuid . '/oauth/token';
    } else {
        $tokenPath = '/oauth/token';
    }

    $tokenUrl = $apiUrlBase . $tokenPath;

    $tokenResponse = rawCurlPost($tokenUrl, array(
        'Client_id'     => $clientId,
        'Client_secret' => $clientSec,
        'Grant_type'    => 'client_credentials',
    ));

    /* Check for cURL-level errors first. */
    $curlOk = empty($tokenResponse['curl_error']);
    result("cURL reached the token endpoint  [{$label}]", $curlOk,
        $curlOk ? "URL: {$tokenUrl}" : 'cURL error: ' . $tokenResponse['curl_error']);

    if (!$curlOk) {
        echo '          Skipping token parse + GET tests.' . PHP_EOL;
        continue;
    }

    /* HTTP 200 from token endpoint. */
    $tokenHttpOk = ($tokenResponse['http_code'] === 200);
    result("Token endpoint returned HTTP 200  [{$label}]", $tokenHttpOk,
        $tokenHttpOk
            ? 'HTTP ' . $tokenResponse['http_code']
            : 'HTTP ' . $tokenResponse['http_code'] . '. Check credentials and API URL.');

    /* Parse access_token from response body. */
    $tokenBody   = json_decode($tokenResponse['body'], true);
    $accessToken = $tokenBody['access_token'] ?? '';
    $tokenValid  = !empty($accessToken);

    result("Response contains a non-empty access_token  [{$label}]", $tokenValid,
        $tokenValid
            ? 'Token received (length: ' . strlen($accessToken) . ' chars).'
            : 'access_token missing or empty. Raw response: ' . substr($tokenResponse['body'], 0, 300));

    if (!$tokenValid) {
        echo '          Skipping GET test — no valid token to use.' . PHP_EOL;
        continue;
    }

    /* -----------------------------------------------------------------------
     * TEST 5 — Authenticated GET returns HTTP 200
     * Use the staffs endpoint as a lightweight, predictably-present resource.
     * --------------------------------------------------------------------- */

    section("Test 5: Authenticated GET (HTTP 200)  [{$label}]");

    /* Build the staffs endpoint URL, mirroring edfiSuite3::generateURL() logic.
     *   Standard:                     /data/v3/{year}/ed-fi/staffs?limit=1
     *   instanceSpec (no year):       /data/v3/ed-fi/staffs?limit=1
     *   UUID (apiInstYearSpecUrls):   /data/v3/{uuid}/{year}/ed-fi/staffs?limit=1
     *   UUID + yearBeforeData (v7.x): /{uuid}/{year}/data/v3/ed-fi/staffs?limit=1  */
    if (!empty($databaseUuid) && $yearBeforeData) {
        $getPath = '/' . $databaseUuid . '/' . $year . '/data/v3/ed-fi/staffs?limit=1';
    } elseif (!empty($databaseUuid)) {
        $getPath = '/data/v3/' . $databaseUuid . '/' . $year . '/ed-fi/staffs?limit=1';
    } elseif ($instanceSpec) {
        $getPath = '/data/v3/ed-fi/staffs?limit=1';
    } else {
        $getPath = '/data/v3/' . $year . '/ed-fi/staffs?limit=1';
    }

    /* Append subscription key if present. */
    $subKey = trim($params['apiSubscriptionKey'] ?? '');
    if (!empty($subKey)) {
        try {
            $decSubKey = driver::$crypto->decrypt($subKey);
        } catch (Exception $e) {
            $decSubKey = $subKey;
        }
        $getPath .= '&subscription-key=' . urlencode($decSubKey);
    }

    $getUrl      = $apiUrlBase . $getPath;
    $getResponse = rawCurlGet($getUrl, $accessToken);

    /* cURL connectivity check. */
    $getCurlOk = empty($getResponse['curl_error']);
    result("cURL reached the staffs endpoint  [{$label}]", $getCurlOk,
        $getCurlOk ? "URL: {$getUrl}" : 'cURL error: ' . $getResponse['curl_error']);

    if (!$getCurlOk) {
        continue;
    }

    /* HTTP 200 check. */
    $getHttpOk = ($getResponse['http_code'] === 200);
    result("GET /staffs returned HTTP 200  [{$label}]", $getHttpOk,
        $getHttpOk
            ? 'HTTP 200 — API is reachable and token is valid.'
            : 'HTTP ' . $getResponse['http_code'] . '. Check API URL, year (' . $year . '), and token scope. Raw: ' . substr($getResponse['body'], 0, 300));

    /* Bonus: confirm response body is valid JSON array. */
    if ($getHttpOk) {
        $getBody   = json_decode($getResponse['body'], true);
        $bodyIsArr = is_array($getBody);
        result("Response body is a valid JSON array  [{$label}]", $bodyIsArr,
            $bodyIsArr
                ? 'Records in response: ' . count($getBody)
                : 'Body did not decode to an array. Raw: ' . substr($getResponse['body'], 0, 200));
    }
}


/* ===========================================================================
 * ===   SUMMARY
 * =========================================================================== */

section('Done');
echo '  All tests complete. Review any FAIL lines above.' . PHP_EOL . PHP_EOL;
