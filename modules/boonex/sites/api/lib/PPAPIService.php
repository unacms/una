<?php

class PPAPIService {
	
	public $endpoint;
	public $serviceName;
	private $logger;
	private $handlers = array();
	private $serviceBinding;

	public function __construct($port, $serviceName, $serviceBinding, $handlers=array()) {
		$this->serviceName = $serviceName;
		$config = PPConfigManager::getInstance();
		if($port!= null)
		{
			$this->endpoint = $config->get('service.EndPoint.'.$port);
		}
		// for backward compatibilty (for those who are using old config files with 'service.EndPoint')
		else
		{
			$this->endpoint = $config->get('service.EndPoint');
		}
		
		$this->logger = new PPLoggingManager(__CLASS__);
		$this->handlers = $handlers;
		$this->serviceBinding = $serviceBinding;
	}

	public function setServiceName($serviceName) {
		$this->serviceName = $serviceName;
	}

	public function addHandler($handler) {
		$this->handlers[] = $handler;
	}

	public function makeRequest($apiMethod, $params, $apiUsername = null, $accessToken = null, $tokenSecret = null) {

		$config = PPConfigManager::getInstance();
		if(is_string($apiUsername) || is_null($apiUsername)) {
			// $apiUsername is optional, if null the default account in config file is taken
			$credMgr = PPCredentialManager::getInstance();
			$apiCredential = clone($credMgr->getCredentialObject($apiUsername ));
		} else {
			$apiCredential = $apiUsername; //TODO: Aargh
		}
		if(isset($accessToken) && isset($tokenSecret)) {
			$apiCredential->setThirdPartyAuthorization(
				new PPTokenAuthorization($accessToken, $tokenSecret));
		}
		
		if($this->serviceBinding == 'SOAP' ) {
			$url = $this->endpoint;
		} else {
			$url = $this->endpoint . $this->serviceName . '/' . $apiMethod;
		}

		$request = new PPRequest($params, $this->serviceBinding);
		$request->setCredential($apiCredential);
		$httpConfig = new PPHttpConfig($url, PPHttpConfig::HTTP_POST);
		$this->runHandlers($httpConfig, $request);
		
		$formatter = FormatterFactory::factory($this->serviceBinding);
		$payload = $formatter->toString($request);
		$connection = PPConnectionManager::getInstance()->getConnection($httpConfig);
		$this->logger->info("Request: $payload");
		$response = $connection->execute($payload);
		$this->logger->info("Response: $response");
		
		return array('request' => $payload, 'response' => $response);
	}

	private function runHandlers($httpConfig, $request) {
		$handler = new PPAuthenticationHandler();
		$handler->handle($httpConfig, $request);
		foreach($this->handlers as $handlerClass) {
			$handler = new $handlerClass();
			$handler->handle($httpConfig, $request);
		}
	}

}
