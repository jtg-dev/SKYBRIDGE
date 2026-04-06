<?php

class edfiSuite3
{

    /**
     * Client ID
     * @var array
     */
    private $clientID = '';

    /**
     * Client Secret
     * @var array
     */
    private $clientSecret = '';

    /**
     * EdWire Subscription Key
     * @var string
     */
    private $apiSubscriptionKey = '';

    /**
     * Instance-specific, multi-year ODS?
     * @var string
     */
    private $apiInstSpec = false;

    /**
     * @var string API Database UUID
     */
    private $databaseUuid = '';

    /**
     * Ed-Fi v7.x SaaS URL pattern: year segment comes before /data/v3 in the path.
     *   token:    /{uuid}/{year}/oauth/token
     *   resource: /{uuid}/{year}/data/v3/ed-fi/...
     * @var bool
     */
    private $yearBeforeData = false;

    /**
     * Ed-Fi oAuth Secrets
     * @var array
     */
    private $oAuthSecrets = array(
        'authorizationCode' => '',
        'oAuthToken'        => ''
    );

    /**
     * api URLs
     * @var array
     */
    public $apiURLs = array(
        'apiBase'                                            => '',
        'getToken'                                           => '/oauth/token?',

        /** Year is resolved dynamically from driver::$currentSY[1] via the %%year%% template token in generateURL(). */
        /* === Resources === */
        'o_credentials'                                      => '/data/v3/%%year%%/ed-fi/credentials?',
        'o_localEducationAgencies'                           => '/data/v3/%%year%%/ed-fi/localEducationAgencies?',
        'o_openStaffPositions'                               => '/data/v3/%%year%%/ed-fi/openStaffPositions?',
        'o_people'                                           => '/data/v3/%%year%%/ed-fi/people?',
        'o_performanceEvaluationRatings'                     => '/data/v3/%%year%%/tpdm/performanceEvaluationRatings?',
        'o_performanceEvaluations'                           => '/data/v3/%%year%%/tpdm/performanceEvaluations?',
        'o_schools'                                          => '/data/v3/%%year%%/ed-fi/schools?',
        'o_staffEducationOrganizationAssignmentAssociations' => '/data/v3/%%year%%/ed-fi/staffEducationOrganizationAssignmentAssociations?',
        'o_staffEducationOrganizationEmploymentAssociations' => '/data/v3/%%year%%/ed-fi/staffEducationOrganizationEmploymentAssociations?',
        'o_staffAbsenceEvents'                               => '/data/v3/%%year%%/ed-fi/staffAbsenceEvents?',
        'o_staffs'                                           => '/data/v3/%%year%%/ed-fi/staffs?',
        'o_stateEducationAgencies'                           => '/data/v3/%%year%%/ed-fi/stateEducationAgencies?',

        /* === Descriptors === */
        'o_absenceEventCategoryDescriptors'                  => '/data/v3/%%year%%/ed-fi/absenceEventCategoryDescriptors?',
        'o_addressTypeDescriptors'                           => '/data/v3/%%year%%/ed-fi/addressTypeDescriptors?',
        'o_certificationFieldDescriptors'                    => '/data/v3/%%year%%/tpdm/certificationFieldDescriptors?',
        'o_certificationLevelDescriptors'                    => '/data/v3/%%year%%/tpdm/certificationLevelDescriptors?',
        'o_credentialFieldDescriptors'                       => '/data/v3/%%year%%/ed-fi/credentialFieldDescriptors?',
        'o_credentialTypeDescriptors'                        => '/data/v3/%%year%%/ed-fi/credentialTypeDescriptors?',
        'o_citizenshipStatusDescriptors'                     => '/data/v3/%%year%%/ed-fi/citizenshipStatusDescriptors?',
        'o_educationOrganizationCategoryDescriptors'         => '/data/v3/%%year%%/ed-fi/educationOrganizationCategoryDescriptors?',
        'o_electronicMailTypeDescriptors'                    => '/data/v3/%%year%%/ed-fi/electronicMailTypeDescriptors?',
        'o_employmentStatusDescriptors'                      => '/data/v3/%%year%%/ed-fi/employmentStatusDescriptors?',
        'o_evaluationPeriodDescriptors'                      => '/data/v3/%%year%%/tpdm/evaluationPeriodDescriptors?',
        'o_gradeLevelDescriptors'                            => '/data/v3/%%year%%/ed-fi/gradeLevelDescriptors?',
        'o_levelOfEducationDescriptors'                      => '/data/v3/%%year%%/ed-fi/levelOfEducationDescriptors?',
        'o_operationalStatusDescriptors'                     => '/data/v3/%%year%%/ed-fi/operationalStatusDescriptors?',
        'o_performanceEvaluationRatingLevelDescriptors'      => '/data/v3/%%year%%/tpdm/performanceEvaluationRatingLevelDescriptors?',
        'o_performanceEvaluationTypeDescriptors'             => '/data/v3/%%year%%/tpdm/performanceEvaluationTypeDescriptors?',
        'o_programCharacteristicDescriptors'                 => '/data/v3/%%year%%/ed-fi/programCharacteristicDescriptors?',
        'o_raceDescriptors'                                  => '/data/v3/%%year%%/ed-fi/raceDescriptors?',
        'o_separationReasonDescriptors'                      => '/data/v3/%%year%%/ed-fi/separationReasonDescriptors?',
        'o_sourceSystemDescriptors'                          => '/data/v3/%%year%%/ed-fi/sourceSystemDescriptors?',
        'o_sexDescriptors'                                   => '/data/v3/%%year%%/ed-fi/sexDescriptors?',
        'o_schoolCategoryDescriptors'                        => '/data/v3/%%year%%/ed-fi/schoolCategoryDescriptors?',
        'o_staffClassificationDescriptors'                   => '/data/v3/%%year%%/ed-fi/staffClassificationDescriptors?',
        'o_staffIdentificationSystemDescriptors'             => '/data/v3/%%year%%/ed-fi/staffIdentificationSystemDescriptors?',
        'o_stateAbbreviationDescriptors'                     => '/data/v3/%%year%%/ed-fi/stateAbbreviationDescriptors?',
        'o_teachingCredentialDescriptors'                    => '/data/v3/%%year%%/ed-fi/teachingCredentialDescriptors?',
        'o_telephoneNumberTypeDescriptors'                   => '/data/v3/%%year%%/ed-fi/telephoneNumberTypeDescriptors?',
        'o_termDescriptors'                                  => '/data/v3/%%year%%/ed-fi/termDescriptors?',
    );

