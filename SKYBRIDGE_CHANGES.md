# SKYBRIDGE — Changes & Differences from PHP_STAFF

**Updated Version:** FOR-FLCODE (SKYBRIDGE)
**Baseline Version:** PHP_STAFF
**Document Date:** 2026-03-17

---

## Overview

SKYBRIDGE (FOR-FLCODE) is an evolved version of the PHP_STAFF framework. It is a PHP CLI-based ETL (Extract, Transform, Load) data migration system designed to pull staff and HR data from Skyward (Progress OpenEdge) databases and push it to Ed-Fi Suite 3 APIs. SKYBRIDGE adds new capabilities on top of the original codebase: historical year data extraction, alternate staff ID support, improved testing tools, split job execution modes, and bug fixes.

---

## 1. Configuration Changes (`config.TODO.php`)

SKYBRIDGE significantly expands the configuration class with new options:

### New: Historical Data Extraction Settings

```php
// PHP_STAFF — NOT PRESENT

// SKYBRIDGE — NEW
public static $useHistoricalData       = false;
public static $historicalYear          = 2024;  // School year end year (e.g. 2024 = 2023-2024)
public static $historicalSnapshotDate  = '2024-06-30';  // "As of" date for snapshot queries
```

When `$useHistoricalData` is `true`, all SQL queries and API calls use the historical year and snapshot date instead of the current school year. A console warning is printed at startup.

### New: Staff Unique ID Field Selection

```php
// PHP_STAFF — NOT PRESENT

// SKYBRIDGE — NEW
public static $staffUniqueIdField      = 'HAAPRO-OTHER-ID';  // or 'ALTERNATE-ID'
public static $staffUniqueIdTableAlias = 'HAAPRO';           // or 'N' for NAME table
```

This allows administrators to choose whether the staff's Ed-Fi unique ID comes from `HAAPRO-OTHER-ID` (the default Skyward field) or the `ALTERNATE-ID` field from the joined NAME table.

## 2. Driver Changes (`driver.php`)

### Historical Mode Awareness

SKYBRIDGE's driver initializes and tracks historical mode, automatically deriving the fiscal year start from the configured `$historicalYear`:

```php
// PHP_STAFF — NOT PRESENT

// SKYBRIDGE — NEW
if (config::$useHistoricalData === true) {
    self::$currentSY = [config::$historicalYear - 1, config::$historicalYear];
    echo "\n*** HISTORICAL DATA MODE: School Year " . self::$currentSY[0] . "-" . self::$currentSY[1] . " ***\n\n";
}
```

In PHP_STAFF, `driver::$currentSY` was always calculated from the live system date. In SKYBRIDGE, when historical mode is enabled, this array is overridden with the configured historical years, and a visible console warning is printed so operators know they are not running against current data.

### Encryption Initialization Guard

```php
// PHP_STAFF
self::$crypto = new crypt_aes(config::$encrytionKey);

// SKYBRIDGE — wrapped in empty check to prevent crashes on unconfigured installs
if (!empty(config::$encryptionKey)) {
    self::$crypto = new crypt_aes(config::$encryptionKey);
}
```

---

## 3. Custom Ed-Fi Suite 3 Queries (`custom_edfiSuite3.php`)

This is the most heavily modified file between the two versions.

### ALTERNATE-ID / NAME Table Join Support

PHP_STAFF's SQL queries used `HAAPRO-OTHER-ID` as the staff unique identifier with no alternative. SKYBRIDGE adds a JOIN to the NAME table (`HAAPRO-PROFILE`) and introduces a runtime-substituted token:

```sql
-- PHP_STAFF (simplified example)
SELECT HAAPRO.HAAPRO-OTHER-ID AS staffUniqueId ...
FROM SKYWARD.PUB.HAAPRO-PROFILE AS HAAPRO

-- SKYBRIDGE — uses %%staffIdColumn%% token
SELECT %%staffIdColumn%% AS staffUniqueId ...
FROM SKYWARD.PUB.HAAPRO-PROFILE AS HAAPRO
LEFT JOIN SKYWARD.PUB.NAME-TABLE AS N ON HAAPRO.SOME-ID = N.SOME-ID
```

The `%%staffIdColumn%%` token is replaced at runtime with either `HAAPRO.HAAPRO-OTHER-ID` or `N.ALTERNATE-ID` based on configuration. This change was applied across **13+ SQL query methods**.

### `getStaffId()` Helper Method — NEW

```php
// SKYBRIDGE — NEW helper
public static function getStaffId(array $row): string {
    $id = $row['staffUniqueId'] ?? '';
    if (empty($id)) {
        // Fallback to HAAPRO-OTHER-ID if ALTERNATE-ID is empty
        $id = $row['staffUniqueIdFallback'] ?? '';
        if (!empty($id)) {
            log::alert('WARN', 'ALTERNATE-ID was empty, falling back to HAAPRO-OTHER-ID for: ' . $row['name'] ?? '');
        }
    }
    return $id;
}
```

