<?php

class edfi {

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
     * Ed-Fi oAuth Secrets
     * @var array
     */
    private $oAuthSecrets = array (
            'authorizatonCode' => '',
            'oAuthToken' => ''
        );

    /**
     * api URLs
     * @var array
     */
    public $apiURLs = array (
            'apiBase' => '',
            'authorize' => '/oauth/authorize?',
            'getToken' => '/oauth/token?',

            //resources
            'o_students' => '/api/v2.0/2020/students?',
            'o_staffs' => '/api/v2.0/2020/staffs?',
            'o_staffEducationOrganizationEmploymentAssociations' => '/api/v2.0/2020/staffEducationOrganizationEmploymentAssociations?',
            'o_staffEducationOrganizationAssignmentAssociations' => '/api/v2.0/2020/staffEducationOrganizationAssignmentAssociations?',
            'o_staffSchoolAssociations' => '/api/v2.0/2020/staffSchoolAssociations?',
            'o_leaveEvents' => '/api/v2.0/2020/leaveEvents?',
            'o_openStaffPositions' => '/api/v2.0/2020/openStaffPositions?',
            'o_staffSectionAssociations' => '/api/v2.0/2020/staffSectionAssociations?',
            'o_accounts' => '/api/v2.0/2020/accounts?',
            'o_actuals' => '/api/v2.0/2020/actuals?',
            'o_budgets' => '/api/v2.0/2020/budgets?',
            'o_contractedStaffs' => '/api/v2.0/2020/contractedStaffs?',
            'o_payrolls' => '/api/v2.0/2020/payrolls?',
            'o_schools' => '/api/v2.0/2020/schools?',
            'o_objectiveAssessments' => '/api/v2.0/2020/objectiveAssessments?',
            'o_assessments' => '/api/v2.0/2020/assessments?',
            'o_studentAssessments' => '/api/v2.0/2020/studentAssessments?',

            //descriptors
            'o_levelOfEducationDescriptors' => '/api/v2.0/2020/levelOfEducationDescriptors?',
            'o_credentialFieldDescriptors' => '/api/v2.0/2020/credentialFieldDescriptors?',
            'o_gradeLevelDescriptors' => '/api/v2.0/2020/gradeLevelDescriptors?',
            'o_teachingCredentialDescriptors' => '/api/v2.0/2020/teachingCredentialDescriptors?',
            'o_separationReasonDescriptors' => '/api/v2.0/2020/separationReasonDescriptors?',
            'o_staffClassificationDescriptors' => '/api/v2.0/2020/staffClassificationDescriptors?',
            'o_programAssignmentDescriptors' => '/api/v2.0/2020/programAssignmentDescriptors?',
            'o_employmentStatusDescriptors' => '/api/v2.0/2020/employmentStatusDescriptors?',
            'o_accountCodeDescriptors' => '/api/v2.0/2020/accountCodeDescriptors?',
            'o_levelDescriptors' => '/api/v2.0/2020/levelDescriptors?',
            'o_performanceLevelDescriptors' => '/api/v2.0/2020/performanceLevelDescriptors?',
            'o_staffIdentificationSystemDescriptors' => '/api/v2.0/2020/staffIdentificationSystemDescriptors?'
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
     * @return bool
     */
    final public function init(string $apiBase, string $clientID, string $clientSecret, string $apiSubscriptionKey = ""): bool {
        $this->apiURLs['apiBase'] = $apiBase;
        $this->clientID = $clientID;
        $this->clientSecret = $clientSecret;

        /* API Subscription Key is optional. Assign if provided. */
        if (!empty($apiSubscriptionKey)) {
           $this->apiSubscriptionKey = $apiSubscriptionKey;
        }

        $attemptLimit = 5;
        $attempts = 0;
        $success = false;

        while($attempts < $attemptLimit) {
            $ret = $this->getAuthorizationCode();

            if ($ret !== false) {
                $success = true;
                break;
            }
            else {
                log::logAlert('Authorization Code Request Failed, attempting again in 1 second');
                ++$attempts;
                sleep(1);
            }
        }

        if ($success === true) {
            $success = false;
            $attempts = 0;

            while($attempts < $attemptLimit) {
                $ret = $this->getAuthorizationToken();

                if ($ret !== false) {
                    $success = true;
                    $this->useAuth('bearer');
                    break;
                }
                else {
                    log::logAlert('Authorization Token Request Failed, attempting again in 1 second');
                    ++$attempts;
                    sleep(1);
                }
            }
        } else {}



        return $success;
    }

    /**
     * This method performs the initial oAuth Authorization call
     * @access public
     * @return bool
     */
    final public function getAuthorizationCode(): bool {
        $url = $this->generateURL('authorize');

        $postParams = array (
                'Client_id' => $this->clientID,
                'Response_type' => 'code'
            );

        $this->oAuthSecrets['authorizatonCode'] = $this->makeCURLRequest('POST', $url, $postParams);


        if ($this->oAuthSecrets['authorizatonCode'] === false) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * This method retrieves the oAuth Token
     * @access public
     * @return bool
     */
    final public function getAuthorizationToken(): bool {
        $url = $this->generateURL('getToken');

        if (isset($this->oAuthSecrets['authorizatonCode']['code']) === true && empty($this->oAuthSecrets['authorizatonCode']['code']) === false) {
            $postParams = array (
                    'Client_id' => $this->clientID,
                    'Client_secret' => $this->clientSecret,
                    'Code' => $this->oAuthSecrets['authorizatonCode']['code'],
                    'Grant_type' => 'authorization_code'
                );

            $this->oAuthSecrets['oAuthToken'] = $this->makeCURLRequest('POST', $url, $postParams);

            if ($this->oAuthSecrets['oAuthToken'] === false) {
                return false;
            }
            else {
                if (isset($this->oAuthSecrets['oAuthToken']['access_token']) === true && empty($this->oAuthSecrets['oAuthToken']['access_token']) === false) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        else {
            return false;
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
        if (!empty($this->apiSubscriptionKey)) {
            $queryStringParams["subscription-key"] = $this->apiSubscriptionKey;
        }

        if (array_key_exists($urlKey, $this->apiURLs) === true) {
            $url .= $this->apiURLs[$urlKey];
            $url = utility::parseTemplate($url, $urlParams);

            if (!empty($queryStringParams)) {
                $url .= http_build_query($queryStringParams);
            }
        } else {}

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
            return true;
        }
        else if ($apiResponse === false) {
            return false;
        }
        else {
            $apiResponse = json_decode($apiResponse, true);

            if ($apiResponse === null) {
                return false;
            }
            else {
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

        while($break === false) {
            $url = $this->generateURL($urlKey, $queryStringParams, $urlParams);
            $records = $this->makeCURLRequest('GET', $url);

            if ($records !== false) {
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
                                } else {}
                            } else {}
                        }
                        else {
                            $recordsOut[] = $records[$i];

                            ++$recordCount;
                            if ($recordLimit > 0 && $recordLimit === $recordCount) {
                                $break = true;
                                break(2);
                            } else {}
                        }
                    }

                    $queryStringParams['offset'] += $limit;
                }
                else {
                    $break = true;
                    break(1);
                }
            }
            else {
                $break = true;
                break(1);
            }
        }

        return $recordsOut;
    }
}
?>