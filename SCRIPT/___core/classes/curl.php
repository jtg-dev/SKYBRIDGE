<?php

/**
 * This is a class housing all the cURL methods
 * @package MMExtranet
 * @version 1.0
 */

class curl {

    /**
     * curl request debug info
     * @var array
     */
    public static $debugInfos = array();

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
    final public static function makeSingleRequest(string $requestMethod, string $url, array $params = array(), array $headers = array(), array $curlOptions = array()) {

        $requestMethod = strtoupper($requestMethod);

        $ch = self::prepareCurlHandle($requestMethod, $url, $params, $headers, $curlOptions);
        $ret = curl_exec($ch);
        self::$debugInfos = curl_getinfo($ch);
        curl_close($ch);

        return $ret;
    }

    /**
     * This method is a generic cURL method for performing multiple simultaneous cURL requests in parallel
     * @access public
     * @param array $requests array of curl request params 'method' => 'POST', 'url' => '', 'data' => array('k => 'v', 'k' => 'v'), 'headers' => []
     * @return mixed
     */
    final public static function makeParallelRequests(array $requests) {
        $chs = $payloads = $responses = self::$debugInfos = array();
        $mh = curl_multi_init();

        $running = null;
        $limit = count($requests);
        for ($i = 0; $i < $limit; $i++) {
            $requests[$i]['headers'] = $requests[$i]['headers'] ?? array();
            $requests[$i]['method'] = strtoupper($requests[$i]['method']);
            $payloads[$i] = $requests[$i]['data'];
            $chs[$i] = self::prepareCurlHandle($requests[$i]['method'], $requests[$i]['url'], $requests[$i]['data'], $requests[$i]['headers']);
            curl_multi_add_handle($mh, $chs[$i]);
        }


        do {
            // execute curl requests
            curl_multi_exec($mh, $running);
            // block to avoid needless cycling until change in status
            curl_multi_select($mh);
        // check flag to see if we're done
        } while($running > 0);

        $limit = count($chs);
        for ($i = 0; $i < $limit; $i++) {
            self::$debugInfos[$i] = curl_getinfo($chs[$i]);
            self::$debugInfos[$i]['__payload'] = $payloads[$i];
            $responses[$i] = curl_multi_getcontent($chs[$i]);
            curl_multi_remove_handle($mh, $chs[$i]);
        }

        curl_multi_close($mh);
        return $responses;
    }

    final private static function prepareCurlHandle(string $requestMethod, string $url, array $params = array(), array $headers = array(), array $curlOptions = array()) {
        $requestMethod = strtoupper($requestMethod);

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        //curl_setopt($ch, CURLINFO_HEADER_OUT, true); //enabling this will disable verbose output

        switch($requestMethod) {
            case 'GET':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);
                $headers[] = 'Accept: application/json';
                //$headers[] = 'Content-Length: 0';
                break;

            case 'POST':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);
                $params = http_build_query($params);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                //$headers[] = 'Content-Length: ' . mb_strlen($params);
                break;

            case 'DELETE':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $requestMethod);
                $params = http_build_query($params);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                //$headers[] = 'Content-Length: ' . mb_strlen($params);
                break;

            case 'POSTJSON':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                $params = json_encode($params);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                $headers[] = 'Content-Type: application/json';
                //$headers[] = 'Content-Length: ' . mb_strlen($params);
                break;

            case 'PUTJSON':
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                $params = json_encode($params);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
                $headers[] = 'Content-Type: application/json';
                //$headers[] = 'Content-Length: ' . mb_strlen($params);
                break;

            default:
                echo 'Unhandled HTTP Verb: ' . $requestMethod;
                die();
                break;
        }

        foreach ($curlOptions as $k => $v) {
            curl_setopt($ch, $k, $v);
        }

        $headers[] = 'User-Agent: MMDeveloper CLI';

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        return $ch;
    }
}
?>