When `ALTERNATE-ID` is configured but empty for a given staff member, this method falls back to `HAAPRO-OTHER-ID` and logs a warning so the issue can be corrected in the source system.

### `%%schoolYear%%` Template Token — NEW

PHP_STAFF had some hardcoded year values in SQL WHERE clauses. SKYBRIDGE replaces these with a `%%schoolYear%%` token resolved at runtime:

```sql
-- PHP_STAFF
WHERE YEAR-COLUMN = 2024

-- SKYBRIDGE
WHERE YEAR-COLUMN = %%schoolYear%%
```

This enables the same query to work correctly for both current and historical data extraction without code changes.

### `%%year%%` Token in API URLs — NEW

PHP_STAFF had hardcoded school year values in approximately **40 API URL paths**. SKYBRIDGE replaces all of them with the `%%year%%` token:

```php
// PHP_STAFF
$url = $apiBase . '/2024/ed-fi/staffs';

// SKYBRIDGE
$url = str_replace('%%year%%', driver::$currentSY[1], $apiBase . '/%%year%%/ed-fi/staffs');
```

---

## 4. Job File Changes (`jobs/Shared/`)

### Split Job Files — Major Change

PHP_STAFF had a single combined job file:
```
jobs/Shared/EdFi_Staff_Suite3.php
```

SKYBRIDGE splits this into two separate, purpose-specific job files:

| File | Purpose |
|------|---------|
| `EdFi_Staff_Suite3-API.php` | Sends staff data to the live Ed-Fi Suite 3 API via HTTP POST/PUT |
| `EdFi_Staff_Suite3-JSON.php` | Exports staff data to local JSON files (for testing/debugging without touching the API) |

This separation makes it much easier to validate data locally before committing to a live API send.

### Job Invocation

```bash

# SKYBRIDGE — API send
php jobs\Shared\EdFi_Staff_Suite3-API.php --dataSource=DISTRICT --api=EdFiSuite3

# SKYBRIDGE — local JSON debug export
php jobs\Shared\EdFi_Staff_Suite3-JSON.php --dataSource=DISTRICT --api=json
```

### Individual Export Enable/Disable Flags — NEW

Each job in SKYBRIDGE has granular flags to enable or disable individual sub-exports:

```php
// SKYBRIDGE — each export can be toggled independently
'enabled' => array(
    'people'                    => true,
    'credentials'               => true,
    'staffs'                    => true,
    'employmentAssociations'    => true,
    'assignmentAssociations'    => true,
    'openStaffPositions'        => false,
    'staffAbsenceEvents'        => true,
),
```

## 5. Testing Tool Improvements

### `test_database.php`

SKYBRIDGE consolidates the scattered database test scripts into a single structured 5-step test:

1. PHP ODBC extension loaded?
2. Progress OpenEdge driver installed?
3. Encryption key configured?
4. Credentials decrypt without error?
5. Test query executes successfully against `DISTRICT-CONFIG`?

Each step is clearly labeled with PASS/FAIL status in the output.

### `test_api.php`

Similar 5-step structured test for API connectivity:

1. PHP cURL extension loaded?
2. Encryption key configured?
3. API parameters populated?
4. OAuth token endpoint responds?
5. Authenticated GET request succeeds?

---

## 6. Bug Fixes

### `loopDataWithCallback` Missing Return Statement

```php
// PHP_STAFF — missing return caused PHP notices (array offset on bool)
public static function loopDataWithCallback(array $data, callable $callback): array {
    foreach ($data as $key => $row) {
        $data[$key] = $callback($row);
        // Missing: return $record;
    }
}

// SKYBRIDGE — fixed
public static function loopDataWithCallback(array $data, callable $callback): array {
    foreach ($data as $key => $row) {
        $record = $callback($row);
        return $record;  // Added
    }
}
```

### Encryption Initialization Crash

```php
// PHP_STAFF — crashes if $encrytionKey is empty (unconfigured install)
self::$crypto = new crypt_aes(config::$encrytionKey);

// SKYBRIDGE — guarded
if (!empty(config::$encryptionKey)) {
    self::$crypto = new crypt_aes(config::$encryptionKey);
}
```

### Config Key Name Mismatch for `locationProperties`

PHP_STAFF had a mismatch between `$locationProperties` array keys in `config::init()` and the values expected by `custom_locationproperties.php`. SKYBRIDGE corrects the key names to be consistent.

### `cryptoexamples.php` Null Check

```php
// PHP_STAFF — could throw a null reference exception
echo config::$encrytionKey;

// SKYBRIDGE — checks first
if (empty(config::$encryptionKey)) {
    echo "No key configured. Generating new key...\n";
}
```

---