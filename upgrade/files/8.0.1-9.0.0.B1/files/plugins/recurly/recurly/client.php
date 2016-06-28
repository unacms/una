<?php

/**
 * Recurly_Client provides methods for interacting with the {@link https://dev.recurly.com/docs/getting-started Recurly} API.
 *
 * @category   Recurly
 * @package    Recurly_Client_PHP
 * @copyright  Copyright (c) 2011 {@link http://recurly.com Recurly, Inc.}
 */
class Recurly_Client
{
  /**
   * Subdomain for all requests.
   */
  public static $subdomain = 'api';

  /**
   * Default API key for all requests, may be overridden with the Recurly_Client constructor
   */
  public static $apiKey;

  /**
   * Base API URL
   */
  public static $apiUrl = 'https://%s.recurly.com/v2';

  /**
   * API Version
   */
  public static $apiVersion = '2.2';

  /**
   * The path to your CA certs. Use only if needed (if you can't fix libcurl/php).
   */
  public static $CACertPath = false;

  /**
   * API Key instance, may differ from the static key
   */
  private $_apiKey;

  /**
   * Language for API validation messages
   */
  private $_acceptLanguage = 'en-US';

  const API_CLIENT_VERSION = '2.5.2';
  const DEFAULT_ENCODING = 'UTF-8';

  const GET = 'GET';
  const POST = 'POST';
  const PUT = 'PUT';
  const DELETE = 'DELETE';
  const HEAD = 'HEAD';

  const PATH_ACCOUNTS = '/accounts';
  const PATH_ADDONS = '/add_ons';
  const PATH_ADJUSTMENTS = '/adjustments';
  const PATH_BILLING_INFO = '/billing_info';
  const PATH_COUPON = '/coupon';
  const PATH_COUPON_REDEMPTION = '/redemption';
  const PATH_COUPON_REDEMPTIONS = '/redemptions';
  const PATH_COUPONS = '/coupons';
  const PATH_UNIQUE_COUPONS = '/unique_coupon_codes';
  const PATH_INVOICES = '/invoices';
  const PATH_NOTES = '/notes';
  const PATH_PLANS = '/plans';
  const PATH_SUBSCRIPTIONS = '/subscriptions';
  const PATH_TRANSACTIONS = '/transactions';
  const PATH_MEASURED_UNITS = '/measured_units';
  const PATH_USAGE = '/usage';

  const PATH_RECURLY_JS_RESULT = '/recurly_js/result';

  const PATH_TRANSPARENT = '/transparent/';
  const PATH_TRANSPARENT_SUBSCRIPTION = '/subscription';
  const PATH_TRANSPARENT_TRANSACTION = '/transaction';
  const PATH_TRANSPARENT_BILLING_INFO = '/billing_info';
  const PATH_TRANSPARENT_RESULTS = 'results/';

  /**
   * Create a new Recurly Client
   * @param string API key. Do not specify to use the default API key (which must be set at the static variable)
   * @param string "Accept-language" header variable from the current user's browser. Localizes validation messages.
   */
  function __construct($apiKey = null, $acceptLanguage = 'en-US') {
    $this->_apiKey = $apiKey;
    $this->_acceptLanguage = $acceptLanguage;
  }

  public function request($method, $uri, $data = null)
  {
    return $this->_sendRequest($method, $uri, $data);
  }

  public function baseUri() {
    return sprintf(Recurly_Client::$apiUrl, Recurly_Client::$subdomain);
  }

  /**
   * Current API key
   * @return string API key
   */
  public function apiKey() {
    return (empty($this->_apiKey) ? Recurly_Client::$apiKey : $this->_apiKey);
  }

