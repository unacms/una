<?php
class PPSignatureAuthHandler implements IPPHandler {
		
	public function handle($httpConfig, $request) {
		
		$credential = $request->getCredential();
		if(!isset($credential)) {
			return;
		}		
		$thirdPartyAuth = $credential->getThirdPartyAuthorization();
		if($thirdPartyAuth && $thirdPartyAuth instanceof PPTokenAuthorization) {
			$httpConfig->addHeader('X-PAYPAL-AUTHORIZATION',
					AuthSignature::generateFullAuthString($credential->getUsername(), $credential->getPassword(),
							$thirdPartyAuth->getAccessToken(), $thirdPartyAuth->getTokenSecret(),
							$httpConfig->getMethod(), $httpConfig->getUrl()));
		}
		
		switch($request->getBindingType()) {
			case 'NV':
				if(!$thirdPartyAuth || !$thirdPartyAuth instanceof PPTokenAuthorization) {
					$httpConfig->addHeader('X-PAYPAL-SECURITY-USERID', $credential->getUserName());
					$httpConfig->addHeader('X-PAYPAL-SECURITY-PASSWORD', $credential->getPassword());
					$httpConfig->addHeader('X-PAYPAL-SECURITY-SIGNATURE', $credential->getSignature());
					if($thirdPartyAuth) {
						$httpConfig->addHeader('X-PAYPAL-SECURITY-SUBJECT', $thirdPartyAuth->getSubject());
					}
				}
				break;
			case 'SOAP':
				if($thirdPartyAuth && $thirdPartyAuth instanceof PPTokenAuthorization) {
					$request->addBindingInfo('securityHeader' , '<ns:RequesterCredentials/>');
				} else {
					$securityHeader = '<ns:RequesterCredentials><ebl:Credentials>';
					$securityHeader .= '<ebl:Username>' . $credential->getUserName() . '</ebl:Username>';
					$securityHeader .= '<ebl:Password>' . $credential->getPassword() . '</ebl:Password>';
					$securityHeader .= '<ebl:Signature>' . $credential->getSignature() . '</ebl:Signature>';					
					if($thirdPartyAuth && $thirdPartyAuth instanceof PPSubjectAuthorization) {
						$securityHeader .= '<ebl:Subject>' . $thirdPartyAuth->getSubject() . '</ebl:Subject>';
					}
					$securityHeader .= '</ebl:Credentials></ns:RequesterCredentials>';
					$request->addBindingInfo('securityHeader' , $securityHeader);					
				}
				break;
		}
	}
	
}