    /**
     * api URLs
     * @var array
     */
    public $apiInstSpecURLs = array(
        'apiBase'                                            => '',
        'getToken'                                           => '/oauth/token?',

        /* === Resources === */
        'o_credentials'                                      => '/data/v3/ed-fi/credentials?',
        'o_localEducationAgencies'                           => '/data/v3/ed-fi/localEducationAgencies?',
        'o_openStaffPositions'                               => '/data/v3/ed-fi/openStaffPositions?',
        'o_people'                                           => '/data/v3/ed-fi/people?',
        'o_performanceEvaluationRatings'                     => '/data/v3/tpdm/performanceEvaluationRatings?',
        'o_performanceEvaluations'                           => '/data/v3/tpdm/performanceEvaluations?',
        'o_schools'                                          => '/data/v3/ed-fi/schools?',
        'o_staffEducationOrganizationAssignmentAssociations' => '/data/v3/ed-fi/staffEducationOrganizationAssignmentAssociations?',
        'o_staffEducationOrganizationEmploymentAssociations' => '/data/v3/ed-fi/staffEducationOrganizationEmploymentAssociations?',
        'o_staffAbsenceEvents'                               => '/data/v3/ed-fi/staffAbsenceEvents?',
        'o_staffs'                                           => '/data/v3/ed-fi/staffs?',
        'o_stateEducationAgencies'                           => '/data/v3/ed-fi/stateEducationAgencies?',

        /* === Descriptors === */
        'o_absenceEventCategoryDescriptors'                  => '/data/v3/ed-fi/absenceEventCategoryDescriptors?',
        'o_addressTypeDescriptors'                           => '/data/v3/ed-fi/addressTypeDescriptors?',
        'o_certificationFieldDescriptors'                    => '/data/v3/tpdm/certificationFieldDescriptors?',
        'o_certificationLevelDescriptors'                    => '/data/v3/tpdm/certificationLevelDescriptors?',
        'o_credentialFieldDescriptors'                       => '/data/v3/ed-fi/credentialFieldDescriptors?',
        'o_credentialTypeDescriptors'                        => '/data/v3/ed-fi/credentialTypeDescriptors?',
        'o_citizenshipStatusDescriptors'                     => '/data/v3/ed-fi/citizenshipStatusDescriptors?',
        'o_educationOrganizationCategoryDescriptors'         => '/data/v3/ed-fi/educationOrganizationCategoryDescriptors?',
        'o_electronicMailTypeDescriptors'                    => '/data/v3/ed-fi/electronicMailTypeDescriptors?',
        'o_employmentStatusDescriptors'                      => '/data/v3/ed-fi/employmentStatusDescriptors?',
        'o_evaluationPeriodDescriptors'                      => '/data/v3/tpdm/evaluationPeriodDescriptors?',
        'o_gradeLevelDescriptors'                            => '/data/v3/ed-fi/gradeLevelDescriptors?',
        'o_levelOfEducationDescriptors'                      => '/data/v3/ed-fi/levelOfEducationDescriptors?',
        'o_operationalStatusDescriptors'                     => '/data/v3/ed-fi/operationalStatusDescriptors?',
        'o_performanceEvaluationRatingLevelDescriptors'      => '/data/v3/tpdm/performanceEvaluationRatingLevelDescriptors?',
        'o_performanceEvaluationTypeDescriptors'             => '/data/v3/tpdm/performanceEvaluationTypeDescriptors?',
        'o_programCharacteristicDescriptors'                 => '/data/v3/ed-fi/programCharacteristicDescriptors?',
        'o_raceDescriptors'                                  => '/data/v3/ed-fi/raceDescriptors?',
        'o_separationReasonDescriptors'                      => '/data/v3/ed-fi/separationReasonDescriptors?',
        'o_sexDescriptors'                                   => '/data/v3/ed-fi/sexDescriptors?',
        'o_sourceSystemDescriptors'                          => '/data/v3/ed-fi/sourceSystemDescriptors?',
        'o_staffClassificationDescriptors'                   => '/data/v3/ed-fi/staffClassificationDescriptors?',
        'o_staffIdentificationSystemDescriptors'             => '/data/v3/ed-fi/staffIdentificationSystemDescriptors?',
        'o_stateAbbreviationDescriptors'                     => '/data/v3/ed-fi/stateAbbreviationDescriptors?',
        'o_teachingCredentialDescriptors'                    => '/data/v3/ed-fi/teachingCredentialDescriptors?',
        'o_telephoneNumberTypeDescriptors'                   => '/data/v3/ed-fi/telephoneNumberTypeDescriptors?',
        'o_termDescriptors'                                  => '/data/v3/ed-fi/termDescriptors?',
    );