  /**
  * Sends an HTTP request to the Recurly API
  *
  * @param string  $method Specifies the HTTP method to be used for this request
  * @param string  $uri    Target URI for this request (relative to the API root)
  * @param mixed   $data   x-www-form-urlencoded data (or array) to be sent in a POST request body
  *
  * @return $code, $response
  */
  private function _sendRequest($method, $uri, $data = '')
  {
    if(function_exists('mb_internal_encoding'))
      mb_internal_encoding(self::DEFAULT_ENCODING);

    if (substr($uri,0,4) != 'http')
      $uri = $this->baseUri() . $uri;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    if (self::$CACertPath) {
      curl_setopt($ch, CURLOPT_CAINFO, self::$CACertPath);
    }
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 45);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Content-Type: application/xml; charset=utf-8',
      'Accept: application/xml',
      Recurly_Client::__userAgent(),
      'Accept-Language: ' . $this->_acceptLanguage,
      'X-Api-Version: ' . Recurly_Client::$apiVersion
    ));
    curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey());

    if ('POST' == $method)
    {
      curl_setopt($ch, CURLOPT_POST, TRUE);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    else if ('PUT' == $method)
    {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }
    else if('GET' != $method)
    {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    }

    $response = curl_exec($ch);

    if ($response === false)
    {
      $errorNumber = curl_errno($ch);
      $message = curl_error($ch);
      curl_close($ch);
      $this->_raiseCurlError($errorNumber, $message);
    }

    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    list($header, $body) = explode("\r\n\r\n", $response, 2);
    // Larger responses end up prefixed by "HTTP/1.1 100 Continue\r\n\r\n" which
    // needs to be discarded.
    if (strpos($header," 100 Continue") !== false ) {
      list($header, $body) = explode("\r\n\r\n", $body, 2);
    }
    $headers = $this->_getHeaders($header);

    return new Recurly_ClientResponse($statusCode, $headers, $body);
  }

  private static function __userAgent() {
    return "User-Agent: Recurly/" . self::API_CLIENT_VERSION . '; PHP ' . phpversion() . ' [' . php_uname('s') . ']';
  }

  private function _getHeaders($headerText)
  {
    $headers = explode("\r\n", $headerText);
    $returnHeaders = array();
    foreach ($headers as &$header) {
      preg_match('/([^:]+): (.*)/', $header, $matches);
      if (sizeof($matches) > 2)
        $returnHeaders[$matches[1]] = $matches[2];
    }
    return $returnHeaders;
  }

  private function _raiseCurlError($errorNumber, $message)
  {
    switch ($errorNumber) {
      case CURLE_COULDNT_CONNECT:
      case CURLE_COULDNT_RESOLVE_HOST:
      case CURLE_OPERATION_TIMEOUTED:
        throw new Recurly_ConnectionError("Failed to connect to Recurly ($message).");
      case CURLE_SSL_CACERT:
      case CURLE_SSL_PEER_CERTIFICATE:
        throw new Recurly_ConnectionError("Could not verify Recurly's SSL certificate.");
      default:
        throw new Recurly_ConnectionError("An unexpected error occurred connecting with Recurly.");
    }
  }

  /**
  *  Requests a PDF document from the given URI
  *
  * @param  string $uri      Target URI for this request (relative to the API root)
  * @param  string $locale   Locale for the PDF invoice (e.g. "en-GB", "en-US", "fr")
  * @return string $response PDF document
  */
  public function getPdf($uri, $locale = null)
  {
    if (substr($uri,0,4) != 'http')
      $uri = $this->baseUri() . $uri;

    if (is_null($locale))
      $locale = $this->_acceptLanguage;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $uri);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 1);
    curl_setopt($ch, CURLOPT_HEADER, FALSE); // do not return headers
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      'Accept: application/pdf',
      Recurly_Client::__userAgent(),
      'Accept-Language: ' . $locale
    ));
    curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey());

    $response = curl_exec($ch);

    if ($response === false)
    {
      $errorNumber = curl_errno($ch);
      $message = curl_error($ch);
      curl_close($ch);
      $this->_raiseCurlError($errorNumber, $message);
    }

    curl_close($ch);

    return $response;
  }
}
