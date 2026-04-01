<?php
class custom_floridacode_queries_InterchangeFinance {

    public static $shared_Account = 'SELECT DISTINCT
                                        FAM."FFAMAM-EDITED-ACCT",
                                        FAM."FFAMAM-DIM-1" AS DIM_FUND,
                                        FAM."FFAMAM-DIM-2" AS DIM_TYPE,
                                        FAM."FFAMAM-DIM-3" AS DIM_FUNCTION,
                                        FAM."FFAMAM-DIM-4" AS DIM_OBJECT,
                                        FAM."FFAMAM-DIM-5" AS DIM_FACILITY,
                                        FAM."FFAMAM-DIM-6" AS DIM_PROJECT,
                                        FAM."FFAMAM-DIM-7" AS DIM_SUBPROJECT,
                                        FAM."FFAMAM-DIM-8" AS DIM_PROGRAM,
                                        FFY."FFAMFA-FIS-YEAR"
                                    FROM
                                        pub."FFAMFA-FIS-YTD" AS FFY
                                        INNER JOIN pub."FFAMAM-ACCT-MST" AS FAM
                                            ON FAM."FFAMAM-ACCT-ID" = FFY."FFAMAM-ACCT-ID"
                                    WHERE
                                        --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                        FFY."FFAMFA-FIS-YEAR" >= %%beginyear%%
                                        AND FAM."FFAMAM-DIM-5" IN (%%entities%%)';

    public static $shared_Actual = 'SELECT DISTINCT
                                        FAM."FFAMAM-EDITED-ACCT",
                                        FAM."FFAMAM-DIM-5" AS DIM_FACILITY,
                                        FFY."FFAMFA-FIS-YEAR",
                                        (FFY."FFAMFA-BEG-BALANCE" + FFY."FFAMFA-AMT-DEBIT" - FFY."FFAMFA-AMT-CREDIT" - FFY."FFAMFA-ENC") AS ENDINGBALANCE
                                    FROM
                                        pub."FFAMFA-FIS-YTD" AS FFY
                                        INNER JOIN pub."FFAMAM-ACCT-MST" AS FAM
                                            ON FAM."FFAMAM-ACCT-ID" = FFY."FFAMAM-ACCT-ID"
                                    WHERE
                                        --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                        FFY."FFAMFA-FIS-YEAR" >= %%beginyear%%
                                        AND FAM."FFAMAM-DIM-5" IN (%%entities%%)';

    public static $shared_Budget = 'SELECT DISTINCT
                                        FAM."FFAMAM-EDITED-ACCT",
                                        FBL."FFAMBL-AMOUNT",
                                        FBL."FFAMBL-FIS-YEAR",
                                        FAM."FFAMAM-DIM-5" AS DIM_FACILITY
                                    FROM
                                        pub."FFAMBL-BUDGET-LEVEL" AS FBL
                                        INNER JOIN pub."FFAMAM-ACCT-MST" AS FAM
                                            ON FAM."FFAMAM-ACCT-ID" = FBL."FFAMAM-ACCT-ID"
                                    WHERE
                                        --FAM."FFAMAM-ACTIVE-FLAG" = 1
                                        FBL."FFAMBL-FIS-YEAR" >= %%beginyear%%
                                        AND FAM."FFAMAM-DIM-5" IN (%%entities%%)';

    public static $shared_ContractedStaff = 'SELECT
                                                    FVM."FFVMVM-VEN-ID",
                                                    N."LAST-NAME",
                                                    N."NALPHAKEY",
                                                    tmpTblYTD."FFAPIM-FIS-YR",
                                                    tmpTblYTD."ENDINGBALANCE"
                                                FROM
                                                    pub."FFVMVM-VEN-MST" AS FVM
                                                    INNER JOIN pub."NAME" AS N
                                                        ON N."NAME-ID" = FVM."FFVMVM-VEN-ID"
                                                    INNER JOIN (
                                                        SELECT
                                                            FIM."FFVMVM-VEN-ID",
                                                            FIM."FFAPIM-FIS-YR",
                                                            SUM(FIM."FFAPIM-AMT") AS ENDINGBALANCE
                                                        FROM
                                                            pub."FFAPIM-INVOICE-MST" AS FIM
                                                        GROUP BY
                                                            FIM."FFVMVM-VEN-ID",
                                                            FIM."FFAPIM-FIS-YR"
                                                    ) AS tmpTblYTD
                                                        ON tmpTblYTD."FFVMVM-VEN-ID" = FVM."FFVMVM-VEN-ID"';