    public $apiInstYearSpecUrls = array(
        'apiBase'                                            => '',
        'getToken'                                           => '/%%databaseUuid%%/oauth/token?',

        /* === Resources === */
        'o_credentials'                                      => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/credentials?',
        'o_localEducationAgencies'                           => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/localEducationAgencies?',
        'o_openStaffPositions'                               => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/openStaffPositions?',
        'o_people'                                           => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/people?',
        'o_performanceEvaluationRatings'                     => '/data/v3/%%databaseUuid%%/%%year%%/tpdm/performanceEvaluationRatings?',
        'o_performanceEvaluations'                           => '/data/v3/%%databaseUuid%%/%%year%%/tpdm/performanceEvaluations?',
        'o_schools'                                          => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/schools?',
        'o_staffEducationOrganizationAssignmentAssociations' => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/staffEducationOrganizationAssignmentAssociations?',
        'o_staffEducationOrganizationEmploymentAssociations' => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/staffEducationOrganizationEmploymentAssociations?',
        'o_staffAbsenceEvents'                               => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/staffAbsenceEvents?',
        'o_staffs'                                           => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/staffs?',
        'o_stateEducationAgencies'                           => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/stateEducationAgencies?',

        /* === Descriptors === */
        'o_absenceEventCategoryDescriptors'                  => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/absenceEventCategoryDescriptors?',
        'o_addressTypeDescriptors'                           => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/addressTypeDescriptors?',
        'o_certificationFieldDescriptors'                    => '/data/v3/%%databaseUuid%%/%%year%%/tpdm/certificationFieldDescriptors?',
        'o_certificationLevelDescriptors'                    => '/data/v3/%%databaseUuid%%/%%year%%/tpdm/certificationLevelDescriptors?',
        'o_credentialFieldDescriptors'                       => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/credentialFieldDescriptors?',
        'o_credentialTypeDescriptors'                        => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/credentialTypeDescriptors?',
        'o_citizenshipStatusDescriptors'                     => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/citizenshipStatusDescriptors?',
        'o_educationOrganizationCategoryDescriptors'         => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/educationOrganizationCategoryDescriptors?',
        'o_electronicMailTypeDescriptors'                    => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/electronicMailTypeDescriptors?',
        'o_employmentStatusDescriptors'                      => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/employmentStatusDescriptors?',
        'o_evaluationPeriodDescriptors'                      => '/data/v3/%%databaseUuid%%/%%year%%/tpdm/evaluationPeriodDescriptors?',
        'o_gradeLevelDescriptors'                            => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/gradeLevelDescriptors?',
        'o_levelOfEducationDescriptors'                      => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/levelOfEducationDescriptors?',
        'o_operationalStatusDescriptors'                     => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/operationalStatusDescriptors?',
        'o_performanceEvaluationRatingLevelDescriptors'      => '/data/v3/%%databaseUuid%%/%%year%%/tpdm/performanceEvaluationRatingLevelDescriptors?',
        'o_performanceEvaluationTypeDescriptors'             => '/data/v3/%%databaseUuid%%/%%year%%/tpdm/performanceEvaluationTypeDescriptors?',
        'o_programCharacteristicDescriptors'                 => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/programCharacteristicDescriptors?',
        'o_raceDescriptors'                                  => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/raceDescriptors?',
        'o_separationReasonDescriptors'                      => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/separationReasonDescriptors?',
        'o_sourceSystemDescriptors'                          => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/sourceSystemDescriptors?',
        'o_sexDescriptors'                                   => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/sexDescriptors?',
        'o_schoolCategoryDescriptors'                        => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/schoolCategoryDescriptors?',
        'o_staffClassificationDescriptors'                   => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/staffClassificationDescriptors?',
        'o_staffIdentificationSystemDescriptors'             => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/staffIdentificationSystemDescriptors?',
        'o_stateAbbreviationDescriptors'                     => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/stateAbbreviationDescriptors?',
        'o_teachingCredentialDescriptors'                    => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/teachingCredentialDescriptors?',
        'o_telephoneNumberTypeDescriptors'                   => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/telephoneNumberTypeDescriptors?',
        'o_termDescriptors'                                  => '/data/v3/%%databaseUuid%%/%%year%%/ed-fi/termDescriptors?',
    );

