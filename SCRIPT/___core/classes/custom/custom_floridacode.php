<?php

/**
 * This is a utility class to convert some skyward-ese data into useful information
 * @package MMExtranet
 * @version 1.0
 */

class custom_floridacode {

    //jobcode descriptor overrides
    public const jobCodes = array (
            //'jobCode', 'Short Description', 'Long Description', 'Start Year', 'End Year', EEO #, 'Edfi Value'
        );

    /**
     * searches state jobcodes array by jobcode and returns requested value index
     * @access public
     * @param string $jobCode the job code
     * @return string
     */
    final public static function getStateCodeIndexValueByJobcode(string $jobCode, int $arrayRecordIndex): string {
        $key = array_search($jobCode, array_column(self::jobCodes, 0));
        if ($key !== false) {
            return self::jobCodes[$key][$arrayRecordIndex] ?? '';
        }
        else {
            $key = array_search($jobCode, array_column(custom_statecodes::jobCodes, 0));
            if ($key !== false) {
                return custom_statecodes::jobCodes[$key][$arrayRecordIndex] ?? '';
            }
            else {
                return '';
            }
        }
    }

    /**
     * converts highest education code to one of the Florida Code enumerated options
     * @access public
     * @param string $code highest education code
     * @return string
     */
    final public static function highestEducationCode(string $code): string {
        switch($code) {
            case 'B':
                return 'Bachelor\'s';
                break;

            case 'A':
                return 'Associate\'s Degree (two years or more)';
                break;

            case 'D':
                return 'Doctorate';
                break;

            case 'M':
                return 'Master\'s';
                break;

            case 'H':
                return 'High School Diploma';
                break;

            case 'S':
                return 'Specialist';
                break;

            case 'C':
                return 'Some College No Degree';
                break;

            case 'N':
            case 'Z':
            default:
                return 'Did Not Graduate High School';
                break;
        }
    }

    /**
     * converts race code to one of the Florida Code enumerated options
     * @access public
     * @param string $raceCode local race code from Skyward
     * @return string
     */
    final public static function raceCodeToDescription(string $raceCode): string {
        switch($raceCode) {
            default:
            case 'W':
                return 'White';
                break;

            case 'I':
                return 'American Indian - Alaskan Native';
                break;

            case 'H':
                return 'White'; //florida code doesn't have a hispanic enumerated option
                break;

            case 'B':
                return 'Black - African American';
                break;

            case 'A':
                return 'Asian';
                break;
        }
    }

    /**
     * Converts the Skyward federal race flags value ("00000") to Ed-Fi race descriptions.
     * @access public
     * @param $fedRaceFlags
     * @return array(string)
     */
    final public static function fedRaceFlagsToDescription($fedRaceFlags) {
        $types = array();
        $fedRaceFlags = str_split($fedRaceFlags);

        /* American Indian/Alaskan Native */
        if ($fedRaceFlags[0] === "1") { $types[] = array("raceType" => 'American Indian - Alaskan Native'); }

        /* Asian */
        if ($fedRaceFlags[1] === "1") { $types[] = array("raceType" => 'Asian'); }

        /* Black/African-American */
        if ($fedRaceFlags[2] === "1") { $types[] = array("raceType" => 'Black - African American'); }

        /* Native Hawaiian/Pacific Islander */
        if ($fedRaceFlags[3] === "1") { $types[] = array("raceType" => 'Native Hawaiian - Pacific Islander'); }

        /* White */
        if ($fedRaceFlags[4] === "1") { $types[] = array("raceType" => 'White'); }

        /* Two or More Races */
      //if (array_sum($fedRaceFlags) > 1) { $types[] = array("raceType" => 'Two or More Races'); }

        return $types;
    }

    /**
     * converts state leave code to one of the Florida Code enumerated options
     * @access public
     * @param string $stateLeaveCode state leave code from Skyward
     * @return string
     */
    final public static function stateHRLeaveCodeToFloridaCodeEnumeration(string $stateLeaveCode): string {
        /*
            Administrative
            Annual leave
            Bereavement
            Compensatory leave time
            Family and medical leave
            Flex time
            Government-requested
            Jury duty
            Military leave
            Other
            Personal
            Professional development
            Release time
            Sabbatical leave
            Sick leave
            Suspension
            Vacation
            Work compensation
        */
        switch($stateLeaveCode) {
            default:
                return 'Other';
                break;

            case 'P':
                return 'Personal';
                break;

            case 'S':
                return 'Sick leave';
                break;

            case 'T':
                return 'Professional development';
                break;
        }
    }

