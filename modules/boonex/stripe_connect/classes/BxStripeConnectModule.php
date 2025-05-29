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
        return [
            BX_STRIPE_CONNECT_MODE_LIVE => _t('_bx_stripe_connect_option_mode_live'),
            BX_STRIPE_CONNECT_MODE_TEST => _t('_bx_stripe_connect_option_mode_test'),
        ];
    }

    /**
     * @page service Service Calls
     * @section bx_stripe_connect Stripe Connect
     * @subsection bx_stripe_connect-other Other
     * @subsubsection bx_stripe_connect-get_options_pmode get_options_pmode
     * 
     * @code bx_srv('bx_stripe_connect', 'get_options_pmode', [...]); @endcode
     * 
     * Get an array with available processing modes for payments. Is used in forms.
     *
     * @return an array with available modes represented as key => value pairs.
     * 
     * @see BxStripeConnectModule::serviceGetOptionsPmode
     */
    /** 
     * @ref bx_stripe_connect-get_options_pmode "get_options_pmode"
     */
    public function serviceGetOptionsPmode()
    {
        return [
            BX_STRIPE_CONNECT_PMODE_DIRECT => _t('_bx_stripe_connect_option_pmode_direct'),
            //BX_STRIPE_CONNECT_PMODE_PLATFORM => _t('_bx_stripe_connect_option_pmode_platform'),
        ];
    }

    public function serviceGetOptionValueMode($iVendorId, $aParams = [])
    {
        return _t('_bx_stripe_connect_option_mode_' . $this->_oConfig->getMode());
    }

    public function serviceGetOptionValueLiveAccountId($iVendorId, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iVendorId]);
        if(empty($aAccount) || !is_array($aAccount))
            return '';

        return $aAccount[$CNF['FIELD_LIVE_ACCOUNT_ID']];
    }

    public function serviceGetOptionValueTestAccountId($iVendorId, $aParams = [])
    {
        $CNF = &$this->_oConfig->CNF;

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iVendorId]);
        if(empty($aAccount) || !is_array($aAccount))
            return '';

        return $aAccount[$CNF['FIELD_TEST_ACCOUNT_ID']];
    }

    public function serviceGetConnectCode($iVendorId, $aParams = [])
    {
        return $this->_oTemplate->getConnectCode($iVendorId, $aParams);
    }

    /**
     * 
     * ACTION METHODS
     * 
     */
    public function actionNotify()
    {
        $iResult = $this->_processEvent();
        http_response_code($iResult);
    }

    public function actionAccountCreate()
    {
        $CNF = &$this->_oConfig->CNF;

        $sError = _t('_bx_stripe_connect_err_perform');

        $iId = (int)bx_get('id');
    	if(empty($iId) || $iId != bx_get_logged_profile_id())
            return echoJson(['code' => 1, 'message' => $sError]);

        $oProfile = BxDolProfile::getInstance($iId);
        if(!$oProfile)
            return echoJson(['code' => 1, 'message' => $sError]);

        $sAccIdField = $CNF['FIELD_' . strtoupper($this->_oConfig->getMode()) . '_ACCOUNT_ID'];

        //TODO: Replace with call to $this->_oConfig->getStripe() method.
        $oStripe = new \Stripe\StripeClient($this->_oConfig->getApiSecretKey());
        $oAccount = $oStripe->accounts->create([
            'type' => $this->_oConfig->getAccountType(),
            'email' => $oProfile->getAccountObject()->getEmail(),
        ]);

        if(!$oAccount)
            return echoJson(['code' => 1, 'message' => $sError]);

        $this->_oDb->insertAccount([
            'profile_id' => $iId,
            $sAccIdField => $oAccount->id
        ]);

        $sLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=payment-details');
        $oAccountLink = $oStripe->accountLinks->create([
            'account' => $oAccount->id,
            'refresh_url' => $sLink,
            'return_url' => $sLink,
            'type' => 'account_onboarding',
        ]);

        return echoJson(['code' => 0, 'redirect' => $oAccountLink->url]);
    }

    public function actionAccountContinue()
    {
        $CNF = &$this->_oConfig->CNF;

        $sError = _t('_bx_stripe_connect_err_perform');

        $iId = (int)bx_get('id');
    	if(empty($iId) || $iId != bx_get_logged_profile_id())
            return echoJson(['code' => 1, 'message' => $sError]);

        $sModeUc = strtoupper($this->_oConfig->getMode());
        $sAccIdField = $CNF['FIELD_' . $sModeUc . '_ACCOUNT_ID'];
        $sAccDetailsField = $CNF['FIELD_' . $sModeUc . '_DETAILS'];

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iId]);
        if(empty($aAccount) || !is_array($aAccount) || $aAccount[$sAccIdField] == '')
            return echoJson(['code' => 1, 'message' => $sError]);

        if((int)$aAccount[$sAccDetailsField] != 0)
            return echoJson(['code' => 0]);

        $oStripe = new \Stripe\StripeClient($this->_oConfig->getApiSecretKey());

        $sLink = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=payment-details');
        $oAccountLink = $oStripe->accountLinks->create([
            'account' => $aAccount[$sAccIdField],
            'refresh_url' => $sLink,
            'return_url' => $sLink,
            'type' => 'account_onboarding',
        ]);

        if(empty($oAccountLink))
            return echoJson(['code' => 1, 'message' => $sError]);

        return echoJson(['code' => 0, 'redirect' => $oAccountLink->url]);
    }

    public function actionAccountDelete()
    {
        $CNF = &$this->_oConfig->CNF;

        $sError = _t('_bx_stripe_connect_err_perform');

        $iId = (int)bx_get('id');
    	if(empty($iId) || $iId != bx_get_logged_profile_id())
            return echoJson(['code' => 1, 'message' => $sError]);

        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iId]);
        if(empty($aAccount) || !is_array($aAccount))
            return echoJson(['code' => 1, 'message' => $sError]);

        $sAccIdField = $CNF['FIELD_' . strtoupper($this->_oConfig->getMode()) . '_ACCOUNT_ID'];

        $oStripe = new \Stripe\StripeClient($this->_oConfig->getApiSecretKey());
        $oAccount = $oStripe->accounts->delete($aAccount[$sAccIdField], []);

        if(!$oAccount || !$oAccount->deleted)
            return echoJson(['code' => 2, 'message' => _t('_bx_stripe_connect_err_delete')]);

        $this->_oDb->deleteAccount([
            'profile_id' => $iId,
            $sAccIdField => $oAccount->id
        ]);

        return echoJson(['code' => 0, 'reload' => 1]);
    }

    /**
     * 
     * OTHER METHODS
     * 
     */   
    public function processAlertStripeV3GetButton($iObject, $iSender, $aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParams['vendor_id']))
            return false;

        $sModeUc = strtoupper($this->_oConfig->getMode());
        $sAccIdField = $CNF['FIELD_' . $sModeUc . '_ACCOUNT_ID'];
            
        $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => (int)$aParams['vendor_id']]);
        if(empty($aAccount) || !is_array($aAccount) || empty($aAccount[$sAccIdField]))
            return false;

        $aParams['public_key'] = $this->_oConfig->getApiPublicKey();
        $aParams['connected_account_id'] = $aAccount[$sAccIdField];

        return !empty($aParams['public_key']);
    }

    public function processAlertStripeV3CreateSession($iObject, $iSender, $aParams)
    {
        $CNF = &$this->_oConfig->CNF;

        if(empty($aParams['session_params']))
            return false;

        try {
            $sModeUc = strtoupper($this->_oConfig->getMode());
            $sAccIdField = $CNF['FIELD_' . $sModeUc . '_ACCOUNT_ID'];

            $iVendorId = (int)$aParams['session_params']['metadata']['vendor'];
            $aAccount = $this->_oDb->getAccount(['sample' => 'profile_id', 'profile_id' => $iVendorId]);
            if(empty($aAccount) || !is_array($aAccount) || empty($aAccount[$sAccIdField]))
                return false;

            switch($aParams['session_params']['mode']) {
                case 'payment':
                    $fAmount = 0;
                    foreach($aParams['session_params']['line_items'] as $aLineItem)
                        $fAmount += (float)$aLineItem['price_data']['unit_amount'] * (int)$aLineItem['quantity'];

                    $aParams['session_params']['payment_intent_data'] = [
                        'application_fee_amount' => $this->_oConfig->getFee(BX_PAYMENT_TYPE_SINGLE, $fAmount)
                    ];
                    break;

                //TODO: Need to complete create Subscription flow.
                case 'subscription':
                    $fAmount = 0;
                    foreach($aParams['session_params']['line_items'] as $aLineItem)
                        $fAmount += (float)$aLineItem['price'] * (int)$aLineItem['quantity'];

                    $aParams['session_params']['payment_intent_data'] = [
                        'application_fee_amount' => $this->_oConfig->getFee(BX_PAYMENT_TYPE_SINGLE, $fAmount)
                    ];
                    break;
            }

            $aParams['session_object'] = BxStripeConnectApi::getInstance()->getStripe()->checkout->sessions->create($aParams['session_params'], [
                'stripe_account' => $aAccount[$sAccIdField]
            ]);
        }
        catch (Exception $oException) {
            $aParams['session_object'] = null;

            return $this->_processException('Create Session Error: ', $oException);
        }

        return !empty($aParams['session_object']);
    }

    /**
     * Methods related to old Stripe integration (which starts with 'processAlertStripe') are outdated.
     */
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

    protected function _processEvent()
    {
        $sInput = @file_get_contents("php://input");
        $aEvent = json_decode($sInput, true);
        if(empty($aEvent) || !is_array($aEvent)) 
            return 404;

        $sType = $aEvent['type'];
        if(!in_array($sType, ['account.updated']))
            return 200;

        $this->_log('Webhooks: ' . (!empty($sType) ? $sType : ''));
        $this->_log($aEvent);

        $sMethod = '_processEvent' . bx_gen_method_name($sType, array('.', '_', '-'));
        if(!method_exists($this, $sMethod))
            return 200;

    	return $this->$sMethod($aEvent) ? 200 : 403;
    }

    protected function _processEventAccountUpdated(&$aEvent)
    {
        $CNF = $this->_oConfig->CNF;

        $sAccountId = '';
        if(!isset($aEvent['data']['object']['id']) || !($sAccountId = bx_process_input($aEvent['data']['object']['id'])))
            return false;

        $sMode = $this->_oConfig->getMode();
        $sAccDetailsField = $CNF['FIELD_' . strtoupper($sMode) . '_DETAILS'];

        $aAccount = $this->_oDb->getAccount(['sample' => 'account_id', $sMode . '_account_id' => $sAccountId]);
        if((int)$aAccount[$sAccDetailsField] == 0 && $aEvent['data']['object']['details_submitted'])
            return $this->_oDb->updateAccount([$sAccDetailsField => 1], [$CNF['FIELD_ID'] => $aAccount[$CNF['FIELD_ID']]]);

        return true;
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
