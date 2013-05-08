<?php

class PPBaseService {

    // SDK Name
	protected  static $SDK_NAME = "paypal-php-sdk";
	// SDK Version
	protected static $SDK_VERSION = "2.1.96";
	
	private $serviceName;
	private $serviceBinding;
	private $handlers;

   /*
    * Setters and getters for Third party authentication (Permission Services)
    */
	protected $accessToken;
	protected $tokenSecret;
	
	protected $lastRequest;
	protected $lastResponse;
	


	/**
	 * Compute the value that needs to sent for the PAYPAL_REQUEST_SOURCE
	 * parameter when making API calls
	 */
	public static function getRequestSource()
	{
		return str_replace(" ", "-", self::$SDK_NAME) . "-" . self::$SDK_VERSION;
	}
	

    public function getLastRequest() {
		return $this->lastRequest;
	}
    public function setLastRequest($lastRqst) {
		$this->lastRequest = $lastRqst;
	}
    public function getLastResponse() {
		return $this->lastResponse;
	}
    public function setLastResponse($lastRspns) {
		$this->lastResponse = $lastRspns;
	}

	public function getAccessToken() {
		return $this->accessToken;
	}
	/**
	 * @deprecated
	 * For using third party token permissions, 
	 * create a ICredential object and pass it to the
	 * call() method instead. 
	 *
	 *<pre>
	 * $service = new *Service();
	 * $cred = new PPSignatureCredential("username", "password", "signature");
	 * $cred->setThirdPartyAuthorization(new PPTokenAuthorization("accessToken", "tokenSecret"));
	 * $service->SomeOperation($reqObject, $cred); 
	 *</pre>	 
	 */
 	public function setAccessToken($accessToken) {
		$this->accessToken = $accessToken;
	}
	public function getTokenSecret() {
		return $this->tokenSecret;
	}
	/**
	 * @deprecated
	 * For using third party token permissions, 
	 * create a ICredential object and pass it to the
	 * call() method instead. 
	 *
	 *<pre>
	 * $service = new *Service();
	 * $cred = new PPSignatureCredential("username", "password", "signature");
	 * $cred->setThirdPartyAuthorization(new PPTokenAuthorization("accessToken", "tokenSecret"));
	 * $service->SomeOperation($reqObject, $cred); 
	 *</pre>	 
	 */
	public function setTokenSecret($tokenSecret) {
		$this->tokenSecret = $tokenSecret;
	}

	public function __construct($serviceName, $serviceBinding, $handlers=array()) {
		$this->serviceName = $serviceName;
		$this->serviceBinding = $serviceBinding;
		$this->handlers = $handlers;
	}

	public function getServiceName() {
		return $this->serviceName;
	}

	/**
	 * 
	 * @param string $method - API method to call
	 * @param object $requestObject Request object 
	 * @param mixed $apiCredential - Optional API credential - can either be
	 * 		a username configured in sdk_config.ini or a ICredential object
	 *      created dynamically 		
	 */
	public function call($port, $method, $requestObject, $apiCredential = null) {		
		$service = new PPAPIService($port, $this->serviceName, 
				$this->serviceBinding, $this->handlers);		
		$ret = $service->makeRequest($method, $requestObject, $apiCredential,
				$this->accessToken, $this->tokenSecret);
		$this->lastRequest = $ret['request'];
		$this->lastResponse = $ret['response'];
		return $this->lastResponse;
	}
}