    /**
     * converts gender code to descriptive string
     * @access public
     * @param string $genderCode gender code
     * @return string
     */
    final public static function genderCodeToDescription(string $genderCode): string {
        switch($genderCode) {
            default:
                return 'Not Selected';
                break;

            case 'F':
                return 'Female';
                break;

            case 'M':
                return 'Male';
                break;
        }
    }

    /**
     * converts term code to descriptive string
     * @access public
     * @param string $termCode term code
     * @return string
     */
    final public static function termCodeToDescription(string $termCode): string {
        switch($termCode) {
            default:
                return 'Year Round';
                break;

            case 'Q1':
                return 'First Quarter';
                break;

            case 'Q2':
                return 'Second Quarter';
                break;

            case 'Q3':
                return 'Third Quarter';
                break;

            case 'Q4':
                return 'Fourth Quarter';
                break;
        }
    }

    /**
     * converts class-meet teacher type code to descriptive string
     * @access public
     * @param string $typeCode type code
     * @return string
     */
    final public static function classMeetTeacherTypeCodeToDescription(string $typeCode): string {
        switch($typeCode) {
            default:
                return 'Support Teacher';
                break;

            case 'P':
                return 'Teacher of Record';
                break;

            case 'A':
                return 'Assistant Teacher';
                break;
        }
    }

    /**
     * converts class-meet teacher-highly-qualified code to boolean string
     * @access public
     * @param string $code teacher-highly-qualified code
     * @return string
     */
    final public static function teacherHighlyQualifiedCodeToBoolString(string $code): string {
        switch($code) {
            default:
                return 'false';
                break;

            case 'Y':
                return 'true';
                break;
        }
    }

    /**
     * attempts to convert free-form termination reason to something FC wants
     * @access public
     * @param string $terminationReason freeform reason from skyward
     * @return string
     */
    final public static function attemptConvertTerminationReasonToFloridaCodeEnumeration(string $terminationReason): string {
        $terminationReason = strtolower($terminationReason);

        if (strpos($terminationReason, 'assignment') !== false) {
            return 'Change of assignment';
        }
        else if (strpos($terminationReason, 'elsewhere') !== false) {
            return 'Employment elsewhere';
        }
        else if (strpos($terminationReason, 'relocat') !== false) {
            return 'Family/personal relocation';
        }
        else if (strpos($terminationReason, 'search') !== false || strpos($terminationReason, 'study') !== false) {
            return 'Formal study or research';
        }
        else if (strpos($terminationReason, 'ill') !== false || strpos($terminationReason, 'death') !== false || strpos($terminationReason, 'disab') !== false) {
            return 'Illness/disability/death';
        }
        else if (strpos($terminationReason, 'layoff') !== false) {
            return 'Layoff';
        }
        else if (strpos($terminationReason, 'persona') !== false) {
            return 'Personal reason';
        }
        else if (strpos($terminationReason, 'retire') !== false) {
            return 'Retirement';
        }
        else if (strpos($terminationReason, 'othe') !== false) {
            return 'Other';
        }
        else {
            return 'Unknown';
        }
    }

    /**
     * attempts to convert free-form program assignment to something FC wants
     * @access public
     * @param string $programAssignment freeform text from skyward
     * @return string
     */
    final public static function attemptConvertProgramAssignmentToFloridaCodeEnumeration(string $programAssignment): string {

        /*
            Bilingual/English as a Second Language
            Other
            Regular Education
            Special Education
            Title I-Academic
            Title I-Non-Academic
        */
        $programAssignment = strtolower($programAssignment);

        if (strpos($programAssignment, 'ell') !== false || strpos($programAssignment, 'esol') !== false) {
            return 'Bilingual/English as a Second Language';
        }
        else if (strpos($programAssignment, 'spec') !== false) {
            return 'Special Education';
        }
        else if (strpos($programAssignment, 'instruction') !== false) {
            return 'Regular Education';
        }
        else if (strpos($programAssignment, 'title') !== false && strpos($programAssignment, 'non') !== false) {
            return 'Title I-Non-Academic';
        }
        else if (strpos($programAssignment, 'title')) {
            return 'Title I-Academic';
        }
        else {
            return 'Other';
        }
    }

