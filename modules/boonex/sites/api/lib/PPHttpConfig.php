<?php
class PPHttpConfig {

	/**
	 * Some default options for curl
	 * These are typically overridden by PPConnectionManager
	 */
	public static $DEFAULT_CURL_OPTS = array(
		CURLOPT_CONNECTTIMEOUT => 10,
		CURLOPT_RETURNTRANSFER => TRUE,
		CURLOPT_TIMEOUT        => 60,	// maximum number of seconds to allow cURL functions to execute
		CURLOPT_USERAGENT      => 'PayPal-PHP-SDK',
		CURLOPT_POST           => 1,
		CURLOPT_HTTPHEADER     => array(),
		CURLOPT_SSL_VERIFYHOST => 2,
		CURLOPT_SSL_VERIFYPEER => 1
	);	
	
	const HEADER_SEPARATOR = ';';	
	const HTTP_GET = 'GET';
	const HTTP_POST = 'POST';

	private $headers = array();

	private $curlOptions;

	private $url;

	private $method;
	/***
	 * Number of times to retry a failed HTTP call
	 */
	private $retryCount;

	/**
	 * 
	 * @param string $url
	 * @param string $method  HTTP method (GET, POST etc) defaults to POST
	 */
	public function __construct($url, $method=self::HTTP_POST) {
		$this->url = $url;
		$this->method = $method;
		$this->curlOptions = self::$DEFAULT_CURL_OPTS;
	}
	
	public function getUrl() {
		return $this->url;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getHeaders() {
		return $this->headers;
	}
	
	public function getHeader($name) {
		if(array_key_exists($name, $this->headers)) {
			return $this->headers[$name];
		}
		return NULL;
	}

	public function setHeaders(array $headers) {
		$this->headers = $headers;
	}

	public function addHeader($name, $value, $overWrite=true) {
		if(!array_key_exists($name, $this->headers) || $overWrite) {
			$this->headers[$name] = $value;
		} else {
			$this->headers[$name] = $this->headers[$name] . HEADER_SEPARATOR . $value;			
		}
	}
	
	public function removeHeader($name) {
		unset($this->headers[$name]);
	}

	
	
	public function getCurlOptions() {
		return $this->curlOptions;
	}

	public function addCurlOption($name, $value) {
		$this->curlOptions[$name] = $value;
	}

	public function setCurlOptions($options) {
		$this->curlOptions = $options;
	}

	

	/**
	 * Set ssl parameters for certificate based client authentication
	 *
	 * @param string $certPath - path to client certificate file (PEM formatted file)
	 */
	public function setSSLCert($certPath, $passPhrase=NULL) {		
		$this->curlOptions[CURLOPT_SSLCERT] = realpath($certPath);
		if(isset($passPhrase) && trim($passPhrase) != "") {
			$this->curlOptions[CURLOPT_SSLCERTPASSWD] = $passPhrase;
		}
	}

	/**
	 * Set connection timeout in seconds
	 * @param integer $timeout
	 */
	public function setHttpTimeout($timeout) {
		$this->curlOptions[CURLOPT_CONNECTTIMEOUT] = $timeout;
	}

	/**
	 * Set HTTP proxy information
	 * @param string $proxy
	 * @throws PPConfigurationException
	 */
	public function setHttpProxy($proxy) {
		$urlParts = parse_url($proxy);
		if($urlParts == false || !array_key_exists("host", $urlParts))
			throw new PPConfigurationException("Invalid proxy configuration ".$proxy);

		$this->curlOptions[CURLOPT_PROXY] = $urlParts["host"];
		if(isset($urlParts["port"]))
			$this->curlOptions[CURLOPT_PROXY] .=  ":" . $urlParts["port"];
		if(isset($urlParts["user"]))
			$this->curlOptions[URLOPT_PROXYUSERPWD]	= $urlParts["user"] . ":" . $urlParts["pass"];
	}	
	
	/**
	 * @param integer $retry
	 */
	public function setHttpRetryCount($retryCount) {
		$this->retryCount = $retryCount;
	}	

	public function getHttpRetryCount() {
		return $this->retryCount;
	}
	
}