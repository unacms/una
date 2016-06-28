<?php

class ChargeBee_Environment {

    private $apiKey;
    private $site;
    private $apiEndPoint;
    
    private static $default_env;
    public static $scheme = "https";
    public static $chargebeeDomain;

    public static  $connectTimeout= 50;
    public static  $timeout=100;
    const API_VERSION = "v2";
    
    function __construct($site, $apiKey) {
        $this->site = $site;
        $this->apiKey = $apiKey;
        if (ChargeBee_Environment::$chargebeeDomain == null) {
            $this->apiEndPoint = "https://$site.chargebee.com/api/" . ChargeBee_Environment::API_VERSION;
        } else {
            $this->apiEndPoint = ChargeBee_Environment::$scheme . "://$site." . ChargeBee_Environment::$chargebeeDomain . "/api/" . ChargeBee_Environment::API_VERSION;
        }
    }

    public static function configure($site, $apiKey) {
        ChargeBee_Environment::$default_env = new ChargeBee_Environment($site, $apiKey);
    }

    public function getApiKey() {
        return $this->apiKey;
    }

    public function getSite() {
        return $this->site;
    }

    public function getApiEndpoint() {
        return $this->apiEndPoint;
    }

    
    
    public static function defaultEnv() {
        return ChargeBee_Environment::$default_env;
    }

    public function apiUrl($url) {
        return $this->apiEndPoint . $url;
    }

}

?>