    /**
     * attempts to convert credential type to a FC enumeration
     * @access public
     * @param string $credentialType description from skyward
     * @return string
     */
    final public static function attemptConvertCredentialTypeToFloridaCodeEnumeration(string $credentialType): string {
        $credentialType = strtolower($credentialType);

        if (strpos($credentialType, 'cert') !== false || strpos($credentialType, 'prof') !== false || strpos($credentialType, 'temp') !== false) {
            return 'Certification';
        }
        else if (strpos($credentialType, 'dorse') !== false) {
            return 'Endorsement';
        }
        else if (strpos($credentialType, 'icens') !== false) {
            return 'Licensure';
        }
        else if (strpos($credentialType, 'regis') !== false) {
            return 'Registration';
        }
        else {
            return 'Other';
        }
    }

    /**
     * attempts to convert teaching credential type to a FC enumeration
     * @access public
     * @param string $credentialType description from skyward
     * @return string
     */
    final public static function attemptConvertTeachingCredentialTypeToFloridaCodeEnumeration(string $credentialType): string {
        /*
            Emergency
            Intern
            Master
            Nonrenewable
            Other
            Paraprofessional
            Probationary/Initial
            Professional
            Provisional
            Regular/Standard
            Retired
            Specialist
            Substitute
            Teacher Assistant
            Temporary
        */
        $credentialType = strtolower($credentialType);

        if (strpos($credentialType, 'pf') !== false || strpos($credentialType, 'pl') !== false || strpos($credentialType, '04') !== false) {
            return 'Professional';
        }
        else if (strpos($credentialType, 'tp') !== false || strpos($credentialType, 'fl') !== false || strpos($credentialType, 'tm') !== false) {
            return 'Temporary';
        }
        else if (strpos($credentialType, 'rg') !== false || strpos($credentialType, 'ac') !== false || strpos($credentialType, 'dc') !== false || strpos($credentialType, '07') !== false) {
            return 'Regular/Standard';
        }
        else if (strpos($credentialType, 'td') !== false || strpos($credentialType, 'np') !== false) {
            return 'Nonrenewable';
        }
        else if (strpos($credentialType, 'ac') !== false) {
            return 'Nonrenewable';
        }
        else {
            log::logAlert('Unknown Credential Code: ' . $credentialType, 5);
            return 'Other';
        }
    }

    /**
     * attempts to convert credential field to a FC enumeration
     * @access public
     * @param string $credentialField description from skyward
     * @return string
     */
    final public static function attemptConvertCredentialFieldToFloridaCodeEnumeration(string $credentialField): string {
        $credentialField = strtolower($credentialField);

        if (strpos($credentialField, 'eng') !== false) {
            return 'English Language Arts';
        }
        else if (strpos($credentialField, 'read') !== false || strpos($credentialField, 'scie') !== false || strpos($credentialField, 'tech') !== false) {
            return 'Reading';
        }
        else if (strpos($credentialField, 'math') !== false) {
            return 'Mathematics';
        }
        else if ((strpos($credentialField, 'life') !== false || strpos($credentialField, 'phys') !== false) && strpos($credentialField, 'sci') !== false || strpos($credentialField, 'heal') !== false) {
            return 'Life and Physical Sciences';
        }
        else if (strpos($credentialField, 'sci') !== false || strpos($credentialField, 'his') !== false) {
            return 'Social Sciences and History';
        }
        else if (strpos($credentialField, 'soc') !== false) {
            return 'Social Studies';
        }
        else if (strpos($credentialField, 'sci') !== false || strpos($credentialField, 'bio') !== false) {
            return 'Science';
        }
        else if (strpos($credentialField, 'art') !== false) {
            return 'Fine and Performing Arts';
        }
        else if (strpos($credentialField, 'lang') !== false || strpos($credentialField, 'span') !== false || strpos($credentialField, 'fren') !== false) {
            return 'Foreign Language and Literature';
        }
        else if (strpos($credentialField, 'writ') !== false) {
            return 'Writing';
        }
        else if (strpos($credentialField, 'phys') !== false || strpos($credentialField, 'pe') !== false || strpos($credentialField, 'athl') !== false) {
            return 'Physical, Health, and Safety Education';
        }
        else if (strpos($credentialField, 'tech') !== false || strpos($credentialField, 'ed') !== false || strpos($credentialField, 'busin') !== false || strpos($credentialField, 'primar') !== false || strpos($credentialField, 'elem') !== false || strpos($credentialField, 'ag') !== false) {
            return 'Career and Technical Education';
        }
        else if (strpos($credentialField, 'relig') !== false || strpos($credentialField, 'theol') !== false) {
            return 'Religious Education and Theology';
        }
        else if (strpos($credentialField, 'mil') !== false) {
            return 'Military Science';
        }
        else {
            return 'Other';
        }
    }