    /**
     * Ed-Fi v7.x SaaS URL pattern: /{uuid}/{year}/oauth/token and /{uuid}/{year}/data/v3/...
     * Use when yearBeforeData = true in the connection config.
     * @var array
     */
    public $apiYearUuidSpecUrls = array(
        'apiBase'                                            => '',
        'getToken'                                           => '/%%databaseUuid%%/%%year%%/oauth/token?',

        /* === Resources === */
        'o_credentials'                                      => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/credentials?',
        'o_localEducationAgencies'                           => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/localEducationAgencies?',
        'o_openStaffPositions'                               => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/openStaffPositions?',
        'o_people'                                           => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/people?',
        'o_performanceEvaluationRatings'                     => '/%%databaseUuid%%/%%year%%/data/v3/tpdm/performanceEvaluationRatings?',
        'o_performanceEvaluations'                           => '/%%databaseUuid%%/%%year%%/data/v3/tpdm/performanceEvaluations?',
        'o_schools'                                          => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/schools?',
        'o_staffEducationOrganizationAssignmentAssociations' => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/staffEducationOrganizationAssignmentAssociations?',
        'o_staffEducationOrganizationEmploymentAssociations' => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/staffEducationOrganizationEmploymentAssociations?',
        'o_staffAbsenceEvents'                               => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/staffAbsenceEvents?',
        'o_staffs'                                           => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/staffs?',
        'o_stateEducationAgencies'                           => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/stateEducationAgencies?',

        /* === Descriptors === */
        'o_absenceEventCategoryDescriptors'                  => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/absenceEventCategoryDescriptors?',
        'o_addressTypeDescriptors'                           => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/addressTypeDescriptors?',
        'o_certificationFieldDescriptors'                    => '/%%databaseUuid%%/%%year%%/data/v3/tpdm/certificationFieldDescriptors?',
        'o_certificationLevelDescriptors'                    => '/%%databaseUuid%%/%%year%%/data/v3/tpdm/certificationLevelDescriptors?',
        'o_credentialFieldDescriptors'                       => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/credentialFieldDescriptors?',
        'o_credentialTypeDescriptors'                        => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/credentialTypeDescriptors?',
        'o_citizenshipStatusDescriptors'                     => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/citizenshipStatusDescriptors?',
        'o_educationOrganizationCategoryDescriptors'         => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/educationOrganizationCategoryDescriptors?',
        'o_electronicMailTypeDescriptors'                    => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/electronicMailTypeDescriptors?',
        'o_employmentStatusDescriptors'                      => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/employmentStatusDescriptors?',
        'o_evaluationPeriodDescriptors'                      => '/%%databaseUuid%%/%%year%%/data/v3/tpdm/evaluationPeriodDescriptors?',
        'o_gradeLevelDescriptors'                            => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/gradeLevelDescriptors?',
        'o_levelOfEducationDescriptors'                      => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/levelOfEducationDescriptors?',
        'o_operationalStatusDescriptors'                     => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/operationalStatusDescriptors?',
        'o_performanceEvaluationRatingLevelDescriptors'      => '/%%databaseUuid%%/%%year%%/data/v3/tpdm/performanceEvaluationRatingLevelDescriptors?',
        'o_performanceEvaluationTypeDescriptors'             => '/%%databaseUuid%%/%%year%%/data/v3/tpdm/performanceEvaluationTypeDescriptors?',
        'o_programCharacteristicDescriptors'                 => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/programCharacteristicDescriptors?',
        'o_raceDescriptors'                                  => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/raceDescriptors?',
        'o_separationReasonDescriptors'                      => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/separationReasonDescriptors?',
        'o_sourceSystemDescriptors'                          => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/sourceSystemDescriptors?',
        'o_sexDescriptors'                                   => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/sexDescriptors?',
        'o_schoolCategoryDescriptors'                        => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/schoolCategoryDescriptors?',
        'o_staffClassificationDescriptors'                   => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/staffClassificationDescriptors?',
        'o_staffIdentificationSystemDescriptors'             => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/staffIdentificationSystemDescriptors?',
        'o_stateAbbreviationDescriptors'                     => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/stateAbbreviationDescriptors?',
        'o_teachingCredentialDescriptors'                    => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/teachingCredentialDescriptors?',
        'o_telephoneNumberTypeDescriptors'                   => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/telephoneNumberTypeDescriptors?',
        'o_termDescriptors'                                  => '/%%databaseUuid%%/%%year%%/data/v3/ed-fi/termDescriptors?',
    );


