<?php

class PPGenericServiceHandler implements IPPHandler {

	public function handle($httpConfig, $request) {		
		$config = PPConfigManager::getInstance();
		$httpConfig->addHeader('X-PAYPAL-REQUEST-DATA-FORMAT', $request->getBindingType());
		$httpConfig->addHeader('X-PAYPAL-RESPONSE-DATA-FORMAT', $request->getBindingType());
		$httpConfig->addHeader('X-PAYPAL-DEVICE-IPADDRESS', PPUtils::getLocalIPAddress());
		$httpConfig->addHeader('X-PAYPAL-REQUEST-SOURCE', PPBaseService::getRequestSource());
		
		if( strstr($httpConfig->getUrl(), "/AdaptiveAccounts/") && strstr($httpConfig->getUrl(), "sandbox")) {
			$httpConfig->addHeader('X-PAYPAL-SANDBOX-EMAIL-ADDRESS', $config->get('service.SandboxEmailAddress'));
		}
	}
}