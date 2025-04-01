<?php

namespace ChargeBee\ChargeBee;

class Environment
{
    private $apiKey;
    private $site;
    private $apiEndPoint;

    private static $default_env;
    public static $scheme = "https";
    public static $chargebeeDomain = "chargebee.com";
    public static $userAgentSuffix = "";

    public static $connectTimeoutInSecs = 30;
    public static $requestTimeoutInSecs = 80;

    public static $timeMachineWaitInSecs = 3;
    public static $exportWaitInSecs = 3;

    const API_VERSION = "v2";

    public function __construct($site, $apiKey)
    {
        $this->site = $site;
        $this->apiKey = $apiKey;
        $this->apiEndPoint = self::$scheme . "://$site." . self::$chargebeeDomain . "/api/" . self::API_VERSION;
    }

    public static function configure($site, $apiKey)
    {
        self::$default_env = new self($site, $apiKey);
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }

    public function getSite()
    {
        return $this->site;
    }

    public function getApiEndpoint()
    {
        return $this->apiEndPoint;
    }

    public static function defaultEnv()
    {
        return self::$default_env;
    }

    private function getSubDomainApiUrl($subDomain)
    {
        return self::$scheme . "://" . self::getSite() . "." . $subDomain . "." . self::$chargebeeDomain . "/api/" . self::API_VERSION;
    }

    public function apiUrl($url, $subDomain = null)
    {
      if($subDomain != null) {
          return self::getSubDomainApiUrl($subDomain) . $url;
      }
      return $this->apiEndPoint . $url;
    }

    public static function updateConnectTimeoutInSecs($connectTimeout)
    {
        self::$connectTimeoutInSecs = $connectTimeout;
    }

    public static function updateRequestTimeoutInSecs($requestTimeout)
    {
        self::$requestTimeoutInSecs = $requestTimeout;
    }

    public static function setUserAgentSuffix($suffix){
        self::$userAgentSuffix = $suffix;
    }
}
