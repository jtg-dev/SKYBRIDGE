<?php

class custom_floridacode_queries_InterchangeStaffAssociation {
    public static $shared_Staff = '
        SELECT
          "A"."ADDRESS2"
         ,"A"."LOC-HOR" AS \'LATITUDE\'
         ,"A"."LOC-VER" AS \'LONGITUDE\'
         ,"A"."STREET-APPT"
         ,"A"."STREET-DIR"
         ,"A"."STREET-NAME"
         ,"A"."STREET-NUMBER"
         ,"A"."ZIP-CODE"
         ,"AC"."COUNTY-LDESC"
         ,"FFAACT"."FFAACT-EmpIDSetup-NumLtr-Opt"
         ,"FFAACT"."FFAACT-EmpIDSetup-Length-Min"
         ,"HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HAAPRO"."HAAPRO-MAIDEN-NAME"
         ,"HAAPRO"."HAAPRO-OTHER-ID"
         ,"HAAPRO"."HAAPRO-US-CITIZEN-X"
         ,"HDTMPTBL"."HAADEG-CODE"
         ,"HDTMPTBL"."HAADEG-STATE-CODE"
         ,"N"."ADDRESS-ID"
         ,"N"."ALTERNATE-ID"
         ,"N"."BIRTHDATE"
         ,"N"."ETHNICITY-HISP-X"
         ,"N"."FEDERAL-ID-NO"
         ,"N"."FED-RACE-FLAGS"
         ,"N"."FIRST-NAME"
         ,"N"."GENDER"
         ,"N"."INTERNET-ADDRESS"
         ,"N"."LANGUAGE-CODE"
         ,"N"."LAST-NAME"
         ,"N"."MIDDLE-NAME"
         ,"N"."NALPHAKEY"
         ,"N"."NAME-ID"
         ,"N"."NAME-SUFFIX-ID"
         ,"N"."PRIMARY-PHONE"
         ,"ND"."DUSER-ID"
         ,"R"."RACE-CODE"
         ,"S"."SALUTATION-SDESC"
         ,"Z"."ZIP-CITY"
         ,"Z"."ZIP-STATE"
         ,("HAAPRO"."HAAPRO-YRS-EXP1" +              -- Teach In District
           "HAAPRO"."HAAPRO-YRS-EXP2" +              -- Teach FL Public
           "HAAPRO"."HAAPRO-YRS-EXP3" +              -- Teach FL Non-Public
           "HAAPRO"."HAAPRO-YRS-EXP4" +              -- Teach Other Public
           "HAAPRO"."HAAPRO-YRS-EXP5") AS \'YPTE\'   -- Teach Other Non-Public 
         ,("HAAPRO"."HAAPRO-YRS-EXP6" +              -- Administrative Experience
           "HAAPRO"."HAAPRO-YRS-EXP7") AS \'YPPE\'   -- Military Service
        
        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        INNER JOIN "SKYWARD"."PUB"."NAME" "N" ON 
         "HAAPRO"."NAME-ID" = "N"."NAME-ID"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
         "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        LEFT OUTER JOIN "SKYWARD"."PUB"."NAME-DUSER" "ND" ON
         "N"."NAME-ID" = "ND"."NAME-ID"
        LEFT OUTER JOIN "SKYWARD"."PUB"."ADDRESS" "A" ON
         "N"."ADDRESS-ID" = "A"."ADDRESS-ID"
        LEFT OUTER JOIN "SKYWARD"."PUB"."COUNTY" "AC" ON 
         "A"."COUNTY-ID" = "AC"."COUNTY-ID" AND 
         "AC"."LIVE" = 1
        LEFT OUTER JOIN "SKYWARD"."PUB"."ZIP" "Z" ON 
         "A"."ZIP-CODE" = "Z"."ZIP-CODE" AND
         "Z"."LIVE" = 1
        LEFT OUTER JOIN "SKYWARD"."PUB"."RACE" "R" ON
         "N"."RACE-CODE" = "R"."RACE-CODE" AND
         "R"."LIVE" = 1
        LEFT JOIN (
            SELECT DISTINCT
              "HD"."NAME-ID"
             ,"HDC"."HAADEG-CODE"
             ,"HDC"."HAADEG-STATE-CODE"
            
            FROM "SKYWARD"."PUB"."HPMPGD-DEGREES" "HD"
            LEFT JOIN "SKYWARD"."PUB"."HAADEG-DEGREE-CODES" "HDC" ON 
             "HD"."HAADEG-CODE" = "HDC"."HAADEG-CODE"
            
            WHERE
             "HD"."HPMPGD-HIGHEST-DEG-X" = 1
        ) "HDTMPTBL" ON 
         "N"."NAME-ID" = "HDTMPTBL"."NAME-ID"
        LEFT JOIN "SKYWARD"."PUB"."SALUTATION" "S" ON
         "N"."SALUTATION-ID" = "S"."SALUTATION-ID" AND
         "S"."LIVE" = 1
        CROSS JOIN "SKYWARD"."PUB"."FFAACT-CONTROL-FILE" "FFAACT" 
    ';

    public static $shared_StaffEducationOrganizationEmploymentAssociation = '
        SELECT
          "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HAAPRO"."HAAPRO-ACTIVE"
         ,"HAAPRO"."HAAPRO-HIRE-DTE"
         ,"HAAPRO"."HAAPRO-OTHER-ID"
         ,"HAAPRO"."HAAPRO-TERM-DTE"
         ,"HAAPRO"."HPETER-TERM-CODE"
         ,"HAAPRO"."NAME-ID"
         ,"HPETER"."HPETER-STATE-CODE"
        
        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        LEFT JOIN "SKYWARD"."PUB"."HPETER-TERM-CODES" "HPETER" ON 
         "HAAPRO"."HPETER-TERM-CODE" = "HPETER"."HPETER-TERM-CODE"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
        "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        
        WHERE
         "HAAPRO"."HAAPRO-HIRE-DTE" IS NOT NULL AND 
         "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%)
    ';

