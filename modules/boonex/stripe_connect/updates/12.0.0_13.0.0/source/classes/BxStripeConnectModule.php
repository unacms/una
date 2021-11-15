<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    StripeConnect Stripe Connect
 * @ingroup     TridentModules
 *
 * @{
 */

define('BX_STRIPE_CONNECT_MODE_LIVE', 'live');
define('BX_STRIPE_CONNECT_MODE_TEST', 'test');

define('BX_STRIPE_CONNECT_PMODE_DIRECT', 'direct');
define('BX_STRIPE_CONNECT_PMODE_PLATFORM', 'platform');

class BxStripeConnectModule extends BxBaseModConnectModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

    /**
     * @page service Service Calls
     * @section bx_stripe_connect Stripe Connect
     * @subsection bx_stripe_connect-other Other
     * @subsubsection bx_stripe_connect-get_options_mode get_options_mode
     * 
     * @code bx_srv('bx_stripe_connect', 'get_options_mode', [...]); @endcode
     * 
     * Get an array with available modes. Is used in forms.
     *
     * @return an array with available modes represented as key => value pairs.
     * 
     * @see BxStripeConnectModule::serviceGetOptionsMode
     */
    /** 
     * @ref bx_stripe_connect-get_options_mode "get_options_mode"
     */
    public function serviceGetOptionsMode()
    {
        return array(
        	BX_STRIPE_CONNECT_MODE_LIVE => _t('_bx_stripe_connect_option_mode_live'),
        	BX_STRIPE_CONNECT_MODE_TEST => _t('_bx_stripe_connect_option_mode_test'),
        );
    }

    /**
     * @page service Service Calls
     * @section bx_stripe_connect Stripe Connect
     * @subsection bx_stripe_connect-other Other
     * @subsubsection bx_stripe_connect-get_options_api_scope get_options_api_scope
     * 
     * @code bx_srv('bx_stripe_connect', 'get_options_api_scope', [...]); @endcode
     * 
     * Get an array with available API scopes. Is used in forms.
     *
     * @return an array with available scopes represented as key => value pairs.
     * 
     * @see BxStripeConnectModule::serviceGetOptionsApiScope
     */
    /** 
     * @ref bx_stripe_connect-get_options_api_scope "get_options_api_scope"
     */
    public function serviceGetOptionsApiScope()
    {
        return array(
        	'read_only' => _t('_bx_stripe_connect_option_api_scope_read_only'),
            'read_write' => _t('_bx_stripe_connect_option_api_scope_read_write'),
        );
    }

    /**
     * @page service Service Calls
     * @section bx_stripe_connect Stripe Connect
     * @subsection bx_stripe_connect-other Other
     * @subsubsection bx_stripe_connect-get_options_pmode_single get_options_pmode_single
     * 
     * @code bx_srv('bx_stripe_connect', 'get_options_pmode_single', [...]); @endcode
     * 
     * Get an array with available processing modes for single time payments. Is used in forms.
     *
     * @return an array with available modes represented as key => value pairs.
     * 
     * @see BxStripeConnectModule::serviceGetOptionsPmodeSingle
     */
    /** 
     * @ref bx_stripe_connect-get_options_pmode_single "get_options_pmode_single"
     */
    public function serviceGetOptionsPmodeSingle()
    {
        return array(
        	BX_STRIPE_CONNECT_PMODE_DIRECT => _t('_bx_stripe_connect_option_pmode_direct'),
            BX_STRIPE_CONNECT_PMODE_PLATFORM => _t('_bx_stripe_connect_option_pmode_platform'),
        );
    }

    /**
     * @page service Service Calls
     * @section bx_stripe_connect Stripe Connect
     * @subsection bx_stripe_connect-other Other
     * @subsubsection bx_stripe_connect-get_options_pmode_recurring get_options_pmode_recurring
     * 
     * @code bx_srv('bx_stripe_connect', 'get_options_pmode_recurring', [...]); @endcode
     * 
     * Get an array with available processing modes for recurring payments. Is used in forms.
     * Note. Subscriptions (recurring payments) do not currently support the Platform mode.
     *
     * @return an array with available modes represented as key => value pairs.
     * 
     * @see BxStripeConnectModule::serviceGetOptionsPmodeRecurring
     */
    /** 
     * @ref bx_stripe_connect-get_options_pmode_recurring "get_options_pmode_recurring"
     */
    public function serviceGetOptionsPmodeRecurring()
    {
        return array(
        	BX_STRIPE_CONNECT_PMODE_DIRECT => _t('_bx_stripe_connect_option_pmode_direct'),
        );
    }

    /**
     * @page service Service Calls
     * @section bx_stripe_connect Stripe Connect
     * @subsection bx_stripe_connect-page_blocks Page Blocks
     * @subsubsection bx_stripe_connect-get_block_connect get_block_connect
     * 
     * @code bx_srv('bx_stripe_connect', 'get_block_connect', [...]); @endcode
     * 
     * Get page block with necessary info and actions to connect/disconnect.
     *
     * @return HTML string with block content to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxStripeConnectModule::serviceGetBlockConnect
     */
    /** 
     * @ref bx_stripe_connect-get_block_connect "get_block_connect"
     */
    public function serviceGetBlockConnect()
    {
    	return $this->_oTemplate->displayBlockConnect();
    }

    public function actionDelete()
    {
    	$sError = _t('_bx_stripe_connect_err_perform');

    	$iId = (int)bx_get('id');
    	if(empty($iId))
    		return echoJson(array('code' => 1, 'message' => $sError));

    	if(!$this->deleteAccount($iId))
    		return echoJson(array('code' => 2, 'message' => $sError));

    	echoJson(array('code' => 0, 'id' => $iId, 'reload' => 1));
    }

    public function actionResult()
    {
    	if(bx_get('error') !== false)
    		$this->_oTemplate->displayErrorOccured(bx_process_input(bx_get('error_description')));

		$mixedResult = $this->createAccount(bx_process_input(bx_get('code')));
		if($mixedResult !== true)
			$this->_oTemplate->displayErrorOccured($mixedResult);

		$this->_oTemplate->displayMsg('_bx_stripe_connect_msg_connected', true);
    }

	public function actionNotify()
    {
		$iResult = $this->_processEvent();
		http_response_code($iResult);
    }

    public function createAccount($sCode)
    {
    	$CNF = $this->_oConfig->CNF;

		$oResult = $this->_request($CNF['URL_API_TOKEN'], array(
			'client_secret' => $this->_oConfig->getApiSecretKey(),
			'code' => $sCode,
			'grant_type' => 'authorization_code'
		));

		if(!empty($oResult->error))
			return bx_process_input($oResult->error_description);

		$bResult = $this->_oDb->insertAccount(array(
			'author' => bx_get_logged_profile_id(),
			'added' => time(),
			'user_id' => $oResult->stripe_user_id,
			'public_key' => $oResult->stripe_publishable_key,
			'access_token' => $oResult->access_token,
			'refresh_token' => $oResult->refresh_token
		));

		return $bResult ? $bResult : _t('_bx_stripe_connect_err_perform');
    }

    public function deleteAccount($iId)
    {
    	$CNF = $this->_oConfig->CNF;

		$aAccount = $this->_oDb->getAccount(array('type' => 'id', 'id' => $iId));
		if(empty($aAccount) || !is_array($aAccount))
			return false;

		$oResult = $this->_request($CNF['URL_API_DEAUTHORIZE'], array(
			'client_id' => $this->_oConfig->getApiId(),
			'client_secret' => $this->_oConfig->getApiSecretKey(),
			'stripe_user_id' => $aAccount['user_id'],
		));

		if(!empty($oResult->error))
			return false;

		return $this->_oDb->deleteAccount(array('id' => $iId));
    }

    public function processAlertStripeGetButton($iObject, $iSender, $aParams)
    {
        if(empty($aParams['type']))
            return false;

        $sType = $aParams['type'];
        $sPayMode = $this->_oConfig->getPayMode($sType);

        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                if($sPayMode == BX_STRIPE_CONNECT_PMODE_PLATFORM)
                    $aParams['public_key'] = $this->_oConfig->getApiPublicKey();
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                //TODO: Perform necessary actions here !WHEN! 'Platform' mode will be available for recurring payments.
                break;
        }

        return !empty($aParams['public_key']);
    }

    public function processAlertStripeCreateCustomer($iObject, $iSender, $aParams)
    {
        if(empty($aParams['type']) || empty($aParams['customer_params']['card']))
            return false;

        $sPayMode = $this->_oConfig->getPayMode($aParams['type']);

        try {
            switch($sPayMode) {
                case BX_STRIPE_CONNECT_PMODE_DIRECT:
                    /*
                     * Do nothing in this case because a Customer 
                     * should be created in Connected Stripe account.
                     */
                    break;

                case BX_STRIPE_CONNECT_PMODE_PLATFORM:
                    \Stripe\Stripe::setApiKey($this->_oConfig->getApiSecretKey());

                    $aParams['customer_object'] = \Stripe\Customer::create($aParams['customer_params']);
                    break;
            }
		}
		catch (Exception $oException) {
		    $aParams['customer_object'] = null;

		    return $this->_processException('Create Customer Error: ', $oException);
		}

		return !empty($aParams['customer_object']);
    }

    public function processAlertStripeRetrieveCustomer($iObject, $iSender, $aParams)
    {
        if(empty($aParams['type']) || empty($aParams['customer_id']))
            return false;

        $sPayMode = $this->_oConfig->getPayMode($aParams['type']);

        try {
            switch($sPayMode) {
                case BX_STRIPE_CONNECT_PMODE_DIRECT:
                    /*
                     * Do nothing in this case because a Customer 
                     * should be retreived from Connected Stripe account.
                     */
                    break;

                case BX_STRIPE_CONNECT_PMODE_PLATFORM:
                    \Stripe\Stripe::setApiKey($this->_oConfig->getApiSecretKey());

                    $aParams['customer_object'] = \Stripe\Customer::retrieve($aParams['customer_id']);
                    break;
            }
		}
		catch (Exception $oException) {
		    $aParams['customer_object'] = null;

		    return $this->_processException('Retrieve Customer Error: ', $oException);
		}

		return !empty($aParams['customer_object']);
    }

    public function processAlertStripeCreateCharge($iObject, $iSender, $aParams)
    {
        if(empty($iObject) || empty($aParams['charge_params']['amount']))
            return false;

        $aAccount = $this->_getAccount($iObject, BX_PAYMENT_TYPE_SINGLE);
        if($aAccount === false)
            return false;

        $sPayMode = $this->_oConfig->getPayMode(BX_PAYMENT_TYPE_SINGLE);
        $aParams['charge_params']['application_fee'] = $this->_oConfig->getFee(BX_PAYMENT_TYPE_SINGLE, (float)$aParams['charge_params']['amount']);

        \Stripe\Stripe::setApiKey($this->_oConfig->getApiSecretKey());

        try {
            switch($sPayMode) {
                case BX_STRIPE_CONNECT_PMODE_DIRECT:
                    $aParams['charge_object'] = \Stripe\Charge::create($aParams['charge_params'], array('stripe_account' => $aAccount['user_id']));
                    break;

                case BX_STRIPE_CONNECT_PMODE_PLATFORM:
                    $aParams['charge_params']['destination'] = $aAccount['user_id'];
                    $aParams['charge_object'] = \Stripe\Charge::create($aParams['charge_params']);
                    break;
            }
		}
		catch (Exception $oException) {
		    $aParams['charge_object'] = null;

			return $this->_processException('Create Charge Error: ', $oException);
		}

		return !empty($aParams['charge_object']);
    }

    public function processAlertStripeRetrieveCharge($iObject, $iSender, $aParams)
    {
        if(empty($aParams['charge_id']))
            return false;

        $sPayMode = $this->_oConfig->getPayMode(BX_PAYMENT_TYPE_SINGLE);

        try {
            switch($sPayMode) {
                case BX_STRIPE_CONNECT_PMODE_DIRECT:
                    /*
                     * Do nothing in this case because a Charge 
                     * should be stored on Connected Stripe account.
                     */
                    break;

                case BX_STRIPE_CONNECT_PMODE_PLATFORM:
                    \Stripe\Stripe::setApiKey($this->_oConfig->getApiSecretKey());

                    $aParams['charge_object'] = \Stripe\Charge::retrieve($aParams['charge_id']);
                    break;
            }
		}
		catch (Exception $oException) {
		    $aParams['charge_object'] = null;

		    return $this->_processException('Retrieve Customer Error: ', $oException);
		}

		return !empty($aParams['charge_object']);
    }
    
    public function processAlertStripeCreateSubscription($iObject, $iSender, $aParams)
    {
        if(empty($iObject) || empty($aParams['subscription_params']['plan']))
            return false;

        $aAccount = $this->_getAccount($iObject, BX_PAYMENT_TYPE_RECURRING);
        if($aAccount === false)
            return false;

        $aParams['subscription_params']['application_fee_percent'] = $this->_oConfig->getFee(BX_PAYMENT_TYPE_RECURRING);

        \Stripe\Stripe::setApiKey($this->_oConfig->getApiSecretKey());

        try {
             $aParams['subscription_object'] = $aParams['customer']->subscriptions->create($aParams['subscription_params'], array('stripe_account' => $aAccount['user_id']));
		}
		catch (Exception $oException) {
		    $aParams['subscription_object'] = null;

			return $this->_processException('Create Subscription Error: ', $oException);
		}

		return !empty($aParams['subscription_object']);
    }

	protected function _request($sUrl, $aParams = array())
	{
	    $sResult = '';
	    if(!function_exists('curl_init'))
			return false;

        $rConnect = curl_init();

		curl_setopt($rConnect, CURLOPT_URL, $sUrl);
		curl_setopt($rConnect, CURLOPT_HEADER, 0);
		curl_setopt($rConnect, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($rConnect, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($rConnect, CURLOPT_POST, 1);
		curl_setopt($rConnect, CURLOPT_POSTFIELDS, http_build_query($aParams));

        if(bx_mb_strpos($sUrl, 'https') !== false) {
	        curl_setopt($rConnect, CURLOPT_SSL_VERIFYPEER, 0);
			curl_setopt($rConnect, CURLOPT_SSL_VERIFYHOST, 0);
        }
	
		$sResult = curl_exec($rConnect);
		curl_close($rConnect);

	    return json_decode($sResult);
	}

	protected function _processEvent()
	{
    	$sInput = @file_get_contents("php://input");
		$aEvent = json_decode($sInput, true);
		if(empty($aEvent) || !is_array($aEvent)) 
			return 404;

		$sType = $aEvent['type'];
		if(!in_array($sType, array('account.application.deauthorized')))
			return 200;

		$this->_log('Webhooks: ' . (!empty($sType) ? $sType : ''));
		$this->_log($aEvent);

		$sMethod = '_processEvent' . bx_gen_method_name($sType, array('.', '_', '-'));
    	if(!method_exists($this, $sMethod))
    		return 200;

    	return $this->$sMethod($aEvent) ? 200 : 403;
    }

	protected function _processEventAccountApplicationDeauthorized(&$aEvent)
	{
		$CNF = $this->_oConfig->CNF;

		$sApiId = bx_process_input($aEvent['data']['object']['id']);
		if(strcmp($sApiId, $this->_oConfig->getApiId()) != 0)
			return false;

		$sUserId = bx_process_input($aEvent['user_id']);
		if(empty($sUserId))
			return false;

		return $this->_oDb->deleteAccount(array('user_id' => $sUserId));
	}

    protected function _processException($sMessage, &$oException)
	{
		$aError = $oException->getJsonBody();

		$sMessage = $aError['error']['message'];
		if(empty($sMessage))
			$sMessage = $oException->getMessage();

		$this->_log($sMessage . $aError['error']['message']);
		$this->_log($aError);

		return false;
	}

    protected function _getAccount($iPending, $sType)
    {
        $aPending = BxDolPayments::getInstance()->getPendingOrdersInfo(array('id' => $iPending));
        if(!empty($aPending) && is_array($aPending) && count($aPending) == 1)
            $aPending = array_shift($aPending);

        if(empty($aPending) || !is_array($aPending) || $aPending['type'] != $sType)
            return false;

        $iSeller = (int)$aPending['seller_id'];
        $oSeller = BxDolProfile::getInstance($iSeller);
        if(!$oSeller)
            return false;

        $aAccount = $this->_oDb->getAccount(array('type' => 'author', 'author' => $iSeller));
        if(empty($aAccount) || !is_array($aAccount))
            return false;

        return $aAccount;
    }

	protected function _log($sContents)
	{
		if (is_array($sContents))
			$sContents = var_export($sContents, true);	
		else if (is_object($sContents))
			$sContents = json_encode($sContents);
		
		bx_log('bx_stripe_connect', $sContents);
	}
}

/** @} */