    /**
     * cURL Authentication Type
     * @var array
     */
    public $authType = '';

    /**
     * This method is an initialization call that sets the API keys and performs the oAuth inits
     * @access public
     * @param string $apiBase the API URL Base
     * @param string $clientID the API Client ID
     * @param string $clientSecret the API Client Secret
     * @param bool $instanceSpecific Instance-specific ODS?
     * @return bool
     */
    final public function init(string $apiBase, string $clientID, string $clientSecret, string $apiSubscriptionKey = "", bool $instanceSpecific = false, string $databaseUuid = "", bool $yearBeforeData = false): bool {
        $this->apiURLs['apiBase'] = $apiBase;
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;

  	/* API Subscription Key is optional. Assign if provided. */
	if (!empty($apiSubscriptionKey)) {
      	    $this->apiSubscriptionKey = $apiSubscriptionKey;
	}

	/* Is this an instance-specific, multi-year API? */
        if (!empty($instanceSpecific)) {
	    $this->apiInstSpec = $instanceSpecific;
        }

	/* Is there a database UUID configured for this connection? */
        if (!empty($databaseUuid)) {
       	    $this->databaseUuid = $databaseUuid;
        }

	/* Ed-Fi v7.x SaaS: year comes before /data/v3 (and before /oauth/token). */
        if ($yearBeforeData) {
	    $this->yearBeforeData = true;
    	}

        $attemptLimit = 5;
        $attempts = 0;
        $success = false;

        while ($attempts < $attemptLimit) {
            $ret = $this->getAuthorizationToken();

            if ($ret !== false) {
                $success = true;
                $this->useAuth('bearer');
                break;
            } else {
                log::logAlert('Authorization Token Request Failed, attempting again in 5 seconds.');
                ++$attempts;
                sleep(5);
            }
        }
        return $success;
    }