    public static $shared_StaffEducationOrganizationAssignmentAssociation = '
        SELECT
          "HAAPRO"."HAAPRO-OTHER-ID"
         ,"HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HPMASN"."HAADSC-DESC-POS"
         ,"HPMASN"."HAAPLC-CODE"
         ,"HPMASN"."HAAPLC-DESC"
         ,"HPMASN"."HPMASN-END-DATE"
         ,"HPMASN"."HPMASN-START-DATE"
         ,"HPMASN"."NAME-ID"
         ,"SC"."CODE-ID" AS \'JOBCODE\'
         ,"SC"."INT-1" AS \'EEONUM\'
        
        FROM "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
         "HPMASN"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        INNER JOIN "SKYWARD"."PUB"."HPMPLN-PLAN" "HPMPLN" ON
         "HPMASN"."HPMPLN-ID" = "HPMPLN"."HPMPLN-ID"
        INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
         "HPMASN"."NAME-ID" = "HAAPRO"."NAME-ID"
        INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC" ON
         "HPMASN"."HAADSC-ID-ASN" = "HAADSC"."HAADSC-ID"
        INNER JOIN "SKYWARD"."PUB"."SYS-CTD" "SC" ON 
         "SC"."TABLE-ID" = \'HR-FL-ACCT-ST-RPT-FLD\' AND 
         "HAADSC"."HAADSC-CODE" = "SC"."CODE-ID"
        
        WHERE
         "HPMASN"."HPMASN-FIS-YEAR" = %%currentsy%% AND
         "HPMPLN"."HPMPLN-SN-PLAN-X" = 0 AND
         "HAADSC"."HAADSC-CODE" != \'\' AND
         "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%)
    ';

    public static $shared_StaffSchoolAssociation = 'SELECT
                                                        HP."NAME-ID",
                                                        HA."HPMASN-ID",
                                                        HA."HAABLD-BLD-CODE",
                                                        HA."HPMASN-FIS-YEAR",
                                                        HA."HAADSC-DESC-ASN",
                                                        HA."HAADSC-DESC-POS"
                                                    FROM
                                                        pub."HPMASN-ASSIGNMENTS" AS HA
                                                        INNER JOIN pub."HAAPRO-PROFILE" AS HP
                                                            ON HP."NAME-ID" = HA."NAME-ID"
                                                        INNER JOIN pub."HAADSC-DESCS" AS HD
                                                            ON HD."HAADSC-ID" = HA."HAADSC-ID-ASN"
                                                        INNER JOIN pub."SYS-CTD" AS SC
                                                            ON SC."TABLE-ID" = \'HR-FL-ACCT-ST-RPT-FLD\'
                                                            AND SC."CODE-ID" = HD."HAADSC-CODE"
                                                    WHERE
                                                        --HP."HAAPRO-ACTIVE" = 1
                                                        HA."HPMASN-FIS-YEAR" = %%currentsy%%
                                                        AND (
                                                                SC."INT-1" >= 21
                                                                AND SC."INT-1" <= 33
                                                            )
                                                      --AND HP."NAME-ID" IN(%%nameIDs%%)';

    public static $shared_LeaveEvent = '
        SELECT
          "HAAPRO"."HAAPRO-OTHER-ID"
         ,"HTODRS"."HTODRS-ABSENCE-TYPE"
         ,"HTODRS"."HTODRS-DESC"
         ,"HTOTRN"."HTOTRN-HRS"
         ,"HTOTRN"."HTOTRN-SUB-NAME-ID"
         ,"HTOTRN"."HTOTRN-TRANS-DATE"
         ,"HTOTRN"."NAME-ID"
        
        FROM "SKYWARD"."PUB"."HTOTRN-TRANS" "HTOTRN"
        INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
         "HTOTRN"."NAME-ID" = "HAAPRO"."NAME-ID"
        INNER JOIN "SKYWARD"."PUB"."HTODRS-REASON-CODES" "HTODRS" ON 
         "HTODRS"."HTODRS-REASON-CODE" = "HTOTRN"."HTOTRN-REASON-CODE"
        