    public static $shared_Payroll = 'SELECT DISTINCT
                                            N."NAME-ID",
                                            tmpTblYTD."HPAHSF-YEAR",
                                            tmpTblYTD."AMT",
                                            tmpTblYTD."PAMT",
                                            tmpContributors000."DIM_FACILITY",
                                            tmpContributors000."FFAMAM-EDITED-ACCT",
                                            tmpContributors000."percentage"
                                        FROM
                                            pub."NAME" AS N
                                            INNER JOIN (
                                                SELECT
                                                    HFH."NAME-ID",
                                                    HFH."HPAHSF-YEAR",
                                                    SUM(HFH."HPAHSF-AMT") AS AMT,
                                                    SUM(HFH."HPAHSF-PAYABLES-AMT") AS PAMT
                                                FROM
                                                    pub."HPAHSF-FTD-HIS" AS HFH
                                                WHERE
                                                    HFH."HPAHSF-TYPE" = \'@\'
                                                GROUP BY
                                                    HFH."NAME-ID",
                                                    HFH."HPAHSF-YEAR"
                                            ) AS tmpTblYTD
                                                ON tmpTblYTD."NAME-ID" = N."NAME-ID"
                                            INNER JOIN (
                                                SELECT
                                                    HA."NAME-ID",
                                                    HA."HPMASN-FIS-YEAR",
                                                    FAM."FFAMAM-EDITED-ACCT",
                                                    FAM."FFAMAM-DIM-5" AS DIM_FACILITY,
                                                    MAX(HAD."HAAACC-PCT") AS percentage
                                                FROM
                                                    pub."HPMASN-ASSIGNMENTS" AS HA
                                                    INNER JOIN pub."HAAACC-ACCT-DIST" AS HAD
                                                        ON HAD."HAAACC-SRC-ID" = HA."HPMASN-ID"
                                                    INNER JOIN pub."FFAMAM-ACCT-MST" AS FAM
                                                        ON FAM."FFAMAM-ACCT-ID" = HAD."FFAMAM-ACCT-ID"
                                                WHERE
                                                     FAM."FFAMAM-DIM-5" IN (%%entities%%)
                                                GROUP BY
                                                    HA."NAME-ID",
                                                    HA."HPMASN-FIS-YEAR",
                                                    FAM."FFAMAM-EDITED-ACCT",
                                                    FAM."FFAMAM-DIM-5"
                                            ) AS tmpContributors000
                                                ON tmpContributors000."NAME-ID" = N."NAME-ID"
                                                AND tmpContributors000."HPMASN-FIS-YEAR" = tmpTblYTD."HPAHSF-YEAR"
                                        WHERE
                                             N."NAME-ID" IN(%%nameIDs%%)
                                             AND tmpTblYTD."HPAHSF-YEAR" >= %%beginyear%%
                                        ORDER BY
                                            N."NAME-ID" ASC,
                                            tmpTblYTD."HPAHSF-YEAR" ASC,
                                            tmpContributors000."percentage" DESC';

    public static $shared_getFirstInvoiceByVendorIDAndFY = 'SELECT TOP 1
                                                                FAM."FFAMAM-EDITED-ACCT",
                                                                FAM."FFAMAM-DIM-5" AS DIM_FACILITY
                                                            FROM
                                                                pub."FFAPIM-INVOICE-MST" AS FIM
                                                                INNER JOIN pub."FFAPIA-INVOICE-ACCTS" AS FIA
                                                                    ON FIA."FFAPIM-ID" = FIM."FFAPIM-ID"
                                                                INNER JOIN pub."FFAMAM-ACCT-MST" AS FAM
                                                                    on FAM."FFAMAM-ACCT-ID" = FIA."FFAMAM-ACCT-ID"
                                                            WHERE
                                                                FIM."FFAPIM-FIS-YR" = %%fiscalyear%%
                                                                AND FIM."FFVMVM-VEN-ID" = %%vendornameid%%
                                                                AND FAM."FFAMAM-DIM-5" IN (%%entities%%)
                                                            ORDER BY
                                                                FIA."FFAPIA-AMOUNT" DESC';

    public static $shared_getPersonAssociatedWithVendorByVendorIDAndFY = 'SELECT TOP 1
                                                                                FIM."FFAPIM-CREATE-BY"
                                                                            FROM
                                                                                pub."FFAPIM-INVOICE-MST" AS FIM
                                                                            WHERE
                                                                                FIM."FFVMVM-VEN-ID" = %%vendorid%%
                                                                                AND FIM."FFAPIM-FIS-YR" = %%fiscalyear%%';

    public static $shared_getMajorityFundSourceForPersonByFY = 'SELECT TOP 1
                                                                    FAM."FFAMAM-EDITED-ACCT",
                                                                    FAM."FFAMAM-DIM-5" AS DIM_FACILITY
                                                                FROM
                                                                    pub."HPMASN-ASSIGNMENTS" AS HA
                                                                    INNER JOIN pub."HAAACC-ACCT-DIST" AS HAD
                                                                        ON HAD."HAAACC-SRC-ID" = HA."HPMASN-ID"
                                                                    INNER JOIN pub."FFAMAM-ACCT-MST" AS FAM
                                                                        ON FAM."FFAMAM-ACCT-ID" = HAD."FFAMAM-ACCT-ID"
                                                                WHERE
                                                                    HA."NAME-ID" = %%nameid%%
                                                                    AND HA."HPMASN-FIS-YEAR" = %%fiscalyear%%
                                                                    AND FAM."FFAMAM-DIM-5" IN (%%entities%%)
                                                                ORDER BY
                                                                    HAD."HAAACC-PCT" DESC,
                                                                    HAD."HAAACC-DEPT" DESC';
}
?>