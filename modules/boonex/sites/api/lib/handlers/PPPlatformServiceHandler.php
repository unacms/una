<?php

class PPPlatformServiceHandler extends PPGenericServiceHandler {
	
	public function handle($httpConfig, $request) {
		parent::handle($httpConfig, $request);
		$credential = $request->getCredential();
		//TODO: Assuming existence of getApplicationId
		if($credential && $credential->getApplicationId() != NULL) {
			$httpConfig->addHeader('X-PAYPAL-APPLICATION-ID', $credential->getApplicationId());
		}
	}
}