        WHERE
         "HTOTRN"."HTOTRN-TRANS-DATE" >= (
             SELECT MIN("HPMASN"."HPMASN-START-DATE") AS \'MINSTARTDATE\'
             FROM "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN"
             WHERE 
              "HPMASN"."HPMASN-FIS-YEAR" = %%currentsy%% AND 
              "HPMASN"."NAME-ID" = "HTOTRN"."NAME-ID"
         ) AND 
         "HTOTRN"."HTOTRN-TRANS-DATE" <= \'%%enddate%%\' AND 
         "HTOTRN"."HTOTRN-TYPE" IN (\'L\',\'U\')
    ';

    /*public static $shared_OpenStaffPosition = 'SELECT
                                                    HJL."HPMPOS-ID",
                                                    HJL."HAPJBL-JOB-LISTING-ID",
                                                    HJL."HAPJBL-POST-INT-BEGIN-DATE",
                                                    HJL."HAPJBL-CLOSE-DATE",
                                                    HJL."HAABLD-BLD-CODE",
                                                    HJL."HAPJBL-ASN-DESC",
                                                    HJL."HAPJBL-FULL-TIME-IND",
                                                    HD."HAADSC-CODE"
                                                FROM
                                                    pub."HAPJBL-JOB-LISTING" AS HJL
                                                    INNER JOIN pub."HAADSC-DESCS" AS HD
                                                        ON HD."HAADSC-ID" = HJL."HAADSC-ID-ASN"
                                                WHERE
                                                    HJL."HAPJBL-STATUS" = \'O\'
                                                    AND HD."HAADSC-CODE" != \'\'
                                                    AND HJL."HAABLD-BLD-CODE" IN (%%entities%%)';*/

    public static $shared_OpenStaffPosition = '
        SELECT
          "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HJL"."HAADSC-ID-ASN"
         ,"HJL"."HAPJBL-ASN-DESC"
         ,"HJL"."HAPJBL-CLOSE-DATE"
         ,"HJL"."HAPJBL-FULL-TIME-IND"
         ,"HJL"."HAPJBL-JOB-LISTING-ID"
         ,"HJL"."HAPJBL-POST-INT-BEGIN-DATE"
         ,"HJL"."HAPJBL-STATUS"
         ,"HJL"."HPMPOS-ID"
         ,(CASE WHEN LTRIM(RTRIM("HD"."HAADSC-CODE")) = \'\' THEN \'99999\' ELSE "HD"."HAADSC-CODE" END) AS \'HAADSC-CODE\'
        
        FROM "SKYWARD"."PUB"."HAPJBL-JOB-LISTING" "HJL"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
         "HJL"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        LEFT OUTER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HD" ON 
         "HJL"."HAADSC-ID-ASN" = "HD"."HAADSC-ID"
        
        WHERE
         "HJL"."HAPJBL-STATUS" IN (\'C\',\'O\') AND
         "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%)
    ';

    public static $shared_StaffSectionAssociation = 'SELECT
                                                            CM."NAME-ID",
                                                            CM."DSP-PERIOD",
                                                            CM."ENTITY-ID",
                                                            CM."BUILDING-ID",
                                                            CM."ROOM-NUMBER",
                                                            CM."HIGHLY-QUALIFIED",
                                                            CM."TCHR-PRIME-FLAG",
                                                            CM."SCHOOL-YEAR",
                                                            C."AIDE-PERCENTAGE",
                                                            C."COR-ALPHAKEY",
                                                            C."CONTROL-SET-ID",
                                                            TDSTART."SEM-TRM-STR-DATE" AS TDSTART,
                                                            TDSTOP."SEM-TRM-STP-DATE" AS TDSTOP
                                                        FROM
                                                            pub."CLASS-MEET" AS CM
                                                            INNER JOIN pub."CLASS" AS C
                                                                ON C."CLAS-SECTION" = CM."CLAS-SECTION"
                                                                    AND C."COR-NUM-ID" = CM."COR-NUM-ID"
                                                            LEFT JOIN pub."TERM-DEFINITION" AS TDSTART
                                                                ON TDSTART."ENTITY-ID" = C."ENTITY-ID"
                                                                    AND TDSTART."SCHOOL-YEAR" = CM."SCHOOL-YEAR"
                                                                    AND TDSTART."SCHD-TRM-STR" = CM."SCH-STR-TRM"
                                                                    AND TDSTART."SCHD-TRM-STP" = CM."SCH-STR-TRM"
                                                            LEFT JOIN pub."TERM-DEFINITION" AS TDSTOP
                                                                ON TDSTOP."ENTITY-ID" = C."ENTITY-ID"
                                                                    AND TDSTOP."SCHOOL-YEAR" = CM."SCHOOL-YEAR"
                                                                    AND TDSTOP."SCHD-TRM-STR" = CM."SCH-STP-TRM"
                                                                    AND TDSTOP."SCHD-TRM-STP" = CM."SCH-STP-TRM"
                                                        WHERE
                                                            CM."SCHOOL-YEAR" = %%currentsy%%
                                                            AND C."CLAS-STATUS" = \'A\'
                                                            AND CM."ROOM-NUMBER" != \'\'
                                                          --AND CM."NAME-ID" IN(%%nameIDs%%)';

    public static $shared_pullUserAccountComparisonRecordsFromStudentDB = 'SELECT
                                                                                N."FEDERAL-ID-NO",
                                                                                N."NAME-ID",
                                                                                N."INTERNET-ADDRESS"
                                                                            FROM
                                                                                pub."staff" AS S
                                                                                INNER JOIN pub."name" AS N
                                                                                    ON N."NAME-ID" = S."NAME-ID"
                                                                            WHERE
                                                                                (
                                                                                    SELECT
                                                                                        COUNT(*)
                                                                                    FROM
                                                                                        pub."STAFF-ENTITY" AS SE
                                                                                    WHERE
                                                                                        SE."NAME-ID" = S."NAME-ID"
                                                                                        AND SE."STATUS-CUR-YR" = \'A\'
                                                                                ) > 0';

    public static $shared_getCredentials = 'SELECT
                                                    C."HPMCEM-ISSUE-DATE",
                                                    C."HPMCEM-EXP-DATE",
                                                    C."HPMCEM-STATE",
                                                    C."HPMCEM-CERT-NBR",
                                                    D0."HAADSC-CODE",
                                                    D0."HAADSC-DESC",
                                                    C."HPMCEM-ID"
                                                FROM
                                                    pub."HPMCEM-CERT-MST" AS C
                                                    INNER JOIN pub."HAADSC-DESCS" AS D0
                                                        ON D0."HAADSC-ID" = C."HAADSC-ID-CERT-TYPE"
                                                WHERE
                                                    C."NAME-ID" = %%nameid%%
                                                    AND C."HPMCEM-EXP-DATE" >= NOW()
                                                    AND C."HPMCEM-ISSUE-DATE" IS NOT NULL
                                                    AND C."HPMCEM-CURRENT-X" = 1';

    public static $shared_getAllCredentials = '
        SELECT
          "HPMCEM"."HPMCEM-CERT-NBR"
         ,"HPMCEM"."HPMCEM-EXP-DATE"
         ,"HPMCEM"."HPMCEM-ID"
         ,"HPMCEM"."HAADSC-ID-CERT-TYPE"
         ,"HPMCEM"."HPMCEM-ISSUE-DATE"
         ,"HPMCEM"."HPMCEM-STATE"
         ,"HAADSC"."HAADSC-CODE"
         ,"HAADSC"."HAADSC-DESC"
         
        FROM "SKYWARD"."PUB"."HPMCEM-CERT-MST" "HPMCEM"
        INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC" ON 
         "HPMCEM"."HAADSC-ID-CERT-TYPE" = "HAADSC"."HAADSC-ID"
        
        WHERE
         "HPMCEM"."NAME-ID" = %%nameid%% AND
         "HPMCEM"."HPMCEM-EXP-DATE" >= NOW() 
       --AND "HPMCEM"."HPMCEM-ISSUE-DATE" IS NOT NULL
    ';

    public static $shared_getCredentialCertAreasAndGradeLevels = 'SELECT TOP 1
                                                                        GCERT1."HAADSC-CODE" AS GCERT1CODE,
                                                                        GCERT1."HAADSC-DESC" AS GCERT1DESC,
                                                                        GCERT2."HAADSC-CODE" AS GCERT2CODE,
                                                                        GCERT2."HAADSC-DESC" AS GCERT2DESC,
                                                                        CD."HPMCED-HIGHLY-QUALIFIED-X"
                                                                    FROM
                                                                        pub."HPMCED-CERT-DTL" AS CD
                                                                        LEFT JOIN pub."HAADSC-DESCS" AS GCERT1
                                                                            ON GCERT1."HAADSC-ID" = CD."HAADSC-ID-CERT1"
                                                                        LEFT JOIN pub."HAADSC-DESCS" AS GCERT2
                                                                            ON GCERT2."HAADSC-ID" = CD."HAADSC-ID-CERT2"
                                                                    WHERE
                                                                        CD."HPMCEM-ID" = %%credid%%';

    public static $shared_getGradeRangeBreakdowns = 'SELECT
                                                            HAB."HAADSC-DESC-ASN",
                                                            HAB."HAADSC-ID-GRADE-TO",
                                                            HAB."HAADSC-ID-GRADE-FROM"
                                                        FROM
                                                            pub."HPMBRK-ASN-BRKDWN" AS HAB
                                                        WHERE
                                                            HAB."HPMASN-ID" = %%asnid%%';


    /* ==============================================
     * ===   Staff Export Queries (Active Only)   ===
     * ============================================== */


    public static $shared_LeaveEventActive = '
        SELECT
          "HAAPRO"."HAAPRO-OTHER-ID"
         ,"HTODRS"."HTODRS-ABSENCE-TYPE"
         ,"HTODRS"."HTODRS-DESC"
         ,"HTOTRN"."HTOTRN-HRS"
         ,"HTOTRN"."HTOTRN-SUB-NAME-ID"
         ,"HTOTRN"."HTOTRN-TRANS-DATE"
         ,"HTOTRN"."NAME-ID"
        
        FROM "SKYWARD"."PUB"."HTOTRN-TRANS" "HTOTRN"
        INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
         "HTOTRN"."NAME-ID" = "HAAPRO"."NAME-ID"
        INNER JOIN "SKYWARD"."PUB"."HTODRS-REASON-CODES" "HTODRS" ON 
         "HTODRS"."HTODRS-REASON-CODE" = "HTOTRN"."HTOTRN-REASON-CODE"
        
        WHERE
         "HTOTRN"."HTOTRN-TRANS-DATE" >= (
             SELECT MIN("HPMASN"."HPMASN-START-DATE") AS \'MINSTARTDATE\'
             FROM "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN"
             WHERE 
              "HPMASN"."HPMASN-FIS-YEAR" = %%currentsy%% AND 
              "HPMASN"."NAME-ID" = "HTOTRN"."NAME-ID"
         ) AND 
         "HTOTRN"."HTOTRN-TRANS-DATE" <= \'%%enddate%%\' AND 
         "HTOTRN"."HTOTRN-TYPE" IN (\'L\',\'U\') AND
         "HAAPRO"."HAAPRO-ACTIVE" = 1
    ';

    public static $shared_StaffActive = '
        SELECT
          "A"."ADDRESS2"
         ,"A"."LOC-HOR" AS \'LATITUDE\'
         ,"A"."LOC-VER" AS \'LONGITUDE\'
         ,"A"."STREET-APPT"
         ,"A"."STREET-DIR"
         ,"A"."STREET-NAME"
         ,"A"."STREET-NUMBER"
         ,"A"."ZIP-CODE"
         ,"AC"."COUNTY-LDESC"
         ,"FFAACT"."FFAACT-EmpIDSetup-NumLtr-Opt"
         ,"FFAACT"."FFAACT-EmpIDSetup-Length-Min"
         ,"HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HAAPRO"."HAAPRO-MAIDEN-NAME"
         ,"HAAPRO"."HAAPRO-OTHER-ID"
         ,"HAAPRO"."HAAPRO-US-CITIZEN-X"
         ,"HDTMPTBL"."HAADEG-CODE"
         ,"HDTMPTBL"."HAADEG-STATE-CODE"
         ,"N"."ADDRESS-ID"
         ,"N"."ALTERNATE-ID"
         ,"N"."BIRTHDATE"
         ,"N"."ETHNICITY-HISP-X"
         ,"N"."FEDERAL-ID-NO"
         ,"N"."FED-RACE-FLAGS"
         ,"N"."FIRST-NAME"
         ,"N"."GENDER"
         ,"N"."INTERNET-ADDRESS"
         ,"N"."LANGUAGE-CODE"
         ,"N"."LAST-NAME"
         ,"N"."MIDDLE-NAME"
         ,"N"."NALPHAKEY"
         ,"N"."NAME-ID"
         ,"N"."NAME-SUFFIX-ID"
         ,"N"."PRIMARY-PHONE"
         ,"ND"."DUSER-ID"
         ,"R"."RACE-CODE"
         ,"S"."SALUTATION-SDESC"
         ,"Z"."ZIP-CITY"
         ,"Z"."ZIP-STATE"
         ,("HAAPRO"."HAAPRO-YRS-EXP1" +              -- Teach In District
           "HAAPRO"."HAAPRO-YRS-EXP2" +              -- Teach FL Public
           "HAAPRO"."HAAPRO-YRS-EXP3" +              -- Teach FL Non-Public
           "HAAPRO"."HAAPRO-YRS-EXP4" +              -- Teach Other Public
           "HAAPRO"."HAAPRO-YRS-EXP5") AS \'YPTE\'   -- Teach Other Non-Public 
         ,("HAAPRO"."HAAPRO-YRS-EXP6" +              -- Administrative Experience
           "HAAPRO"."HAAPRO-YRS-EXP7") AS \'YPPE\'   -- Military Service
        
        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        INNER JOIN "SKYWARD"."PUB"."NAME" "N" ON 
         "HAAPRO"."NAME-ID" = "N"."NAME-ID"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
         "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        LEFT OUTER JOIN "SKYWARD"."PUB"."NAME-DUSER" "ND" ON
         "N"."NAME-ID" = "ND"."NAME-ID"
        LEFT OUTER JOIN "SKYWARD"."PUB"."ADDRESS" "A" ON
         "N"."ADDRESS-ID" = "A"."ADDRESS-ID"
        LEFT OUTER JOIN "SKYWARD"."PUB"."COUNTY" "AC" ON 
         "A"."COUNTY-ID" = "AC"."COUNTY-ID" AND 
         "AC"."LIVE" = 1
        LEFT OUTER JOIN "SKYWARD"."PUB"."ZIP" "Z" ON 
         "A"."ZIP-CODE" = "Z"."ZIP-CODE" AND
         "Z"."LIVE" = 1
        LEFT OUTER JOIN "SKYWARD"."PUB"."RACE" "R" ON
         "N"."RACE-CODE" = "R"."RACE-CODE" AND
         "R"."LIVE" = 1
        LEFT JOIN (
            SELECT DISTINCT
              "HD"."NAME-ID"
             ,"HDC"."HAADEG-CODE"
             ,"HDC"."HAADEG-STATE-CODE"
            
            FROM "SKYWARD"."PUB"."HPMPGD-DEGREES" "HD"
            LEFT JOIN "SKYWARD"."PUB"."HAADEG-DEGREE-CODES" "HDC" ON 
             "HD"."HAADEG-CODE" = "HDC"."HAADEG-CODE"
            
            WHERE
             "HD"."HPMPGD-HIGHEST-DEG-X" = 1
        ) "HDTMPTBL" ON 
         "N"."NAME-ID" = "HDTMPTBL"."NAME-ID"
        LEFT JOIN "SKYWARD"."PUB"."SALUTATION" "S" ON
         "N"."SALUTATION-ID" = "S"."SALUTATION-ID" AND
         "S"."LIVE" = 1
        CROSS JOIN "SKYWARD"."PUB"."FFAACT-CONTROL-FILE" "FFAACT"
        
        WHERE
         "HAAPRO"."HAAPRO-ACTIVE" = 1
    ';

    public static $shared_StaffEducationOrganizationAssignmentAssociationActive = '
        SELECT
          "HAAPRO"."HAAPRO-OTHER-ID"
         ,"HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HPMASN"."HAADSC-DESC-POS"
         ,"HPMASN"."HAAPLC-CODE"
         ,"HPMASN"."HAAPLC-DESC"
         ,"HPMASN"."HPMASN-END-DATE"
         ,"HPMASN"."HPMASN-START-DATE"
         ,"HPMASN"."NAME-ID"
         ,"SC"."CODE-ID" AS \'JOBCODE\'
         ,"SC"."INT-1" AS \'EEONUM\'
        
        FROM "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
         "HPMASN"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        INNER JOIN "SKYWARD"."PUB"."HPMPLN-PLAN" "HPMPLN" ON
         "HPMASN"."HPMPLN-ID" = "HPMPLN"."HPMPLN-ID"
        INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
         "HPMASN"."NAME-ID" = "HAAPRO"."NAME-ID"
        INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC" ON
         "HPMASN"."HAADSC-ID-ASN" = "HAADSC"."HAADSC-ID"
        INNER JOIN "SKYWARD"."PUB"."SYS-CTD" "SC" ON 
         "SC"."TABLE-ID" = \'HR-FL-ACCT-ST-RPT-FLD\' AND 
         "HAADSC"."HAADSC-CODE" = "SC"."CODE-ID"
        
        WHERE
         "HPMASN"."HPMASN-FIS-YEAR" = %%currentsy%% AND
         "HPMPLN"."HPMPLN-SN-PLAN-X" = 0 AND
         "HAADSC"."HAADSC-CODE" != \'\' AND
         "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%) AND
         "HAAPRO"."HAAPRO-ACTIVE" = 1
    ';

    public static $shared_StaffEducationOrganizationEmploymentAssociationActive = '
        SELECT
          "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HAAPRO"."HAAPRO-ACTIVE"
         ,"HAAPRO"."HAAPRO-HIRE-DTE"
         ,"HAAPRO"."HAAPRO-OTHER-ID"
         ,"HAAPRO"."HAAPRO-TERM-DTE"
         ,"HAAPRO"."HPETER-TERM-CODE"
         ,"HAAPRO"."NAME-ID"
         ,"HPETER"."HPETER-STATE-CODE"
        
        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        LEFT JOIN "SKYWARD"."PUB"."HPETER-TERM-CODES" "HPETER" ON 
         "HAAPRO"."HPETER-TERM-CODE" = "HPETER"."HPETER-TERM-CODE"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
        "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        
        WHERE
         "HAAPRO"."HAAPRO-HIRE-DTE" IS NOT NULL AND 
         "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%) AND
         "HAAPRO"."HAAPRO-ACTIVE" = 1
    ';

    public static $shared_StaffSchoolAssociationActive = '';

    public static $shared_StaffSectionAssociationActive = '';



    /* ==================================================
     * ===   Additional Methods of Pulling Job Code   ===
     * ================================================== */
    public static $shared_GetEmployeesWithoutAssignments = '
        SELECT
          "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HAAPRO"."HAAETY-EMP-TYPE-CODE"
         ,"HAAPRO"."HAAPRO-OTHER-ID"
         ,"HAAPRO"."NAME-ID"
         ,"HPAPRM"."HPADCP-PAY-CODE"
         ,"HPAPRM"."HPAPRM-START-DATE"
         ,"HPAPRM"."HPAPRM-STOP-DATE"
        
        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
         "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        LEFT OUTER JOIN "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN" ON
         "HAAPRO"."NAME-ID" = "HPMASN"."NAME-ID" AND
         "HPMASN"."HPMASN-FIS-YEAR" = %%currentsy%%
        LEFT OUTER JOIN (
            SELECT 
              "HPAPRM"."NAME-ID"
             ,"HPAPRM"."HPADCP-PAY-CODE"
             ,"HPAPRM"."HPAPRM-START-DATE"
             ,"HPAPRM"."HPAPRM-STOP-DATE"
             
            FROM "SKYWARD"."PUB"."HPAPRM-PAY-REC-MASTER" "HPAPRM"
            WHERE "HPAPRM"."HPAPRM-PRIMARY-X" = 1
        ) "HPAPRM" ON
         "HAAPRO"."NAME-ID" = "HPAPRM"."NAME-ID"
        
        WHERE
         "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%) AND
         "HPMASN"."NAME-ID" IS NULL
    ';

    public static $shared_GetActiveEmployeesWithoutAssignments = '
        SELECT
          "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
         ,"HAAPRO"."HAAETY-EMP-TYPE-CODE"
         ,"HAAPRO"."HAAPRO-OTHER-ID"
         ,"HAAPRO"."NAME-ID"
         ,"HPAPRM"."HPADCP-PAY-CODE"
         ,"HPAPRM"."HPAPRM-START-DATE"
         ,"HPAPRM"."HPAPRM-STOP-DATE"
        
        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
         "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        LEFT OUTER JOIN "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN" ON
         "HAAPRO"."NAME-ID" = "HPMASN"."NAME-ID" AND
         "HPMASN"."HPMASN-FIS-YEAR" = %%currentsy%%
        LEFT OUTER JOIN (
            SELECT 
              "HPAPRM"."NAME-ID"
             ,"HPAPRM"."HPADCP-PAY-CODE"
             ,"HPAPRM"."HPAPRM-START-DATE"
             ,"HPAPRM"."HPAPRM-STOP-DATE"
             
            FROM "SKYWARD"."PUB"."HPAPRM-PAY-REC-MASTER" "HPAPRM"
            WHERE "HPAPRM"."HPAPRM-PRIMARY-X" = 1
        ) "HPAPRM" ON
         "HAAPRO"."NAME-ID" = "HPAPRM"."NAME-ID"
        
        WHERE
         "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%) AND
         "HAAPRO"."HAAPRO-ACTIVE" = 1 AND
         "HPMASN"."NAME-ID" IS NULL
    ';

    public static $shared_GetPayRecsUseAcctDistJobCode = '
        SELECT "FFAACT"."FFAACT-AcctStRptFld-X" 
        FROM "SKYWARD"."PUB"."FFAACT-CONTROL-FILE" "FFAACT"
    ';

    public static $shared_GetJobCodeByAccountDistribution = '
        SELECT
          "HPACFP"."HPACFP-START-DATE"
         ,"HPACFP"."HPACFP-STOP-DATE"
         ,"HPAPRA"."HPAPRA-DEPT"
         ,"HPAPRM"."HPAPRM-START-DATE"
         ,"HPAPRM"."HPAPRM-STOP-DATE"
         ,SUM("HPAPRA"."HPAPRA-PERCENT") AS \'SUM-HPAPRA-PERCENT\'
         ,"HPAPRM"."NAME-ID"
        
        FROM "SKYWARD"."PUB"."HPAPRM-PAY-REC-MASTER" "HPAPRM"
        LEFT OUTER JOIN "SKYWARD"."PUB"."HPACFP-PAY-CTRL-FILE" "HPACFP" ON
         "HPAPRM"."NAME-ID" = "HPACFP"."NAME-ID" AND
         "HPAPRM"."HPADCP-PAY-CODE" = "HPACFP"."HPADCP-PAY-CODE" 
        INNER JOIN "SKYWARD"."PUB"."HPAPRA-PAY-REC-ACCT-DISTRIB" "HPAPRA" ON
         "HPAPRM"."HPAPRM-PAY-REC-ID" = "HPAPRA"."HPAPRM-PAY-REC-ID"
        
        WHERE
         "HPAPRM"."HPAPRM-ACTIVE" = 1 AND
         "HPAPRM"."HPAPRM-PRIMARY-X" = 1 AND
         ("HPAPRA"."HPAPRA-DEPT" IS NOT NULL AND LTRIM(RTRIM("HPAPRA"."HPAPRA-DEPT")) <> \'\') AND
         "HPAPRM"."NAME-ID" = %%nameId%%
         
        GROUP BY
          "HPAPRM"."NAME-ID"
         ,"HPAPRM"."HPAPRM-START-DATE"
         ,"HPAPRM"."HPAPRM-STOP-DATE"
         ,"HPACFP"."HPACFP-START-DATE"
         ,"HPACFP"."HPACFP-STOP-DATE"
         ,"HPAPRA"."HPAPRA-DEPT"
    ';

    public static $shared_GetJobCodeByCheckHistory = '
        SELECT DISTINCT
          "HPAHDC"."NAME-ID"
         ,"HPAHDC"."HPAHDC-CHK-DTE"
         ,"HPAHDP"."HPAHDP-DEPT"
         ,"HPAPRM"."HPAPRM-START-DATE"
         ,"HPAPRM"."HPAPRM-STOP-DATE"
        
        FROM "SKYWARD"."PUB"."HPAHDC-HIST-CHK" "HPAHDC"
        INNER JOIN (
            SELECT
               "HPAHDC"."NAME-ID"
              ,MAX("HPAHDC"."HPAHDC-CHK-DTE") AS \'MAX-HPAHDC-CHK-DTE\'
            
            FROM "SKYWARD"."PUB"."HPAHDC-HIST-CHK" "HPAHDC"
            GROUP BY "HPAHDC"."NAME-ID"
        ) "HPAHDCX" ON
         "HPAHDC"."NAME-ID" = "HPAHDCX"."NAME-ID" AND
         "HPAHDC"."HPAHDC-CHK-DTE" = "HPAHDCX"."MAX-HPAHDC-CHK-DTE"
        INNER JOIN "SKYWARD"."PUB"."HPAHDP-HIST-PAY" "HPAHDP" ON
         "HPAHDC"."NAME-ID" = "HPAHDP"."NAME-ID" AND
         "HPAHDC"."HPAHDM-ID" = "HPAHDP"."HPAHDM-ID"
        LEFT OUTER JOIN "SKYWARD"."PUB"."HPAPRM-PAY-REC-MASTER" "HPAPRM" ON
         "HPAHDP"."NAME-ID" = "HPAPRM"."NAME-ID" AND
         "HPAHDP"."HPADCP-PAY-CODE" = "HPAPRM"."HPADCP-PAY-CODE"
        
        WHERE
         ("HPAHDP"."HPAHDP-DEPT" IS NOT NULL AND LTRIM(RTRIM("HPAHDP"."HPAHDP-DEPT")) <> \'\') AND
         "HPAHDC"."NAME-ID" = %%nameId%%
    ';

    public static $shared_GetJobCodeByStateReportingParameterSet = '
        SELECT
          "QGTREC"."QGTREC-SRC-ID" AS \'PARAM-SET-YEAR\'
         ,"QGTREC"."QGTREC-SRC-CODE" AS \'PARAM-SET-SUBM\'
         ,"QGTREC"."QGTREC-CHR"[1] AS \'PARAM-SET-HAAETY\'
         ,"QGTREC"."QGTREC-CHR"[2] AS \'PARAM-SET-HPADCP\'
         ,"QRBGRT"."QRBGRT-SORT4" AS \'X-REF-METHOD\'
         ,"QRBGRT"."QRBGRT-ALPHA"[1] AS \'X-REF-HPADCP\'
         ,"QRBGRT"."QRBGRT-ALPHA"[2] AS \'X-REF-HAADSC\'
        
        FROM "SKYWARD"."PUB"."QGTREC-GENERIC-TABLE" "QGTREC"
        INNER JOIN "SKYWARD"."PUB"."QRBGRT" ON
      -- "QRBGRT"."QRBGRT-ID" = 20 AND
         "QRBGRT"."QRBGRT-SORT1" = \'SR-FL-DOE-STAFF-JC-CROSS-REF\' AND
         "QGTREC"."QGTREC-SRC-ID" = "QRBGRT"."QRBGRT-SORT2" AND
         "QGTREC"."QGTREC-SRC-CODE" = "QRBGRT"."QRBGRT-SORT3" AND
         "QRBGRT"."QRBGRT-SORT4" IN (\'F\',\'P\') AND
         LTRIM(RTRIM("QGTREC"."QGTREC-SORT1")) = "QRBGRT"."QRBGRT-SORT5"
        
        WHERE
         "QGTREC"."QGTREC-TABLE-NAME" = \'SR-FL-DOE-STAFF-PAYROLL-PARAM-SET\' AND
         "QGTREC"."QGTREC-SRC-ID" = \'%%schoolYearXXYY%%\' AND
         "QGTREC"."QGTREC-SRC-CODE" IN (\'2\',\'3\',\'5\')
        
        ORDER BY 
          "QGTREC"."QGTREC-SRC-ID"
         ,"QGTREC"."QGTREC-SRC-CODE" DESC
         ,"QRBGRT"."QRBGRT-ALPHA"[1]
    ';










    /* ==============================
     * ===   Deprecated Queries   ===
     * ============================== */

/*   public static $shared_Staff = 'SELECT
                                        N."NAME-ID",
                                        N."NALPHAKEY",
                                        S."SALUTATION-SDESC",
                                        N."FIRST-NAME",
                                        N."MIDDLE-NAME",
                                        N."LAST-NAME",
                                        N."NAME-SUFFIX-ID",
                                        HP."HAAPRO-MAIDEN-NAME",
                                        N."GENDER",
                                        N."BIRTHDATE",
                                        A."STREET-NUMBER",
                                        A."STREET-DIR",
                                        A."STREET-NAME",
                                        A."STREET-APPT",
                                        A."ADDRESS2",
                                        A."ZIP-CODE",
                                        Z."ZIP-CITY",
                                        AC."COUNTY-LDESC",
                                        Z."ZIP-STATE",
                                        A."LOC-HOR" AS latitude,
                                        A."LOC-VER" AS longitude,
                                        N."PRIMARY-PHONE",
                                        N."INTERNET-ADDRESS",
                                        N."ETHNICITY-HISP-X",
                                        R."RACE-CODE",
                                        HP."HAAPRO-US-CITIZEN-X",
                                        N."LANGUAGE-CODE",
                                        HDTMPTBL."HAADEG-STATE-CODE",
                                        N."FEDERAL-ID-NO",
                                        N."ALTERNATE-ID",
                                        HP."HAAPRO-OTHER-ID",
                                        --ND."DUSER-ID",
                                        (HP."HAAPRO-YRS-EXP2" + HP."HAAPRO-YRS-EXP3" + HP."HAAPRO-YRS-EXP4" + HP."HAAPRO-YRS-EXP5") AS YPTE,
                                        (HP."HAAPRO-YRS-EXP8" + HP."HAAPRO-YRS-EXP9") AS YPPE

                                    FROM
                                        pub."HAAPRO-PROFILE" AS HP
                                        INNER JOIN pub."NAME" AS N
                                            ON N."NAME-ID" = HP."NAME-ID"
                                        INNER JOIN pub."ADDRESS" AS A --TODO: MAKE LEFT OUTER JOIN
                                            ON A."address-id" = N."address-id"
                                        LEFT JOIN pub."county" AS AC
                                            ON AC."county-id" = A."county-id"
                                            AND AC."live" = 1
                                        INNER JOIN pub."ZIP" AS Z --TODO: MAKE LEFT OUTER JOIN
                                            ON Z."zip-code" = A."zip-code"
                                            AND Z."LIVE" = 1
                                        INNER JOIN pub."RACE" AS R  --TODO: REPLACE WITH FED RACE CODES
                                            ON R."race-code" = N."race-code"
                                            AND R."LIVE" = 1
                                        INNER JOIN pub."name-duser" AS ND --TODO: REMOVE?
                                            ON ND."NAME-ID" = HP."NAME-ID"
                                        LEFT JOIN (
                                                    SELECT DISTINCT
                                                        HDC."HAADEG-STATE-CODE",
                                                        HD."NAME-ID"
                                                    FROM
                                                        pub."HPMPGD-DEGREES" AS HD
                                                        LEFT JOIN pub."HAADEG-DEGREE-CODES" AS HDC
                                                            ON HDC."HAADEG-CODE" = HD."HAADEG-CODE"
                                                    WHERE
                                                        HD."HPMPGD-HIGHEST-DEG-X" = 1
                                                ) AS HDTMPTBL
                                                ON HDTMPTBL."NAME-ID" = N."NAME-ID"
                                        LEFT JOIN pub."salutation" AS S
                                            ON S."salutation-id" = N."salutation-id"
                                            AND S."LIVE" = 1
                                    --WHERE
                                        --HP."HAAPRO-ACTIVE" = 1
                                        --N."NAME-ID" IN(%%nameIDs%%)';

        public static $shared_StaffEducationOrganizationAssignmentAssociation = 'SELECT
                                                                                    A."NAME-ID",
                                                                                    A."HAABLD-BLD-CODE",
                                                                                    A."HAAPLC-CODE",
                                                                                    A."HAAPLC-DESC",
                                                                                    A."HPMASN-START-DATE",
                                                                                    A."HPMASN-END-DATE",
                                                                                    A."HAADSC-DESC-POS",
                                                                                    HP."HAAPRO-OTHER-ID",
                                                                                    SC."CODE-ID" AS JOBCODE,
                                                                                    SC."INT-1" AS EEONUM
                                                                                FROM
                                                                                    pub."HPMASN-ASSIGNMENTS" AS A
                                                                                    INNER JOIN pub."HAAPRO-PROFILE" AS HP
                                                                                        ON HP."NAME-ID" = A."NAME-ID"
                                                                                    INNER JOIN pub."HAADSC-DESCS" AS HD
                                                                                        ON HD."HAADSC-ID" = A."HAADSC-ID-ASN"
                                                                                    INNER JOIN pub."SYS-CTD" AS SC
                                                                                        ON SC."TABLE-ID" = \'HR-FL-ACCT-ST-RPT-FLD\'
                                                                                        AND SC."CODE-ID" = HD."HAADSC-CODE"
                                                                                WHERE
                                                                                    --HP."HAAPRO-ACTIVE" = 1
                                                                                    A."HPMASN-FIS-YEAR" = %%currentsy%%
                                                                                    AND HD."HAADSC-CODE" != \'\'
                                                                                  --AND A."NAME-ID" IN(%%nameIDs%%)
                                                                                    AND A."HAABLD-BLD-CODE" IN (%%entities%%)';

*/
}
?>