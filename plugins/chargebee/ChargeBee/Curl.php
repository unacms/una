<?php

class ChargeBee_Curl {

    public static function utf8($value) {
        if (is_string($value))
            return utf8_encode($value);
        else
            return $value;
    }

    public static function doRequest($meth, $url, $env, $params = array(), $headers = array()) {
        list($response, $httpCode) = self::request($meth, $url, $env, $params, $headers);
        $respJson = self::processResponse($response, $httpCode);
        return $respJson;
    }

    public static function request($meth, $url, $env, $params, $headers) {
        $curl = curl_init();
        $opts = array();
        if ($meth == ChargeBee_Request::GET) {
            $opts[CURLOPT_HTTPGET] = 1;
            if (count($params) > 0) {
                $encoded = http_build_query($params, null, '&');
                $url = "$url?$encoded";
            }
        } else if ($meth == ChargeBee_Request::POST) {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, '&');
        } else {
            throw new Exception("Invalid http method $meth");
        }
        $url = self::utf8($env->apiUrl($url));
        $opts[CURLOPT_URL] = $url;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = ChargeBee_Environment::$connectTimeout;
        $opts[CURLOPT_TIMEOUT] = ChargeBee_Environment::$timeout;
        $userAgent = "Chargebee-PHP-Client" . " v" . ChargeBee_Version::VERSION;
		
		$httpHeaders = self::addCustomHeaders($headers);
		array_push($httpHeaders, 'Accept: application/json', "User-Agent: " . $userAgent); // Adding headers to array
		
        $opts[CURLOPT_HTTPHEADER] = $httpHeaders;
        $opts[CURLOPT_USERPWD] = $env->getApiKey() . ':';
        if (ChargeBee::getVerifyCaCerts()) {
            $opts[CURLOPT_SSL_VERIFYPEER] = true;
            $opts[CURLOPT_SSL_VERIFYHOST] = 2;
            $opts[CURLOPT_CAINFO] = ChargeBee::getCaCertPath();
        }
        curl_setopt_array($curl, $opts);

        $response = curl_exec($curl);

        if ($response === false) {
            $errno = curl_errno($curl);
            $curlMsg = curl_error($curl);
            $message = "IO exception occurred when trying to connect to " . $url . " . Reason : " . $curlMsg;
            curl_close($curl);
            throw new ChargeBee_IOException($message, $errno);
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return array($response, $httpCode);
    }
	
	public static function addCustomHeaders($headers) {
		$httpHeaders = array();
		foreach ($headers as $key => $val) {
			array_push($httpHeaders, $key.": ".$val);
		}
		return $httpHeaders;
	}
	
    public static function processResponse($response, $httpCode) {
        $respJson = json_decode($response, true);
        if(!$respJson){
            throw new Exception("Response not in JSON format. Might not be a ChargeBee Response.");
        }
        if ($httpCode < 200 || $httpCode > 299) {
            self::handleAPIRespError($httpCode, $respJson,$response);
        }
        return $respJson;
    }

    public static function handleAPIRespError($httpCode, $respJson,$response) {
        if(!isset($respJson['api_error_code'])){
            throw new Exception("No api_error_code attribute in content. Probably not a ChargeBee's error response. The content is \n " . $response);
        }
        $type="unknown";
        if(isset($respJson['type'])){
            $type = $respJson['type'];
        }
        if ($type == "payment") {
            throw new ChargeBee_PaymentException($httpCode, $respJson);
        } elseif ($type == "operation_failed") {
            throw new ChargeBee_OperationFailedException($httpCode, $respJson);
        } elseif ($type == "invalid_request") {
            throw new ChargeBee_InvalidRequestException($httpCode, $respJson);
        } else {
            throw new ChargeBee_APIError($httpCode, $respJson);
        }
    }

}