    /**
     * attempts to convert credential level to a FC enumeration
     * @access public
     * @param string $credentialLevel description from skyward
     * @return string
     */
    final public static function attemptConvertCredentialLevelToFloridaCodeEnumeration(string $credentialLevel): string {
        $credentialLevel = strtolower($credentialLevel);

        if (strpos($credentialLevel, 'adult') !== false) {
            return 'Adult Education';
        }
        else if (strpos($credentialLevel, 'early') !== false) {
            return 'Early Education';
        }
        else if (strpos($credentialLevel, 'infa') !== false || strpos($credentialLevel, 'todd') !== false) {
            return 'Infant/toddler';
        }
        else if (strpos($credentialLevel, 'pre') !== false) {
            return 'Preschool/Prekindergarten';
        }
        else if (strpos($credentialLevel, 'inderga') !== false || strpos($credentialLevel, 'k') !== false) {
            return 'Kindergarten';
        }
        else if (strpos($credentialLevel, '13') !== false) {
            return 'Grade 13';
        }
        else if (strpos($credentialLevel, '12') !== false) {
            return 'Twelfth grade';
        }
        else if (strpos($credentialLevel, '11') !== false) {
            return 'Eleventh grade';
        }
        else if (strpos($credentialLevel, '10') !== false) {
            return 'Tenth grade';
        }
        else if (strpos($credentialLevel, '1') !== false) {
            return 'First grade';
        }
        else if (strpos($credentialLevel, '2') !== false) {
            return 'Second grade';
        }
        else if (strpos($credentialLevel, '3') !== false) {
            return 'Third grade';
        }
        else if (strpos($credentialLevel, '4') !== false) {
            return 'Fourth grade';
        }
        else if (strpos($credentialLevel, '5') !== false) {
            return 'Fifth grade';
        }
        else if (strpos($credentialLevel, '6') !== false) {
            return 'Sixth grade';
        }
        else if (strpos($credentialLevel, '7') !== false) {
            return 'Seventh grade';
        }
        else if (strpos($credentialLevel, '8') !== false) {
            return 'Eighth grade';
        }
        else if (strpos($credentialLevel, '9') !== false) {
            return 'Nineth grade';
        }
        else if (strpos($credentialLevel, 'seconda') !== false) {
            return 'Postsecondary';
        }
        else if (strpos($credentialLevel, 'ungra') !== false) {
            return 'Ungraded';
        }
        else {
            return 'Other';
        }
    }

    /***
     * Derive a certificate issue date from the expiration date, July 1st five school years prior to the provided date.
     * @param DateTime $expirationDate Certificate Expiration Date
     * @return bool|DateTime Derived Certificate Issue Date
     */
    final public static function deriveCertIssueDateFromExpDate(DateTime $expirationDate) {
        if (!empty($expirationDate)) {
            if (intval($expirationDate->format("m")) >= 7) {
                return new DateTime(intval($expirationDate->format("Y"))-4 . "-07-01");
            } else {
                return new DateTime(intval($expirationDate->format("Y"))-5 . "-07-01");
            }
        } else {
            return false;
        }
    }
}
?>