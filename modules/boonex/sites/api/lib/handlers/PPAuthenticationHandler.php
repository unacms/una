<?php

class PPAuthenticationHandler implements IPPHandler {	
	
	public function handle($httpConfig, $request) {
		$credential = $request->getCredential();
		if(isset($credential)) {
			if($credential instanceof PPSignatureCredential) {
				$handler = new PPSignatureAuthHandler($credential);
			} else if($credential instanceof PPCertificateCredential) {
				$handler = new PPCredentialAuthHandler($credential);
			} else {
				throw new PPInvalidCredentialException();
			}
			$handler->handle($httpConfig, $request);
		}
	}
}