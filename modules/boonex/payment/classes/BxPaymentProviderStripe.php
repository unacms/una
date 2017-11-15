<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Payment Payment
 * @ingroup     UnaModules
 *
 * @{
 */

require_once(BX_DIRECTORY_PATH_PLUGINS . 'stripe/init.php');

define('STRP_MODE_LIVE', 1);
define('STRP_MODE_TEST', 2);

class BxPaymentProviderStripe extends BxBaseModPaymentProvider implements iBxBaseModPaymentProvider
{
	protected $_aIncludeJs;
	protected $_aIncludeCss;

	protected $_sFormDetails;
	protected $_sFormDisplayDetailsEdit;

    protected $_sFormCard;
	protected $_sFormDisplayCardAdd;

	protected $_iMode;
	protected $_bCheckAmount;
	protected $_bProrate;

	protected $_oCustomer;


    function __construct($aConfig)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_sFormDetails = 'bx_payment_form_strp_details';
        $this->_sFormDisplayDetailsEdit = 'bx_payment_form_strp_details_edit';

        $this->_sFormCard = 'bx_payment_form_strp_card';
        $this->_sFormDisplayCardAdd = 'bx_payment_form_strp_card_add';

        $this->_bRedirectOnResult = false;
        $this->_iMode = (int)$this->getOption('mode');
        $this->_bCheckAmount = $this->getOption('check_amount') == 'on'; 
        $this->_bUseSsl = $this->getOption('ssl') == 'on';
        $this->_bProrate = false;
        $this->_sLogFile = BX_DIRECTORY_PATH_LOGS . 'bx_pp_' . $this->_sName . '.log';

        $this->_aIncludeJs = array(
        	'https://checkout.stripe.com/checkout.js',
        	'main.js',
        	'stripe.js'
        );
        $this->_aIncludeCss = array(
        	'stripe.css'
        );

        $this->_oCustomer = null;

        \Stripe\Stripe::setApiKey($this->_getSecretKey());
    }

	public function addJsCss()
    {
    	if(!$this->isActive())
    		return;

        $this->_oModule->_oTemplate->addJs($this->_aIncludeJs);
        $this->_oModule->_oTemplate->addCss($this->_aIncludeCss);
    }

    public function initializeCheckout($iPendingId, $aCartInfo, $sRedirect = '')
    {
    	$sToken = bx_process_input(bx_get('token'));

    	if(empty($aCartInfo['items']) || !is_array($aCartInfo['items']))
    		return $this->_sLangsPrefix . 'err_empty_items';

		$aClient = $this->_oModule->getProfileInfo();
		$aVendor = $this->_oModule->getProfileInfo($aCartInfo['vendor_id']);

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
		if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return $this->_sLangsPrefix . 'err_already_processed';

		switch($aPending['type']) {
			case BX_PAYMENT_TYPE_SINGLE:
				$aCartInfo['items_title'] = '';
				foreach($aCartInfo['items'] as $aItem)
		            $aCartInfo['items_title'] .= ' ' . $aItem['title'] . ',';
				$aCartInfo['items_title'] = trim($aCartInfo['items_title'], ', ');

				$mixedResult = $this->_createCharge($sToken, $iPendingId, $aClient, $aCartInfo);
				if($mixedResult === false)
					return $this->_sLangsPrefix . 'err_cannot_perform';

				header("Location: " . $this->getReturnDataUrl($aVendor['id'], array(
					'order_id' => $mixedResult['order'],
					'customer_id' => $mixedResult['customer'], 
					'pending_id' => $aPending['id'],
				    'redirect' => $sRedirect
				)));
				exit;

			case BX_PAYMENT_TYPE_RECURRING:
				$mixedResult = $this->_createSubscription($sToken, $iPendingId, $aClient, $aCartInfo);
				if($mixedResult === false)
					return $this->_sLangsPrefix . 'err_cannot_perform';

				return array(
					'code' => 0,
					'eval' => $this->_oModule->_oConfig->getJsObject('cart') . '.onSubscribeSubmit(oData);',
					'redirect' => $this->getReturnDataUrl($aVendor['id'], array(
						'order_id' => $mixedResult['order'],
						'customer_id' => $mixedResult['customer'],
						'pending_id' => $aPending['id'],
						'redirect' => $sRedirect
					))
				);
		}
    }

    public function finalizeCheckout(&$aData)
    {
    	$sOrderId = bx_process_input($aData['order_id']);
    	$sCustomerId = bx_process_input($aData['customer_id']);
		$iPendingId = bx_process_input($aData['pending_id'], BX_DATA_INT);
        if(empty($iPendingId))
        	return array('code' => 1, 'message' => $this->_sLangsPrefix . 'err_wrong_data');

        $sRedirect = bx_process_input($aData['redirect']);

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return array('code' => 3, 'message' => $this->_sLangsPrefix . 'err_already_processed');

		$aResult = array(
			'code' => BX_PAYMENT_RESULT_SUCCESS,
        	'message' => '',
			'pending_id' => $iPendingId,
		    'customer_id' => '',
		    'subscription_id' => '',
			'client_name' => '',
			'client_email' => '',
			'paid' => false,
			'trial' => false,
		    'redirect' => $sRedirect
		);

		switch($aPending['type']) {
			case BX_PAYMENT_TYPE_SINGLE:
			    $oCustomer = $this->_retrieveCustomer(BX_PAYMENT_TYPE_SINGLE, $sCustomerId);
				$oCharge = $this->_retrieveCharge($sOrderId);
				if($oCustomer === false || $oCharge === false)
				    return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');
				
				$aCustomer = $oCustomer->jsonSerialize();
				$aCharge = $oCharge->jsonSerialize();
				if(empty($aCustomer) || !is_array($aCustomer) || empty($aCharge) || !is_array($aCharge))
					return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

				$aResult = array_merge($aResult, array(
					'message' => $this->_sLangsPrefix . 'strp_msg_charged',
					'client_email' => $aCustomer['email'],
					'paid' => (bool)$aCharge['paid']
				));
				break;

			case BX_PAYMENT_TYPE_RECURRING:
			    $oCustomer = $this->_retrieveCustomer(BX_PAYMENT_TYPE_RECURRING, $sCustomerId);
				$oSubscription = $this->_retrieveSubscription($sCustomerId, $sOrderId);
				if($oCustomer === false || $oSubscription === false)
					return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

				$aCustomer = $oCustomer->jsonSerialize();
				$aSubscription = $oSubscription->jsonSerialize();
				if(empty($aCustomer) || !is_array($aCustomer) || empty($aSubscription) || !is_array($aSubscription))
					return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

				$aResult = array_merge($aResult, array(
					'message' => $this->_sLangsPrefix . 'strp_msg_subscribed',
                    'customer_id' => $sCustomerId,
				    'subscription_id' => $sOrderId,
					'client_email' => $aCustomer['email'],
				    'trial' => $aSubscription['status'] == 'trialing'
				));
				break;
		}

        //--- Update pending transaction ---//
        $this->_oModule->_oDb->updateOrderPending($iPendingId, array(
            'order' => $sOrderId,
            'error_code' => $aResult['code'],
            'error_msg' => _t($aResult['message'])
        ));

        return $aResult;
    }

    public function notify()
    {
		$iResult = $this->_processEvent();
		http_response_code($iResult);
    }

    public function getButtonSingle($iClientId, $iVendorId, $aParams = array())
    {
        $aItems = array();
        $aCartInfo = $this->_oModule->getObjectCart()->getInfo(BX_PAYMENT_TYPE_SINGLE, $iClientId, (int)$iVendorId, $aItems);
        if(empty($aCartInfo) || !is_array($aCartInfo))
            return '';

    	return $this->_getButton(BX_PAYMENT_TYPE_SINGLE, $iClientId, $iVendorId, array_merge($aParams, array(
			'sVendorName' => _t($this->_sLangsPrefix . 'txt_payment_to', $aCartInfo['vendor_name']),
			'sVendorCurrency' => $aCartInfo['vendor_currency_code'],
			'sVendorIcon' => $aCartInfo['vendor_avatar'],
    	)));
    }

    public function getButtonRecurring($iClientId, $iVendorId, $aParams = array())
    {
    	$aVendor = $this->_oModule->getVendorInfo((int)$iVendorId);

		return $this->_getButton(BX_PAYMENT_TYPE_RECURRING, $iClientId, $iVendorId, array_merge($aParams, array(
			'sVendorName' => _t($this->_sLangsPrefix . 'txt_payment_to', $aVendor['name']),
			'sVendorCurrency' => $aVendor['currency_code'],
			'sVendorIcon' => $aVendor['avatar'],
		)));
    }

    public function getMenuItemsActionsRecurring($iClientId, $iVendorId, $aParams = array())
    {
        if(empty($aParams['order']))
            return array();

        $sJsObject = $this->_oModule->_oConfig->getJsObject(BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION);

        $sPrefix = 'bx-payment-strp-';
        return array(
            array('id' => $sPrefix . 'details', 'name' => $sPrefix . 'details', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".getDetails(this, '" . $aParams['id'] . "')", 'target' => '_self', 'title' => _t('_bx_payment_strp_menu_item_title_details')),
            array('id' => $sPrefix . 'details_change', 'name' => $sPrefix . 'details_change', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".changeDetails(this, '" . $aParams['id'] . "')", 'target' => '_self', 'title' => _t('_bx_payment_strp_menu_item_title_details_change')),
            array('id' => $sPrefix . 'billing', 'name' => $sPrefix . 'billing', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".getBilling(this, '" . $aParams['id'] . "')", 'target' => '_self', 'title' => _t('_bx_payment_strp_menu_item_title_billing')),
            array('id' => $sPrefix . 'billing_change', 'name' => $sPrefix . 'billing_change', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:" . $sJsObject . ".changeBilling(this, '" . $aParams['id'] . "')", 'target' => '_self', 'title' => _t('_bx_payment_strp_menu_item_title_billing_change')),
        );
    }

    public function getDetailsRecurring($iPendingId, $sCustomerId, $sSubscriptionId)
    {
        $oSubscription = $this->_retrieveSubscription($sCustomerId, $sSubscriptionId);
        if($oSubscription === false)
            return '';

        $aSubscription = $oSubscription->jsonSerialize();
        if(empty($aSubscription) || !is_array($aSubscription))
            return '';

        $sNone = _t('_bx_payment_txt_none');
        return $this->_oModule->_oTemplate->parseHtmlByName('strp_details_recurring.html', array(
            'plan' => $aSubscription['plan']['name'],
            'cost' => _t('_bx_payment_strp_txt_cost_mask', (int)$aSubscription['plan']['amount'] / 100, $aSubscription['plan']['currency'], $aSubscription['plan']['interval']),
            'status' => $aSubscription['status'],
        	'created' => bx_time_js($aSubscription['created']),
        	'started' => !empty($aSubscription['start']) ? bx_time_js($aSubscription['start']) : $sNone,
        	'trial_start' => !empty($aSubscription['trial_start']) ? bx_time_js($aSubscription['trial_start']) : $sNone,
        	'trial_end' => !empty($aSubscription['trial_end']) ? bx_time_js($aSubscription['trial_end']) : $sNone,
            'cperiod_start' => !empty($aSubscription['current_period_start']) ? bx_time_js($aSubscription['current_period_start']) : $sNone,
        	'cperiod_end' => !empty($aSubscription['current_period_end']) ? bx_time_js($aSubscription['current_period_end']) : $sNone,
        ));
    }

    public function changeDetailsRecurring($iPendingId, $sCustomerId, $sSubscriptionId)
    {
        $oForm = BxDolForm::getObjectInstance($this->_sFormDetails, $this->_sFormDisplayDetailsEdit, $this->_oModule->_oTemplate);
        $oForm->aFormAttrs['id'] = $this->_oModule->_oConfig->getHtmlIds('subscription', 'form_subscription_change_details');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'subscription_change_details/' . $iPendingId;

        $oForm->aInputs['item_id']['values'] = $this->_getDataChangeDetailsRecurring($iPendingId);

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $aResultError = array('code' => 1, 'message' => _t('_bx_payment_strp_err_details_changed'));

            $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
            list($iVendorId, $iModuleId, $iItemId, $iItemCount) = $this->_oModule->_oConfig->descriptorS2A($aPending['items']);

            $aItem = $this->_oModule->callGetCartItem($iModuleId, array($oForm->getCleanValue('item_id')));
    		if(empty($aItem) || !is_array($aItem))
    			return $aResultError;

            $oSubscription = $this->_retrieveSubscription($sCustomerId, $sSubscriptionId);
            if($oSubscription === false)
                return $aResultError;

            $oSubscription->plan = $aItem['name'];
            $oSubscription->prorate = $this->_bProrate;
            $oSubscription = $oSubscription->save();
            if(strcmp($oSubscription->plan->id, $aItem['name']) !== 0)
                return $aResultError;

            $this->_oModule->_oDb->updateOrderPending($iPendingId, array(
                'items' => $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $aItem['id'], $iItemCount)),
            ));

            return array('code' => 0, 'message' => _t('_bx_payment_strp_msg_details_changed'));
        }

        return $this->_oModule->_oTemplate->parseHtmlByName('strp_details_change_recuring.html', array(
        	'object' => $this->_oModule->_oConfig->getJsObject('subscription'),
            'form' => $oForm->getCode(),
            'form_id' => $oForm->aFormAttrs['id'],
        ));
    }

    public function getBillingRecurring($iPendingId, $sCustomerId, $sSubscriptionId)
    {
        $aCard = $this->_retrieveCard($sCustomerId)->jsonSerialize();

        return $this->_oModule->_oTemplate->parseHtmlByName('strp_billing_recurring.html', array(
            'brand' => $aCard['brand'],
        	'origin' => $aCard['country'],
            'type' => $aCard['funding'],
            'number' => _t('_bx_payment_strp_txt_card_number_mask', $aCard['last4']),
            'expires' => _t('_bx_payment_strp_txt_card_expires_mask', $aCard['exp_month'], $aCard['exp_year']),
            'cvc' => _t(strcmp($aCard['cvc_check'], 'pass') === 0 ? '_bx_payment_strp_txt_card_cvc_passed' : '_bx_payment_txt_none'),
        ));
    }

    public function changeBillingRecurring($iPendingId, $sCustomerId, $sSubscriptionId)
    {
        $oForm = BxDolForm::getObjectInstance($this->_sFormCard, $this->_sFormDisplayCardAdd, $this->_oModule->_oTemplate);
        $oForm->aFormAttrs['id'] = $this->_oModule->_oConfig->getHtmlIds('subscription', 'form_subscription_change_billing');
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . $this->_oModule->_oConfig->getBaseUri() . 'subscription_change_billing/' . $iPendingId;

        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $aResultError = array('code' => 1, 'message' => _t('_bx_payment_strp_err_billing_changed'));

            $aMatch = array();
            if(!preg_match('/^([0-9]{2})\D([0-9]{4})$/i', $oForm->getCleanValue('card_expire'), $aMatch))
                return $aResultError;

            list($iMonth, $iYear) = array_slice($aMatch, 1);

        	$aToken = $this->_createToken(array(
                'number' => $oForm->getCleanValue('card_number'),
                'exp_month' => $iMonth,
                'exp_year' => $iYear,
                'cvc' => $oForm->getCleanValue('card_cvv')
            ));
            if(empty($aToken) || !is_array($aToken))
                return $aResultError;

            $aCard = $this->_createCard(BX_PAYMENT_TYPE_RECURRING, $sCustomerId, $aToken['id']);
            if(empty($aCard) || !is_array($aCard))
                return $aResultError;

            $oCustomer = $this->_retrieveCustomer(BX_PAYMENT_TYPE_RECURRING, $sCustomerId);
            if($oCustomer === false)
                return $aResultError;

            $oCustomer->default_source = $aCard['id'];
            $oCustomer->save();

            return array('code' => 0, 'message' => _t('_bx_payment_strp_msg_billing_changed'));
        }

        return $this->_oModule->_oTemplate->parseHtmlByName('strp_billing_change_recuring.html', array(
        	'object' => $this->_oModule->_oConfig->getJsObject('subscription'),
            'form' => $oForm->getCode(),
            'form_id' => $oForm->aFormAttrs['id'],
        ));
    }

    public function cancelRecurring($iPendingId, $sCustomerId, $sSubscriptionId)
    {
        $oSubscription = $this->_cancelSubscription($sCustomerId, $sSubscriptionId);
        if($oSubscription === false)
            return false;

        return true;
    }

    public function getCheckoutParamsSingle($aParams, &$oGrid)
    {
    	if(bx_get('token') !== false)
    		$aParams['token'] = bx_process_input(bx_get('token'));

    	return $aParams;
    }

	protected function _getPublicKey()
	{
		return $this->_iMode == STRP_MODE_LIVE ? $this->getOption('live_pub_key') : $this->getOption('test_pub_key');
	}

	protected function _getSecretKey()
	{
		return $this->_iMode == STRP_MODE_LIVE ? $this->getOption('live_sec_key') : $this->getOption('test_sec_key');
	}

    protected function _createToken($aCard)
    {
		try {
			$oToken = Stripe\Token::create(array('card' => $aCard));
		}
		catch (Stripe\Error\Base $oException) {
			return $this->_processException('Create Token Error: ', $oException);
		}

		return $oToken->jsonSerialize();
	}

	protected function _createCustomer($sType, $sToken, $aClient)
	{
	    $oCustomer = null;
	    $aCustomer = array(
			'card' => $sToken,
			'email' => !empty($aClient['email']) ? $aClient['email'] : ''
		);

	    bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_create_customer', 0, $aClient['id'], array(
	    	'type' => $sType,
			'customer_object' => &$oCustomer, 
			'customer_params' => &$aCustomer
		));

		try {
			$this->_oCustomer = !empty($oCustomer) ? $oCustomer : \Stripe\Customer::create($aCustomer);
		}
		catch (Exception $oException) {
			return $this->_processException('Create Customer Error: ', $oException);
		}

		return $this->_oCustomer->jsonSerialize();
	}

	protected function _retrieveCustomer($sType, $sId)
	{
	    $oCustomer = null;
	    bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_retrieve_customer', 0, false, array(
	    	'type' => $sType,
	    	'customer_id' => &$sId,
			'customer_object' => &$oCustomer
		));

		try {
		    if(empty($oCustomer))
			    $oCustomer = \Stripe\Customer::retrieve($sId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Customer Error: ', $oException);
		}

		return $oCustomer;
	}

	protected function _createCharge($sToken, $iPendingId, &$aClient, &$aCartInfo) {
		if(empty($this->_oCustomer))
			$this->_createCustomer(BX_PAYMENT_TYPE_SINGLE, $sToken, $aClient);

		if(empty($this->_oCustomer))
			return false;

		$fAmount = 100 * (float)$aCartInfo['items_price'];

		$oCharge = null;
		$aCharge = array(
			'customer' => $this->_oCustomer->id,
			'amount' => $fAmount,
			'currency' => $aCartInfo['vendor_currency_code'],
			'description' => $aCartInfo['items_title'],
			'metadata' => array(
				'vendor' => $aCartInfo['vendor_id'],
				'client' => $aClient['id'],
				'product' => $iPendingId,
				'verification' => $this->_getVerificationCodeCharge($aCartInfo['vendor_id'], $aClient['id'], $fAmount, $aCartInfo['vendor_currency_code'])
			)
		);

		bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_create_charge', $iPendingId, false, array(
			'charge_object' => &$oCharge, 
			'charge_params' => &$aCharge
		));

		try {
		    if(empty($oCharge))
			    $oCharge = \Stripe\Charge::create($aCharge);
		}
		catch (Exception $oException) {
			return $this->_processException('Create Charge Error: ', $oException);
		}

		$aResult = $oCharge->jsonSerialize();
		if(empty($aResult) || !is_array($aResult) || empty($aResult['paid']))
			return false;

		$aMetadata = $aResult['metadata'];
		if(empty($aMetadata['verification']) || $aMetadata['verification'] != $this->_getVerificationCodeCharge($aCartInfo['vendor_id'], $aClient['id'], $aResult['amount'], $aResult['currency']))
			return false;

		return array(
			'pending' => $iPendingId,
			'amount' =>(float)$aResult['amount'] / 100,
			'customer' => $this->_oCustomer->id,
			'order' => $aResult['id']
		);
	}

	protected function _retrieveCharge($sId) {
	    $oCharge = null;
	    bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_retrieve_charge', 0, false, array(
	    	'charge_id' => &$sId,
			'charge_object' => &$oCharge
		));

		try {
		    if(empty($oCharge))
			    $oCharge = \Stripe\Charge::retrieve($sId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Charge Error: ', $oException);
		}

		return $oCharge;
	}

	protected function _createSubscription($sToken, $iPendingId, &$aClient, &$aCartInfo)
	{
		if(empty($this->_oCustomer))
			$this->_createCustomer(BX_PAYMENT_TYPE_RECURRING, $sToken, $aClient);

		if(empty($this->_oCustomer))
			return false;

		$aItem = array_shift($aCartInfo['items']);
		if(empty($aItem) || !is_array($aItem))
			return false;

		$iTrial = $this->_oModule->_oConfig->getTrial(BX_PAYMENT_TYPE_RECURRING, $aItem);
		$bTrial = !empty($iTrial);

		$oSubscription = null;
		$aSubscription = array(
			'plan' => $aItem['name'],
			'metadata' => array(
				'vendor' => $aCartInfo['vendor_id'],
				'client' => $aClient['id'],
				'product' => $iPendingId,
				'verification' => $this->_getVerificationCodeSubscription($aCartInfo['vendor_id'], $aClient['id'], $aItem['name'], $aCartInfo['vendor_currency_code'])
			)
		);

		bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_create_subscription', $iPendingId, false, array(
		    'customer' => &$this->_oCustomer,
			'subscription_object' => &$oSubscription, 
			'subscription_params' => &$aSubscription
		));

		try {
             if(empty($oSubscription))
    			$oSubscription = $this->_oCustomer->subscriptions->create($aSubscription);
		}
		catch (Exception $oException) {
			return $this->_processException('Create Subscription Error: ', $oException);
		}

		$aResult = $oSubscription->jsonSerialize();
		if(empty($aResult) || !is_array($aResult) || (!$bTrial && $aResult['status'] != 'active') || ($bTrial && !in_array($aResult['status'], array('active', 'trialing'))))
			return false;

		$aMetadata = $aResult['metadata'];
		if(empty($aMetadata['verification']) || $aMetadata['verification'] != $this->_getVerificationCodeSubscription($aCartInfo['vendor_id'], $aClient['id'], $aResult['plan']['id'], $aResult['plan']['currency']))
			return false;

		return array(
			'pending' => $iPendingId,
			'amount' =>(float)$aCartInfo['items_price'],
			'customer' => $this->_oCustomer->id, 
			'order' => $aResult['id'],
			'trial' => $bTrial
		);
	}

	protected function _retrieveSubscription($sCustomerId, $sSubscriptionId)
	{
		try {
			$oCustomer = $this->_retrieveCustomer(BX_PAYMENT_TYPE_RECURRING, $sCustomerId);
			$oSubscription = $oCustomer->subscriptions->retrieve($sSubscriptionId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Subscription Error: ', $oException);
		}

		return $oSubscription;
	}
//TODO: Continue from here!
	protected function _cancelSubscription($sCustomerId, $sSubscriptionId)
	{
	    try {
	        $oSubscription = $this->_retrieveSubscription($sCustomerId, $sSubscriptionId);
	        $oSubscription = $oSubscription->cancel();
	    }
	    catch (Exception $oException) {
			return $this->_processException('Cancel Subscription Error: ', $oException);
		}

		bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_cancel_subscription', 0, false, array(
		    'subscription_id' => $sSubscriptionId,
			'subscription_object' => &$oSubscription
		));

		return $oSubscription;
	}

    protected function _listPlans()
	{
		try {
			$oPlans = \Stripe\Plan::all();
		}
		catch (Exception $oException) {
			return $this->_processException('List Plans Error: ', $oException);
		}

		return $oPlans;
	}

    protected function _createCard($sType, $sCustomerId, $sToken)
	{
		try {
			$oCard = $this->_retrieveCustomer($sType, $sCustomerId)->sources->create(array(
            	'source' => $sToken
            ));
		}
		catch (Stripe\Error\Base $oException) {
			return $this->_processException('Create Card Error: ', $oException);
		}

		return $oCard->jsonSerialize();
	}

    protected function _retrieveCard($sCustomerId, $sCardId = '') {
		try {
			$oCustomer = \Stripe\Customer::retrieve($sCustomerId);
			$oCard = $oCustomer->sources->retrieve(!empty($sCardId) ? $sCardId : $oCustomer->default_source);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Card Error: ', $oException);
		}

		return $oCard;
	}

	protected function _retrieveCoupon($sId) {
		try {
			$oCoupon = \Stripe\Coupon::retrieve($sId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Coupon Error: ', $oException);
		}

		return $oCoupon;
	}

	protected function _retrieveEvent($sId) {
		try {
			$oEvent = \Stripe\Event::retrieve($sId);
		}
		catch (Exception $oException) {
			return $this->_processException('Retrieve Event Error: ', $oException);
		}

		return $oEvent;
	}

	protected function _processEvent()
	{
    	$sInput = @file_get_contents("php://input");
		$aEvent = json_decode($sInput, true);
		if(empty($aEvent) || !is_array($aEvent)) 
			return 404;

		$sType = $aEvent['type'];
		if(!in_array($sType, array('invoice.payment_succeeded', 'charge.refunded', 'customer.subscription.deleted')))
			return 200;

		$this->log('Webhooks: ' . (!empty($sType) ? $sType : ''));
		$this->log($aEvent);

		$sMethod = '_processEvent' . bx_gen_method_name($sType, array('.', '_', '-'));
    	if(!method_exists($this, $sMethod))
    		return 200;

    	return $this->$sMethod($aEvent) ? 200 : 403;
    }

	protected function _processEventInvoicePaymentSucceeded(&$aEvent)
	{
		$mixedResult = $this->_getData($aEvent);
		if($mixedResult === false)
			return false;

		list($aPending, $oCharge) = $mixedResult;
		if(empty($aPending) || !is_array($aPending) || empty($oCharge))
			return false;

		$fChargeAmount = (float)$oCharge->amount / 100;
		$sChargeCurrency = strtoupper($oCharge->currency);
		if($this->_bCheckAmount && ((float)$aPending['amount'] != $fChargeAmount || strcasecmp($this->_oModule->_oConfig->getDefaultCurrencyCode(), $sChargeCurrency) !== 0))
			return false;

        if($aPending['type'] == BX_PAYMENT_TYPE_RECURRING)
            $this->_oModule->updateSubscription($aPending, array(
                'paid' => 1
            ));

		return $this->_oModule->registerPayment($aPending);
	}

	protected function _processEventChargeRefunded(&$aEvent)
	{
		$mixedResult = $this->_getData($aEvent);
		if($mixedResult === false)
			return false;

		list($aPending) = $mixedResult;
		if(empty($aPending) || !is_array($aPending))
			return false;

		return $this->_oModule->refundPayment($aPending);
	}

	protected function _processEventCustomerSubscriptionDeleted(&$aEvent)
	{
		$mixedResult = $this->_getData($aEvent);
		if($mixedResult === false)
			return false;

		list($aPending) = $mixedResult;
		if(empty($aPending) || !is_array($aPending))
			return true;

		return $this->_oModule->cancelSubscription($aPending);
	}

	protected function _processException($sMessage, &$oException)
	{
		$aError = $oException->getJsonBody();

		$sMessage = $aError['error']['message'];
		if(empty($sMessage))
			$sMessage = $oException->getMessage();

		$this->log($sMessage . $aError['error']['message']);
		$this->log($aError);

		return false;
	}

	protected function _getData(&$aEvent, $bRetrieve = true)
	{
		if($bRetrieve)
			$oEvent = $this->_retrieveEvent($aEvent['id']);
		else 
			$oEvent = \Stripe\Util\Util::convertToStripeObject($aEvent, array());

		if(empty($oEvent))
			return false;

		$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'order', 'order' => $oEvent->data->object->subscription));
		$oCharge = $this->_retrieveCharge($oEvent->data->object->charge);

		return array($aPending, $oCharge);
	}

    protected function _getDataChangeDetailsRecurring($iPendingId)
    {
        $aResult = array();

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        list($iVendorId, $iModuleId, $iItemId) = $this->_oModule->_oConfig->descriptorS2A($aPending['items']);
        if(empty($iModuleId) || empty($iVendorId) || (int)$iVendorId != (int)$aPending['seller_id'])
            return $aResult;

        $aItems = $this->_oModule->callGetCartItems((int)$iModuleId, array($iVendorId));
        if(empty($aItems) || !is_array($aItems))
            return $aResult;

        $oPlans = $this->_listPlans();
        if($oPlans === false)
            return $aResult;

        $aPlans = $oPlans->jsonSerialize();
        if(empty($aPlans) || !is_array($aPlans) || empty($aPlans['data']) || !is_array($aPlans['data']))
            return $aResult;

        $aPlans = $aPlans['data'];

        $aPlanNames = array();
        foreach($aPlans as $aPlan)
            $aPlanNames[] = $aPlan['id'];
        if(empty($aPlanNames) || !is_array($aPlanNames))
            return $aResult;

        foreach($aItems as $aItem) {
            $fPrice = $this->_oModule->_oConfig->getPrice(BX_PAYMENT_TYPE_RECURRING, $aItem);
            if($fPrice == 0 || (int)$aItem['id'] == (int)$iItemId || !in_array($aItem['name'], $aPlanNames))
                continue;

            $aResult[] = array('key' => $aItem['id'], 'value' => $aItem['title']);
        }

        return $aResult;
    }

	protected function _getVerificationCodeCharge($iVendorId, $iCustomerId, $fAmount, $sCurrency) {
		return md5(implode('#-#', array(
			(int)$iVendorId,
			(int)$iCustomerId,
			(float)$fAmount,
			strtoupper($sCurrency)
		)));
	}

	protected function _getVerificationCodeSubscription($iVendorId, $iCustomerId, $sSubscription, $sCurrency) {
		return md5(implode('#-#', array(
			(int)$iVendorId,
			(int)$iCustomerId,
			strtoupper($sSubscription),
			strtoupper($sCurrency)
		)));
	}

    protected function _getButton($sType, $iClientId, $iVendorId, $aParams = array())
    {
    	$sClientEmail = '';
    	if(!empty($iClientId)) {
    		$oClient = BxDolProfile::getInstance($iClientId);
    		if($oClient)
    			$sClientEmail = $oClient->getAccountObject()->getEmail();
    	}

    	$sPublicKey = '';
    	bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_get_button', 0, $iClientId, array(
			'type' => &$sType, 
			'public_key' => &$sPublicKey
		));

    	return $this->_oModule->_oTemplate->parseHtmlByName('strp_button_' . $sType . '.html', array(
    		'type' => $sType,
    		'caption' => _t($this->_sLangsPrefix . 'strp_txt_checkout_with_' . $sType, $this->_sCaption),
    		'js_object' => $this->_oModule->_oConfig->getJsObject($this->_sName),
    		'js_code' => $this->_oModule->_oTemplate->getJsCode($this->_sName, array_merge(array(
	    		'sProvider' => $this->_sName,
	    		'sPublicKey' => !empty($sPublicKey) ? $sPublicKey : $this->_getPublicKey(),
	    		'sVendorName' => '',
	    		'sVendorCurrency' => '',
	    		'sVendorIcon' => '',
	    		'sClientEmail' => $sClientEmail,
	    	), $aParams))
    	));
    }
}

/** @} */
