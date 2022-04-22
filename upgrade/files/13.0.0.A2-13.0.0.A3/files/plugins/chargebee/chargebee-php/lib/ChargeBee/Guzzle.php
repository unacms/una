<?php


namespace ChargeBee\ChargeBee;

use ChargeBee\ChargeBee;
use ChargeBee\ChargeBee\Exceptions\IOException;
use ChargeBee\ChargeBee\Exceptions\PaymentException;
use ChargeBee\ChargeBee\Exceptions\OperationFailedException;
use ChargeBee\ChargeBee\Exceptions\InvalidRequestException;
use ChargeBee\ChargeBee\Exceptions\APIError;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Guzzle
{
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
        $client = new Client();

        $opts = array(
            'connect_timeout' => Environment::$connectTimeoutInSecs,
            'timeout' => Environment::$requestTimeoutInSecs,
            'allow_redirects' => true,
            'http_errors' => false
        );
        if ($meth == Request::GET) {
            if (count($params) > 0) {
                $opts['query'] = $params;
            }
        } else if ($meth == Request::POST) {
            $opts['form_params'] = $params;
        } else {
            throw new Exception("Invalid http method $meth");
        }
        $url = self::utf8($env->apiUrl($url));

        $userAgent = "Chargebee-PHP-Client" . " v" . Version::VERSION;
        $httpHeaders = array_merge($headers, ['Accept' => 'application/json', 'User-Agent' => $userAgent, 'Lang-Version' => phpversion() , 'OS-Version' => PHP_OS]);

        $opts['headers'] = $httpHeaders;
        $opts['auth'] = [$env->getApiKey(), ''];

        // Specifying a CA bundle results in the following error when running in Google App Engine:
        // "Unsupported SSL context options are set. The following options are present, but have been ignored: allow_self_signed, cafile"
        // https://cloud.google.com/appengine/docs/php/outbound-requests#secure_connections_and_https
        $opts['verify'] = ChargeBee::getVerifyCaCerts() && !self::isAppEngine() ? ChargeBee::getCaCertPath() : false;

        $response = null;
        try {
            $response = $client->request($meth, $url, $opts);
        } catch (RequestException $e) {
            $errno = $e->getCode();
            $guzzleMsg = $e->getMessage();
            $message = "IO exception occurred when trying to connect to " . $url . " . Reason : " . $guzzleMsg;
            throw new IOException($message, $errno);
        }

        $httpCode = $response->getStatusCode();
        return array((string)$response->getBody(), $httpCode);
    }

    /**
     * Recommended way to check if script is running in Google App Engine:
     * https://github.com/google/google-api-php-client/blob/master/src/Google/Client.php#L799
     *
     * @return bool Returns true if running in Google App Engine
     */
    private static function isAppEngine() {
        return (isset($_SERVER['SERVER_SOFTWARE']) && strpos($_SERVER['SERVER_SOFTWARE'], 'Google App Engine') !== false);
    }

    /**
     * @param $response
     * @param $httpCode
     *
     * @return mixed
     * @throws APIError
     * @throws InvalidRequestException
     * @throws OperationFailedException
     * @throws PaymentException
     */
    public static function processResponse($response, $httpCode) {
        $respJson = json_decode($response, true);
        if(!$respJson){
            if (strpos($response, '503') !== false)
                 throw new Exception("Sorry, the server is currently unable to handle the request due to a temporary overload or scheduled maintenance. Please retry after sometime. \n type: internal_temporary_error, \n http_status_code: 503, \n error_code: internal_temporary_error");
            else if (strpos($response, '504') !== false)
                 throw new Exception("The server did not receive a timely response from an upstream server, request aborted. If this problem persists, contact us at support@chargebee.com. \n type: gateway_timeout, \n http_status_code: 504, \n error_code: gateway_timeout");
            else          
                 throw new Exception("Sorry, something went wrong when trying to process the request. If this problem persists, contact us at support@chargebee.com. \n type: internal_error, \n http_status_code: 500, \n error_code: internal_error ");
        }
        if ($httpCode < 200 || $httpCode > 299) {
            self::handleAPIRespError($httpCode, $respJson,$response);
        }
        return $respJson;
    }

    /**
     * @param $httpCode
     * @param $respJson
     * @param $response
     *
     * @throws APIError
     * @throws InvalidRequestException
     * @throws OperationFailedException
     * @throws PaymentException
     */
    public static function handleAPIRespError($httpCode, $respJson,$response) {
        if(!isset($respJson['api_error_code'])){
            throw new Exception("No api_error_code attribute in content. Probably not a ChargeBee's error response. The content is \n " . $response);
        }
        $type="unknown";
        if(isset($respJson['type'])){
            $type = $respJson['type'];
        }
        if ($type == "payment") {
            throw new PaymentException($httpCode, $respJson);
        } elseif ($type == "operation_failed") {
            throw new OperationFailedException($httpCode, $respJson);
        } elseif ($type == "invalid_request") {
            throw new InvalidRequestException($httpCode, $respJson);
        } else {
            throw new APIError($httpCode, $respJson);
        }
    }

}