    /**
     * This method retrieves the oAuth Token
     * @access public
     * @return bool
     */
    final public function getAuthorizationToken(): bool {
        $url = $this->generateURL('getToken');

        $postParams = array(
            'Client_id'     => $this->clientID,
            'Client_secret' => $this->clientSecret,
            'Grant_type'    => 'client_credentials'
        );

        $this->oAuthSecrets['oAuthToken'] = $this->makeCURLRequest('POST', $url, $postParams);
        //var_dump($this->oAuthSecrets['oAuthToken']);

        if ($this->oAuthSecrets['oAuthToken'] === false) {
            return false;
        } else {
            if (isset($this->oAuthSecrets['oAuthToken']['access_token']) === true && empty($this->oAuthSecrets['oAuthToken']['access_token']) === false) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * This method is an initialization call that sets the API keys and performs the oAuth inits
     * @access public
     * @param string $clientID the API Client ID
     * @param string $clientSecret the API Client Secret
     * @return string
     */
    final public function generateURL(string $urlKey, array $queryStringParams = array(), array $urlParams = array()): string {
        $url = $this->apiURLs['apiBase'];

        /* If subscription key is provided, it must be appended to the URL as a
         * query string on every transaction or else the request will be denied. */
        if (isset($this->apiSubscriptionKey) && !empty($this->apiSubscriptionKey)) {
            $queryStringParams["subscription-key"] = $this->apiSubscriptionKey;
        }

        if (array_key_exists($urlKey, $this->apiURLs) === true) {
            if ($this->apiInstSpec) {
		/* Pattern 2: instance-specific, no year in path. */
                $url .= $this->apiInstSpecURLs[$urlKey];
            } elseif (!empty($this->databaseUuid) && $this->yearBeforeData) {
                /* Pattern 4 (v7.x SaaS): /{uuid}/{year}/oauth/token  and  /{uuid}/{year}/data/v3/... */
                $url .= str_replace("%%databaseUuid%%", $this->databaseUuid, $this->apiYearUuidSpecUrls[$urlKey]);
            } elseif (!empty($this->databaseUuid)) {
                /* Pattern 3: /data/v3/{uuid}/{year}/...  token: /{uuid}/oauth/token */
                $url .= str_replace("%%databaseUuid%%", $this->databaseUuid, $this->apiInstYearSpecUrls[$urlKey]);
            } else {
                /* Pattern 1: standard /data/v3/{year}/... */
                $url .= $this->apiURLs[$urlKey];
            }
            // Inject the current school year so %%year%% tokens are resolved automatically.
            $urlParams['year'] = driver::$currentSY[1];
            $url = utility::parseTemplate($url, $urlParams);

            if (!empty($queryStringParams)) {
                $url .= http_build_query($queryStringParams);
            }
        } else {
        }
		//Prints edfi url to ensure url is correctly formatted
		echo $url;
        return $url;
    }

    /**
     * This method is a generic cURL wrapper
     * @access public
     * @param string $requestMethod the cURL Method (Post, Get, etc)
     * @param string $url the URL to communicate with
     * @param array $params array of HTTP POST keys=>vals for posts
     * @param array $headers array http headers to include with the request
     * @param array $curlOptions array of additional cURL options in the keys=>vals format
     * @return mixed
     */
    final public function makeCURLRequest(string $requestMethod, string $url, array $params = array(), array $headers = array(), array $curlOptions = array()) {

        $requestMethod = strtoupper($requestMethod);

        switch ($this->authType) {
            default:
                break;

            case 'bearer':
                $headers[] = 'Authorization: Bearer ' . $this->oAuthSecrets['oAuthToken']['access_token'];
                break;
        }

        $ret = curl::makeSingleRequest($requestMethod, $url, $params, $headers, $curlOptions);

        return $this->apiResponseErrorChecking($ret);
    }

    /**
     * This method is a cURL class wrapper for performing multiple simultaneous cURL requests in parallel
     * @access public
     * @param array $requests array of curl request params 'method' => 'POST', 'url' => '', 'data' => array('k => 'v', 'k' => 'v')
     * @return mixed
     */
    final public function makeCURLParallelRequests(array $requests) {

        $limit = count($requests);
        for ($i = 0; $i < $limit; $i++) {
            $requests[$i]['headers'] = array();
            $requests[$i]['method'] = strtoupper($requests[$i]['method']);

            switch ($this->authType) {
                default:
                    break;

                case 'bearer':
                    $requests[$i]['headers'][] = 'Authorization: Bearer ' . $this->oAuthSecrets['oAuthToken']['access_token'];
                    break;
            }
        }

        $responses = curl::makeParallelRequests($requests);

        $limit = count($responses);
        for ($i = 0; $i < $limit; $i++) {
            $responses[$i] = $this->apiResponseErrorChecking($responses[$i]);
        }

        return $responses;
    }

    /**
     * This method checks the cURL response and returns a string or bool
     * @access public
     * @param mixed $apiResponse raw api call response
     * @return mixed
     */
    final public function apiResponseErrorChecking($apiResponse) {
        if (empty($apiResponse) === true) {
            // Empty body (e.g. HTTP 204 or empty 200) means no records — return an empty array,
            // NOT boolean true, so that callers can safely do is_array() / count() checks.
            return array();
        } else if ($apiResponse === false) {
            return false;
        } else {
	    echo "\n[DEBUG API RESPONSE]: " . substr($apiResponse, 0, 500) . "\n";
            $apiResponse = json_decode($apiResponse, true);

            if ($apiResponse === null) {
                return false;
            } else {
                return $apiResponse;
            }
        }
    }

    /**
     * This method sets the current authentication method
     * @access public
     * @param string $authType the new authentication method to use for cURL
     * @return void
     */
    final public function useAuth(string $authType = '') {
        $this->authType = $authType;
    }

    final public function loopDataWithCallback(string $urlKey, array $queryStringParams = array(), array $urlParams = array(), $filterFunction = false, int $recordLimit = 0) {
        $queryStringParams['offset'] = $queryStringParams['offset'] ?? 0;
        $break = false;
        $recordsOut = array();
        $recordCount = 0;

        while ($break === false) {
            $url = $this->generateURL($urlKey, $queryStringParams, $urlParams);
            $records = $this->makeCURLRequest('GET', $url);

	    /* ── 401 Re-auth fallback ──────────────────────────────────────────
             * Bearer tokens can expire mid-job on long paginated pulls.
             * If the API returns 401, attempt one token refresh and retry the
             * same offset. If the refresh also fails (bad credentials, revoked
             * key, etc.) getAuthorizationToken() returns false and we abort —
             * so this will not loop indefinitely on a permanent auth failure.  */
            if (curl::$debugInfos['http_code'] === 401) {
                log::logAlert('HTTP 401 on GET — attempting token refresh.');
                if ($this->getAuthorizationToken()) {
                    log::logAlert('Token refreshed — retrying offset ' . $queryStringParams['offset'] . '.');
                    continue;
                } else {
                    log::logAlert('Token refresh failed — aborting loop.');
                    break;
                }
            }
            /* ─────────────────────────────────────────────────────────────── */

            if ($records !== false && is_array($records) && isset($records[0])) {
                $limit = count($records);

                if ($limit > 0) {
                    for ($i = 0; $i < $limit; $i++) {
                        if ($filterFunction !== false && is_callable($filterFunction) === true) {
                            $records[$i] = $filterFunction($records[$i]);
                            if ($records[$i] !== false) {
                                $recordsOut[] = $records[$i];

                                ++$recordCount;
                                if ($recordLimit > 0 && $recordLimit === $recordCount) {
                                    $break = true;
                                    break(2);
                                } else {
                                }
                            } else {
                            }
                        } else {
                            $recordsOut[] = $records[$i];

                            ++$recordCount;
                            if ($recordLimit > 0 && $recordLimit === $recordCount) {
                                $break = true;
                                break(2);
                            } else {
                            }
                        }
                    }

                    $queryStringParams['offset'] += $limit;
                } else {
                    $break = true;
                    break(1);
                }
            } else {
                $break = true;
                break(1);
            }
        }

        return $recordsOut;
    }
}

?>
