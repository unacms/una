<?php

/**
 * 
 *
 */
class PPIPNMessage {
	
	const IPN_CMD = 'cmd=_notify-validate';
	
	/*
	 *@var boolian
	 *
	 */
	private $isIpnVerified;
	
	/**
	 * 
	 * @var array
	 */
	private $ipnData = array();

	/**
	 * 
	 * @param string $postData OPTIONAL post data. If null, 
	 * 				the class automatically reads incoming POST data 
	 * 				from the input stream
	 */
	public function __construct($postData='') {
		if($postData == '') {			
			// reading posted data from directly from $_POST may causes serialization issues with array data in POST
			// reading raw POST data from input stream instead.			
			$postData = file_get_contents('php://input');
		}
		
		$rawPostArray = explode('&', $postData);		
		foreach ($rawPostArray as $keyValue) {
			$keyValue = explode ('=', $keyValue);
			if (count($keyValue) == 2)
				$this->ipnData[$keyValue[0]] = urldecode($keyValue[1]);
		}
		//var_dump($this->ipnData);	
	}
	
	/**
	 * Returns a hashmap of raw IPN data
	 * 
	 * @return array  
	 */
	public function getRawData() {
		return $this->ipnData;
	}
	
	/**
	 * Validates a IPN message
	 * 
	 * @return boolean
	 */
	public function validate() {
	    if(isset($this->isIpnVerified))
		{
			return $this->isIpnVerified;
		}
		else
			{
			$request = self::IPN_CMD;
			if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc() == 1) {
				$get_magic_quotes_exists = true;
			} else {
				$get_magic_quotes_exists = false;
			}
			foreach ($this->ipnData as $key => $value) {
				if($get_magic_quotes_exists) {
					$value = urlencode(stripslashes($value));
				} else {
					$value = urlencode($value);
				}
				$request .= "&$key=$value";
			}			
			$httpConfig = new PPHttpConfig(PPConfigManager::getInstance()->get('service.EndPoint.IPN'));
			$httpConfig->addCurlOption(CURLOPT_FORBID_REUSE, 1);
			$httpConfig->addCurlOption(CURLOPT_HTTPHEADER, array('Connection: Close'));
			
			$connection = PPConnectionManager::getInstance()->getConnection($httpConfig);
			$response = $connection->execute($request);
			if($response == 'VERIFIED') {
				$this->isIpnVerified = true;
				return true;
			}
			$this->isIpnVerified = false;	
			return false; // value is 'INVALID'
			}
	}
	
	/**
	 * Returns the transaction id for which
	 * this IPN was generated, if one is available
	 *
	 * @return string
	 */
	public function getTransactionId() {
		if(isset($this->ipnData['txn_id'])) {
			return $this->ipnData['txn_id'];
		} else if(isset($this->ipnData['transaction[0].id'])) {
			$idx = 0;
			do {				
				$transId[] =  $this->ipnData["transaction[$idx].id"];				
				$idx++;
			} while(isset($this->ipnData["transaction[$idx].id"]));
			return $transId;
		}
	}
	
	/**
	 * Returns the transaction type for which
	 * this IPN was generated
	 * 
	 * @return string
	 */
	public function getTransactionType() {
		return $this->ipnData['transaction_type'];
	}	
	
}
