<?php

/**
 * This is the site config class
 * @package MMExtranet
 */
class config
{

    /**
     * array winscp configuration values
     * @var array
     */
    public static $winscp = array(
        'executable' => '"C:\Program Files (x86)\WinSCP\WinSCP.exe"'
    );

    /**
     * array winscp configuration values
     * @var array
     */
     
    /* GitHub Notes:
     *   Run \___core\cryptoexamples.php to generate a new encryption key and insert here. 
     *   Then, use \___core\encryptString.php to encrypt your key(s), secret(s), and other connection info in the config.php file. */
    public static $encrytionKey = '';
    
    /* ==================================================================================================
     * ===   HISTORICAL DATA EXTRACTION CONFIG START  ===================================================
     * ================================================================================================== */
    
    /**
     * Set to TRUE to force the driver to use specific historical dates.
     * Set to FALSE for normal daily operations (uses current date/year).
     * @var bool
     */
    public static $useHistoricalData = false; 

    /**
     * The target school year for the extract (e.g., 2022 for the 2021-2022 school year).
     * Only used if $useHistoricalData is true.
     * @var int
     */
    public static $historicalYear = 2025;

    /**
     * The "As Of" date to check if staff were active/certified. 
     * Usually Oct 15th (Survey 2) or Feb 15th (Survey 3) of that school year.
     * Format: YYYY-MM-DD
     * @var string
     */
    public static $historicalSnapshotDate = '2025-06-30';
    // survey dates (reporting period) - set due dates by the state

    /* ================================================================================================
     * ===   HISTORICAL DATA EXTRACTION CONFIG END  ===================================================
     * ================================================================================================ */
    /**
     * software path, this is dynamically filled in
     * @var string
     */
    public static $path = '';

    /**
     * buffer size before logs are dumped to file
     * @var int
     */
    public static $alertLogBufferSize = 50;

    /**
     * Suppress duplicate log entries
     * @var bool
     */
	 
	/** 
	public static $databaseDSNArray = array();
	*/
	
    public static $suppressDuplicateLogEntries = true;

    /**
     * date format for alert data prefix
     * @var int
     */
    public static $logDataPrefixDateFormat = 'D F jS, Y h:i:s T: ';

    /**
     * buffer size before export data is dumped to file
     * @var int
     */
    public static $exportDataBufferSize = 20;

    /**
     * roughly how many records (sent to egress) between reminders which export is currently running
     * @var int
     */
    public static $egressRecordsBetweenExportFileReminder = 2000;

    /**
     * array of locations and their associated properties (such as name, misc codes, etc)
     * @var array
     */
    public static $locationProperties = array();

    /**
     * array of locations and their associated properties (such as name, misc codes, etc)
     * @var array
     */
    public static $connectionParametersStorage = array();

    /**
     * Array of Skyward HR plan descriptions use for the primary job assignment records.
     * @var array
     */
    public static $hrPlans = array(
        "DISCTRICT_NAME" => "HR_PLAN_NAME",
    );

    /**
     * The staff identifier field (column name) to use as the unique staff ID sent to Ed-Fi.
     *
     * This value is combined with $staffUniqueIdTableAlias below to form a fully-qualified
     * SQL column reference ("ALIAS"."COLUMN-NAME") injected into every staff query at runtime
     * via the %%staffIdColumn%% template token.
     *
     * The SQL queries always also select "HAAPRO"."HAAPRO-OTHER-ID" as a guaranteed fallback.
     * If the configured column resolves but is empty for a specific row, a log::logAlert()
     * warning is written and "HAAPRO-OTHER-ID" is used as the fallback value.
     *
     * Common values:
     *   'HAAPRO-OTHER-ID' = Florida Education ID (FLEID) — use with $staffUniqueIdTableAlias = 'HAAPRO'
     *   'ALTERNATE-ID'    = Alternate ID from the NAME table — use with $staffUniqueIdTableAlias = 'N'
     *   'FEDERAL-ID-NO'   = Federal ID from the NAME table — use with $staffUniqueIdTableAlias = 'N'
     * @var string
     */
    public static $staffUniqueIdField = 'HAAPRO-OTHER-ID';

    /**
     * The SQL table alias that owns $staffUniqueIdField in the staff queries.
     *
     * Every staff query joins exactly two tables that may hold a staff identifier:
     *   'HAAPRO' → "SKYWARD"."PUB"."HAAPRO-PROFILE"  (columns prefixed HAAPRO-*)
     *   'N'      → "SKYWARD"."PUB"."NAME"             (e.g. ALTERNATE-ID, FEDERAL-ID-NO)
     *
     * These two values are combined at runtime as: "ALIAS"."COLUMN-NAME"
     * Example: alias='N', field='ALTERNATE-ID' → "N"."ALTERNATE-ID" in SQL
     *
     * If an invalid alias is set, a SQL error will occur immediately (intentional —
     * misconfiguration should fail loudly, not silently fall back).
     * @var string
     */
    public static $staffUniqueIdTableAlias = 'HAAPRO';

    public static function init() {
        self::$connectionParametersStorage = array();

        self::$connectionParametersStorage['DISTRICT_NAME'] = array( // Change to your district name.
            /* === Skyward Business Database (Production) === */
            "prod_skyward_fin"            => array(
                'DSN'      => 'Driver={Progress OpenEdge 11.7 driver};Host=;Db=SKYWARD;Port=;DIL=READ UNCOMMITTED', // Set host and port values.
                'username' => '', // Use \___core\encryptString.php to encrypt.
                'password' => ''  // Use \___core\encryptString.php to encrypt.
            ),
            /* === Ed-Fi Suite 2 API Connection === */
            "EdFiSuite2" => array(
                'descriptorURLBase'  => 'http://',
                'apiUrlBase'         => '',
                'apiClientID'        => '', // Use \___core\encryptString.php to encrypt.
                'apiClientSecret'    => '', // Use \___core\encryptString.php to encrypt.
                'apiSubscriptionKey' => ''  // Leave blank if your API URL does not require a subscription key. Use \___core\encryptString.php to encrypt.
            ),
            /* === Ed-Fi Suite 3 API Connection === */
            "EdFiSuite3" => array(
                'descriptorURLBase' => 'uri://skywardbis.com/',
                'apiUrlBase'        => '', // Use \___core\encryptString.php to encrypt.
                'apiClientID'       => '', // Use \___core\encryptString.php to encrypt.
                'apiClientSecret'   => '', // Use \___core\encryptString.php to encrypt.
                'databaseUuid'      => '',   // Leave blank if database UUID is not part of the API URL. Use \___core\encryptString.php to encrypt.
                'instanceSpecific'  => false // true = School year not in API URL; false = School year part of API URL. (see \___core\classes\edFiSuite3.php)
            ),
            /* === JSON Exports (for debugging) === */
            "json"          => array(
                'descriptorURLBase' => '' // Just copy what you use for Ed-Fi sends.
            )
        );



        /* ===============================
         * ===   LOCATION PROPERTIES   ===
         * =============================== */
        self::$locationProperties = array(
            'DISTRICT_NAME' => array(
                'districtCode' => '', // Florida DOE district number, or use your EdOrg number.
                'planName'     => self::$hrPlans["DISTRICT_NAME"], // Inherits from $hrPlans above.
            )
        );
    }
}

?>
