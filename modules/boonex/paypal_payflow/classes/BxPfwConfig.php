<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 */

require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/payment/classes/BxPmtConfig.php');

class BxPfwConfig extends BxPmtConfig
{
	protected $_sParentHomePath;
	protected $_sParentHomeUrl;
	protected $_sParentClassPrefix;

	protected $_sProvider;
	protected $_sMode;
	protected $_iTimeout;
	protected $_sCancelUrl;
	protected $_sResponseUrl;

	protected $_sPfwEndpointCall;
	protected $_sPfwEndpointHosted;
	protected $_sPpEndpointHosted;

	protected $_sLogPath;
	protected $_aLogFiles;
	protected $_aLogEnabled;

    function BxPfwConfig($aModule)
    {
        parent::BxPmtConfig($aModule);

        $sParentDirectory = 'boonex/payment/';
        $this->_sParentHomePath = BX_DIRECTORY_PATH_MODULES . $sParentDirectory;
        $this->_sParentHomeUrl = BX_DOL_URL_MODULES . $sParentDirectory;
        $this->_sParentClassPrefix = 'BxPmt';

        $this->_sProvider = '';
        $this->_sMode = BX_PFW_MODE_LIVE;
        $this->_iTimeout = 90;
		$this->_sReturnUrl = $this->getBaseUri();
        $this->_sCancelUrl = $this->getBaseUri() . 'cart/';
        $this->_sResponseUrl = $this->getBaseUri() . 'response/';

        $this->_aPrefixes = array(
        	'general' => 'bx_pfw_',
        	'langs' => '_bx_pfw_',
        	'options' => 'bx_pfw_',
        );
        $this->_aJsClasses = array(
        	'cart' => 'BxPfwCart',
        	'cart_parent' => 'BxPmtCart',
        	'orders' => 'BxPfwOrders',
        	'orders_parent' => 'BxPmtOrders'
        );
        $this->_aJsObjects = array(
        	'cart' => 'oPfwCart',
        	'orders' => 'oPfwOrders'
        );

        $this->_sOptionsCategory = 'PayPal PayFlow Pro';

        $this->_sLogPath = $this->getHomePath() . 'log/';
        $this->_aLogFiles = array(
        	'info' => 'pp.info.log',
	        'error' => 'pp.error.log',
        );
        $this->_aLogEnabled = array(
        	'info' => 1,
	        'error' => 1,
        );
    }

	function init(&$oDb)
    {
        parent::init($oDb);

        $sOptionPrefix = $this->getOptionsPrefix();
        //TODO: init necessary settings here.
    }

	function setProvider($sProvider)
    {
    	$this->_sProvider = $sProvider;
    }
	function getProvider()
    {
    	return $this->_sProvider;
    }
    function setMode($sMode)
    {
    	$this->_sMode = $sMode;
    }
	function getMode()
    {
    	return $this->_sMode;
    }
	function getParentClassPrefix()
    {
        return $this->_sParentClassPrefix;
    }
	function getParentHomePath()
    {
        return $this->_sParentHomePath;
    }
    function getParentHomeUrl()
    {
        return $this->_sParentHomeUrl;
    }

    function getTimeout()
    {
    	return $this->_iTimeout;
    }
	function getReturnUrl($bSsl = false)
    {
    	$sResult = '';

    	switch($this->getProvider()) {
    		case BX_PFW_PROVIDER_HOSTED:
    			$sResult = $this->_sReturnUrl . 'finalize_checkout/';
    	 		break;

    		case BX_PFW_PROVIDER_EXPRESS:
    	 		$sResult = $this->_sReturnUrl . 'confirm/';
    	 		break;

    	 	case BX_PFW_PROVIDER_RECURRING:
    	 		$sResult = $this->_sReturnUrl . 'confirm/';
    	 		break;
    	}

    	$sResult = BX_DOL_URL_ROOT . $sResult;
    	if($bSsl && strpos($sResult, 'https://') === false)
    		$sResult = 'https://' . bx_ltrim_str($sResult, 'http://');

        return $sResult;
    }
	function getCancelUrl($bSsl = false)
    {
    	$sResult = BX_DOL_URL_ROOT . $this->_sCancelUrl;
    	if($bSsl && strpos($sResult, 'https://') === false)
    		$sResult = 'https://' . bx_ltrim_str($sResult, 'http://');

        return $sResult;
    }
	function getResponseUrl($bSsl = false)
    {
    	$sResult = BX_DOL_URL_ROOT . $this->_sResponseUrl;
    	if($bSsl && strpos($sResult, 'https://') === false)
    		$sResult = 'https://' . bx_ltrim_str($sResult, 'http://');

        return $sResult;
    }
    function getPfwEndpoint($sType = BX_PFW_ENDPOINT_TYPE_CALL)
    {
    	switch($this->_sMode) {
    		case BX_PFW_MODE_LIVE:
    			$sPfwEndpointCall = 'https://payflowpro.paypal.com';
				$sPfwEndpointHosted = 'https://payflowlink.paypal.com';
    			break;

    		case BX_PFW_MODE_TEST:
    			$sPfwEndpointCall = 'https://pilot-payflowpro.paypal.com';
				$sPfwEndpointHosted = 'https://pilot-payflowlink.paypal.com';
    			break;
    	}

    	$sResult = '';
    	switch($sType) {
    		case BX_PFW_ENDPOINT_TYPE_CALL;
    			$sResult = $sPfwEndpointCall;
    			break;

    		case BX_PFW_ENDPOINT_TYPE_HOSTED:
    			$sResult = $sPfwEndpointHosted;
    			break;
    	}

    	return $sResult;
    }
    function getPpEndpoint($sType = BX_PFW_ENDPOINT_TYPE_HOSTED)
    {
    	switch($this->_sMode) {
    		case BX_PFW_MODE_LIVE:
    			$sPpEndpointHosted = 'https://www.paypal.com/cgi-bin/webscr';
    			break;

    		case BX_PFW_MODE_TEST:
    			$sPpEndpointHosted = 'https://www.sandbox.paypal.com/cgi-bin/webscr';
    			break;
    	}

    	$sResult = '';
    	switch($sType) {
    		case BX_PFW_ENDPOINT_TYPE_HOSTED:
    			$sResult = $sPpEndpointHosted;
    			break;
    	}

    	return $sResult;
    }
    function isLog($sType)
    {
    	return isset($this->_aLogEnabled[$sType]) && (int)$this->_aLogEnabled[$sType] == 1;
    }
	function getLogPath()
    {
        return $this->_sLogPath;
    }
    function getLogFile($sType)
    {
    	return isset($this->_aLogFiles[$sType]) ? $this->_aLogFiles[$sType] : $this->_aLogFiles['error'];
    }
}
