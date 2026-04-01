# SKYBRIDGE (FOR-FLCODE) — Setup & Usage Guide

**Version:** SKYBRIDGE / FOR-FLCODE
**Document Date:** 2026-03-17

---

## Table of Contents

1. [System Requirements](#1-system-requirements)
2. [File Structure Overview](#2-file-structure-overview)
3. [Step 1 — Install PHP](#3-step-1--install-php)
4. [Step 2 — Install ODBC Driver](#4-step-2--install-odbc-driver)
5. [Step 3 — Generate an Encryption Key](#5-step-3--generate-an-encryption-key)
6. [Step 4 — Configure the System (`config.TODO.php`)](#6-step-4--configure-the-system-configtodophp)
7. [Step 5 — Encrypt Your Credentials](#7-step-5--encrypt-your-credentials)
8. [Step 6 — Test Your Connections](#8-step-6--test-your-connections)
9. [Step 7 — Run a Job](#9-step-7--run-a-job)
10. [Historical Data Mode](#10-historical-data-mode)
11. [Staff Unique ID Configuration](#11-staff-unique-id-configuration)
12. [Job File Reference](#12-job-file-reference)
13. [Debugging with JSON Export](#13-debugging-with-json-export)
14. [Utility Scripts](#14-utility-scripts)
15. [Troubleshooting](#15-troubleshooting)

---

## 1. System Requirements

| Requirement | Details |
|---|---|
| PHP Version | 7.4.x (7.4.32 recommended) — CLI only |
| PHP Extensions | `php_odbc`, `php_curl`, `mbstring` |
| Database Driver | Progress OpenEdge 11.7 ODBC driver (for Skyward) |
| OS | Windows (primary) or Linux (see `deploy-php.sh`) |
| Network Access | Must reach Skyward database host and Ed-Fi API endpoint |
| Skyward Access | Read-only ODBC user credentials for the SKYWARD database |
| Ed-Fi Access | API client ID and secret for your Ed-Fi Suite 3 endpoint |

---

## 2. File Structure Overview

```
FOR-FLCODE/
├── init.bat                    ← Set your PHP and path variables here
├── staff.bat                   ← Shortcut batch script for staff jobs
├── test_database.php           ← Run first: ODBC connection test
├── test_api.php                ← Run second: Ed-Fi API connection test
├── query_name_table.php        ← Utility: browse the Skyward NAME table
├── phpInfo.php                 ← Utility: display PHP configuration
├── deploy-php.sh               ← Linux: PHP 7.4.32 install script
├── SETUP.md                    ← This file
├── YEAR_CONFIG.txt             ← Quick reference for historical year values
│
├── ___core/
│   ├── init.php                ← Framework bootstrap (do not edit)
│   ├── autoload.php            ← Class autoloader (do not edit)
│   ├── cryptoexamples.php      ← Step 3: generate encryption key
│   ├── encryptString.php       ← Step 5: encrypt individual credential values
│   └── classes/
│       └── config.TODO.php     ← Step 4: YOUR MAIN CONFIG FILE
│
├── jobs/
│   └── Shared/
│       ├── EdFi_Staff_Suite3-API.php   ← Live API staff export
│       ├── EdFi_Staff_Suite3-JSON.php  ← Debug JSON staff export
│       └── EdFi_Descriptors_Suite3.php ← Descriptor export
│
└── logs/                       ← Log files written here automatically
```

---

## 3. Step 1 — Install PHP

### Windows

1. Download PHP 7.4.x (Non-Thread-Safe, x64) from https://windows.php.net/download/
2. Extract to a folder such as `E:\php7\`
3. Copy `php.ini-development` to `php.ini` in the same folder
4. Edit `php.ini` and enable these extensions (remove the leading `;`):
   ```ini
   extension=curl
   extension=mbstring
   extension=odbc
   ```
5. Set the extension directory if needed:
   ```ini
   extension_dir = "ext"
   ```

### Linux

Run the included deploy script as root:
```bash
chmod +x deploy-php.sh
sudo ./deploy-php.sh
```
This installs PHP 7.4.32 with the required extensions.

---

## 4. Step 2 — Install ODBC Driver

The framework uses Progress OpenEdge ODBC to connect to Skyward.

1. Obtain the **Progress OpenEdge 11.7 ODBC driver** from your Skyward vendor or internal IT team.
2. Install the driver on the machine that will run the scripts.
3. Confirm installation by running:
   ```bash
   php test_database.php
   ```
   The second check ("Progress OpenEdge driver present") will pass if installed correctly.

> **Note:** The DSN is configured entirely in `config.TODO.php` — you do not need to create a Windows ODBC data source (DSN) in the system ODBC manager.

---

## 5. Step 3 — Generate an Encryption Key

All credentials in SKYBRIDGE are stored encrypted. You must generate a unique encryption key for your installation.

1. Edit `init.bat` and set your PHP path:
   ```bat
   set PHPPATH=C:\php7\php.exe
   set COREBASE=C:\path\to\FOR-FLCODE\___core
   ```

2. Run the key generator:
   ```bash
   php ___core\cryptoexamples.php
   ```

3. The script will output a key that looks like:
   ```
   def00000...  (long encoded string)
   ```

4. Copy this key value. Open `___core\classes\config.TODO.php` and paste it:
   ```php
   public static $encryptionKey = 'def00000...your key here...';
   ```

> **Important:** Keep this key secure. Without it, encrypted credentials cannot be decrypted. Do not commit it to version control.

---

## 6. Step 4 — Configure the System (`config.TODO.php`)

Open `___core\classes\config.TODO.php`. This is the only file you need to edit for a standard deployment.

### 4a. Set the Encryption Key

Already done in Step 3. Confirm it is in place:
```php
public static $encryptionKey = 'def00000...';
```

### 4b. Add Your Data Source

Replace `"dataSource goes here"` with your district's identifier (e.g., `"MYDISTRICT"`). This name is used as the `--dataSource` argument when running jobs.

```php
self::$connectionParametersStorage['MYDISTRICT'] = array(

    /* === Skyward Business Database (Production) === */
    "prod_skyward_fin" => array(
        'DSN'      => 'Driver={Progress OpenEdge 11.7 driver};Host=YOUR_HOST;Db=SKYWARD;Port=YOUR_PORT;DIL=READ UNCOMMITTED',
        'username' => '',  // Will be filled with encrypted value in Step 5
        'password' => ''   // Will be filled with encrypted value in Step 5
    ),

    /* === Ed-Fi Suite 3 API === */
    "EdFiSuite3" => array(
        'descriptorURLBase' => 'uri://skywardsis.com/',
        'apiUrlBase'        => 'https://your-edfi-api.example.com/api/v5.3',
        'apiClientID'       => '',   // Will be filled with encrypted value in Step 5
        'apiClientSecret'   => '',   // Will be filled with encrypted value in Step 5
        'databaseUuid'      => '',   // Optional: e.g. 'Prod-v5-3', leave blank if not needed
        'instanceSpecific'  => false // true = school year NOT in URL path; false = school year IN URL path
    ),

    /* === JSON Debug Exports === */
    "json" => array(
        'descriptorURLBase' => 'uri://skywardsis.com/'
    )
);
```

### 4c. Set HR Plans

Map your district name to the Skyward HR plan name used for primary job assignments:
```php
public static $hrPlans = array(
    "MYDISTRICT" => "YOUR HR PLAN NAME",
);
```

### 4d. Set Location Properties

```php
self::$locationProperties = array(
    'MYDISTRICT' => array(
        'districtCode' => '12',           // Florida DOE district number (or EdOrg number)
        'planName'     => self::$hrPlans["MYDISTRICT"],
    )
);
```

### 4e. Historical Data (Optional)

Leave these at their defaults for normal operation. See [Section 10](#10-historical-data-mode) for details.
```php
public static $useHistoricalData      = false;
public static $historicalYear         = 2024;
public static $historicalSnapshotDate = '2024-06-30';
```

### 4f. Staff Unique ID (Optional)

Leave at defaults unless your district uses the ALTERNATE-ID field. See [Section 11](#11-staff-unique-id-configuration).
```php
public static $staffUniqueIdField      = 'HAAPRO-OTHER-ID';
public static $staffUniqueIdTableAlias = 'HAAPRO';
```

---

## 7. Step 5 — Encrypt Your Credentials

Never store plain-text passwords in the config file. Use the included encryption utility.

1. Run the encryption helper:
   ```bash
   php ___core\encryptString.php
   ```

2. When prompted, enter the value to encrypt (e.g., your Skyward password). Copy the output.

3. Paste the encrypted value into `config.TODO.php` for each credential:
   ```php
   'username' => '...encrypted-username...',
   'password' => '...encrypted-password...',
   'apiClientID'     => '...encrypted-client-id...',
   'apiClientSecret' => '...encrypted-secret...',
   ```

Repeat for every credential that needs protecting: Skyward username, Skyward password, Ed-Fi client ID, Ed-Fi client secret.

---

## 8. Step 6 — Test Your Connections

### Test the Database Connection

```bash
php test_database.php
```

Expected output (all five checks should show PASS):
```
[1/5] PHP ODBC extension loaded ................ PASS
[2/5] Progress OpenEdge driver present ......... PASS
[3/5] Encryption key set ....................... PASS
[4/5] Credentials decrypt without error ........ PASS
[5/5] Test query executes ...................... PASS
```

### Test the Ed-Fi API Connection

```bash
php test_api.php
```

Expected output:
```
[1/5] PHP cURL extension loaded ................ PASS
[2/5] Encryption key set ....................... PASS
[3/5] API parameters populated ................. PASS
[4/5] OAuth token endpoint responds ............ PASS
[5/5] Authenticated GET request succeeds ....... PASS
```

If any check fails, refer to [Section 15 — Troubleshooting](#15-troubleshooting).

---

## 9. Step 7 — Run a Job

### Edit `init.bat` First

Set the paths to match your environment:
```bat
set PHPPATH=C:\php7\php.exe
set COREBASE=C:\path\to\FOR-FLCODE\___core
set JOBBASEPATH=C:\path\to\FOR-FLCODE\jobs
```

### Run the Staff API Export

```bash
php jobs\Shared\EdFi_Staff_Suite3-API.php --dataSource=MYDISTRICT --api=EdFiSuite3
```

This sends staff data (people, credentials, assignments, etc.) to your Ed-Fi Suite 3 API endpoint.

### Run the Descriptor Export First (if needed)

If your Ed-Fi instance does not yet have descriptors loaded, run the descriptor job first:
```bash
php jobs\Shared\EdFi_Descriptors_Suite3.php --dataSource=MYDISTRICT --api=EdFiSuite3
```

### Enable/Disable Individual Sub-Exports

Open `EdFi_Staff_Suite3-API.php` and find the `enabled` array to toggle individual sub-exports:
```php
'enabled' => array(
    'people'                    => true,   // Staff demographic records
    'credentials'               => true,   // Credentials / certifications
    'staffs'                    => true,   // Core staff records
    'employmentAssociations'    => true,   // Employment associations
    'assignmentAssociations'    => true,   // Position assignments
    'openStaffPositions'        => false,  // Open positions (disabled by default)
    'staffAbsenceEvents'        => true,   // Absence/leave events
),
```

Set any to `false` to skip that export in a given run.

---

## 10. Historical Data Mode

Historical mode allows you to extract data "as of" a past school year. This is useful for corrections, audits, and re-submissions.

### Enable Historical Mode

In `config.TODO.php`, set:
```php
public static $useHistoricalData      = true;
public static $historicalYear         = 2023;         // End year of the desired school year
public static $historicalSnapshotDate = '2023-06-30'; // "As of" date for Skyward snapshots
```

### YEAR_CONFIG.txt Quick Reference

The file `YEAR_CONFIG.txt` contains pre-filled values for common historical years:

| School Year | `$historicalYear` | `$historicalSnapshotDate` |
|---|---|---|
| 2021–2022 | 2022 | 2022-06-30 |
| 2022–2023 | 2023 | 2023-06-30 |
| 2023–2024 | 2024 | 2024-06-30 |
| 2024–2025 | 2025 | 2025-06-30 |

Copy and paste the appropriate values into your config.

### What Historical Mode Affects

- SQL WHERE clauses use `%%schoolYear%%` resolved to the historical year
- API URLs use `%%year%%` resolved to `$historicalYear`
- A large warning banner prints to the console at startup:
  ```
  *** HISTORICAL DATA MODE: School Year 2022-2023 ***
  ```
- `driver::$currentSY` is set to `[historicalYear - 1, historicalYear]`

### Return to Normal Mode

Set `$useHistoricalData = false;`. The system will automatically use the current school year.

---

## 11. Staff Unique ID Configuration

By default, SKYBRIDGE uses the `HAAPRO-OTHER-ID` field from the `HAAPRO-PROFILE` Skyward table as the Ed-Fi staff unique identifier. If your district stores the Ed-Fi ID in a different field (`ALTERNATE-ID` from the NAME table), you can switch sources.

### Use ALTERNATE-ID

In `config.TODO.php`:
```php
public static $staffUniqueIdField      = 'ALTERNATE-ID';
public static $staffUniqueIdTableAlias = 'N';
```

### Fallback Behavior

If `ALTERNATE-ID` is configured but empty for a specific staff record, SKYBRIDGE automatically falls back to `HAAPRO-OTHER-ID` and logs a warning:
```
WARN: ALTERNATE-ID was empty, falling back to HAAPRO-OTHER-ID for: SMITH, JOHN
```

Review the log file after each run to identify staff records that need their ALTERNATE-ID populated in Skyward.

### Use HAAPRO-OTHER-ID (Default)

```php
public static $staffUniqueIdField      = 'HAAPRO-OTHER-ID';
public static $staffUniqueIdTableAlias = 'HAAPRO';
```

---

## 12. Job File Reference

### `EdFi_Staff_Suite3-API.php`

**Purpose:** Sends staff data to the live Ed-Fi Suite 3 API.
**Usage:**
```bash
php jobs\Shared\EdFi_Staff_Suite3-API.php --dataSource=MYDISTRICT --api=EdFiSuite3
```

**Sub-exports available:**
- `people` — StaffEducationOrganizationContactAssociations / staff demographics
- `credentials` — StaffCredentials
- `staffs` — Core Staff records
- `employmentAssociations` — StaffEducationOrganizationEmploymentAssociations
- `assignmentAssociations` — StaffSectionAssociations / assignment records
- `openStaffPositions` — OpenStaffPositions
- `staffAbsenceEvents` — StaffAbsenceEvents (leave records)

---

### `EdFi_Staff_Suite3-JSON.php`

**Purpose:** Exports staff data to local JSON files for testing. Does NOT send to the API.
**Usage:**
```bash
php jobs\Shared\EdFi_Staff_Suite3-JSON.php --dataSource=MYDISTRICT --api=json
```

JSON files are written to the `logs/` folder with timestamps. Useful for inspecting the exact payload that would be sent to the API before going live.

---

### `EdFi_Descriptors_Suite3.php`

**Purpose:** Uploads Ed-Fi descriptor values to the API (required once, or when descriptors change).
**Usage:**
```bash
php jobs\Shared\EdFi_Descriptors_Suite3.php --dataSource=MYDISTRICT --api=EdFiSuite3
```

---

### `floridaCodeProduction_InterchangeStaffAssociation_edfi_apiv25.php`

**Purpose:** Generates Florida DOE Interchange Staff Association XML for state reporting (Ed-Fi API v2.5 format).
**Usage:**
```bash
php jobs\Shared\floridaCodeProduction_InterchangeStaffAssociation_edfi_apiv25.php --dataSource=MYDISTRICT
```

---

## 13. Debugging with JSON Export

When troubleshooting data issues, always run the JSON export before the API export:

1. Run the JSON export:
   ```bash
   php jobs\Shared\EdFi_Staff_Suite3-JSON.php --dataSource=MYDISTRICT --api=json
   ```

2. Open the generated files in `logs/`:
   ```
   logs\MYDISTRICT_staffs_20260317_143022.json
   logs\MYDISTRICT_people_20260317_143022.json
   ```

3. Verify the data looks correct before sending to the API.

4. When ready, run the API export:
   ```bash
   php jobs\Shared\EdFi_Staff_Suite3-API.php --dataSource=MYDISTRICT --api=EdFiSuite3
   ```

---

## 14. Utility Scripts

### `phpInfo.php` — PHP Configuration Viewer

```bash
php phpInfo.php | more
```

Displays full PHP configuration. Useful for verifying extension loading and INI settings. Alternatively, open it in a browser if you have a local web server running.

---

### `___core\cryptoexamples.php` — Generate Encryption Key

```bash
php ___core\cryptoexamples.php
```

Generates a new Defuse encryption key. Run once per installation.

---

### `___core\encryptString.php` — Encrypt a Value

```bash
php ___core\encryptString.php
```

Prompts for a plaintext value, returns the encrypted ciphertext to paste into `config.TODO.php`.

---

## 15. Troubleshooting

### `test_database.php` Fails at Step 1 — ODBC Extension Not Loaded

- Edit your `php.ini` and ensure `extension=odbc` is uncommented.
- On Linux, ensure `php7.4-odbc` is installed.

### `test_database.php` Fails at Step 2 — Driver Not Found

- Install the Progress OpenEdge 11.7 ODBC driver.
- The exact driver name in `config.TODO.php` must match the installed driver name exactly. Check via Windows ODBC Data Source Administrator > Drivers tab.

### `test_database.php` Fails at Step 4 — Credentials Cannot Decrypt

- Confirm `$encryptionKey` in `config.TODO.php` matches the key used when `encryptString.php` was run.
- Re-encrypt credentials using the current key if the key changed.

### `test_database.php` Fails at Step 5 — Query Fails

- Verify the `Host` and `Port` values in the DSN string.
- Confirm the Skyward database service is running and accessible from this machine.
- Verify the ODBC user has SELECT permission on `SKYWARD.PUB.DISTRICT-CONFIG`.

### `test_api.php` Fails at Step 4 — Token Endpoint Fails

- Confirm `apiUrlBase` is correct and accessible from this machine.
- Check that the API client ID and secret are correctly encrypted and pasted into config.
- Try pinging or curl-ing the token URL manually from the command line.

### API Export Returns 409 Conflict

- A resource with the same natural key already exists in Ed-Fi.
- The framework will attempt a PUT (update) automatically if POST returns 409.
- If it still fails, the record may have a conflicting key difference; inspect the JSON export to find mismatches.

### Historical Mode Warning at Startup

```
*** HISTORICAL DATA MODE: School Year 2022-2023 ***
```

This is expected and intentional when `$useHistoricalData = true`. If you see this warning during what should be a normal/current run, set `$useHistoricalData = false` in `config.TODO.php`.

### Log Files

All jobs write logs to the `logs/` directory. Logs are named with the data source and timestamp:
```
logs\MYDISTRICT_EdFi_Staff_Suite3_20260317_143022.log
```

Check logs for `WARN` and `ERROR` entries to diagnose issues.

---

## Quick Start Checklist

- [ ] PHP 7.4.x installed and accessible from CLI
- [ ] `php.ini` has `curl`, `mbstring`, `odbc` extensions enabled
- [ ] Progress OpenEdge 11.7 ODBC driver installed
- [ ] `init.bat` updated with correct PHP path
- [ ] `___core\cryptoexamples.php` run — encryption key generated
- [ ] `config.TODO.php` renamed to `config.TODO.php`
- [ ] `$encryptionKey` set in `config.TODO.php`
- [ ] District data source added to `$connectionParametersStorage`
- [ ] Skyward DSN host/port set
- [ ] Skyward credentials encrypted and placed in config
- [ ] Ed-Fi API URL set
- [ ] Ed-Fi client ID and secret encrypted and placed in config
- [ ] `$hrPlans` and `$locationProperties` set
- [ ] `php test_database.php` — all 5 checks PASS
- [ ] `php test_api.php` — all 5 checks PASS
- [ ] `php jobs\Shared\EdFi_Staff_Suite3-JSON.php` — JSON output looks correct
- [ ] `php jobs\Shared\EdFi_Descriptors_Suite3.php` — descriptors loaded
- [ ] `php jobs\Shared\EdFi_Staff_Suite3-API.php` — live export successful
