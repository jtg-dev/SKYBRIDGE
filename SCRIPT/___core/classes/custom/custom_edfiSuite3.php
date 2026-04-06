<?php

class custom_EdFiSuite3
{

    public static $queries = array(
        "absenceEventCategoryDescriptor" => '
            SELECT
              "HTODCD"."HTODCD-TOF-CODE"
             ,"HTODCD"."HTODCD-DESC"
            
            FROM "SKYWARD"."PUB"."HTODCD-TOF-CODES" "HTODCD"
        ',
        "credentials" => '
            SELECT
              "HAADSC_CertType"."HAADSC-CODE" AS \'HAADSC-CODE-CertType\'
             ,"HAADSC_CertType"."HAADSC-DESC" AS \'HAADSC-DESC-CertType\'
             ,"HAADSC_Level"."HAADSC-CODE" AS \'HAADSC-CODE-Level\'
             ,"HAADSC_Level"."HAADSC-DESC" AS \'HAADSC-DESC-Level\'
             ,"HAADSC_SubjArea"."HAADSC-CODE" AS \'HAADSC-CODE-SubjArea\'
             ,"HAADSC_SubjArea"."HAADSC-DESC" AS \'HAADSC-DESC-SubjArea\'
             ,"HAAPRO"."HAAPRO-OTHER-ID"
             ,"HPMCED"."HAADSC-ID-CERT1"
             ,"HPMCED"."HAADSC-ID-CERT2"
             ,"HPMCEM"."HAADSC-ID-CERT-TYPE"
             ,"HPMCEM"."HPMCEM-CERT-NBR"
             ,"HPMCEM"."HPMCEM-EXP-DATE"
             ,"HPMCEM"."HPMCEM-ID"
             ,"HPMCEM"."HPMCEM-ISSUE-DATE"
             ,"HPMCEM"."HPMCEM-STATE"
             ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
             ,"N"."NAME-ID"

            FROM "SKYWARD"."PUB"."HPMCEM-CERT-MST" "HPMCEM"
            INNER JOIN "SKYWARD"."PUB"."HPMCED-CERT-DTL" "HPMCED" ON
             "HPMCEM"."HPMCEM-ID" = "HPMCED"."HPMCEM-ID"
            INNER JOIN (
                SELECT
                  "HPMCEM"."HPMCEM-CERT-NBR"
                 ,"HPMCED"."HAADSC-ID-CERT1"
                 ,"HPMCED"."HAADSC-ID-CERT2"
                 ,MAX("HPMCEM"."HPMCEM-EXP-DATE") AS \'MAX-HPMCEM-EXP-DATE\' 
                
                FROM "SKYWARD"."PUB"."HPMCEM-CERT-MST" "HPMCEM"
                INNER JOIN "SKYWARD"."PUB"."HPMCED-CERT-DTL" "HPMCED" ON
                 "HPMCEM"."HPMCEM-ID" = "HPMCED"."HPMCEM-ID"
                INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_CertType" ON 
                 "HPMCEM"."HAADSC-ID-CERT-TYPE" = "HAADSC_CertType"."HAADSC-ID"
                INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_Level" ON 
                 "HPMCED"."HAADSC-ID-CERT1" = "HAADSC_Level"."HAADSC-ID"
                INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_SubjArea" ON 
                 "HPMCED"."HAADSC-ID-CERT2" = "HAADSC_SubjArea"."HAADSC-ID"
                
                WHERE
                 "HPMCEM"."HPMCEM-ISSUE-DATE" IS NOT NULL AND
                 ("HPMCEM"."HPMCEM-CERT-NBR" IS NOT NULL AND LTRIM(RTRIM("HPMCEM"."HPMCEM-CERT-NBR")) <> \'\') AND
                 ("HAADSC_CertType"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_CertType"."HAADSC-CODE")) <> \'\') AND
                 ("HAADSC_Level"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_Level"."HAADSC-CODE")) <> \'\') AND
                 ("HAADSC_SubjArea"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_SubjArea"."HAADSC-CODE")) <> \'\')
                
                GROUP BY
                  "HPMCEM"."HPMCEM-CERT-NBR"
                 ,"HPMCED"."HAADSC-ID-CERT1"
                 ,"HPMCED"."HAADSC-ID-CERT2"
                  
            ) "HPMCEMX" ON
             "HPMCEM"."HPMCEM-CERT-NBR" = "HPMCEMX"."HPMCEM-CERT-NBR" AND
             "HPMCED"."HAADSC-ID-CERT1" = "HPMCEMX"."HAADSC-ID-CERT1" AND
             "HPMCED"."HAADSC-ID-CERT2" = "HPMCEMX"."HAADSC-ID-CERT2" AND
             "HPMCEM"."HPMCEM-EXP-DATE" = "HPMCEMX"."MAX-HPMCEM-EXP-DATE"
            INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
             "HPMCEM"."NAME-ID" = "HAAPRO"."NAME-ID"
            LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
             "HAAPRO"."NAME-ID" = "N"."NAME-ID"
            INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_CertType" ON
             "HPMCEM"."HAADSC-ID-CERT-TYPE" = "HAADSC_CertType"."HAADSC-ID"
            INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_Level" ON
             "HPMCED"."HAADSC-ID-CERT1" = "HAADSC_Level"."HAADSC-ID"
            INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_SubjArea" ON
             "HPMCED"."HAADSC-ID-CERT2" = "HAADSC_SubjArea"."HAADSC-ID"

            WHERE
             "HPMCEM"."HPMCEM-ISSUE-DATE" IS NOT NULL AND
             "HPMCEM"."HPMCEM-ISSUE-DATE" <= \'%%snapshotDate%%\' AND
             ("HAAPRO"."HAAPRO-OTHER-ID" IS NOT NULL AND LTRIM(RTRIM("HAAPRO"."HAAPRO-OTHER-ID")) <> \'\') AND -- Needed for TPDM
             ("HPMCEM"."HPMCEM-CERT-NBR" IS NOT NULL AND LTRIM(RTRIM("HPMCEM"."HPMCEM-CERT-NBR")) <> \'\') AND
             ("HAADSC_CertType"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_CertType"."HAADSC-CODE")) <> \'\') AND
             ("HAADSC_Level"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_Level"."HAADSC-CODE")) <> \'\') AND
             ("HAADSC_SubjArea"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_SubjArea"."HAADSC-CODE")) <> \'\')
        ',
        "credentialFieldDescriptor" => '
            SELECT
              "HAADSC"."HAADSC-CODE"
             ,"HAADSC"."HAADSC-DESC"
            
            FROM "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC"
            
            WHERE
             "HAADSC"."HAADSC-IND" = \'CERT2\'
            
            ORDER BY
              "HAADSC"."HAADSC-CODE"
        ',
        "methodsOfInstruction" => '
            SELECT
              "QGTREC"."QGTREC-SRC-CODE" AS \'CodeValue\'
             ,"QGTREC"."QGTREC-CHR"[1] AS \'ShortDescription\'
             ,"QGTREC"."QGTREC-CHR"[2] AS \'Description\'
            
            FROM "SKYWARD"."PUB"."QGTREC-GENERIC-TABLE" "QGTREC"
            
            WHERE
             "QGTREC"."QGTREC-TABLE-NAME" = \'INSTR-MTHD\'
        ',
        "openStaffPosition" => '
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
             "HJL"."HAPJBL-POST-INT-BEGIN-DATE" <= \'%%snapshotDate%%\' AND
             "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%)
        ',
        "people" => '
            SELECT
              "HAAPRO"."HAAPRO-OTHER-ID"
             ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
             ,"N"."NAME-ID"

            FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
            INNER JOIN "SKYWARD"."PUB"."NAME" "N" ON
             "HAAPRO"."NAME-ID" = "N"."NAME-ID"

            WHERE
             ("HAAPRO"."HAAPRO-OTHER-ID" IS NOT NULL AND LTRIM(RTRIM("HAAPRO"."HAAPRO-OTHER-ID")) <> \'\')
        ',
        "performanceEvaluationRatings" => '
            SELECT
              "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
             ,"HAADSC"."HAADSC-CODE" AS \'HAAEVL-EVAL-PERIOD\'
             ,"HAAEVL"."HAAEVL-EVAL-MISC1" AS \'HAAEVL-EVAL-TYPE\'
             ,"HAAEVL"."HAAEVL-EVAL-MISC3" AS \'HAAEVL-EVAL-CLASS\'
             ,"HAAEVL"."HAAEVL-EVALUATION-DATE"
             ,"HAAEVL"."HAAEVL-EVALUATION-STATUS"
             ,"HAAPRO"."HAAPRO-OTHER-ID"
             ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
             ,"N"."NAME-ID"

            FROM "SKYWARD"."PUB"."HAAEVL-TEACH-EVAL" "HAAEVL"
            INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
             "HAAEVL"."NAME-ID" = "HAAPRO"."NAME-ID"
            LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
             "HAAPRO"."NAME-ID" = "N"."NAME-ID"
            INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC" ON
             "HAAEVL"."HAAEVL-EVAL-MISC2" = "HAADSC"."HAADSC-ID"
            INNER JOIN "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN" ON
             "HAAEVL"."NAME-ID" = "HPMASN"."NAME-ID" AND
             "HAAEVL"."HAAEVL-EVALUATION-DATE" BETWEEN "HPMASN"."HPMASN-START-DATE" AND "HPMASN"."HPMASN-END-DATE"
            INNER JOIN "SKYWARD"."PUB"."HPMPLN-PLAN" "HPMPLN" ON
             "HPMASN"."HPMPLN-ID" = "HPMPLN"."HPMPLN-ID"
            INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
             "HPMASN"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"

            WHERE
             ("HAAPRO"."HAAPRO-OTHER-ID" IS NOT NULL AND LTRIM(RTRIM("HAAPRO"."HAAPRO-OTHER-ID")) <> \'\') AND
             "HPMASN"."HAADSC-DESC-POS" <> \'Supplement\' AND
             "HPMPLN"."HPMPLN-SN-PLAN-X" = 0 AND
             "HPMASN"."HPMASN-FIS-YEAR" <= %%currentsy%% AND
             "HAAEVL"."HAAEVL-EVALUATION-DATE" <= \'%%snapshotDate%%\' AND
             "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%)
        ',
        "performanceEvaluations" => '
            SELECT DISTINCT
              "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
             ,"HAADSC"."HAADSC-CODE" AS \'HAAEVL-EVAL-PERIOD\'
             ,"HAAEVL"."HAAEVL-EVAL-MISC1" AS \'HAAEVL-EVAL-TYPE\'
             ,"HAAEVL"."HAAEVL-EVAL-MISC3" AS \'HAAEVL-EVAL-CLASS\'
             ,"HAAEVL"."HAAEVL-EVALUATION-DATE"
            
            FROM "SKYWARD"."PUB"."HAAEVL-TEACH-EVAL" "HAAEVL"
            INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
             "HAAEVL"."NAME-ID" = "HAAPRO"."NAME-ID"
            INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC" ON
             "HAAEVL"."HAAEVL-EVAL-MISC2" = "HAADSC"."HAADSC-ID"
            INNER JOIN "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN" ON
             "HAAEVL"."NAME-ID" = "HPMASN"."NAME-ID" AND
             "HAAEVL"."HAAEVL-EVALUATION-DATE" BETWEEN "HPMASN"."HPMASN-START-DATE" AND "HPMASN"."HPMASN-END-DATE"
            INNER JOIN "SKYWARD"."PUB"."HPMPLN-PLAN" "HPMPLN" ON
             "HPMASN"."HPMPLN-ID" = "HPMPLN"."HPMPLN-ID"
            INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
             "HPMASN"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
            
            WHERE
             ("HAAPRO"."HAAPRO-OTHER-ID" IS NOT NULL AND LTRIM(RTRIM("HAAPRO"."HAAPRO-OTHER-ID")) <> \'\') AND
             "HPMASN"."HAADSC-DESC-POS" <> \'Supplement\' AND
             "HPMPLN"."HPMPLN-SN-PLAN-X" = 0 AND
             "HPMASN"."HPMASN-FIS-YEAR" <= %%currentsy%% AND
             "HAAEVL"."HAAEVL-EVALUATION-DATE" <= \'%%snapshotDate%%\' AND
             "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%)
        ',
        "staff" => '
            SELECT
              "A"."ADDRESS2"
             ,"A"."POBOX"
             ,"A"."STREET-APPT"
             ,"A"."STREET-DIR"
             ,"A"."STREET-NAME"
             ,"A"."STREET-NUMBER"
             ,"A"."ZIP-CODE"
             ,"AC"."COUNTY-LDESC"
             ,"FFAACT"."FFAACT-EmpIDSetup-Length-Min"  
             ,"FFAACT"."FFAACT-EmpIDSetup-NumLtr-Opt"
             ,"HAABLD"."HAABLD-BLD-CODE"
             ,"HAABLD"."HAABLD-STATE-CODE"
             ,"HAAPRO"."HAAPRO-MAIDEN-NAME"
             ,"HAAPRO"."HAAPRO-OTHER-ID"
             ,"HAAPRO"."HAAPRO-US-CITIZEN-X"
             ,"HAAPRO"."HAAPRO-YRS-EXP1"  AS \'HAAPRO-YRS-TEACH-IN-DISTRICT\'
             ,"HAAPRO"."HAAPRO-YRS-EXP10" AS \'HAAPRO-YRS-VOCATIONAL\'
             ,"HAAPRO"."HAAPRO-YRS-EXP2"  AS \'HAAPRO-YRS-TEACH-FL-PUBLIC\'
             ,"HAAPRO"."HAAPRO-YRS-EXP3"  AS \'HAAPRO-YRS-TEACH-FL-NON-PUBLIC\'
             ,"HAAPRO"."HAAPRO-YRS-EXP4"  AS \'HAAPRO-YRS-TEACH-OTHER-PUBLIC\'
             ,"HAAPRO"."HAAPRO-YRS-EXP5"  AS \'HAAPRO-YRS-TEACH-OTHER-NON-PUBLIC\'
             ,"HAAPRO"."HAAPRO-YRS-EXP6"  AS \'HAAPRO-YRS-ADMIN-EXP\'
             ,"HAAPRO"."HAAPRO-YRS-EXP7"  AS \'HAAPRO-YRS-MILITARY-SERVICE\'
             ,"HAAPRO"."HAAPRO-YRS-EXP8"  AS \'HAAPRO-YRS-NONINST-IN-DISTRICT\'
             ,"HAAPRO"."HAAPRO-YRS-EXP9"  AS \'HAAPRO-YRS-NONINST-OTHER\'
             ,"HDTMPTBL"."HAADEG-CODE"
             ,"HDTMPTBL"."HAADEG-STATE-CODE"
             ,"N"."ADDRESS-ID"
             ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
             ,"N"."BIRTHDATE"
             ,"N"."CONFIDENTIAL-CODE"
             ,"N"."ETHNICITY-HISP-X"
             ,"N"."FED-RACE-FLAGS"
             ,"N"."FEDERAL-ID-NO"
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
             ,"S"."SALUTATION-SDESC"
             ,"Z"."ZIP-CITY"
             ,"Z"."ZIP-STATE"
            
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
        ',
        "staffAbsenceEvent" => '
            SELECT
              "HTOTRN"."NAME-ID"
             ,"HAAPRO"."HAAPRO-OTHER-ID"
             ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
             ,"HTOTRN"."HTOTRN-TRANS-DATE"
             ,"HTOTRN"."HTODCD-TOF-CODES"
             ,(CASE WHEN "HTOTRN"."HTOTRN-HRS" < 0 THEN 1 ELSE 0 END) AS \'X-IS-ALLOCATION\'
             ,MIN("HTODRS"."HTODRS-DESC") AS \'HTODRS-DESC\'
             ,SUM("HTOTRN"."HTOTRN-HRS") AS \'HTOTRN-HRS\'

            FROM "SKYWARD"."PUB"."HTOTRN-TRANS" "HTOTRN"
            INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
             "HTOTRN"."NAME-ID" = "HAAPRO"."NAME-ID"
            LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
             "HAAPRO"."NAME-ID" = "N"."NAME-ID"
            INNER JOIN "SKYWARD"."PUB"."HTODRS-REASON-CODES" "HTODRS" ON
             "HTODRS"."HTODRS-REASON-CODE" = "HTOTRN"."HTOTRN-REASON-CODE"

            WHERE
             "HTOTRN"."HTOTRN-TRANS-DATE" >= \'07/01/%%schoolYear%%\' AND
             "HTOTRN"."HTOTRN-TRANS-DATE" <= \'%%snapshotDate%%\' AND
             "HTOTRN"."HTOTRN-TYPE" IN (\'L\',\'U\')

            GROUP BY
              "HTOTRN"."NAME-ID"
             ,"HAAPRO"."HAAPRO-OTHER-ID"
             ,%%staffIdColumn%%
             ,"HTOTRN"."HTOTRN-TRANS-DATE"
             ,"HTOTRN"."HTODCD-TOF-CODES"
             ,(CASE WHEN "HTOTRN"."HTOTRN-HRS" < 0 THEN 1 ELSE 0 END)
        ',
        "staffEducationOrganizationAssignmentAssociation" => '
            SELECT
              "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
             ,"HAABLD_PRO"."HAABLD-STATE-CODE" AS \'HAAPRO-HAABLD-BLD-CODE\'
             ,"HAAPRO"."HAAPRO-HIRE-DTE"
             ,"HAAPRO"."HAAPRO-OTHER-ID"
             ,"HPMASN"."HAADSC-DESC-POS"
             ,"HPMASN"."HAAPLC-CODE"
             ,"HPMASN"."HAAPLC-DESC"
             ,"HPMASN"."HPMASN-END-DATE"
             ,"HPMASN"."HPMASN-START-DATE"
             ,"HPMASN"."NAME-ID"
             ,"SC"."CODE-ID" AS \'JOBCODE\'
             ,"SC"."INT-1" AS \'EEONUM\'
             ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'

            FROM "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN"
            INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
             "HPMASN"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
            INNER JOIN "SKYWARD"."PUB"."HPMPLN-PLAN" "HPMPLN" ON
             "HPMASN"."HPMPLN-ID" = "HPMPLN"."HPMPLN-ID"
            INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
             "HPMASN"."NAME-ID" = "HAAPRO"."NAME-ID"
            LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
             "HAAPRO"."NAME-ID" = "N"."NAME-ID"
            INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD_PRO" ON
             "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD_PRO"."HAABLD-BLD-CODE"
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
             "HPMASN"."HPMASN-START-DATE" <= \'%%snapshotDate%%\' AND
             "HPMPLN"."HPMPLN-DESC" = \'%%planName%%\'
        ',
        "staffEducationOrganizationEmploymentAssociation" => '
            SELECT
              "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
             ,"HAAPRO"."HAAPRO-ACTIVE"
             ,"HAAPRO"."HAAPRO-HIRE-DTE"
             ,"HAAPRO"."HAAPRO-OTHER-ID"
             ,"HAAPRO"."HAAPRO-TERM-DTE"
             ,"HAAPRO"."HPETER-TERM-CODE"
             ,"HAAPRO"."NAME-ID"
             ,"HPETER"."HPETER-STATE-CODE"
             ,"FTE"."SUM-HPMASN-FTE-CALC"
             ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'

            FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
            LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
             "HAAPRO"."NAME-ID" = "N"."NAME-ID"
            LEFT JOIN "SKYWARD"."PUB"."HPETER-TERM-CODES" "HPETER" ON
             "HAAPRO"."HPETER-TERM-CODE" = "HPETER"."HPETER-TERM-CODE"
            INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
            "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
            LEFT OUTER JOIN (
                SELECT
                  "HPMASN"."NAME-ID"
                 ,SUM("HPMASN"."HPMASN-FTE-CALC") AS \'SUM-HPMASN-FTE-CALC\'

                FROM "SKYWARD"."PUB"."HPMASN-ASSIGNMENTS" "HPMASN"
                INNER JOIN "SKYWARD"."PUB"."HPMPLN-PLAN" "HPMPLN" ON
                 "HPMASN"."HPMPLN-ID" = "HPMPLN"."HPMPLN-ID"

                WHERE
                 \'%%snapshotDate%%\' BETWEEN "HPMASN"."HPMASN-START-DATE" AND "HPMASN"."HPMASN-END-DATE" AND
                 "HPMASN"."HPMASN-FIS-YEAR" = %%currentsy%% AND
                 "HPMPLN"."HPMPLN-SN-PLAN-X" = 0 AND
                 "HPMPLN"."HPMPLN-DESC" = \'%%planName%%\' AND
                 "HPMASN"."HAADSC-DESC-POS" <> \'Supplement\'

                GROUP BY
                  "HPMASN"."NAME-ID"
            ) "FTE" ON
             "HAAPRO"."NAME-ID" = "FTE"."NAME-ID"

            WHERE
             "HAAPRO"."HAAPRO-HIRE-DTE" IS NOT NULL AND
             "HAABLD"."HAABLD-STATE-CODE" IN (%%entities%%)
        ',
        "staffWithoutAssignments" => '
            SELECT
              "HAABLD"."HAABLD-STATE-CODE" AS \'HAABLD-BLD-CODE\'
             ,"HAAPRO"."HAAETY-EMP-TYPE-CODE"
             ,"HAAPRO"."HAAPRO-OTHER-ID"
             ,"HAAPRO"."NAME-ID"
             ,"HPAPRM"."HPADCP-PAY-CODE"
             ,"HPAPRM"."HPAPRM-START-DATE"
             ,"HPAPRM"."HPAPRM-STOP-DATE"
             ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'

            FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
            LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
             "HAAPRO"."NAME-ID" = "N"."NAME-ID"
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
             
        ',
        "teachingCredentialDescriptor" => '
            SELECT
              "HAADSC"."HAADSC-CODE"
             ,"HAADSC"."HAADSC-DESC"
            
            FROM "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC"
            
            WHERE
             "HAADSC"."HAADSC-IND" = \'CERTT\' AND
             ("HAADSC"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC"."HAADSC-CODE")) <> \'\')
            
            ORDER BY
              "HAADSC"."HAADSC-CODE"
        ',
        "terms" => '
            SELECT
              "HAADSC"."HAADSC-CODE" AS \'CodeValue\'
             ,"HAADSC"."HAADSC-DESC" AS \'Description\'
            
            FROM "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC"
            
            WHERE 
             "HAADSC"."HAADSC-IND" = \'FL-PRSNL-EVAL-PRD\' AND
             ("HAADSC"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC"."HAADSC-CODE")) <> \'\')
        '
    );

    public static $subqueries = array(
        "getJobCodeByAccountDistribution" => '
            SELECT
              "HAABLD_PRO"."HAABLD-STATE-CODE" AS \'HAAPRO-HAABLD-BLD-CODE\'
             ,"HAAPRO"."HAAPRO-HIRE-DTE"
             ,"HPACFP"."HPACFP-START-DATE"
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
            INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
             "HPAPRM"."NAME-ID" = "HAAPRO"."NAME-ID"
            INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD_PRO" ON
             "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD_PRO"."HAABLD-BLD-CODE"
            
            
            WHERE
             "HPAPRM"."HPAPRM-ACTIVE" = 1 AND
             "HPAPRM"."HPAPRM-PRIMARY-X" = 1 AND
             ("HPAPRA"."HPAPRA-DEPT" IS NOT NULL AND LTRIM(RTRIM("HPAPRA"."HPAPRA-DEPT")) <> \'\') AND
             COALESCE("HPAPRM"."HPAPRM-START-DATE", "HPACFP"."HPACFP-START-DATE") <= \'%%snapshotDate%%\' AND
             "HPAPRM"."NAME-ID" = %%nameId%%
             
            GROUP BY
              "HPAPRM"."NAME-ID"
             ,"HPAPRM"."HPAPRM-START-DATE"
             ,"HPAPRM"."HPAPRM-STOP-DATE"
             ,"HPACFP"."HPACFP-START-DATE"
             ,"HPACFP"."HPACFP-STOP-DATE"
             ,"HPAPRA"."HPAPRA-DEPT"
             ,"HAABLD_PRO"."HAABLD-STATE-CODE"
             ,"HAAPRO"."HAAPRO-HIRE-DTE"
        ',
        "getJobCodeByCheckHistory" => '
            SELECT DISTINCT
              "HAABLD_PRO"."HAABLD-STATE-CODE" AS \'HAAPRO-HAABLD-BLD-CODE\'
             ,"HAAPRO"."HAAPRO-HIRE-DTE"
             ,"HPAHDC"."NAME-ID"
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
                WHERE "HPAHDC"."HPAHDC-CHK-DTE" <= \'%%snapshotDate%%\'
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
            INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
             "HPAHDC"."NAME-ID" = "HAAPRO"."NAME-ID"
            INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD_PRO" ON
             "HAAPRO"."HAABLD-BLD-CODE" = "HAABLD_PRO"."HAABLD-BLD-CODE"
            
            WHERE
             ("HPAHDP"."HPAHDP-DEPT" IS NOT NULL AND LTRIM(RTRIM("HPAHDP"."HPAHDP-DEPT")) <> \'\') AND
             "HPAHDC"."NAME-ID" = %%nameId%%
        ',
        "getJobCodeByStateReportingParameterSet" => '
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
         "QRBGRT"."QRBGRT-ID" = 20 AND
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
        ',
        "getPayRecsUseAcctDistJobCode" => '
            SELECT "FFAACT"."FFAACT-AcctStRptFld-X" 
            FROM "SKYWARD"."PUB"."FFAACT-CONTROL-FILE" "FFAACT"
        ',
        "staffCredentials" => '
            SELECT
              "HPMCED"."HAADSC-ID-CERT1"
             ,"HPMCED"."HAADSC-ID-CERT2"
             ,"HPMCEM"."HPMCEM-CERT-NBR"
             ,"HPMCEM"."HPMCEM-STATE"
            
            FROM "SKYWARD"."PUB"."HPMCEM-CERT-MST" "HPMCEM"
            INNER JOIN "SKYWARD"."PUB"."HPMCED-CERT-DTL" "HPMCED" ON
             "HPMCEM"."HPMCEM-ID" = "HPMCED"."HPMCEM-ID"
            INNER JOIN (
                SELECT
                  "HPMCEM"."HPMCEM-CERT-NBR"
                 ,"HPMCED"."HAADSC-ID-CERT1"
                 ,"HPMCED"."HAADSC-ID-CERT2"
                 ,MAX("HPMCEM"."HPMCEM-EXP-DATE") AS \'MAX-HPMCEM-EXP-DATE\' 
                
                FROM "SKYWARD"."PUB"."HPMCEM-CERT-MST" "HPMCEM"
                INNER JOIN "SKYWARD"."PUB"."HPMCED-CERT-DTL" "HPMCED" ON
                 "HPMCEM"."HPMCEM-ID" = "HPMCED"."HPMCEM-ID"
                INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_CertType" ON 
                 "HPMCEM"."HAADSC-ID-CERT-TYPE" = "HAADSC_CertType"."HAADSC-ID"
                INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_Level" ON 
                 "HPMCED"."HAADSC-ID-CERT1" = "HAADSC_Level"."HAADSC-ID"
                INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_SubjArea" ON 
                 "HPMCED"."HAADSC-ID-CERT2" = "HAADSC_SubjArea"."HAADSC-ID"
                
                WHERE
                 "HPMCEM"."HPMCEM-ISSUE-DATE" IS NOT NULL AND
                 ("HPMCEM"."HPMCEM-CERT-NBR" IS NOT NULL AND LTRIM(RTRIM("HPMCEM"."HPMCEM-CERT-NBR")) <> \'\') AND
                 ("HAADSC_CertType"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_CertType"."HAADSC-CODE")) <> \'\') AND
                 ("HAADSC_Level"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_Level"."HAADSC-CODE")) <> \'\') AND
                 ("HAADSC_SubjArea"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_SubjArea"."HAADSC-CODE")) <> \'\')
                
                GROUP BY
                  "HPMCEM"."HPMCEM-CERT-NBR"
                 ,"HPMCED"."HAADSC-ID-CERT1"
                 ,"HPMCED"."HAADSC-ID-CERT2"
                  
            ) "HPMCEMX" ON
             "HPMCEM"."HPMCEM-CERT-NBR" = "HPMCEMX"."HPMCEM-CERT-NBR" AND
             "HPMCED"."HAADSC-ID-CERT1" = "HPMCEMX"."HAADSC-ID-CERT1" AND
             "HPMCED"."HAADSC-ID-CERT2" = "HPMCEMX"."HAADSC-ID-CERT2" AND
             "HPMCEM"."HPMCEM-EXP-DATE" = "HPMCEMX"."MAX-HPMCEM-EXP-DATE"
            INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
             "HPMCEM"."NAME-ID" = "HAAPRO"."NAME-ID"
            INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_CertType" ON 
             "HPMCEM"."HAADSC-ID-CERT-TYPE" = "HAADSC_CertType"."HAADSC-ID"
            INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_Level" ON 
             "HPMCED"."HAADSC-ID-CERT1" = "HAADSC_Level"."HAADSC-ID"
            INNER JOIN "SKYWARD"."PUB"."HAADSC-DESCS" "HAADSC_SubjArea" ON 
             "HPMCED"."HAADSC-ID-CERT2" = "HAADSC_SubjArea"."HAADSC-ID"
            
            WHERE
             "HPMCEM"."HPMCEM-ISSUE-DATE" IS NOT NULL AND
             ("HAAPRO"."HAAPRO-OTHER-ID" IS NOT NULL AND LTRIM(RTRIM("HAAPRO"."HAAPRO-OTHER-ID")) <> \'\') AND -- Needed for TPDM
             ("HPMCEM"."HPMCEM-CERT-NBR" IS NOT NULL AND LTRIM(RTRIM("HPMCEM"."HPMCEM-CERT-NBR")) <> \'\') AND
             ("HAADSC_CertType"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_CertType"."HAADSC-CODE")) <> \'\') AND
             ("HAADSC_Level"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_Level"."HAADSC-CODE")) <> \'\') AND
             ("HAADSC_SubjArea"."HAADSC-CODE" IS NOT NULL AND LTRIM(RTRIM("HAADSC_SubjArea"."HAADSC-CODE")) <> \'\') AND
             "HPMCEM"."HPMCEM-EXP-DATE" >= \'%%snapshotDate%%\' AND
             "HPMCEM"."HPMCEM-ISSUE-DATE" <= \'%%snapshotDate%%\' AND
             "HPMCEM"."NAME-ID" = %%nameId%%
        '
    );


    /* ====================================
     * ===   Static Descriptor Values   ===
     * ==================================== */

    /* === Address Type Descriptors === */
    public static $addressTypes = array(
        "Org-Physical",
        "Physical"
    );

    /* === Certificate Level Descriptors ===*/
    public static $certificateLevelCodes = array(
        "1" => "6-12",
        "2" => "Adult",
        "6" => "K-12",
        "7" => "Career and Technical Education",
        "C" => "5-9",
        "D" => "PK-12",
        "E" => "Endorsement",
        "F" => "All Levels",
        "H" => "PK-3",
        "K" => "K-6",
        "L" => "0-4 Yrs"
    );

    /* === DOE Certificate Type Codes === */
    /* See Staff Database Manual for latest updates. (http://www.fldoe.org/accountability/data-sys/database-manuals-updates/) */
    public static $certificateTypeCodes = array(
        /* Modern teaching certificate types */
        "AC" => "Athletic Coaching",
        "NP" => "Non-renewable Professional",
        "RG" => "Regular/Professional",
        "RP" => "Restricted Professional",
        "SB" => "Substitute",
        "TB" => "Temporary (one year) certificate with credit required to reissue",
        "TC" => "Temporary (one year) certificate with no credit required to reissue",
        "TD" => "Temporary (three years) Non-renewable certificate",
        "TM" => "Temporary (two years)",
        /* Legacy teaching certificate types */
        "01" => "Advanced postgraduate",
        "02" => "Postgraduate",
        "03" => "Graduate",
        "05" => "Life certificate",
        "06" => "Special postgraduate"
    );

    /* === U.S. Citizenship Statuses === */
    public static $citizenshipStatuses = array(
        "Non-resident alien",
        "US Citizen"
    );

    /* === Credential Types === */
    public static $credentialTypes = array(
        "Certificate"
    );

    /* === Education Organization Categories === */
    public static $educationOrganizationCategories = array(
        "State Education Agency",
        "Local Education Agency",
        "School"
    );

    /* === Grade Levels === */
    public static $gradeLevels = array(
        "01"       => "First Grade",
        "02"       => "Second Grade",
        "03"       => "Third Grade",
        "04"       => "Fourth Grade",
        "05"       => "Fifth Grade",
        "06"       => "Sixth Grade",
        "07"       => "Seventh Grade",
        "08"       => "Eighth Grade",
        "09"       => "Ninth Grade",
        "10"       => "Tenth Grade",
        "11"       => "Eleventh Grade",
        "12"       => "Twelfth Grade",
        "30"       => "Adult, Non-High School graduate",
        "31"       => "Adult, High School graduate",
        "KG"       => "Kindergarten",
        "PK"       => "Pre-Kindergarten",
        "Ungraded" => "Ungraded",
        "Other"    => "Other"
    );

    /* === Email (Electronic Mail) Types === */
    public static $electronicMailTypes = array(
        "Work"
    );

    /* === Employment Status Codes === */
    public static $employmentStatusCodes = array(
        "Contractual",
        "Employed part-time",
        "Other",
        "Tenured or permanent"
    );

    /* === Evaluation Period Codes === */
    public static $evaluationPeriods = array(
        "End-of-Year",
        "Mid-Year"
    );

    public static $jobCodes = array(
        "51001" => array("TEACHER, DR PREV-E", "Teacher, Dropout Prevention, Elementary"),
        "51002" => array("TEACHER, DR PREV-M/J", "Teacher, Dropout Prevention, Middle/Junior"),
        "51003" => array("TEACHER, DR PREV-SH", "Teacher, Dropout Prevention, Senior High"),
        "51004" => array("TEACHER, ART-E", "Teacher, Art, Elementary"),
        "51005" => array("TEACHER, ART-M/J", "Teacher, Art, Middle/Junior"),
        "51006" => array("TEACHER, ART-SH", "Teacher, Art, Senior High"),
        "51007" => array("TEACHER, COMPU ED-E", "Teacher, Computer Education, Elementary"),
        "51008" => array("TEACHER, COMPU ED-M/J", "Teacher, Computer Education, Middle/Junior"),
        "51009" => array("TEACHER, COMPU ED-SH", "Teacher, Computer Education, Senior High"),
        "51010" => array("TEACHER, DANCE-M/J", "Teacher, Dance, Middle/Junior High"),
        "51011" => array("TEACHER, DANCE-SH", "Teacher, Dance, Senior High"),
        "51012" => array("TEACHER, DRAMA-M/J", "Teacher, Drama, Middle/Junior"),
        "51013" => array("TEACHER, DRAMA-SH", "Teacher, Drama, Senior High"),
        "51014" => array("TEACHER, EXPER ED-SH", "Teacher, Experiential Education, Senior High"),
        "51015" => array("TEACHER, WORLD LANG-E", "Teacher, World Language, Elementary"),
        "51016" => array("TEACHER, WORLD LANG-M/J", "Teacher, World Language, Middle/Junior"),
        "51017" => array("TEACHER, WORLD LANG-SH", "Teacher, World Language, Senior High"),
        "51018" => array("TEACHER, HEALTH-E", "Teacher, Health, Elementary"),
        "51019" => array("TEACHER, HEALTH-M/J", "Teacher, Health, Middle/Junior"),
        "51020" => array("TEACHER, HEALTH-SH", "Teacher, Health, Senior high"),
        "51021" => array("TEACHER, HUM-M/J", "Teacher, Humanities, Middle/Junior High"),
        "51022" => array("TEACHER, HUM-SH", "Teacher, Humanities, Senior High"),
        "51023" => array("TEACHER, ISS-E", "Teacher, In-School Suspension, Elementary"),
        "51024" => array("TEACHER, ISS-M/J", "Teacher, In-School Suspension, Middle/Junior"),
        "51025" => array("TEACHER, ISS-SH", "Teacher, In-School Suspension, Senior High"),
        "51026" => array("TEACHER, LANG AR-E", "Teacher, Language Arts, Elementary"),
        "51027" => array("TEACHER, LANG AR-M/J", "Teacher, Language Arts, Middle/Junior"),
        "51028" => array("TEACHER, LANG AR-SH", "Teacher, Language Arts, Senior High"),
        "51029" => array("TEACHER, LIB/MED-M/J", "Teacher, Library/Media, Middle/Junior"),
        "51030" => array("TEACHER, LIB/MED-SH", "Teacher, Library/Media, Senior High"),
        "51031" => array("TEACHER, MATH-E", "Teacher, Mathematics, Elementary"),
        "51032" => array("TEACHER, MATH-M/J", "Teacher, Mathematics, Middle/Junior"),
        "51033" => array("TEACHER, MATH-SH", "Teacher, Mathematics, Senior High"),
        "51034" => array("TEACHER, MUSIC-E", "Teacher, Music, Elementary"),
        "51035" => array("TEACHER, MUSIC-M/J", "Teacher, Music, Middle/Junior"),
        "51036" => array("TEACHER, MUSIC-SH", "Teacher, Music, Senior High"),
        "51037" => array("TEACHER, PEER CN-M/J", "Teacher, Peer Counseling, Middle/Junior"),
        "51038" => array("TEACHER, PEER CN-SH", "Teacher, Peer Counseling, Senior High"),
        "51039" => array("TEACHER, PE-E", "Teacher, Physical Education, Elementary"),
        "51040" => array("TEACHER, PE-M/J", "Teacher, Physical Education, Middle/Junior"),
        "51041" => array("TEACHER, PE-SH", "Teacher, Physical Education, Senior High"),
        "51042" => array("TEACHER, READING-SH", "Teacher, Reading, Senior High"),
        "51043" => array("TEACHER, REM/CE-E", "Teacher, Remedial/Comp Ed, Elementary"),
        "51044" => array("TEACHER, REM/CE-M/J", "Teacher, Remedial/Comp Ed, Middle/Junior"),
        "51045" => array("TEACHER, REM/CE-SH", "Teacher, Remedial/Comp Ed, Senior High"),
        "51046" => array("TEACHER, RSRCH/CRIT THNK-SH", "Teacher, Research/Critical Thinking, Senior High"),
        "51047" => array("TEACHER, ROTC/MIL TRNG-M/J", "Teacher, ROTC/Military Training, Middle/Junior"),
        "51048" => array("TEACHER, ROTC/MIL TRNG-SH", "Teacher, ROTC/Military Training, Senior High"),
        "51049" => array("TEACHER, DR ED-SH", "Teacher, Safety/Driver Education, Senior High"),
        "51050" => array("TEACHER, SCIENCE-E", "Teacher, Science, Elementary"),
        "51051" => array("TEACHER, SCIENCE-M/J", "Teacher, Science, Middle/Junior"),
        "51052" => array("TEACHER, SCIENCE-SH", "Teacher, Science, Senior High"),
        "51053" => array("TEACHER, LEAD DEV-SH", "Teacher, Leadership Skills Development, Senior High"),
        "51054" => array("TEACHER, KG", "Teacher, Self Contained, Kindergarten"),
        "51055" => array("TEACHER, FIRST", "Teacher, Self Contained, First Grade"),
        "51056" => array("TEACHER, SECOND", "Teacher, Self Contained, Second Grade"),
        "51057" => array("TEACHER, THIRD", "Teacher, Self Contained, Third Grade"),
        "51058" => array("TEACHER, FOURTH", "Teacher, Self Contained, Fourth Grade"),
        "51059" => array("TEACHER, FIFTH", "Teacher, Self Contained, Fifth Grade"),
        "51060" => array("TEACHER, SIXTH", "Teacher, Self Contained, Sixth Grade"),
        "51061" => array("TEACHER, SOC ST-E", "Teacher, Social Studies, Elementary"),
        "51062" => array("TEACHER, SOC ST-M/J", "Teacher, Social Studies, Middle/Junior"),
        "51063" => array("TEACHER, SOC ST-SH", "Teacher, Social Studies, Senior High"),
        "51064" => array("TEACHER, ST HALL-E", "Teacher, Study Hall, Elementary"),
        "51065" => array("TEACHER, ST HALL-M/J", "Teacher, Study Hall, Middle/Junior"),
        "51066" => array("TEACHER, ST HALL-SH", "Teacher, Study Hall, Senior High"),
        "51067" => array("TEACHER, UNGRADED-E", "Teacher, Ungraded Elementary"),
        "51068" => array("TEACHER, COMBIN-E", "Teacher, Combination, Elementary Grades"),
        "51069" => array("TEACHER, M/J", "Teacher, Middle/Junior High Classroom"),
        "51070" => array("TEACHER, SH", "Teacher, Senior High Classroom"),
        "51071" => array("TEACHER, OTHER", "Teacher, Other Classroom"),
        "51072" => array("TEACHER, CAREER & TECH ED BASIC", "Teacher, Career and Technical Education Basic"),
        "51073" => array("TEACHER, TTL I-E", "Teacher, Title I, Elementary"),
        "51074" => array("TEACHER, TTL I-M/J", "Teacher, Title I, Middle/Junior"),
        "51075" => array("TEACHER, RSRCH/CRIT THNK-M/J", "Teacher, Research/Critical Thinking, Middle/Junior"),
        "51076" => array("INTER RESOURCE TEACHER", "Intermediate Resource Teacher"),
        "51077" => array("BILINGUAL SPEC", "Bilingual Specialist"),
        "51078" => array("LAB ASSISTANT", "Lab Assistant"),
        "51079" => array("TUTOR", "Tutor"),
        "51080" => array("SUB TEACH, BASIC", "Substitute Teacher, Basic Program"),
        "51081" => array("ATHLETIC COACH", "Athletic Coach"),
        "51082" => array("INTERPRETER, BASIC", "Interpreter, Basic Instruction"),
        "51083" => array("TEACHER, EXPLOR, M/J", "Teacher, Exploratory and Experiential Education, Middle/Junior High"),
        "51084" => array("TEACHER, READING-E", "Teacher, Reading, Elementary"),
        "51085" => array("TEACHER, READING-M/J", "Teacher, Reading, Middle/Junior High"),
        "51090" => array("TSA, BASIC", "Teacher on Special Assignment, Basic Instruction"),
        "51101" => array("PARAPROFESSIONAL, KG", "Paraprofessional, Kindergarten"),
        "51102" => array("PARAPROFESSIONAL, FIRST", "Paraprofessional, First Grade"),
        "51103" => array("PARAPROFESSIONAL, SECOND", "Paraprofessional, Second Grade"),
        "51104" => array("PARAPROFESSIONAL, THIRD", "Paraprofessional, Third Grade"),
        "51105" => array("PARAPROFESSIONAL, FOURTH", "Paraprofessional, Fourth Grade"),
        "51106" => array("PARAPROFESSIONAL, FIFTH", "Paraprofessional, Fifth Grade"),
        "51107" => array("PARAPROFESSIONAL, SIXTH", "Paraprofessional, Sixth Grade"),
        "51108" => array("PARAPROFESSIONAL, ELEM", "Paraprofessional, Elementary"),
        "51109" => array("PARAPROFESSIONAL, M/J", "Paraprofessional, Middle/Junior High"),
        "51110" => array("PARAPROFESSIONAL, SH", "Paraprofessional, Senior High"),
        "51111" => array("PARAPROFESSIONAL, TTL I-E", "Paraprofessional, Title I, Elementary"),
        "51112" => array("PARAPROFESSIONAL, TTL I-M/J", "Paraprofessional, Title I, Middle/Junior High"),
        "51113" => array("PARAPROFESSIONAL, TTL I-SH", "Paraprofessional, Title I, Senior High"),
        "51114" => array("PARAPROFESSIONAL, OTH BAS", "Paraprofessional, Other Basic Program"),
        "51115" => array("SUB PARAPROFESSIONAL", "Substitute Paraprofessional"),
        "52001" => array("TEACHER, ID", "Teacher, Intellectual Disabilities"),
        "52003" => array("TEACHER, OI", "Teacher, Orthopedically Impaired"),
        "52004" => array("TEACHER, HI", "Teacher, Deaf or Hard of Hearing"),
        "52005" => array("TEACHER, VI", "Teacher, Visually Impaired"),
        "52006" => array("TEACHER, EBD", "Teacher, Emotional/Behavioral Disabilities"),
        "52007" => array("TEACHER, SLD", "Teacher, Specific Learning Disabled"),
        "52008" => array("TEACHER, GIFTED", "Teacher, Gifted"),
        "52009" => array("TEACHER, H/H", "Teacher, Hospital/Homebound"),
        "52010" => array("TEACHER, ASD", "Teacher, Autism Spectrum Disorder"),
        "52013" => array("TEACHER, DSI", "Teacher, Dual-Sensory Impaired"),
        "52014" => array("TEACHER, VE", "Teacher, Varying Exceptionalities"),
        "52015" => array("TEACHER, PK HDC", "Teacher, Prekindergarten Handicapped"),
        "52016" => array("PHYSICAL THERAPIST", "Physical Therapist"),
        "52017" => array("OCC THERAPIST", "Occupational Therapist"),
        "52018" => array("SP/LANG PATH", "Speech and Language Pathologist"),
        "52019" => array("O/M SPECIALIST", "Orientation & Mobility Specialist"),
        "52020" => array("MUSIC THERAPIST", "Music Therapist"),
        "52021" => array("REC THERAPIST", "Recreation Therapist"),
        "52022" => array("JOB COACH, ESE", "Job Coach, Exceptional Student Education"),
        "52027" => array("TUTOR COMP/ATTEND", "Tutor Companion/Attendant"),
        "52028" => array("INTERPRETER", "Interpreter, Exceptional Student Education"),
        "52029" => array("ART SPEC", "Art Specialist"),
        "52030" => array("PT ASST, LIC", "Physical Therapist Assistant, Licensed"),
        "52031" => array("OT ASST, LIC", "Occupational Therapy Assistant, Licensed"),
        "52032" => array("SP THERAPY AIDE", "Speech Therapy Aide"),
        "52033" => array("TEACHER, ADAPTIVE PE", "Teacher, Adaptive Physical Education"),
        "52034" => array("TEACHER, TBI", "Teacher, Traumatic Brain Injury"),
        "52050" => array("PARAPROFESSIONAL, EX ST ED", "Paraprofessional, Exceptional Student Education"),
        "52051" => array("BUS AIDE, EX ST ED", "Bus Aide, Exceptional Student Education"),
        "52052" => array("SELF-CARE AIDE, EX ST ED", "Self-Care Aide, Exceptional Student Education"),
        "52053" => array("PARAPROFESSIONAL, ESE, AGES 0-2", "Paraprofessional, Exceptional Student Education, Ages 0-2"),
        "52054" => array("PARAPROFESSIONAL, ESE, AGES 3-5", "Paraprofessional, Exceptional Student Education, Ages 3-5"),
        "52055" => array("PARAPROFESSIONAL, ESE, AGES 6-21", "Paraprofessional, Exceptional Student Education, Ages 6-21"),
        "52080" => array("SUB TEACH, EX ST ED", "Substitute Teacher, Exceptional Student Education"),
        "52090" => array("TSA, EX ST ED", "Teacher on Special Assignment, Exceptional Student Education"),
        "53001" => array("TEACHER, AGRI/NRE", "Teacher, Agribusiness/Natural Resources Education"),
        "53002" => array("TEACHER, BUSINESS ED", "Teacher, Business Technology Education"),
        "53003" => array("TEACHER, DIVERS ED", "Teacher, Diversified Education"),
        "53004" => array("TEACHER, HEALTH ED", "Teacher, Health Science Education"),
        "53005" => array("TEACHER, FAM/CONS", "Teacher, Family and Consumer Sciences"),
        "53006" => array("TEACHER, TECH ED", "Teacher, Technology Education"),
        "53007" => array("TEACHER, IND ED", "Teacher, Industrial Education"),
        "53008" => array("TEACHER, MARKETING ED", "Teacher, Marketing Education"),
        "53009" => array("TEACHER, PUB SER", "Teacher, Public Service Education"),
        "53010" => array("TEACHER, CAREER & TECH ED OR/EXP", "Teacher, Career and Technical Education Orientation/Exploration"),
        "53011" => array("TEACHER, CAREER & TECH ED-ISS", "Teacher, Career and Technical Education Instructional Support Services"),
        "53012" => array("TEACHER, SAIL", "Teacher, System for Applied Individualized Learning (SAIL)"),
        "53013" => array("WORK-STUDY COOR", "Work-Study Coordinator"),
        "53014" => array("TEACHER, OTH CAREER & TECH ED", "Teacher, Experiential Education, Senior High"),
        "53050" => array("PARAPROFESSIONAL, CAREER & TECH ED", "Paraprofessional, Career and Technical Education"),
        "53080" => array("SUB TEACH, CAREER & TECH ED", "Substitute Teacher, Career and Technical Education"),
        "53090" => array("TSA, CAREER & TECH ED", "Teacher on Special Assignment, Career and Technical Education"),
        "54001" => array("TEACHER, ADULT ED", "Teacher, Adult Education"),
        "54050" => array("PARAPROFESSIONAL, ADULT ED", "Paraprofessional, Adult Education"),
        "54080" => array("SUB TEACH, ADULT ED", "Substitute Teacher, Adult Education"),
        "54090" => array("TSA, ADULT ED", "Teacher on Special Assignment, Adult Education"),
        "55051" => array("PARAPROFESSIONAL, PK", "Paraprofessional, Prekindergarten"),
        "55052" => array("TEACHER, PK", "Teacher, Self Contained, Prekindergarten"),
        "55080" => array("SUB TEACH, PK", "Substitute Teacher, Prekindergarten"),
        "59001" => array("TEACHER, OTH INS", "Teacher, Other Instruction"),
        "59050" => array("PARAPROFESSIONAL, OTH INS", "Paraprofessional, Other Instruction"),
        "59080" => array("SUB TEACH, OTH INS", "Substitute Teacher, Other Instruction"),
        "59090" => array("TSA, OTH INS", "Teacher on Special Assignment, Other Instruction"),
        "61001" => array("DEP SUPER, PPS", "Deputy Superintendent, Pupil Personnel Services"),
        "61002" => array("ASSOC SUPER, PPS", "Associate Superintendent, Pupil Personnel Services"),
        "61003" => array("ASST SUPER, PPS", "Assistant/Area Superintendent, Pupil Personnel Services"),
        "61004" => array("EXEC DIR, PPS", "Executive/General Director, Pupil Personnel Services"),
        "61005" => array("DIR, PPS", "Director, Pupil Personnel Services"),
        "61006" => array("ASST DIR, PPS", "Assistant Director, Pupil Personnel Services"),
        "61007" => array("SUP, PPS", "Supervisor, Pupil Personnel Services"),
        "61008" => array("COOR, PPS", "Coordinator, Pupil Personnel Services"),
        "61009" => array("ADMIN SA, PPS", "Administrator on Special Assignment, Pupil Personnel Services"),
        "61010" => array("SPEC, PPS", "Specialist/Manager, Pupil Personnel Services"),
        "61011" => array("ADMIN ASST, PPS", "Administrative Assistant, Pupil Personnel Services"),
        "61012" => array("TSA, PPS", "Teacher on Special Assignment, Pupil Personnel Services"),
        "61020" => array("STUDENT SER WORKER", "Student Services Worker"),
        "61021" => array("SCH RES OFFICER", "School Resource Officer"),
        "61022" => array("PARENT ED SPEC", "Parent Education Specialist"),
        "61023" => array("RECRUITER, MIG ED", "Recruiter, Migrant Education"),
        "61024" => array("DROPOUT PREV SPEC", "Dropout Prevention Specialist"),
        "61025" => array("CHILD FIND SPEC", "Child Find Specialist"),
        "61026" => array("DIAGNOSTIC SPEC", "Diagnostic Specialist"),
        "61040" => array("RESIDENT SUP", "Residential Supervisor"),
        "61041" => array("ASST RESIDENT SUP", "Assistant Residential Supervisor"),
        "61042" => array("RESIDENT INSTRUC", "Residential Instructor"),
        "61043" => array("ASST RESIDENT INST", "Assistant Residential Instructor"),
        "61090" => array("EXEC SEC, PPS", "Executive Secretary, Pupil Personnel Services"),
        "61091" => array("SEC, PPS", "Secretary, Pupil Personnel Services"),
        "61092" => array("CLERK TYP, PPS", "Clerk Typist, Pupil Personnel Services"),
        "61093" => array("CLERK, PPS", "Clerk, Pupil Personnel Services"),
        "61094" => array("OFF AIDE, PPS", "Office Aide, Pupil Personnel Services"),
        "61095" => array("RECEP, PPS", "Receptionist, Pupil Personnel Services"),
        "61096" => array("DATA ENT OP, PPS", "Data Entry Operator, Pupil Personnel Services"),
        "61097" => array("BOOKKEEPER, PPS", "Bookkeeper, Pupil Personnel Services"),
        "61098" => array("MESSENGER, PPS", "Messenger/Deliveryman, Pupil Personnel Services"),
        "61099" => array("OTH CLER, PPS", "Other Clerical Staff, Pupil Personnel Services"),
        "61101" => array("DEP SUPER, ATT/SW", "Deputy Superintendent, Attendance/Social Work"),
        "61102" => array("ASSOC SUPER, ATT/SW", "Associate Superintendent, Attendance/Social Work"),
        "61103" => array("ASST SUPER, ATT/SW", "Assistant/Area Superintendent, Attendance/Social Work"),
        "61104" => array("EXEC DIR, ATT/SW", "Executive/General Director, Attendance/Social Work"),
        "61105" => array("DIR, ATT/SW", "Director, Attendance/Social Work"),
        "61106" => array("ASST DIR, ATT/SW", "Assistant Director, Attendance/Social Work"),
        "61107" => array("SUP, ATT/SW", "Supervisor, Attendance/Social Work"),
        "61108" => array("COOR, ATT/SW", "Coordinator, Attendance/Social Work"),
        "61109" => array("ADMIN SA, ATT/SW", "Administrator on Special Assignment, Attendance/Social Work"),
        "61110" => array("SPEC, ATT/SW", "Specialist/Manager, Attendance/Social Work"),
        "61111" => array("ADMIN ASST, ATT/SW", "Administrative Assistant, Attendance/Social Work"),
        "61112" => array("TSA, ATT/SW", "Teacher on Special Assignment, Attendance/Social Work"),
        "61119" => array("DIR, ATTENDANCE", "Director, Attendance"),
        "61120" => array("DIR, SOCIAL WORK", "Director, Social Work"),
        "61121" => array("SUP, ATTENDANCE", "Supervisor, Attendance"),
        "61122" => array("SUP, SOCIAL WORK", "Supervisor, Social Work"),
        "61123" => array("COOR, ATTENDANCE", "Coordinator, Attendance"),
        "61124" => array("COOR, SOCIAL WORK", "Coordinator, Social Work"),
        "61130" => array("ATTENDANCE ASST", "Attendance Assistant/Truancy Officer"),
        "61131" => array("SCH SOC WK", "School Social Worker"),
        "61190" => array("EXEC SEC, ATT/SW", "Executive Secretary, Attendance/Social Work"),
        "61191" => array("SEC, ATT/SW", "Secretary, Attendance/Social Work"),
        "61192" => array("CLERK TYP, ATT/SW", "Clerk Typist, Attendance/Social Work"),
        "61193" => array("CLERK, ATT/SW", "Clerk, Attendance/Social Work"),
        "61194" => array("OFF AIDE, ATT/SW", "Office Aide, Attendance/Social Work"),
        "61195" => array("RECEP, ATT/SW", "Receptionist, Attendance/Social Work"),
        "61196" => array("DATA ENT OP, ATT/SW", "Data Entry Operator, Attendance/Social Work"),
        "61197" => array("BOOKKEEPER, ATT/SW", "Bookkeeper, Attendance/Social Work"),
        "61198" => array("MESSENGER, ATT/SW", "Messenger/Deliveryman, Attendance/Social Work"),
        "61199" => array("OTH CLER, ATT/SW", "Other Clerical Staff, Attendance/Social Work"),
        "61201" => array("DEP SUPER, GUIDANCE", "Deputy Superintendent, Guidance Services"),
        "61202" => array("ASSOC SUPER, GUIDANCE", "Associate Superintendent, Guidance Services"),
        "61203" => array("ASST SUPER, GUIDANCE", "Assistant/Area Superintendent, Guidance Services"),
        "61204" => array("EXEC DIR, GUIDANCE", "Executive/General Director, Guidance Services"),
        "61205" => array("DIR, GUIDANCE", "Director, Guidance Services"),
        "61206" => array("ASST DIR, GUIDANCE", "Assistant Director, Guidance Services"),
        "61207" => array("SUP, GUIDANCE", "Supervisor, Guidance Services"),
        "61208" => array("COOR, GUIDANCE", "Coordinator, Guidance Services"),
        "61209" => array("ADMIN SA, GUIDANCE", "Administrator on Special Assignment, Guidance Services"),
        "61210" => array("SPEC, GUIDANCE", "Specialist/Manager, Guidance Services"),
        "61211" => array("ADMIN ASST, GUIDANCE", "Administrative Assistant, Guidance Services"),
        "61212" => array("TSA, GUIDANCE", "Teacher on Special Assignment, Guidance Services"),
        "61219" => array("DIR, CAREER ED", "Director, Career Education"),
        "61220" => array("SUP, CAREER ED", "Supervisor, Career Education"),
        "61221" => array("COOR, CAREER ED", "Coordinator, Career Education"),
        "61222" => array("DIR, ELEM GUIDANCE", "Director, Elementary Guidance"),
        "61223" => array("SUP, ELEM GUIDANCE", "Supervisor, Elementary Guidance"),
        "61224" => array("COOR, ELEM GUIDANCE", "Coordinator, Elementary Guidance"),
        "61225" => array("DIR, SECON GUIDANCE", "Director, Secondary Guidance"),
        "61226" => array("SUP, SECON GUIDANCE", "Supervisor, Secondary Guidance"),
        "61227" => array("COOR, SECON GUIDANCE", "Coordinator, Secondary Guidance"),
        "61228" => array("DIR, OCC/PL SER", "Director, Occupational and Placement Services"),
        "61229" => array("SUP, OCC/PL SER", "Supervisor, Occupational and Placement Services"),
        "61230" => array("COOR, OCC/PL SER", "Coordinator, Occupational and Placement Services"),
        "61231" => array("COUNSELOR-E", "Counselor, Elementary School"),
        "61232" => array("COUNSELOR-M/J", "Counselor, Middle/Junior High"),
        "61233" => array("COUNSELOR-SH", "Counselor, Senior High School"),
        "61234" => array("COUNSELOR-ADULT/CAREER & TECH ED", "Counselor, Adult/Career and Technical Education School"),
        "61235" => array("COUNSELOR-EX ED", "Counselor, Exceptional Education School"),
        "61236" => array("COUNSELOR-OTHER SCH", "Counselor, Other Type School"),
        "61237" => array("COUNSELOR-CAREER ED", "Counselor, Career Education"),
        "61238" => array("CAREER SPEC", "Career Specialist"),
        "61239" => array("JOB DEV COUNSELOR", "Job Development Counselor"),
        "61290" => array("EXEC SEC, GUIDANCE", "Executive Secretary, Guidance Services"),
        "61291" => array("SEC, GUIDANCE", "Secretary, Guidance Services"),
        "61292" => array("CLERK TYP, GUIDANCE", "Clerk Typist, Guidance Services"),
        "61293" => array("CLERK, GUIDANCE", "Clerk, Guidance Services"),
        "61294" => array("OFF AIDE, GUIDANCE", "Office Aide, Guidance Services"),
        "61295" => array("RECEP, GUIDANCE", "Receptionist, Guidance Services"),
        "61296" => array("DATA ENT OP, GUIDANCE", "Data Entry Operator, Guidance Services"),
        "61297" => array("BOOKKEEPER, GUIDANCE", "Bookkeeper, Guidance Services"),
        "61298" => array("MESSENGER, GUIDANCE", "Messenger/Deliveryman, Guidance Services"),
        "61299" => array("OTH CLER, GUIDANCE", "Other Clerical Staff, Guidance Services"),
        "61301" => array("DEP SUPER, HEALTH SER", "Deputy Superintendent, Health Services"),
        "61302" => array("ASSOC SUPER, HEALTH SER", "Associate Superintendent, Health Services"),
        "61303" => array("ASST SUPER, HEALTH SER", "Assistant/Area Superintendent, Health Services"),
        "61304" => array("EXEC DIR, HEALTH SER", "Executive/General Director, Health Services"),
        "61305" => array("DIR, HEALTH SER", "Director, Health Services"),
        "61306" => array("ASST DIR, HEALTH SER", "Assistant Director, Health Services"),
        "61307" => array("SUP, HEALTH SER", "Supervisor, Health Services"),
        "61308" => array("COOR, HEALTH SER", "Coordinator, Health Services"),
        "61309" => array("ADMIN SA, HEALTH SER", "Administrator on Special Assignment, Health Services"),
        "61310" => array("SPEC, HEALTH SER", "Specialist/Manager, Health Services"),
        "61311" => array("ADMIN ASST, HEALTH SER", "Administrative Assistant, Health Services"),
        "61312" => array("TSA, HEALTH SER", "Teacher on Special Assignment, Health Services"),
        "61320" => array("NURSE, RN", "Nurse, Registered (RN)"),
        "61321" => array("NURSE, LPN", "Nurse, Licensed Practical (LPN)"),
        "61322" => array("DOCTOR", "Doctor"),
        "61323" => array("DENTIST", "Dentist"),
        "61324" => array("DENTAL ASST/ORAL HYG", "Dental Assistant/Oral Hygienist"),
        "61325" => array("NURSE ASST", "Nurse's Assistant"),
        "61326" => array("NUTRITION SPEC", "Nutritional Specialist"),
        "61327" => array("SUBSTANCE ABUSE COOR", "Substance Abuse Coordinator"),
        "61328" => array("COMMUNITY HEALTH ADV", "Community Health Advocate"),
        "61329" => array("PHARMACY AIDE", "Pharmacy Aide"),
        "61330" => array("CLINIC ATTENDANT", "Clinic Attendant/Health Aide"),
        "61331" => array("HEALTH SER TRAINER", "Health Services Trainer"),
        "61332" => array("AUDIOLOGIST", "Audiologist"),
        "61390" => array("EXEC SEC, HEALTH SER", "Executive Secretary, Health Services"),
        "61391" => array("SEC, HEALTH SER", "Secretary, Health Services"),
        "61392" => array("CLERK TYP, HEALTH SER", "Clerk Typist, Health Services"),
        "61393" => array("CLERK, HEALTH SER", "Clerk, Health Services"),
        "61394" => array("OFF AIDE, HEALTH SER", "Office Aide, Health Services"),
        "61395" => array("RECEP, HEALTH SER", "Receptionist, Health Services"),
        "61396" => array("DATA ENT OP, HEALTH SER", "Data Entry Operator, Health Services"),
        "61397" => array("BOOKKEEPER, HEALTH SER", "Bookkeeper, Health Services"),
        "61398" => array("MESSENGER, HEALTH SER", "Messenger/Deliveryman, Health Services"),
        "61399" => array("OTH CLER, HEALTH SER", "Other Clerical Staff, Health Services"),
        "61401" => array("DEP SUPER, PSYCH SER", "Deputy Superintendent, Psychological Services"),
        "61402" => array("ASSOC SUPER, PSYCH SER", "Associate Superintendent, Psychological Services"),
        "61403" => array("ASST SUPER, PSYCH SER", "Assistant/Area Superintendent, Psychological Services"),
        "61404" => array("EXEC DIR, PSYCH SER", "Executive/General Director, Psychological services"),
        "61405" => array("DIR, PSYCH SER", "Director, Psychological Services"),
        "61406" => array("ASST DIR, PSYCH SER", "Assistant Director, Psychological Services"),
        "61407" => array("SUP, PSYCH SER", "Supervisor, Psychological Services"),
        "61408" => array("COOR, PSYCH SER", "Coordinator, Psychological Services"),
        "61409" => array("ADMIN SA, PSYCH SER", "Administrator on Special Assignment, Psychological Services"),
        "61410" => array("SPEC, PSYCH SER", "Specialist/Manager, Psychological Services"),
        "61411" => array("ADMIN ASST, PSYCH SER", "Administrative Assistant, Psychological Services"),
        "61412" => array("TSA, PSYCH SER", "Teacher on Special Assignment, Psychological Services"),
        "61420" => array("SCH PSYCHOLOGIST", "School Psychologist"),
        "61421" => array("PSYCHOMETRIST", "Psychometrist"),
        "61490" => array("EXEC SEC, PSYCH SER", "Executive Secretary, Psychological Services"),
        "61491" => array("SEC, PSYCH SER", "Secretary, Psychological Services"),
        "61492" => array("CLERK TYP, PSYCH SER", "Clerk Typist, Psychological Services"),
        "61493" => array("CLERK, PSYCH SER", "Clerk, Psychological Services"),
        "61494" => array("OFF AIDE, PSYCH SER", "Office Aide, Psychological Services"),
        "61495" => array("RECEP, PSYCH SER", "Receptionist, Psychological Services"),
        "61496" => array("DATA ENT OP, PSYCH SER", "Data Entry Operator, Psychological Services"),
        "61497" => array("BOOKKEEPER, PSYCH SER", "Bookkeeper, Psychological Services"),
        "61498" => array("MESSENGER, PSYCH SER", "Messenger/Deliveryman, Psychological Services"),
        "61499" => array("OTH CLER, PSYCH SER", "Other Clerical Staff, Psychological Services"),
        "62001" => array("DEP SUPER, MEDIA", "Deputy Superintendent, Instructional Media"),
        "62002" => array("ASSOC SUPER, MEDIA", "Associate Superintendent, Instructional Media"),
        "62003" => array("ASST SUPER, MEDIA", "Assistant/Area Superintendent, Instructional Media"),
        "62004" => array("EXEC DIR, MEDIA", "Executive/General Director, Instructional Media"),
        "62005" => array("DIR, MEDIA", "Director, Instructional Media"),
        "62006" => array("ASST DIR, MEDIA", "Assistant Director, Instructional Media"),
        "62007" => array("SUP, MEDIA", "Supervisor, Instructional Media"),
        "62008" => array("COOR, MEDIA", "Coordinator, Instructional Media"),
        "62009" => array("ADMIN SA, MEDIA", "Administrator on Special Assignment, Instructional Media Services"),
        "62010" => array("SPEC, MEDIA", "Specialist/Manager, Instructional Media Services"),
        "62011" => array("ADMIN ASST, MEDIA", "Administrative Assistant, Instructional Media Services"),
        "62012" => array("TSA, MEDIA", "Teacher on Special Assignment, Instructional Media Services"),
        "62013" => array("MANAGER, MEDIA", "Manager, Instructional Media Services"),
        "62014" => array("FOREMAN, MEDIA", "Foreman, Instructional Media Services"),
        "62015" => array("LEAD WK, MEDIA", "Lead Worker, Instructional Media Services"),
        "62016" => array("DIR, TEXTBOOKS", "Director, Textbooks"),
        "62017" => array("COOR, TEXTBOOKS", "Coordinator, Textbooks"),
        "62018" => array("SUP, TEXTBOOKS", "Supervisor, Textbooks"),
        "62019" => array("DIR, LIB/MEDIA", "Director, Library/Media Services"),
        "62020" => array("COOR, LIB/MEDIA", "Coordinator, Library/Media Services"),
        "62021" => array("SUP, LIB/MEDIA", "Supervisor, Library/Media Services"),
        "62022" => array("INS TV PROG SPEC", "Instructional Television Program Specialist"),
        "62030" => array("LIB/MED SPEC-E", "School Librarian/Media Specialist, Elementary School"),
        "62031" => array("LIB/MED SPEC-M/J", "School Librarian/Media Specialist, Middle/Junior High"),
        "62032" => array("LIB/MED SPEC-SH", "School Librarian/Media Specialist, Senior High"),
        "62033" => array("LIB/MED SPEC-AD/CAREER & TECH ED", "School Librarian/Media Specialist, Career and Technical Education/Adult School"),
        "62034" => array("LIB/MED SPEC-OTHER SCH", "School Librarian/Media Specialist, Other Type School"),
        "62035" => array("LIB/MEDIA SPEC-DIST", "Librarian/Media Specialist, District Office"),
        "62040" => array("LIB/MEDIA AIDE", "Library/Media Aide"),
        "62077" => array("LIB TECH ASST", "Library Technical Assistant"),
        "62078" => array("FILM TECH", "Film Technician"),
        "62079" => array("INS EQUIP REPAIR MAN", "Instructional Equipment Repair Manager"),
        "62080" => array("AV TECHNICIAN", "Audio Visual Technician"),
        "62081" => array("ELECTRONICS, MEDIA", "Electronics Technician, Instructional Media"),
        "62082" => array("MEDIA TECHNICIAN", "Media Technician"),
        "62083" => array("SOFTWARE TECH", "Software Support Technician"),
        "62084" => array("OTH MEDIA STAFF", "Other Instructional Media Staff"),
        "62090" => array("EXEC SEC, MEDIA", "Executive Secretary, Instructional Media Services"),
        "62091" => array("SEC, MEDIA", "Secretary, Instructional Media Services"),
        "62092" => array("CLERK TYP, MEDIA", "Clerk Typist, Instructional Medial Services"),
        "62093" => array("CLERK, MEDIA", "Clerk, Instructional Media Services"),
        "62094" => array("OFF AIDE, MEDIA", "Office Aide, Instructional Media Services"),
        "62095" => array("RECEP, MEDIA", "Receptionist, Instructional Media Services"),
        "62096" => array("DATA ENT OP, MEDIA", "Data Entry Operator, Instructional Media Services"),
        "62097" => array("BOOKKEEPER, MEDIA", "Bookkeeper, Instructional Media Services"),
        "62098" => array("MESSENGER, MEDIA", "Messenger/Deliveryman, Instructional Media Services"),
        "62099" => array("OTH CLER, MEDIA", "Other Clerical Staff, Instructional Media Services"),
        "63001" => array("DEP SUPER, INS/CUR", "Deputy Superintendent, Instruction/Curriculum"),
        "63002" => array("ASSOC SUPER, INS/CUR", "Associate Superintendent, Instruction/Curriculum"),
        "63003" => array("ASST SUPER, INS/CUR", "Assistant/Area Superintendent, Instruction/Curriculum"),
        "63004" => array("EXEC DIR, INS/CUR", "Executive/General Director, Instruction/Curriculum"),
        "63005" => array("DIR, INS/CUR", "Director, Instruction/Curriculum"),
        "63006" => array("ASST DIR, INS/CUR", "Assistant Director, Instruction/Curriculum"),
        "63007" => array("SUP, INS/CUR", "Supervisor, Instruction/Curriculum"),
        "63008" => array("COOR, INS/CUR", "Coordinator, Instruction/Curriculum"),
        "63009" => array("ADMIN SA, INS/CUR", "Administrator on Special Assignment, Instruction/Curriculum"),
        "63010" => array("SPEC, INS/CUR", "Specialist/Manager, Instruction/Curriculum"),
        "63011" => array("ADMIN ASST, INS/CUR", "Administrative Assistant, Instruction/Curriculum"),
        "63012" => array("TSA, INS/CUR", "Teacher on Special Assignment, Instruction/Curriculum"),
        "63016" => array("DIR, ELEM ED", "Director, Elementary Education"),
        "63017" => array("DIR, MIDDLE/JR", "Director, Middle/Junior High Education"),
        "63018" => array("DIR, SECON ED", "Director, Secondary Education"),
        "63019" => array("DIR, CAREER & TECH ED", "Director, Career and Technical Education"),
        "63020" => array("DIR, EX ST ED", "Director, Exceptional Student Education"),
        "63021" => array("DIR, ADULT ED", "Director, Adult Education"),
        "63022" => array("DIR, TTL I PROG", "Director, Title I Programs"),
        "63023" => array("DIR, FED PROG", "Director, Federal Programs"),
        "63024" => array("SUP/COOR, DANCE", "Supervisor/Coordinator, Dance"),
        "63025" => array("SUP/COOR, DRAMA", "Supervisor/Coordinator, Drama"),
        "63026" => array("SUP/COOR, WORLD LANG", "Supervisor/Coordinator, World Language"),
        "63027" => array("SUP/COOR, HEALTH", "Supervisor/Coordinator, Health"),
        "63028" => array("SUP/COOR, LANG ARTS", "Supervisor/Coordinator, Language Arts"),
        "63029" => array("SUP/COOR, LIB/MEDIA", "Supervisor/Coordinator, Library/Media"),
        "63030" => array("SUP/COOR, MATH", "Supervisor/Coordinator, Mathematics"),
        "63031" => array("SUP/COOR, MUSIC", "Supervisor/Coordinator, Music"),
        "63032" => array("SUP/COOR, PE", "Supervisor/Coordinator, Physical Education"),
        "63033" => array("SUP/COOR, COMP ED", "Supervisor/Coordinator, Remedial/Compensatory Education"),
        "63034" => array("SUP/COOR, ROTC", "Supervisor/Coordinator, ROTC"),
        "63035" => array("SUP/COOR, DRIVER ED", "Supervisor/Coordinator, Safety/Driver Education"),
        "63036" => array("SUP/COOR, SCIENCE", "Supervisor/Coordinator, Science"),
        "63037" => array("SUP/COOR, SOC ST", "Supervisor/Coordinator, Social Studies"),
        "63038" => array("SUP/COOR, ADULT ED", "Supervisor/Coordinator, Adult Education"),
        "63039" => array("SUP/COOR, OTH ED SER", "Supervisor/Coordinator, Other Educational Services"),
        "63040" => array("SUP/COOR, EX ED", "Supervisor/Coordinator, Exceptional Education"),
        "63041" => array("SUP/COOR, ID", "Supervisor/Coordinator, Intellectual Disabilities"),
        "63043" => array("SUP/COOR, PI", "Supervisor/Coordinator, Physically Impaired"),
        "63044" => array("SUP/COOR, PT/OT", "Supervisor/Coordinator, Physical and Occupational Therapy"),
        "63045" => array("SUP/COOR, SP/LANG", "Supervisor/Coordinator, Speech & Language Impaired"),
        "63046" => array("SUP/COOR, HI", "Supervisor/Coordinator, Deaf or Hard of Hearing"),
        "63047" => array("SUP/COOR, VI", "Supervisor/Coordinator, Visually Impaired"),
        "63048" => array("SUP/COOR, SLD", "Supervisor/Coordinator, Specific Learning Disabled"),
        "63049" => array("SUP/COOR, GIFTED", "Supervisor/Coordinator, Gifted"),
        "63050" => array("SUP/COOR, H/H", "Supervisor/Coordinator, Hospital/Homebound"),
        "63051" => array("SUP/COOR, ASD", "Supervisor/Coordinator, Autism Spectrum Disorder"),
        "63052" => array("SUP/COOR, DSI", "Supervisor/Coordinator, Dual-Sensory Impaired"),
        "63055" => array("SUP/COOR, VE", "Supervisor/Coordinator, Varying Exceptionalities"),
        "63056" => array("SUP/COOR, PK HDC", "Supervisor/Coordinator, Prekindergarten Handicapped"),
        "63057" => array("SUP/COOR, OTH ESE", "Supervisor/Coordinator, Other ESE Programs"),
        "63058" => array("SUP/COOR, STAFFING", "Supervisor/Coordinator, Staffing & Admissions"),
        "63059" => array("SUP/COOR, CAREER & TECH ED", "Supervisor/Coordinator, Career and Technical Education"),
        "63060" => array("SUP/COOR, AGRI/NRE", "Supervisor/Coordinator, Agribusiness Natural Resources Education"),
        "63061" => array("SUP/COOR, BUSINESS ED", "Supervisor/Coordinator, Business Technology Education"),
        "63062" => array("SUP/COOR, DIVERS OCC ED", "Supervisor/Coordinator, Diversified Education"),
        "63063" => array("SUP/COOR, HEALTH OCC ED", "Supervisor/Coordinator, Health Science Education"),
        "63064" => array("SUP/COOR, HOME EC ED", "Supervisor/Coordinator, Family and Consumer Science"),
        "63065" => array("SUP/COOR, TECH ED", "Supervisor/Coordinator, Technology Education"),
        "63066" => array("SUP/COOR, IND ED", "Supervisor/Coordinator, Industrial Education"),
        "63067" => array("SUP/COOR, MARKETING ED", "Supervisor/Coordinator, Marketing Education"),
        "63068" => array("SUP/COOR, PUB SER OCC ED", "Supervisor/Coordinator, Public Service Education"),
        "63069" => array("SUP/COOR, CAREER & TECH ED OR/EXP", "Supervisor/Coordinator, Career and Technical Education Orientation/Exploration"),
        "63070" => array("SUP/COOR, OTH CAREER & TECH ED", "Supervisor/Coordinator, Other Career and Technical Education Programs"),
        "63071" => array("SUP/COOR, CAREER & TECH ED-ISS", "Supervisor/Coordinator, Career and Technical Education Instructional Support Ser"),
        "63072" => array("SUP/COOR, MIGRANT ED", "Supervisor/Coordinator, Migrant Education"),
        "63073" => array("SUP/COOR, TTL I", "Supervisor/Coordinator, Title I"),
        "63074" => array("SUP/COOR, VOLUNTEER", "Supervisor/Coordinator, Volunteer Program"),
        "63075" => array("SUP/COOR, PREP", "Supervisor/Coordinator, PREP"),
        "63076" => array("SUP/COOR, EARLY CH", "Supervisor/Coordinator, Early Childhood Education"),
        "63077" => array("PROJECT COOR", "Project Coordinator"),
        "63078" => array("ASST PROJECT COOR", "Assistant Project Coordinator"),
        "63079" => array("SUP/COOR, ART", "Supervisor/Coordinator, Art"),
        "63080" => array("SUP/COOR, COMPU ED", "Supervisor/Coordinator, Computer Education"),
        "63081" => array("SUP/COOR, EBD", "Supervisor/Coordinator, Emotional/Behavioral Disabilities"),
        "63082" => array("SUP/COOR, DROPOUT PV", "Supervisor/Coordinator, Dropout Prevention"),
        "63083" => array("COOR, COMM ED", "Coordinator, Community Education"),
        "63084" => array("SUP/COOR, ELEM ED", "Supervisor/Coordinator, Elementary Education"),
        "63085" => array("SUP/COOR, MIDDLE/JR", "Supervisor/Coordinator, Middle/Junior High Education"),
        "63086" => array("SUP/COOR, SECON ED", "Supervisor/Coordinator, Secondary Education"),
        "63087" => array("SUP/COOR, BILINGUAL", "Supervisor/Coordinator, Bilingual Education"),
        "63090" => array("EXEC SEC, INS/CUR", "Executive Secretary, Instruction/Curriculum Development Services"),
        "63091" => array("SEC, INS/CUR", "Secretary, Instruction/Curriculum Development Services"),
        "63092" => array("CLERK TYP, INS/CUR", "Clerk Typist, Instruction/Curriculum Development Services"),
        "63093" => array("CLERK, INS/CUR", "Clerk, Instruction/Curriculum"),
        "63094" => array("OFF AIDE, INS/CUR", "Office Aide, Instruction/Curriculum Development Services"),
        "63095" => array("RECEP, INS/CUR", "Receptionist, Instruction/Curriculum Development Services"),
        "63096" => array("DATA ENT OP, INS/CUR", "Data Entry Operator, Instruction/Curriculum"),
        "63097" => array("BOOKKEEPER, INS/CUR", "Bookkeeper, Instruction/Curriculum"),
        "63098" => array("MESSENGER, INS/CUR", "Messenger/Deliveryman, Instruction/Curriculum"),
        "63099" => array("OTH CLER, INS/CUR", "Other Clerical Staff, Instruction/Curriculum"),
        "63100" => array("PRIMARY SPEC", "Primary Specialist"),
        "63101" => array("PROGRAM SPEC", "Program Specialist"),
        "63102" => array("STAFFING SPEC", "Staffing Specialist"),
        "63103" => array("LEARN RES SPEC", "Learning Resource Specialist"),
        "63104" => array("TECHNOLOGY SPEC", "Technology Specialist"),
        "63105" => array("BEHAVIOR SPEC", "Behavior Specialist"),
        "63106" => array("DIR, COMM ED", "Director, Community Education"),
        "63107" => array("SUP/COOR, HEAD START", "Supervisor/Coordinator, Head Start"),
        "64001" => array("DEP SUPER, ST TRNG", "Deputy Superintendent, Instructional Staff Training Services"),
        "64002" => array("ASSOC SUPER, ST TRNG", "Associate Superintendent, Instructional Staff Training Services"),
        "64003" => array("ASST SUPER, ST TRNG", "Assistant/Area Superintendent, Instructional Staff Training Services"),
        "64004" => array("EXEC DIR, ST TRNG", "Executive/General Director, Instructional Staff Training Services"),
        "64005" => array("DIR, ST TRNG", "Director, Instructional Staff Training Services"),
        "64006" => array("ASST DIR, ST TRNG", "Assistant Director, Instructional Staff Training Services"),
        "64007" => array("SUP, ST TRNG", "Supervisor, Instructional Staff Training Services"),
        "64008" => array("COOR, ST TRNG", "Coordinator, Instructional Staff Training Services"),
        "64009" => array("ADMIN SA, ST TRNG", "Administrator on Special Assignment, Instructional Staff Training Services"),
        "64010" => array("SPEC, ST TRNG", "Specialist/Manager, Instructional Staff Training Services"),
        "64011" => array("ADMIN ASST, ST TRNG", "Administrative Assistant, Instructional Staff Training Services"),
        "64012" => array("TSA, ST TRNG", "Teacher on Special Assignment, Instructional Staff Training Services"),
        "64016" => array("DIR, TEC", "Director, Teacher Education Center"),
        "64017" => array("SUP/COOR, TEC", "Supervisor/Coordinator, Teacher Education Center"),
        "64018" => array("DIR, PROF ORIEN PROG", "Director, Professional Orientation Program"),
        "64019" => array("SUP/COOR, PROF ORIEN PROG", "Supervisor/Coordinator, Professional Orientation Program"),
        "64020" => array("TRAINER, INS", "Trainer, Instructional"),
        "64021" => array("RDG COACH-E", "Reading Coach, Elementary"),
        "64022" => array("RDG COACH-M/J", "Reading Coach, Middle/Junior"),
        "64023" => array("RDG COACH-SH", "Reading Coach, Senior High"),
        "64024" => array("MATH COACH-E", "Math Coach, Elementary"),
        "64025" => array("MATH COACH-M/J", "Math Coach, Middle/Junior"),
        "64026" => array("MATH COACH-SH", "Math Coach, Senior High"),
        "64090" => array("EXEC SEC, ST TRNG", "Executive Secretary, Instructional Staff Training Services"),
        "64091" => array("SEC, ST TRNG", "Secretary, Instructional Staff Training Services"),
        "64092" => array("CLERK TYP, ST TRNG", "Clerk Typist, Instructional Staff Training Services"),
        "64093" => array("CLERK, ST TRNG", "Clerk, Instructional Staff Training Services"),
        "64094" => array("OFF AIDE, ST TRNG", "Office Aide, Instructional Staff Training Services"),
        "64095" => array("RECEP, ST TRNG", "Receptionist, Instructional Staff Training Services"),
        "64096" => array("DATA ENT OP, ST TRNG", "Data Entry Operator, Instructional Staff Training Services"),
        "64097" => array("BOOKKEEPER, ST TRNG", "Bookkeeper, Instructional Staff Training Services"),
        "64098" => array("MESSENGER, ST TRNG", "Messenger/Deliveryman, Instructional Staff Training Services"),
        "64099" => array("OTH CLER, ST TRNG", "Other Clerical Staff, Instructional Staff Training Services"),
        "65001" => array("DEP SUPER, IT", "Deputy Superintendent, Instructional Technology"),
        "65002" => array("ASSOC SUPER, IT", "Associate Superintendent, Instructional Technology"),
        "65003" => array("ASST SUPER, IT", "Assistant/Area Superintendent, Instructional Technology"),
        "65004" => array("EXEC DIR, IT", "Executive/General Director, Instructional Technology"),
        "65005" => array("DIR, IT", "Director, Instructional Technology"),
        "65006" => array("ASST DIR, IT", "Assistant Director, Instructional Technology"),
        "65007" => array("SUP, IT", "Supervisor, Instructional Technology"),
        "65008" => array("COOR, IT", "Coordinator, Instructional Technology"),
        "65009" => array("ADMIN SA, IT", "Administrator on Special Assignment, Instructional Technology"),
        "65010" => array("SPEC, IT", "Specialist/Manager, Instructional Technology"),
        "65011" => array("ADMIN ASST, IT", "Administrative Assistant, Instructional Technology"),
        "65012" => array("TSA, IT", "Teacher on Special Assignment, Instructional Technology"),
        "65020" => array("COMPU SYS ANALYST, IT", "Computer Systems Analyst, Instructional Technology"),
        "65021" => array("COMPU SYS USER ED, IT", "Computer Systems User Educator, Instructional Technology"),
        "65022" => array("COMPU PROG, IT", "Computer Programmer, Instructional Technology"),
        "65023" => array("COMPU OP, IT", "Computer Operator, Instructional Technology"),
        "65024" => array("DATA ENT SUP, IT", "Data Entry Supervisor, Instructional Technology"),
        "65025" => array("LEAD COMPU OP, IT", "Lead Computer Operator, Instructional Technology"),
        "65026" => array("MICROFILM CLERK, IT", "Microfilm Clerk, Instructional Technology"),
        "65027" => array("COMPU NET SPEC, IT", "Computer Network Specialist, Instructional Technology"),
        "65028" => array("PROG ANALYST, IT", "Programmer Analyst, Instructional Technology"),
        "65029" => array("SR PROG ANALYST, IT", "Senior Programmer Analyst, Instructional Technology"),
        "65030" => array("PROJ MGR, IT", "Project Manager, Instructional Technology"),
        "65031" => array("ASST COMPU PROG, IT", "Assistant Computer Programmer, Instructional Technology"),
        "65032" => array("TELECOMM SPEC, IT", "Telecommunications Specialist, Instructional Technology"),
        "65033" => array("TECH, IT", "Technician, Instructional Technology"),
        "65034" => array("INFO SPEC, IT", "Information Specialist, Instructional Technology"),
        "65035" => array("SYS SUPPORT SPEC, IT", "Systems Support Specialist, Instructional Technology"),
        "65090" => array("EXEC SEC, IT", "Executive Secretary, Instructional Technology"),
        "65091" => array("SEC, IT", "Secretary, Instructional Technology"),
        "65092" => array("CLERK TYP, IT", "Clerk Typist, Instructional Technology"),
        "65093" => array("CLERK, IT", "Clerk, Instructional Technology"),
        "65094" => array("OFF AIDE, IT", "Office Aide, Instructional Technology"),
        "65095" => array("RECEP, IT", "Receptionist, Instructional Technology"),
        "65096" => array("DATA ENT OP, IT", "Data Entry Operator, Instructional Technology"),
        "65097" => array("BOOKKEEPER, IT", "Bookkeeper, Instructional Technology"),
        "65098" => array("MESSENGER, IT", "Messenger/Deliveryman, Instructional Technology"),
        "65099" => array("OTH CLER, IT", "Other Clerical Staff, Instructional Technology"),
        "71001" => array("BOARD MEMBER", "Board Member"),
        "71002" => array("BOARD COUNSEL", "Board General Counsel"),
        "71003" => array("OTH BOARD ATTORNEY", "Other Board Attorney"),
        "71004" => array("BOARD AUDITOR", "Board Auditor"),
        "71005" => array("OTH BOARD EMP", "Other Board Employee (Assigned to Board)"),
        "71011" => array("ADMIN ASST, SCH BOARD", "Administrative Assistant, School Board"),
        "71089" => array("LEGAL SEC, SCH BOARD", "Legal Secretary, School Board"),
        "71090" => array("EXEC SEC, SCH BOARD", "Executive Secretary, School Board"),
        "71091" => array("SEC, SCH BOARD", "Secretary, Board Members (s)"),
        "71092" => array("CLERK TYP, SCH BOARD", "Clerk Typist, Board Member(s)"),
        "71093" => array("CLERK, SCH BOARD", "Clerk, School Board"),
        "71094" => array("OFF AIDE, SCH BOARD", "Office Aide, Board Member (s)"),
        "71095" => array("RECEP, SCH BOARD", "Receptionist, Board Member(s)"),
        "71096" => array("DATA ENT OP, SCH BOARD", "Data Entry Operator, School Board"),
        "71097" => array("BOOKKEEPER, SCH BOARD", "Bookkeeper, School Board"),
        "71098" => array("MESSENGER, SCH BOARD", "Messenger/Deliveryman, School Board"),
        "71099" => array("OTH CLER, SCH BOARD", "Other Clerical Staff, School Board"),
        "72000" => array("SUPERINTENDENT", "District Superintendent"),
        "72001" => array("DEP SUPER, ADMIN", "Deputy Superintendent, Administration"),
        "72002" => array("ASSOC SUPER, ADMIN", "Associate Superintendent, Administration"),
        "72003" => array("ASST SUPER, ADMIN", "Assistant/Area Superintendent, Administration"),
        "72004" => array("EXEC DIR, ADMIN", "Executive/General Director, Administration"),
        "72005" => array("DIR, ADMIN", "Director, Administration"),
        "72006" => array("ASST DIR, ADMIN", "Assistant Director, Administration"),
        "72007" => array("SUP, ADMIN", "Supervisor, Administration"),
        "72008" => array("COOR, ADMIN", "Coordinator, Administration"),
        "72009" => array("ADMIN SA, ADMIN", "Administrator on Special Assignment, Administration"),
        "72010" => array("SPEC, ADMIN", "Specialist/Manager, Administration"),
        "72011" => array("ADMIN ASST, ADMIN", "Administrative Assistant, Administration"),
        "72012" => array("TSA, ADMIN", "Teacher on Special Assignment, Administration"),
        "72020" => array("STAFF ATTORNEY", "Staff Attorney"),
        "72022" => array("ADMIN INTERN", "Administrative Intern"),
        "72023" => array("NEGOTIATOR", "Negotiator"),
        "72024" => array("FTE ADMIN", "FTE Administrator"),
        "72090" => array("EXEC SEC, ADMIN", "Executive Secretary, Administration"),
        "72091" => array("SEC, ADMIN", "Secretary, Administration"),
        "72092" => array("CLERK TYP, ADMIN", "Clerk Typist, Administration"),
        "72093" => array("CLERK, ADMIN", "Clerk, Administration"),
        "72094" => array("OFF AIDE, ADMIN", "Office Aide, Administration"),
        "72095" => array("RECEP, ADMIN", "Receptionist, Administration"),
        "72096" => array("DATA ENT OP, ADMIN", "Data Entry Operator, Administration"),
        "72097" => array("BOOKKEEPER, ADMIN", "Bookkeeper, Administration"),
        "72098" => array("MESSENGER, ADMIN", "Messenger/Deliveryman, Administration"),
        "72099" => array("OTH CLER, ADMIN", "Other Clerical Staff, Administration"),
        "73001" => array("PRINCIPAL-E", "Principal, Elementary School"),
        "73002" => array("PRINCIPAL-M/J", "Principal, Middle/Junior High"),
        "73003" => array("PRINCIPAL-SH", "Principal, Senior High"),
        "73004" => array("PRINCIPAL-OTHER SCH", "Principal, Other Elementary Secondary School"),
        "73005" => array("PRINCIPAL-EX ED", "Principal, Exceptional Student School"),
        "73006" => array("PRINCIPAL-ADULT", "Principal, Adult School"),
        "73007" => array("DIR, VO-TEC", "Director, Vocational Technical Center"),
        "73008" => array("ASST PRIN-E", "Assistant Principal, Elementary"),
        "73009" => array("ASST PRIN-M/J", "Assistant Principal, Middle/Junior High"),
        "73010" => array("ASST PRIN-SH", "Assistant Principal, Senior High"),
        "73011" => array("ASST PRIN-OTHER SCH", "Assistant Principal, Other Elementary Secondary School"),
        "73012" => array("ASST PRIN-EX ED", "Assistant Principal, Exceptional Student School"),
        "73013" => array("ASST PRIN-ADULT", "Assistant Principal, Adult School"),
        "73014" => array("ASST DIR, VO-TEC", "Assistant Director, Vocational Technical Center"),
        "73015" => array("CURR COOR-E", "Curriculum Coordinator/Assistant Principal for Curriculum, Elementary"),
        "73016" => array("CURR COOR-M/J", "Curriculum Coordinator/Assistant Principal for Curriculum, Middle/Junior High"),
        "73017" => array("CURR COOR-SH", "Curriculum Coordinator/Assistant Principal for Curriculum, Senior High"),
        "73018" => array("CURR COOR-OTHER SCH", "Curriculum Coordinator/Assistant Principal for Curriculum, Other Type School"),
        "73019" => array("DEAN-E", "Dean/Assistant Principal for Student Affairs, Elementary"),
        "73020" => array("DEAN-M/J", "Dean/Assistant Principal for Student Affairs, Middle/Junior High"),
        "73021" => array("DEAN-SH", "Dean/Assistant Principal for Student Affairs, Senior High"),
        "73022" => array("DEAN-OTHER SCH", "Dean/Assistant Principal for Student Affairs, Other Type School"),
        "73023" => array("ACTIVITIES DIR", "Activities Director"),
        "73024" => array("ATHLETIC DIR", "Athletic Director"),
        "73025" => array("BUSINESS DIR", "Business Director"),
        "73026" => array("REGISTRAR", "Registrar"),
        "73027" => array("ATTENDANCE CLERK", "Attendance Clerk"),
        "73028" => array("ADMIN ASST, SCH", "Administrative Assistant, School"),
        "73029" => array("LABORER, SCH", "Laborer, School"),
        "73030" => array("TEACHER, APP TR I", "Teacher, Apprentice Trainer I"),
        "73031" => array("TEACHER, APP TR II", "Teacher, Apprentice Trainer II"),
        "73032" => array("TEACHER, ATH TR", "Teacher, Athletic Trainer"),
        "73033" => array("ATHLETIC TR", "Athletic Trainer"),
        "73090" => array("EXEC SEC, SCH", "Executive Secretary, School"),
        "73091" => array("SEC, SCH", "Secretary, School"),
        "73092" => array("CLERK TYP, SCH", "Clerk Typist, School"),
        "73093" => array("CLERK, SCH", "Clerk, School"),
        "73094" => array("OFF AIDE, SCH", "Office Aide, School"),
        "73095" => array("RECEP, SCH", "Receptionist, School"),
        "73096" => array("DATA ENT OP, SCH", "Data Entry Operator, School"),
        "73097" => array("BOOKKEEPER, SCH", "Bookkeeper, School"),
        "73098" => array("MESSENGER, SCH", "Messenger/Deliveryman, School"),
        "73099" => array("OTH CLER, SCH", "Other Clerical Staff, School"),
        "73101" => array("INTERN PRIN-E", "Intern Principal, Elementary School"),
        "73102" => array("INTERN PRIN-M/J", "Intern Principal, Middle/Junior High"),
        "73103" => array("INTERN PRIN-SH", "Intern Principal, Senior High"),
        "73104" => array("INTERN PRIN-OTHER SCH", "Intern Principal, Other Elementary Secondary School"),
        "73105" => array("INTERN PRIN-EX ED", "Intern Principal, Exceptional Student School"),
        "73106" => array("INTERN PRIN-ADULT", "Intern Principal, Adult School"),
        "73107" => array("INTERN DIR, VO-TECH", "Intern Director, Vocational Technical Center"),
        "73108" => array("INTERN AP-E", "Intern Assistant Principal, Elementary School"),
        "73109" => array("INTERN AP-M/J", "Intern Assistant Principal, Middle/Junior High"),
        "73110" => array("INTERN AP-SH", "Intern Assistant Principal, Senior High"),
        "73111" => array("INTERN AP-OTHER SCH", "Intern Assistant Principal, Other Elementary Secondary School"),
        "73112" => array("INTERN AP-EX ED", "Intern Assistant Principal, Exceptional Student School"),
        "73113" => array("INTERN AP-ADULT", "Intern Assistant Principal, Adult School"),
        "73114" => array("INTERN AD, VO-TECH", "Intern Assistant Director, Vocational Technical Center"),
        "73201" => array("INTERIM PRIN-E", "Interim Principal, Elementary School"),
        "73202" => array("INTERIM PRIN-M/J", "Interim Principal, Middle/Junior High"),
        "73203" => array("INTERIM PRIN-SH", "Interim Principal, Senior High"),
        "73204" => array("INTERIM PRIN-OTHER SCH", "Interim Principal, Other Elementary Secondary School"),
        "73205" => array("INTERIM PRIN-EX ED", "Interim Principal, Exceptional Student School"),
        "73206" => array("INTERIM PRIN-ADULT", "Interim Principal, Adult School"),
        "73207" => array("INTERIM DIR, VO-TECH", "Interim Director, Vocational Technical Center"),
        "73208" => array("INTERIM AP-E", "Interim Assistant Principal, Elementary School"),
        "73209" => array("INTERIM AP-M/J", "Interim Assistant Principal, Middle/Junior High"),
        "73210" => array("INTERIM AP-SH", "Interim Assistant Principal, Senior High"),
        "73211" => array("INTERIM AP-OTHER SCH", "Interim Assistant Principal, Other Elementary Secondary School"),
        "73212" => array("INTERIM AP-EX ED", "Interim Assistant Principal, Exceptional Student School"),
        "73213" => array("INTERIM AP-ADULT", "Interim Assistant Principal, Adult School"),
        "73214" => array("INTERIM AD, VO-TECH", "Interim Assistant Director, Vocational Technical Center"),
        "74001" => array("DEP SUPER, FACIL", "Deputy Superintendent, Facilities/Construction"),
        "74002" => array("ASSOC SUPER, FACIL", "Associate Superintendent, Facilities/Construction"),
        "74003" => array("ASST SUPER, FACIL", "Assistant/Area Superintendent, Facilities/Construction"),
        "74004" => array("EXEC DIR, FACIL", "Executive/General Director, Facilities/Construction"),
        "74005" => array("DIR, FACIL", "Director, Facilities/Construction"),
        "74006" => array("ASST DIR, FACIL", "Assistant Director, Facilities/Construction"),
        "74007" => array("SUP, FACIL", "Supervisor, Facilities/Construction"),
        "74008" => array("COOR, FACIL", "Coordinator, Facilities/Construction"),
        "74009" => array("ADMIN SA, FACIL", "Administrator on Special Assignment, Facilities/Construction"),
        "74010" => array("SPEC, FACIL", "Specialist, Facilities/Construction"),
        "74011" => array("ADMIN ASST, FACIL", "Administrative Assistant, Facilities/Construction"),
        "74012" => array("TSA, FACIL", "Teacher on Special Assignment, Facilities/Construction"),
        "74013" => array("MANAGER, FACIL", "Manager, Facilities/Construction"),
        "74014" => array("FOREMAN, FACIL", "Foreman, Facilities/Construction"),
        "74015" => array("LEAD WK, FACIL", "Lead Worker, Facilities/Construction"),
        "74016" => array("DIR, FACIL PL", "Director, Facilities Planning"),
        "74017" => array("SUP, FACIL PL", "Supervisor, Facilities Planning"),
        "74018" => array("COOR, FACIL PL", "Coordinator, Facilities Planning"),
        "74020" => array("FACILITIES PLANNER", "Facilities Planner"),
        "74021" => array("CHIEF ARCHITECT", "Chief Architect"),
        "74022" => array("OTH ARCHITECT", "Other District Architect"),
        "74023" => array("BLDG INSPECTOR", "Building Inspector"),
        "74024" => array("PROJECT MAN, FACIL", "Project Manager, Facilities/Construction"),
        "74025" => array("MECH ENGINEER", "Mechanical Engineer"),
        "74026" => array("CIVIL ENGINEER", "Civil Engineer"),
        "74027" => array("ELEC ENGINEER", "Electrical Engineer"),
        "74028" => array("DRAFTSMAN", "Draftsman"),
        "74029" => array("STATIONARY ENGINEER", "Stationary Engineer"),
        "74030" => array("MATERIALS SPEC, FACIL", "Materials Specialist, Facilities/Construction"),
        "74031" => array("ACCOUNTANT, FACIL", "Accountant, Facilities/Construction"),
        "74032" => array("LABORER, FACIL", "Laborer, Facilities/Construction"),
        "74033" => array("OTH FACIL ST", "Other Facilities Staff"),
        "74090" => array("EXEC SEC, FACIL", "Executive Secretary, Facilities/Construction"),
        "74091" => array("SEC, FACIL", "Secretary, Facilities/Construction"),
        "74092" => array("CLERK TYP, FACIL", "Clerk Typist, Facilities/Construction"),
        "74093" => array("CLERK, FACIL", "Clerk, Facilities/Construction"),
        "74094" => array("OFF AIDE, FACIL", "Office Aide, Facilities/Construction"),
        "74095" => array("RECEP, FACIL", "Receptionist, Facilities/Construction"),
        "74096" => array("DATA ENT OP, FACIL", "Data Entry Operator, Facilities/Construction"),
        "74097" => array("BOOKKEEPER, FACIL", "Bookkeeper, Facilities/Construction"),
        "74098" => array("MESSENGER, FACIL", "Messenger/Deliveryman, Facilities/Construction"),
        "74099" => array("OTH CLER, FACIL", "Other Clerical Staff, Facilities/Construction"),
        "75001" => array("DEP SUPER, FISCAL", "Deputy Superintendent, Business & Finance"),
        "75002" => array("ASSOC SUPER, FISCAL", "Associate Superintendent, Business & Finance"),
        "75003" => array("ASST SUPER, FISCAL", "Assistant/Area Superintendent, Business & Finance"),
        "75004" => array("EXEC DIR, FISCAL", "Executive/General Director, Business & Finance"),
        "75005" => array("DIR, FISCAL", "Director, Business & Finance"),
        "75006" => array("ASST DIR, FISCAL", "Assistant Director, Business & Finance"),
        "75007" => array("SUP, FISCAL", "Supervisor, Business & Finance"),
        "75008" => array("COOR, FISCAL", "Coordinator, Business & Finance"),
        "75009" => array("ADMIN SA, FISCAL", "Administrator on Special Assignment, Business & Finance"),
        "75010" => array("SPEC, FISCAL", "Specialist/Manager, Fiscal Services"),
        "75011" => array("ADMIN ASST, FISCAL", "Administrative Assistant, Fiscal Services"),
        "75012" => array("TSA, FISCAL", "Teacher on Special Assignment, Fiscal Services"),
        "75013" => array("MANAGER, FISCAL", "Manager, Fiscal Services"),
        "75016" => array("DIR, BUDGET", "Director, Budgeting"),
        "75017" => array("DIR, ACCOUNTING", "Director, Accounting"),
        "75018" => array("SUP/COOR, BUDGET", "Supervisor/Coordinator, Budgeting"),
        "75019" => array("SUP/COOR, INT AUDIT", "Supervisor/Coordinator, Internal Auditing"),
        "75020" => array("SUP/COOR, ACCOUNTING", "Supervisor/Coordinator, Accounting"),
        "75021" => array("SUP/COOR, PAYROLL", "Supervisor/Coordinator, Payroll"),
        "75022" => array("SUP/COOR, INVEST", "Supervisor/Coordinator, Investments"),
        "75023" => array("FINANCE OFFICER", "Finance Officer/Comptroller"),
        "75024" => array("ASST FINANCE OFFICER", "Assistant Finance Officer/Comptroller"),
        "75030" => array("BUDGET ANALYST", "Fiscal/Budget Analyst"),
        "75031" => array("ACCOUNTANT", "Accountant"),
        "75032" => array("ACCOUNT CLERK", "Account Clerk/Payroll Clerk"),
        "75033" => array("INT AUDITOR", "Internal Accounts Auditor"),
        "75034" => array("OTH FISCAL EMP", "Other Fiscal Personnel"),
        "75090" => array("EXEC SEC, FISCAL", "Executive Secretary, Business & Finance"),
        "75091" => array("SEC, FISCAL", "Secretary, Business & Finance"),
        "75092" => array("CLERK TYP, FISCAL", "Clerk Typist, Business & Finance"),
        "75093" => array("CLERK, FISCAL", "Clerk, Fiscal Services"),
        "75094" => array("OFF AIDE, FISCAL", "Office Aide, Business & Finance"),
        "75095" => array("RECEP, FISCAL", "Receptionist, Business & Finance"),
        "75096" => array("DATA ENT OP, FISCAL", "Data Entry Operator, Fiscal Services"),
        "75097" => array("BOOKKEEPER, FISCAL", "Bookkeeper, Fiscal Services"),
        "75098" => array("MESSENGER, FISCAL", "Messenger/Deliveryman, Fiscal Services"),
        "75099" => array("OTH CLER, FISCAL", "Other Clerical Staff, Fiscal Services"),
        "76001" => array("DEP SUPER, FOOD SER", "Deputy Superintendent, Food Services"),
        "76002" => array("ASSOC SUPER, FOOD SER", "Associate Superintendent, Food Services"),
        "76003" => array("ASST SUPER, FOOD SER", "Assistant/Area Superintendent, Food Services"),
        "76004" => array("EXEC DIR, FOOD SER", "Executive/General Director, Food Services"),
        "76005" => array("DIR, FOOD SER", "Director, Food Services"),
        "76006" => array("ASST DIR, FOOD SER", "Assistant Director, Food Services"),
        "76007" => array("SUP, FOOD SER", "Supervisor, Food Services"),
        "76008" => array("COOR, FOOD SER", "Coordinator, Food Services"),
        "76009" => array("ADMIN SA, FOOD SER", "Administrator on Special Assignment, Food Services"),
        "76010" => array("SPEC, FOOD SER", "Specialist/Manager, Food Services"),
        "76011" => array("ADMIN ASST, FOOD SER", "Administrative Assistant, Food Services"),
        "76012" => array("TSA, FOOD SER", "Teacher on Special Assignment, Food Services"),
        "76013" => array("MANAGER, FOOD SER", "Manager, Food Services"),
        "76016" => array("ASST FOOD SER MANAGER", "Assistant Food Service Manager"),
        "76020" => array("BAKER", "Baker"),
        "76021" => array("COOK", "Cook"),
        "76022" => array("SALAD MAKER", "Salad Maker"),
        "76023" => array("FOOD SER WORKER", "School Food Service Worker/Assistant"),
        "76024" => array("LUNCH ROOM AIDE", "Lunch Room Aide"),
        "76025" => array("CASHIER, FOOD SER", "Cashier, Food Services"),
        "76027" => array("STORES CLERK, FOOD SER", "Stores Clerk/Buyer, Food Services"),
        "76028" => array("FOOD SER DRIVER", "Food Service Driver"),
        "76029" => array("OTH FOOD SER EMP", "Other Food Service Personnel"),
        "76030" => array("SUB FOOD SER WK", "Substitute Food Service Worker"),
        "76090" => array("EXEC SEC, FOOD SER", "Executive Secretary, Food Services"),
        "76091" => array("SEC, FOOD SER", "Secretary, Food Services"),
        "76092" => array("CLERK TYP, FOOD SER", "Clerk Typist, Food Services"),
        "76093" => array("CLERK, FOOD SER", "Clerk, Food Services"),
        "76094" => array("OFF AIDE, FOOD SER", "Office Aide, Food Services"),
        "76095" => array("RECEP, FOOD SER", "Receptionist, Food Services"),
        "76096" => array("DATA ENT OP, FOOD SER", "Data Entry Operator, Food Services"),
        "76097" => array("BOOKKEEPER, FOOD SER", "Bookkeeper, Food Services"),
        "76098" => array("MESSENGER, FOOD SER", "Messenger/Deliveryman, Food Services"),
        "76099" => array("OTH CLER, FOOD SER", "Other Clerical Staff, Food Services"),
        "77001" => array("DEP SUPER, CEN SER", "Deputy Superintendent, Central Services"),
        "77002" => array("ASSOC SUPER, CEN SER", "Associate Superintendent, Central Services"),
        "77003" => array("ASST SUPER, CEN SER", "Assistant/Area Superintendent, Central Services"),
        "77004" => array("EXEC DIR, CEN SER", "Executive/General Director, Central Services"),
        "77005" => array("DIR, CEN SER", "Director, Central Services"),
        "77006" => array("ASST DIR, CEN SER", "Assistant Director, Central Services"),
        "77007" => array("SUP, CEN SER", "Supervisor, Central Services"),
        "77008" => array("COOR, CEN SER", "Coordinator, Central Services"),
        "77009" => array("ADMIN SA, CEN SER", "Administrator on Special Assignment, Central Services"),
        "77010" => array("SPEC, CEN SER", "Specialist/Manager, Central Services"),
        "77011" => array("ADMIN ASST, CEN SER", "Administrative Assistant, Central Services"),
        "77012" => array("TSA, CEN SER", "Teacher on Special Assignment, Central Services"),
        "77013" => array("MANAGER, CEN SER", "Manager, Central Services"),
        "77090" => array("EXEC SEC, CEN SER", "Executive Secretary, Central Services"),
        "77091" => array("SEC, CEN SER", "Secretary, Central Services"),
        "77092" => array("CLERK TYP, CEN SER", "Clerk Typist, Central Services"),
        "77093" => array("CLERK, CEN SER", "Clerk, Central Services"),
        "77094" => array("OFF AIDE, CEN SER", "Office Aide, Central Services"),
        "77095" => array("RECEP, CEN SER", "Receptionist, Central Services"),
        "77096" => array("DATA ENT OP, CEN SER", "Data Entry Operator, Central Services"),
        "77097" => array("BOOKKEEPER, CEN SER", "Bookkeeper, Central Services"),
        "77098" => array("MESSENGER, CEN SER", "Messenger/Deliveryman, Central Services"),
        "77099" => array("OTH CLER, CEN SER", "Other Clerical Staff, Central Services"),
        "77101" => array("DEP SUPER, P/R/E", "Deputy Superintendent, Planning, Research & Evaluation"),
        "77102" => array("ASSOC SUPER, P/R/E", "Associate Superintendent, Planning, Research & Evaluation"),
        "77103" => array("ASST SUPER, P/R/E", "Assistant/Area Superintendent, Planning, Research & Evaluation"),
        "77104" => array("EXEC DIR, P/R/E", "Executive/General Director, Planning, Research & Evaluation"),
        "77105" => array("DIR, P/R/E", "Director, Planning, Research & Evaluation"),
        "77106" => array("ASST DIR, P/R/E", "Assistant Director, Planning, Research & Evaluation"),
        "77107" => array("SUP, P/R/E", "Supervisor, Planning Research & Evaluation"),
        "77108" => array("COOR, P/R/E", "Coordinator, Planning, Research & Evaluation"),
        "77109" => array("ADMIN SA, P/R/E", "Administrator on Special Assignment, Planning, Research & Evaluation"),
        "77110" => array("SPEC, P/R/E", "Specialist/Manager, Planning, Research & Evaluation"),
        "77111" => array("ADMIN ASST, P/R/E", "Administrative Assistant, Planning, Research & Evaluation"),
        "77112" => array("TSA, P/R/E", "Teacher on Special Assignment, Planning, Research & Evaluation"),
        "77117" => array("DIR, RES/EVAL", "Director, Research & Evaluation"),
        "77118" => array("SUP, RES/EVAL", "Supervisor, Research & Evaluation"),
        "77119" => array("COOR, RES/EVAL", "Coordinator, Research & Evaluation"),
        "77120" => array("DIR, PLAN", "Director, Planning"),
        "77121" => array("SUP, PLAN", "Supervisor, Planning"),
        "77122" => array("COOR, PLAN", "Coordinator, Planning"),
        "77130" => array("PLANNING SPEC", "Planning Specialist"),
        "77131" => array("EVAL SPEC", "Testing/Evaluation/Assessment Specialist"),
        "77132" => array("ED DIAG", "Educational Diagnostician"),
        "77133" => array("OTH DIAG", "Other Diagnosticians"),
        "77134" => array("TESTING ASST", "Testing Assistant"),
        "77190" => array("EXEC SEC, P/R/E", "Executive Secretary, Planning, Research & Evaluation"),
        "77191" => array("SEC, P/R/E", "Secretary, Planning, Research & Evaluation"),
        "77192" => array("CLERK TYP, P/R/E", "Clerk Typist, Planning, Research & Evaluation"),
        "77193" => array("CLERK, P/R/E", "Clerk, Planning, Research & Evaluation"),
        "77194" => array("OFF AIDE, P/R/E", "Office Aide, Planning, Research & Evaluation"),
        "77195" => array("RECEP, P/R/E", "Receptionist, Planning, Research & Evaluation"),
        "77196" => array("DATA ENT OP, P/R/E", "Data Entry Operator, Planning, Research & Evaluation"),
        "77197" => array("BOOKKEEPER, P/R/E", "Bookkeeper, Planning, Research & Evaluation"),
        "77198" => array("MESSENGER, P/R/E", "Messenger/Deliveryman, Planning, Research & Evaluation"),
        "77199" => array("OTH CLER, P/R/E", "Other Clerical Staff, Planning Research & Evaluation"),
        "77201" => array("DEP SUPER, INFO SER", "Deputy Superintendent, Information Services"),
        "77202" => array("ASSOC SUPER, INFO SER", "Associate Superintendent, Information Services"),
        "77203" => array("ASST SUPER, INFO SER", "Assistant/Area Superintendent, Information Services"),
        "77204" => array("EXEC DIR, INFO SER", "Executive/General Director, Information Services"),
        "77205" => array("DIR, INFO SER", "Director, Information Services"),
        "77206" => array("ASST DIR, INFO SER", "Assistant Director, Information Services"),
        "77207" => array("SUP, INFO SER", "Supervisor, Information Services"),
        "77208" => array("COOR, INFO SER", "Coordinator, Information Services"),
        "77209" => array("ADMIN SA, INFO SER", "Administrator on Special Assignment, Information Services"),
        "77210" => array("SPEC, INFO SER", "Specialist/Manager, Information Services"),
        "77211" => array("ADMIN ASST, INFO SER", "Administrative Assistant, Information Services"),
        "77212" => array("TSA, INFO SER", "Teacher on Special Assignment, Information Services"),
        "77213" => array("MANAGER, INFO SER", "Manager, Information Services"),
        "77220" => array("PUB REL INFO SPEC", "Public Relations information Specialist"),
        "77221" => array("WP MANAGER", "Word Processing Center Manager"),
        "77222" => array("WP OPERATOR", "Word Processing Operator"),
        "77223" => array("GRAPHICS ARTIST", "Graphics Artist"),
        "77224" => array("PHOTOGRAPHER", "Photographer"),
        "77225" => array("DATA ANALYST", "Data Analyst"),
        "77226" => array("RESEARCH ASSOC", "Research Associate"),
        "77227" => array("RECORDS/FORMS ANAL", "Records/Forms Analyst"),
        "77290" => array("EXEC SEC, INFO SER", "Executive Secretary, Information Services"),
        "77291" => array("SEC, INFO SER", "Secretary, Information Services"),
        "77292" => array("CLERK TYP, INFO SER", "Clerk Typist, Information Services"),
        "77293" => array("CLERK, INFO SER", "Clerk, Information Services"),
        "77294" => array("OFF AIDE, INFO SER", "Office Aide, Information Services"),
        "77295" => array("RECEP, INFO SER", "Receptionist, Information Services"),
        "77296" => array("DATA ENT OP, INFO SER", "Data Entry Operator, Information Services"),
        "77297" => array("BOOKKEEPER, INFO SER", "Bookkeeper, Information Services"),
        "77298" => array("MESSENGER, INFO SER", "Messenger/Deliveryman, Information Services"),
        "77299" => array("OTH CLER, INFO SER", "Other Clerical Staff, Information Services"),
        "77301" => array("DEP SUPER, STAFF", "Deputy Superintendent, Staff Services"),
        "77302" => array("ASSOC SUPER, STAFF", "Associate Superintendent, Staff Services"),
        "77303" => array("ASST SUPER, STAFF", "Assistant/Area Superintendent, Staff Services"),
        "77304" => array("EXEC DIR, STAFF", "Executive/General Director, Staff Services"),
        "77305" => array("DIR, STAFF", "Director, Staff Services"),
        "77306" => array("ASST DIR, STAFF", "Assistant Director, Staff Services"),
        "77307" => array("SUP, STAFF", "Supervisor, Staff Services"),
        "77308" => array("COOR, STAFF", "Coordinator, Staff Services"),
        "77309" => array("ADMIN SA, STAFF", "Administrator on Special Assignment, Staff Services"),
        "77310" => array("SPEC, STAFF", "Specialist/Manager, Staff Services"),
        "77311" => array("ADMIN ASST, STAFF", "Administrative Assistant, Staff Services"),
        "77312" => array("TSA, STAFF", "Teacher on Special Assignment, Staff Services"),
        "77316" => array("DIR, RISK MAN", "Director, Risk Management"),
        "77317" => array("SUP, RISK MAN", "Supervisor, Risk Management"),
        "77318" => array("COOR, RISK MAN", "Coordinator, Risk Management"),
        "77319" => array("DIR, EMP REL", "Director, Employee Relations"),
        "77320" => array("SUP, EMP REL", "Supervisor, Employee Relations"),
        "77321" => array("COOR, EMP REL", "Coordinator, Employee Relations"),
        "77322" => array("DIR, PERS", "Director, Personnel"),
        "77323" => array("SUP, PERS", "Supervisor, Personnel"),
        "77324" => array("COOR, PERS", "Coordinator, Personnel"),
        "77325" => array("ASST CERT", "Assistant for Certification"),
        "77326" => array("ASST RET", "Assistant for Retirement"),
        "77327" => array("CLAIMS REP", "Claims Representative"),
        "77328" => array("TRAINER, NONINST", "Trainer, Noninstructional"),
        "77329" => array("SR PERS ANALYST", "Senior Personnel Analyst"),
        "77330" => array("PERS SPEC", "Personnel Specialist"),
        "77331" => array("AFF ACTION SPEC", "Affirmative Action/Title IX Specialist"),
        "77332" => array("INSURANCE SPEC", "Insurance Specialist"),
        "77333" => array("EQUITY OFF", "Equity Officer"),
        "77334" => array("SALARY ADMIN", "Salary Administrator"),
        "77335" => array("RECRUITER", "Recruiter"),
        "77336" => array("HUMAN REL SPEC", "Human Relations Specialist"),
        "77337" => array("CERT SPEC", "Certification Specialist"),
        "77338" => array("SUP, HRMD TRNG", "Supervisor, Human Resource Management Development Training"),
        "77390" => array("EXEC SEC, STAFF", "Executive Secretary, Staff Services"),
        "77391" => array("SEC, STAFF SER", "Secretary, Staff Services"),
        "77392" => array("CLERK TYP, STAFF", "Clerk Typist, Staff Services"),
        "77393" => array("CLERK, STAFF", "Clerk, Staff Services"),
        "77394" => array("OFF AIDE, STAFF", "Office Aide, Staff Services"),
        "77395" => array("RECEP, STAFF", "Receptionist, Staff Services"),
        "77396" => array("DATA ENT OP, STAFF", "Data Entry Operator, Staff Services"),
        "77397" => array("BOOKKEEPER, STAFF", "Bookkeeper, Staff Services"),
        "77398" => array("MESSENGER, STAFF", "Messenger/Deliveryman, Staff Services"),
        "77399" => array("OTH CLER, STAFF", "Other Clerical Staff, Staff Services"),
        "77420" => array("STATISTICIAN", "Statistician"),
        "77421" => array("STAT AIDE", "Statistical Aide/Clerk"),
        "77601" => array("DEP SUPER, INT SER", "Deputy Superintendent, Internal Services"),
        "77602" => array("ASSOC SUPER, INT SER", "Associate Superintendent, Internal Services"),
        "77603" => array("ASST SUPER, INT SER", "Assistant/Area Superintendent, Internal Services"),
        "77604" => array("EXEC DIR, INT SER", "Executive/General Director, Internal Services"),
        "77605" => array("DIR, INT SER", "Director, Internal Services"),
        "77606" => array("ASST DIR, INT SER", "Assistant Director, Internal Services"),
        "77607" => array("SUP, INT SER", "Supervisor, Internal Services"),
        "77608" => array("COOR, INT SER", "Coordinator, Internal Services"),
        "77609" => array("ADMIN SA, INT SER", "Administrator on Special Assignment, Internal Service"),
        "77610" => array("SPEC, INT SER", "Specialist/Manager, Internal Services"),
        "77611" => array("ADMIN ASST, INT SER", "Administrative Assistant, Internal Services"),
        "77612" => array("TSA, INT SER", "Teacher on Special Assignment, Internal Services"),
        "77613" => array("MANAGER, INT SER", "Manager, Internal Services"),
        "77614" => array("FOREMAN, INT SER", "Foreman, Internal Services"),
        "77615" => array("LEAD WK, INT SER", "Lead Worker, Internal Services"),
        "77616" => array("DIR, PROP REC", "Director, Property Records"),
        "77617" => array("SUP, PROP REC", "Supervisor, Property Records"),
        "77618" => array("COOR, PROP REC", "Coordinator, Property Records"),
        "77619" => array("DIR, WAREHOUSING", "Director, Warehousing"),
        "77620" => array("SUP, WAREHOUSING", "Supervisor, Warehousing"),
        "77621" => array("COOR, WAREHOUSING", "Coordinator, Warehousing"),
        "77622" => array("DIR, PURCHASING", "Director, Purchasing"),
        "77623" => array("SUP, PURCHASING", "Supervisor, Purchasing"),
        "77624" => array("COOR, PURCHASING", "Coordinator, Purchasing"),
        "77625" => array("PROP CONTROL SPEC", "Property Control Specialist"),
        "77626" => array("STOREROOM MANAGER", "Storeroom Manager"),
        "77627" => array("WAREHOUSEMAN", "Storekeeper/Warehouseman"),
        "77628" => array("SHIP/REC CLERK", "Shipping/Receiving Clerk"),
        "77629" => array("TEXTBOOK SPEC, WHSE", "Textbook Specialist, Warehouse"),
        "77630" => array("DUP EQUIP OP", "Duplicating/Reproduction Equipment Operator"),
        "77631" => array("PRINTER", "Printer/Print Manager"),
        "77632" => array("PRODUCTION SPEC", "Production Specialist"),
        "77633" => array("CAMERAMAN", "Cameraman (Print Shop)"),
        "77634" => array("OFFSET PRESSMAN", "Offset Pressman"),
        "77635" => array("BINDERY TECH", "Bindery Technician"),
        "77636" => array("MICROGRAPHICS TECH", "Micrographics Technician"),
        "77637" => array("PURCHASING AGENT", "Purchasing Agent/Buyer"),
        "77638" => array("SUPPLIES SPEC", "Supplies Specialist"),
        "77639" => array("WAREHOUSE MANAGER", "Warehouse Manager"),
        "77640" => array("FOREMAN, PRINT SHOP", "Foreman, Print Shop"),
        "77641" => array("RECORDS SPEC", "Records Specialist/Technician"),
        "77642" => array("GRAPHICS SPEC", "Graphics Production Specialist"),
        "77643" => array("MATERIALS SPEC, INT SER", "Materials Control/Testing Specialist, Internal Services"),
        "77644" => array("MAIL ROOM SUP", "Mail Room Supervisor"),
        "77645" => array("COURIER", "Mail Delivery Clerk/Courier"),
        "77690" => array("EXEC SEC, INT SER", "Executive Secretary, Internal Services"),
        "77691" => array("SEC, INT SER", "Secretary, Internal Services"),
        "77692" => array("CLERK TYP, INT SER", "Clerk Typist, Internal Services"),
        "77693" => array("CLERK, INT SER", "Clerk, Internal Services"),
        "77694" => array("OFF AIDE, INT SER", "Office Aide, Internal Services"),
        "77695" => array("RECEP, INT SER", "Receptionist, Internal Services"),
        "77696" => array("DATA ENT OP, INT SER", "Data Entry Operator, Internal Services"),
        "77697" => array("BOOKKEEPER, INT SER", "Bookkeeper, Internal Services"),
        "77698" => array("MESSENGER, INT SER", "Messenger/Deliveryman, Internal Services"),
        "77699" => array("OTH CLER, INT SER", "Other Clerical Staff, Internal Services"),
        "78001" => array("DEP SUPER, TRANS", "Deputy Superintendent, Transportation"),
        "78002" => array("ASSOC SUPER, TRANS", "Associate Superintendent, Transportation"),
        "78003" => array("ASST SUPER, TRANS", "Assistant/Area Superintendent, Transportation"),
        "78004" => array("EXEC DIR, TRANS", "Executive/General Director, Transportation"),
        "78005" => array("DIR, TRANS", "Director, Transportation"),
        "78006" => array("ASST DIR, TRANS", "Assistant Director, Transportation"),
        "78007" => array("SUP, TRANS", "Supervisor, Transportation"),
        "78008" => array("COOR, TRANS", "Coordinator, Transportation"),
        "78009" => array("ADMIN SA, TRANS", "Administrator on Special Assignment, Transportation"),
        "78010" => array("SPEC, TRANS", "Specialist/Manager, Transportation"),
        "78011" => array("ADMIN ASST, TRANS", "Administrative Assistant, Transportation"),
        "78012" => array("TSA, TRANS", "Teacher on Special Assignment, Transportation"),
        "78013" => array("MANAGER, TRANS", "Manager, Transportation"),
        "78014" => array("FOREMAN, TRANS", "Foreman, Transportation"),
        "78015" => array("LEAD WK, TRANS", "Lead Worker, Transportation"),
        "78020" => array("ROUTE COOR", "Route Coordinator/Manager"),
        "78021" => array("OTH ROUTING EMP", "Other Routing Personnel/Assistants"),
        "78022" => array("SUP/COOR, EX ED TRANS", "Supervisor/Coordinator of Exceptional Education Transportation"),
        "78023" => array("SUP/COOR, VEH SER", "Supervisor/Coordinator, Vehicle Service"),
        "78024" => array("MECHANIC", "Mechanic"),
        "78025" => array("MECHANIC HELPER", "Mechanic?s Helper"),
        "78026" => array("PAINT & BODY EMP", "Paint & Body Personnel"),
        "78027" => array("PARTS EMP", "Parts Personnel"),
        "78028" => array("GAS ATTENDANT", "Gas Attendant/Tire Personnel"),
        "78029" => array("DISPATCHER, TRANS", "Dispatcher, Transportation"),
        "78030" => array("BUS DRIVER", "Bus Driver"),
        "78031" => array("SUB BUS DRIVER", "Relief Driver/Substitute"),
        "78032" => array("BUS AIDE", "Bus Aide/Bus Attendant"),
        "78033" => array("BUS DRIVER TRAINER", "Bus Driver Trainer/Safety Specialist"),
        "78034" => array("OTH TRANS EMP", "Other Transportation Personnel"),
        "78035" => array("SHOP SUP", "Shop Supervisor"),
        "78090" => array("EXEC SEC, TRANS", "Executive Secretary, Transportation"),
        "78091" => array("SEC, TRANS", "Secretary, Transportation"),
        "78092" => array("CLERK TYP, TRANS", "Clerk Typist, Transportation"),
        "78093" => array("CLERK, TRANS", "Clerk, Transportation"),
        "78094" => array("OFF AIDE, TRANS", "Office Aide, Transportation"),
        "78095" => array("RECEP, TRANS", "Receptionist, Transportation"),
        "78096" => array("DATA ENT OP, TRANS", "Data Entry Operator, Transportation"),
        "78097" => array("BOOKKEEPER, TRANS", "Bookkeeper, Transportation"),
        "78098" => array("MESSENGER, TRANS", "Messenger/Deliveryman, Transportation"),
        "78099" => array("OTH CLER, TRANS", "Other Clerical Staff, Transportation"),
        "79001" => array("DEP SUPER, OP", "Deputy Superintendent, Operations"),
        "79002" => array("ASSOC SUPER, OP", "Associate Superintendent, Operations"),
        "79003" => array("ASST SUPER, OP", "Assistant/Area Superintendent, Operations"),
        "79004" => array("EXEC DIR, OP", "Executive/General Director, Operations"),
        "79005" => array("DIR, OP", "Director, Operations"),
        "79006" => array("ASST DIR, OP", "Assistant Director, Operations"),
        "79007" => array("SUP, OP", "Supervisor, Operations"),
        "79008" => array("COOR, OP", "Coordinator, Operations"),
        "79009" => array("ADMIN SA, OP", "Administrator on Special Assignment, Operations"),
        "79010" => array("SPEC, OP", "Specialist/Manager, Operations"),
        "79011" => array("ADMIN ASST, OP", "Administrative Assistant, Operations"),
        "79012" => array("TSA, OP", "Teacher on Special Assignment, Operations"),
        "79013" => array("MANAGER, OP", "Manager, Operations"),
        "79014" => array("FOREMAN, OP", "Foreman, Operations"),
        "79015" => array("LEAD WK, OP", "Lead Worker, Operations"),
        "79016" => array("UTILITIES MANAGER", "Utilities Manager"),
        "79017" => array("SUP, SECURITY", "Supervisor of Security"),
        "79018" => array("ENERGY MANAGER", "Energy Manager"),
        "79020" => array("INVESTIGATOR", "Special Investigator"),
        "79021" => array("SAFETY OFF", "Safety and Security Officer"),
        "79022" => array("SAFETY INSPECTOR", "Loss Prevention/Fire & Safety Inspector"),
        "79023" => array("SECURITY GUARD", "Security Guard/Night Watchman"),
        "79024" => array("CROSSING GUARD, SCH", "Crossing Guard, School"),
        "79025" => array("HEAD CUSTODIAN", "Head Custodian/Maintenance Unit Manager"),
        "79026" => array("CUSTODIAN", "Custodian"),
        "79027" => array("MAID", "Maid"),
        "79028" => array("PEST CONTROL WK", "Insect/Pest Control Worker"),
        "79029" => array("GARDNER", "Landscape Gardener/Worker"),
        "79032" => array("COMMUNICATIONS TECH", "Communications Technician"),
        "79033" => array("DISPATCHER, OP", "Dispatcher, Operations"),
        "79034" => array("SUB CUSTODIAN", "Substitute Custodian"),
        "79035" => array("LABORER, OP", "Laborer, Operations"),
        "79036" => array("GROUNDS MAIN TECH", "Grounds Maintenance Technician/Tree Surgeon"),
        "79037" => array("ENVIRONMENTAL ENG", "Environmental Engineer"),
        "79090" => array("EXEC SEC, OP", "Executive Secretary, Operations"),
        "79091" => array("SEC, OP", "Secretary, Operations"),
        "79092" => array("CLERK TYP, OP", "Clerk Typist, Operations"),
        "79093" => array("CLERK, OP", "Clerk, Operations"),
        "79094" => array("OFF AIDE, OP", "Office Aide, Operations"),
        "79095" => array("RECEP, OP", "Receptionist, Operations"),
        "79096" => array("DATA ENT OP, OP", "Data Entry Operator, Operations"),
        "79097" => array("BOOKKEEPER, OP", "Bookkeeper, Operations"),
        "79098" => array("MESSENGER, OP", "Messenger/Deliveryman, Operations"),
        "79099" => array("OTH CLER, OP", "Other Clerical Staff, Operations"),
        "81001" => array("DEP SUPER, MAIN", "Deputy Superintendent, Maintenance"),
        "81002" => array("ASSOC SUPER, MAIN", "Associate Superintendent, Maintenance"),
        "81003" => array("ASST SUPER, MAIN", "Assistant/Area Superintendent, Maintenance"),
        "81004" => array("EXEC DIR, MAIN", "Executive/General Director, Maintenance"),
        "81005" => array("DIR, MAIN", "Director, Maintenance"),
        "81006" => array("ASST DIR, MAIN", "Assistant Director, Maintenance"),
        "81007" => array("SUP, MAIN", "Supervisor, Maintenance"),
        "81008" => array("COOR, MAIN", "Coordinator, Maintenance"),
        "81009" => array("ADMIN SA, MAIN", "Administrator on Special Assignment, Maintenance"),
        "81010" => array("SPEC, MAIN", "Specialist/Manager, Maintenance"),
        "81011" => array("ADMIN ASST, MAIN", "Administrative Assistant, Maintenance"),
        "81012" => array("TSA, MAIN", "Teacher on Special Assignment, Maintenance"),
        "81013" => array("MANAGER, MAIN", "Manager, Maintenance"),
        "81014" => array("FOREMAN, MAIN", "Foreman, Maintenance"),
        "81015" => array("LEAD WK, MAIN", "Lead Worker, Maintenance"),
        "81020" => array("EQUIP OP", "Equipment Operator"),
        "81021" => array("TRUCK DRIVER", "Truck Driver"),
        "81022" => array("WASTE PLANT OP", "Wastewater Plant Operator"),
        "81024" => array("AC MECHANIC", "Air Conditioning and Refrigeration Mechanic"),
        "81025" => array("BOILER MECHANIC", "Boiler Mechanic"),
        "81026" => array("ELECTRICIAN", "Electrician"),
        "81027" => array("PLUMBER", "Plumber"),
        "81028" => array("WELDER", "Welder"),
        "81029" => array("CARPENTER", "Carpenter"),
        "81030" => array("MASON", "Mason"),
        "81031" => array("CARPET REPAIRMAN", "Carpet & Tile Repairman"),
        "81032" => array("GLAZIER", "Glazier (Window Repairman)"),
        "81033" => array("PAINTER", "Painter"),
        "81034" => array("ROOFER", "Roofer"),
        "81035" => array("SHEET METAL WK", "Sheet Metal Worker"),
        "81036" => array("REFINISHER", "Refinisher"),
        "81037" => array("EQUIP MECHANIC", "Equipment Mechanic"),
        "81038" => array("SMALL ENGINE MECH", "Small Engine Mechanic"),
        "81039" => array("HVY EQUIP MECH", "Heavy Equipment Mechanic"),
        "81040" => array("APPL REPAIRMAN", "Appliance Repairman"),
        "81041" => array("LOCKSMITH", "Locksmith"),
        "81042" => array("OFF MACH REPAIRMAN", "Office Machine Repairman"),
        "81043" => array("MAIN WK", "Maintenance Worker/Trades worker"),
        "81044" => array("CABINET MAKER", "Millshop Worker/Cabinet Maker"),
        "81045" => array("ELECTRONICS, MAIN", "Electronics Technician, Maintenance"),
        "81046" => array("OTH MECHANICS", "Other Mechanics"),
        "81047" => array("VENETIAN BL REPAIR", "Venetian Blind Repairman"),
        "81048" => array("FURNITURE REPAIR", "Furniture Repairman"),
        "81049" => array("PLASTERER", "Plasterer"),
        "81050" => array("OTH MAIN PERS", "Other Maintenance Personnel"),
        "81090" => array("EXEC SEC, MAIN", "Executive Secretary, Maintenance"),
        "81091" => array("SEC, MAIN", "Secretary, Maintenance"),
        "81092" => array("CLERK TYP, MAIN", "Clerk Typist, Maintenance"),
        "81093" => array("CLERK, MAIN", "Clerk, Maintenance"),
        "81094" => array("OFF AIDE, MAIN", "Office Aide, Maintenance"),
        "81095" => array("RECEP, MAIN", "Receptionist, Maintenance"),
        "81096" => array("DATA ENT OP, MAIN", "Data Entry Operator, Maintenance"),
        "81097" => array("BOOKKEEPER, MAIN", "Bookkeeper, Maintenance"),
        "81098" => array("MESSENGER, MAIN", "Messenger/Deliveryman, Maintenance"),
        "81099" => array("OTH CLER, MAIN", "Other Clerical Staff, Maintenance"),
        "82001" => array("DEP SUPER, AT", "Deputy Superintendent, Administrative Technology"),
        "82002" => array("ASSOC SUPER, AT", "Associate Superintendent, Administrative Technology"),
        "82003" => array("ASST SUPER, AT", "Assistant/Area Superintendent, Administrative Technology"),
        "82004" => array("EXEC DIR, AT", "Executive/General Director, Administrative Technology"),
        "82005" => array("DIR, AT", "Director, Administrative Technology"),
        "82006" => array("ASST DIR, AT", "Assistant Director, Administrative Technology"),
        "82007" => array("SUP, AT", "Supervisor, Administrative Technology"),
        "82008" => array("COOR, AT", "Coordinator, Administrative Technology"),
        "82009" => array("ADMIN SA, AT", "Administrator on Special Assignment, Administrative Technology"),
        "82010" => array("SPEC, AT", "Specialist/Manager, Administrative Technology"),
        "82011" => array("ADMIN ASST, AT", "Administrative Assistant, Administrative Technology"),
        "82012" => array("TSA, AT", "Teacher on Special Assignment, Administrative Technology"),
        "82020" => array("COMPU SYS ANALYST, AT", "Computer Systems Analyst, Administrative Technology"),
        "82021" => array("COMPU SYS USER ED, AT", "Computer Systems User Educator, Administrative Technology"),
        "82022" => array("COMPU PROG, AT", "Computer Programmer, Administrative Technology"),
        "82023" => array("COMPU OP, AT", "Computer Operator, Administrative Technology"),
        "82024" => array("DATA ENT SUP, AT", "Data Entry Supervisor, Administrative Technology"),
        "82025" => array("LEAD COMPU OP, AT", "Lead Computer Operator, Administrative Technology"),
        "82026" => array("MICROFILM CLERK, AT", "Microfilm Clerk, Administrative Technology"),
        "82027" => array("COMPU NET SPEC, AT", "Computer Network Specialist, Administrative Technology"),
        "82028" => array("PROG ANALYST, AT", "Programmer Analyst, Administrative Technology"),
        "82029" => array("SR PROG ANALYST, AT", "Senior Programmer Analyst, Administrative Technology"),
        "82030" => array("PROJ MGR, AT", "Project Manager, Administrative Technology"),
        "82031" => array("ASST COMPU PROG, AT", "Assistant Computer Programmer, Administrative Technology"),
        "82032" => array("TELECOMM SPEC, AT", "Telecommunications Specialist, Administrative Technology"),
        "82033" => array("TECH, AT", "Technician, Administrative Technology"),
        "82034" => array("INFO SPEC, AT", "Information Specialist, Administrative Technology"),
        "82035" => array("SYS SUPPORT SPEC, AT", "Systems Support Specialist, Administrative Technology"),
        "82090" => array("EXEC SEC, AT", "Executive Secretary, Administrative Technology"),
        "82091" => array("SEC, AT", "Secretary, Administrative Technology"),
        "82092" => array("CLERK TYP, AT", "Clerk Typist, Administrative Technology"),
        "82093" => array("CLERK, AT", "Clerk, Administrative Technology"),
        "82094" => array("OFF AIDE, AT", "Office Aide, Administrative Technology"),
        "82095" => array("RECEP, AT", "Receptionist, Administrative Technology"),
        "82096" => array("DATA ENT OP, AT", "Data Entry Operator, Administrative Technology"),
        "82097" => array("BOOKKEEPER, AT", "Bookkeeper, Administrative Technology"),
        "82098" => array("MESSENGER, AT", "Messenger/Deliveryman, Administrative Technology"),
        "82099" => array("OTH CLER, AT", "Other Clerical Staff, Administrative Technology"),
        "91001" => array("DEP SUPER, COMM", "Deputy Superintendent, Community Services"),
        "91002" => array("ASSOC SUPER, COMM", "Associate Superintendent, Community Services"),
        "91003" => array("ASST SUPER, COMM", "Assistant/Area Superintendent, Community Services"),
        "91004" => array("EXEC DIR, COMM", "Executive/General Director, Community Services"),
        "91005" => array("DIR, COMM", "Director, Community Services"),
        "91006" => array("ASST DIR, COMM", "Assistant Director, Community Services"),
        "91007" => array("SUP, COMM", "Supervisor, Community Services"),
        "91008" => array("COOR, COMM", "Coordinator, Community Services"),
        "91009" => array("ADMIN SA, COMM", "Administrator on Special Assignment, Community Services"),
        "91010" => array("SPEC, COMM", "Specialist/Manager, Community Services"),
        "91011" => array("ADMIN ASST, COMM", "Administrative Assistant, Community Services"),
        "91012" => array("TSA, COMM", "Teacher on Special Assignment, Community Services"),
        "91030" => array("REC SPEC", "Recreation Specialist"),
        "91031" => array("ACTIVITIES LEADER", "Activities Leader"),
        "91032" => array("PARENT SPEC, COMM", "Parent Specialist, Community Services"),
        "91033" => array("AIDE, COMM", "Aide, Community Services"),
        "91034" => array("OTH COMM PERS", "Other Community Services Personnel"),
        "91090" => array("EXEC SEC, COMM", "Executive Secretary, Community Services"),
        "91091" => array("SEC, COMM", "Secretary, Community Services"),
        "91092" => array("CLERK TYP, COMM", "Clerk Typist, Community Services"),
        "91093" => array("CLERK, COMM", "Clerk, Community Services"),
        "91094" => array("OFF AIDE, COMM", "Office Aide, Community Services"),
        "91095" => array("RECEP, COMM", "Receptionist, Community Services"),
        "91096" => array("DATA ENT OP, COMM", "Data Entry Operator, Community Services"),
        "91097" => array("BOOKKEEPER, COMM", "Bookkeeper, Community Services"),
        "91098" => array("MESSENGER, COMM", "Messenger/Deliveryman, Community Services"),
        "91099" => array("OTH CLER, COMM", "Other Clerical Staff, Community Services"),
        "99999" => array("UNDEFINED, OPEN POS", "Undefined, Open Position")
    );

    /* === Level of Education Descriptors === */
    public static $levelOfEducationDescriptors = array(
        "Associate's Degree (two years or more)",
        "Bachelor's",
        "Did Not Graduate High School",
        "Doctorate",
        "High School Diploma",
        "Master's",
        "Some College No Degree",
        "Specialist"
    );

    /* === Operational Status Descriptors === */
    public static $operationalStatusDescriptors = array(
        "A" => "Active",
        "C" => "Closed",
        "F" => "Future"
    );

    /* === Performance Evaluation Rating Level Codes === */
    public static $performanceEvaluationRatingLevels = array(
        "A" => array(
            "shortDescription" => "Unsatisfactory (Prior to 2011-12)",
            "description"      => "(Removed 2011-12) The teacher or principal was unsatisfactory",
        ),
        "B" => array(
            "shortDescription" => "Not Unsatisfactory (Prior to 2011-12)",
            "description"      => "(Removed 2011-12) The teacher or principal was not unsatisfactory",
        ),
        "C" => array(
            "shortDescription" => "Highly Effective",
            "description"      => "The instr. staff or school admin. determined to be highly effective",
        ),
        "D" => array(
            "shortDescription" => "Effective",
            "description"      => "The instr. staff or school admin. determined to be effective",
        ),
        "E" => array(
            "shortDescription" => "Needs Improvement",
            "description"      => "The instr. staff or school admin. determined to need improvement",
        ),
        "F" => array(
            "shortDescription" => "Developing",
            "description"      => "The instr. staff in first 3 years needs improvement and is developing",
        ),
        "G" => array(
            "shortDescription" => "Unsatisfactory",
            "description"      => "The instr. staff or school admin. was determined to be unsatisfactory",
        ),
        "H" => array(
            "shortDescription" => "Not Evaluated",
            "description"      => "The instr. staff or school admin. was req to be eval but was not eval",
        ),
        "I" => array(
            "shortDescription" => "Not Required",
            "description"      => "The instr. staff or school admin. was not required to be evaluated",
        ),
        "Z" => array(
            "shortDescription" => "Not Applicable",
            "description"      => "The staff member is not an instr. staff or a school admin",
        )
    );

    /* === Performance Evaluation Types === */
    public static $performanceEvaluationTypes = array(
        "Administrative",
        "Instructional",
        "Other"
    );

    /* === Race Types === */
    public static $raceTypes = array(
        "American Indian - Alaska Native",
        "Asian",
        "Black - African American",
        "Choose Not to Respond",
        "Native Hawaiian - Pacific Islander",
        "Other",
        "White"
    );

    /* === DOE Separation Reason Codes === */
    /* See Staff Database Manual for latest updates. (http://www.fldoe.org/accountability/data-sys/database-manuals-updates/) */
    public static $separationReasonCodes = array(
        "A" => "Retirement",
        "B" => "Resignation for employment in education in Florida",
        "C" => "Resignation for employment outside of education",
        "D" => "Resignation with prejudice",
        "E" => "Resignation for other personal reasons",
        "F" => "Staff reduction",
        "G" => "Dismissal due to findings by the board related to charges",
        "H" => "Death",
        "I" => "Contract expired",
        "J" => "Reason not known",
        "K" => "Disabled",
        "L" => "Resignation for employment in education outside Florida",
        "M" => "Contract not renewed, due to less than satisfactory performance",
        "N" => "Dismissal during probationary period.",
        "O" => "Job Abandonment",
        "P" => array(
            "short" => "Classroom teachers or principals dismissed for ineffective performance.",
            "long"  => "Classroom teachers or principals who were dismissed for ineffective performance as demonstrated through the district's evaluation system."
        ),
        "Z" => "Not applicable. Include temporary employees here.",
    );

    /* === Sex/Gender Types === */
    public static $sexTypes = array(
        "Female",
        "Male",
        "Not Selected"
    );

    /* === School Category Types === */
    public static $schoolCategories = array(
        "School"
    );

    /* === Source Systems === */
    public static $sourceSystems = array(
        "District",
        "Federal",
        "School",
        "Skyward",
        "State"
    );

    /* === Staff Identification Descriptors === */
    public static $staffIdentificationDescriptors = array(
        array('Business Name ID', 'Business Name ID', 'Business Name Identifier', 'District'),
        array('Employee ID', 'Employee ID', 'Employee Identifier', 'District'),
        array('FLEID', 'FLEID', 'Florida Education Identifier', 'State'),
        array('SSN', 'SSN', 'Social Security Number', 'SSN'),
        array('Professional Certificate', 'Professional Certificate', 'Professional Certificate', 'State'),
        array('Business Alphakey', 'Business Alphakey', 'Business Alphakey', 'District'),
        array('Business Username', 'Business Username', 'Business Username', 'District')
    );

    /* === State Abbreviation Codes === */
    public static $stateAbbreviationCodes = array(
        "AK" => "Alaska",
        "AL" => "Alabama",
        "AR" => "Arkansas",
        "AZ" => "Arizona",
        "CA" => "California",
        "CO" => "Colorado",
        "CT" => "Connecticut",
        "DC" => "District of Columbia",
        "DE" => "Delaware",
        "FL" => "Florida",
        "GA" => "Georgia",
        "HI" => "Hawaii",
        "IA" => "Iowa",
        "ID" => "Idaho",
        "IL" => "Illinois",
        "IN" => "Indiana",
        "KS" => "Kansas",
        "KY" => "Kentucky",
        "LA" => "Louisiana",
        "MA" => "Massachusetts",
        "MD" => "Maryland",
        "ME" => "Maine",
        "MI" => "Michigan",
        "MN" => "Minnesota",
        "MO" => "Missouri",
        "MS" => "Mississippi",
        "MT" => "Montana",
        "NC" => "North Carolina",
        "ND" => "North Dakota",
        "NE" => "Nebraska",
        "NH" => "New Hampshire",
        "NJ" => "New Jersey",
        "NM" => "New Mexico",
        "NV" => "Nevada",
        "NY" => "New York",
        "OH" => "Ohio",
        "OK" => "Oklahoma",
        "OR" => "Oregon",
        "PA" => "Pennsylvania",
        "RI" => "Rhode Island",
        "SC" => "South Carolina",
        "SD" => "South Dakota",
        "TN" => "Tennessee",
        "TX" => "Texas",
        "UT" => "Utah",
        "VA" => "Virginia",
        "VT" => "Vermont",
        "WA" => "Washington",
        "WI" => "Wisconsin",
        "WV" => "West Virginia",
        "WY" => "Wyoming",
        "ZZ" => "Not Applicable"
    );

    /* === Telephone Number Type Descriptors === */
    public static $telephoneNumberTypes = array(
        "Primary",
        "Secondary",
        "Tertiary",
        "Home",
        "Mobile",
        "Work",
        "Other"
    );






    /* ======================
     * ===   DEPRECATED   ===
     * ====================== */

    /* === Certificate Field Descriptors ===*/
    public static $certificateSubjectAreas = array(
        "1000" => "Administration of Adult Education",
        "1001" => "Art",
        "1002" => "Athletic Coaching",
        "1003" => "Biology",
        "1004" => "Chemistry",
        "1005" => "World Language - Chinese",
        "1006" => "Computer Science",
        "1007" => "Dance",
        "1008" => "Drama",
        "1009" => "Earth-Space Science",
        "1011" => "Educational Leadership",
        "1012" => "Educational Media Specialist",
        "1013" => "Elementary Education",
        "1015" => "English",
        "1016" => "English for Speakers of Other Languages (ESOL)",
        "1017" => "World Language - French",
        "1019" => "World Language - German",
        "1020" => "World Language - Greek",
        "1021" => "School Counselor",
        "1022" => "Health",
        "1023" => "Hearing Impaired",
        "1024" => "World Language - Hebrew",
        "1026" => "Humanities",
        "1027" => "World Language - Italian",
        "1028" => "World Language - Japanese",
        "1030" => "World Language - Latin",
        "1031" => "Mathematics",
        "1033" => "Middle Grades General Science",
        "1035" => "Music",
        "1036" => "Physical Education",
        "1038" => "Physics",
        "1040" => "World Language - Portuguese",
        "1041" => "PreKindergarten/Primary Education",
        "1042" => "Preschool Education (Birth through Age 4)",
        "1046" => "Reading",
        "1047" => "World Language - Russian",
        "1049" => "School Principal",
        "1050" => "School Psychologist",
        "1051" => "School Social Worker",
        "1052" => "Social Science",
        "1054" => "World Language - Spanish",
        "1057" => "Speech-Language Impaired",
        "1059" => "Visually Impaired",
        "1061" => "Driver Education",
        "1062" => "Gifted",
        "1064" => "Orientation and Mobility",
        "1065" => "PreKindergarten Disabilities",
        "1066" => "Severe or Profound Disabilities",
        "1067" => "Agriculture",
        "1068" => "Business Education",
        "1069" => "Family and Consumer Science",
        "1070" => "Engineering and Technology Education",
        "1071" => "Local Director of Career and Technical Education",
        "1072" => "Marketing",
        "1077" => "Exceptional Student Education",
        "1078" => "Autism Spectrum Disorders",
        "1079" => "American Sign Language",
        "1080" => "World Language - Arabic",
        "1081" => "World Language - Farsi",
        "1082" => "World Language - Haitian Creole",
        "1083" => "World Language - Hindi",
        "1084" => "World Language - Turkish"
    );













































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
         ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
         ,"HTODRS"."HTODRS-ABSENCE-TYPE"
         ,"HTODRS"."HTODRS-DESC"
         ,"HTOTRN"."HTOTRN-HRS"
         ,"HTOTRN"."HTOTRN-SUB-NAME-ID"
         ,"HTOTRN"."HTOTRN-TRANS-DATE"
         ,"HTOTRN"."NAME-ID"

        FROM "SKYWARD"."PUB"."HTOTRN-TRANS" "HTOTRN"
        INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
         "HTOTRN"."NAME-ID" = "HAAPRO"."NAME-ID"
        LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
         "HAAPRO"."NAME-ID" = "N"."NAME-ID"
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
                                                                                -- Replaced NOW() with snapshotDate placeholder 1/26/26
                                                                                AND C."HPMCEM-EXP-DATE" >= \'%%snapshotDate%%\'
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
            "HPMCEM"."HPMCEM-EXP-DATE" >= \'%%snapshotDate%%\' AND
            "HPMCEM"."HPMCEM-ISSUE-DATE" <= \'%%snapshotDate%%\'
    ';

    // This one does not need changes as it has no date logic
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
         ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
         ,"HTODRS"."HTODRS-ABSENCE-TYPE"
         ,"HTODRS"."HTODRS-DESC"
         ,"HTOTRN"."HTOTRN-HRS"
         ,"HTOTRN"."HTOTRN-SUB-NAME-ID"
         ,"HTOTRN"."HTOTRN-TRANS-DATE"
         ,"HTOTRN"."NAME-ID"

        FROM "SKYWARD"."PUB"."HTOTRN-TRANS" "HTOTRN"
        INNER JOIN "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO" ON
         "HTOTRN"."NAME-ID" = "HAAPRO"."NAME-ID"
        LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
         "HAAPRO"."NAME-ID" = "N"."NAME-ID"
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
         ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
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
         ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
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
        LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
         "HAAPRO"."NAME-ID" = "N"."NAME-ID"
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
         "HPMASN"."HPMASN-START-DATE" <= \'%%snapshotDate%%\' AND
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
         ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'

        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
         "HAAPRO"."NAME-ID" = "N"."NAME-ID"
        LEFT JOIN "SKYWARD"."PUB"."HPETER-TERM-CODES" "HPETER" ON
         "HAAPRO"."HPETER-TERM-CODE" = "HPETER"."HPETER-TERM-CODE"
        INNER JOIN "SKYWARD"."PUB"."HAABLD-BLD-CODES" "HAABLD" ON
        "HP"."HAABLD-BLD-CODE" = "HAABLD"."HAABLD-BLD-CODE"
        
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
         ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
         ,"HPAPRM"."HPADCP-PAY-CODE"
         ,"HPAPRM"."HPAPRM-START-DATE"
         ,"HPAPRM"."HPAPRM-STOP-DATE"

        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
         "HAAPRO"."NAME-ID" = "N"."NAME-ID"
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
         ,%%staffIdColumn%% AS \'STAFF-UNIQUE-ID\'
         ,"HPAPRM"."HPADCP-PAY-CODE"
         ,"HPAPRM"."HPAPRM-START-DATE"
         ,"HPAPRM"."HPAPRM-STOP-DATE"

        FROM "SKYWARD"."PUB"."HAAPRO-PROFILE" "HAAPRO"
        LEFT JOIN "SKYWARD"."PUB"."NAME" "N" ON
         "HAAPRO"."NAME-ID" = "N"."NAME-ID"
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
         "QRBGRT"."QRBGRT-ID" = 20 AND
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

    /* ====================================
     * ===   Static Descriptor Values   ===
     * ==================================== */

    /**
     * Shared function to get credentials for a single staff member.
     * Handles swapping the %%snapshotDate%% placeholder automatically.
     */
    public static function shared_getCredentials($nameId, $snapshotDate = null) {
        $db = driver::$ingressClassName::getDBPointer();
        
        // Point to the query inside the subqueries array
        $sql = self::$subqueries['staffCredentials']; 

        // Handle the Date Logic
            // If no date passed, calculate it based on your config settings
        if ($snapshotDate === null) {
             $snapshotDate = (config::$useHistoricalData) ? date('m/d/Y', strtotime(config::$historicalSnapshotDate)) : date('m/d/Y');
        }

        // Swap the placeholders
        $sql = str_replace('%%nameId%%', $nameId, $sql);
        $sql = str_replace('%%snapshotDate%%', $snapshotDate, $sql); 

        return $db->query($sql);
    }
}

?>
