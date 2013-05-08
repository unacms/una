<?php

class PPMerchantServiceHandler extends PPGenericServiceHandler {

	public function handle($httpConfig, $request) {
		parent::handle($httpConfig, $request);
		if($httpConfig->getHeader('X-PAYPAL-AUTHORIZATION')) {
			$httpConfig->addHeader('X-PP-AUTHORIZATION', $httpConfig->getHeader('X-PAYPAL-AUTHORIZATION'));
			$httpConfig->removeHeader('X-PAYPAL-AUTHORIZATION');
		}
		$request->addBindingInfo("namespace", "xmlns:ns=\"urn:ebay:api:PayPalAPI\" xmlns:ebl=\"urn:ebay:apis:eBLBaseComponents\" xmlns:cc=\"urn:ebay:apis:CoreComponentTypes\" xmlns:ed=\"urn:ebay:apis:EnhancedDataTypes\"");
	}
}