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

define('STRP_MODE_LIVE', 1);
define('STRP_MODE_TEST', 2);

class BxPaymentProviderStripeBasic extends BxBaseModPaymentProvider
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

        $this->_aSbsStatuses = array(
            'incomplete' => BX_PAYMENT_SBS_STATUS_UNPAID, 
            'incomplete_expired' => BX_PAYMENT_SBS_STATUS_UNPAID, 
            'trialing' => BX_PAYMENT_SBS_STATUS_TRIAL, 
            'active' => BX_PAYMENT_SBS_STATUS_ACTIVE, 
            'past_due' => BX_PAYMENT_SBS_STATUS_UNPAID,
            'unpaid' => BX_PAYMENT_SBS_STATUS_UNPAID,
            'canceled' => BX_PAYMENT_SBS_STATUS_CANCELED,
        );

        $this->_sFormDetails = 'bx_payment_form_strp_details';
        $this->_sFormDisplayDetailsEdit = 'bx_payment_form_strp_details_edit';

        $this->_sFormCard = 'bx_payment_form_strp_card';
        $this->_sFormDisplayCardAdd = 'bx_payment_form_strp_card_add';

        $this->_bProrate = false;

        $this->_aIncludeJs = array(
            'main.js',
        );

        $this->_aIncludeCss = array(
            'stripe.css'
        );

        $this->_oCustomer = null;

        \Stripe\Stripe::setApiKey($this->_getSecretKey());
    }

    public function initOptions($aOptions)
    {
    	parent::initOptions($aOptions);

    	$this->_iMode = (int)$this->getOption('mode');
    	$this->_bCheckAmount = $this->getOption('check_amount') == 'on';
    	$this->_bUseSsl = $this->getOption('ssl') == 'on';
    }

    public function addJsCss()
    {
    	if(!$this->isActive())
    		return;

        $this->_oModule->_oTemplate->addJs($this->_aIncludeJs);
        $this->_oModule->_oTemplate->addCss($this->_aIncludeCss);
    }

    public function getJsObject($aParams = array())
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject($this->_sName);
        if(isset($aParams['iModuleId'], $aParams['iSellerId'], $aParams['iItemId']))
            $sJsObject .= '_' . md5($aParams['iModuleId'] . '-' . $aParams['iSellerId'] . '-' . $aParams['iItemId']);
        
        return $sJsObject;
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

    public function getButtonSingleJs($iClientId, $iVendorId, $aParams = array())
    {
        $aItems = array();
        $aCartInfo = $this->_oModule->getObjectCart()->getInfo(BX_PAYMENT_TYPE_SINGLE, $iClientId, (int)$iVendorId, $aItems);
        if(empty($aCartInfo) || !is_array($aCartInfo))
            return '';

    	return $this->_getButtonJs(BX_PAYMENT_TYPE_SINGLE, $iClientId, $iVendorId, array_merge($aParams, array(
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
    
    public function getButtonRecurringJs($iClientId, $iVendorId, $aParams = array())
    {
    	$aVendor = $this->_oModule->getVendorInfo((int)$iVendorId);

        return $this->_getButtonJs(BX_PAYMENT_TYPE_RECURRING, $iClientId, $iVendorId, array_merge($aParams, array(
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

        $sName = '';
        if(!empty($aSubscription['plan']['name']))
            $sName = $aSubscription['plan']['name'];
        else if(!empty($aSubscription['plan']['product'])){
            $oProduct = $this->_retrieveProduct($aSubscription['plan']['product']);
            if($oProduct !== false) 
                $sName = $oProduct->name;
        }

        $sNone = _t('_bx_payment_txt_none');
        return $this->_oModule->_oTemplate->parseHtmlByName('strp_details_recurring.html', array(
            'plan' => $sName,
            'cost' => _t('_bx_payment_strp_txt_cost_mask', (int)$aSubscription['plan']['amount'] / 100, $aSubscription['plan']['currency'], $aSubscription['plan']['interval']),
            'status' => $aSubscription['status'],
            'created' => bx_time_js($aSubscription['created'], BX_FORMAT_DATE_TIME, true),
            'started' => !empty($aSubscription['start']) ? bx_time_js($aSubscription['start'], BX_FORMAT_DATE_TIME, true) : $sNone,
            'trial_start' => !empty($aSubscription['trial_start']) ? bx_time_js($aSubscription['trial_start'], BX_FORMAT_DATE_TIME, true) : $sNone,
            'trial_end' => !empty($aSubscription['trial_end']) ? bx_time_js($aSubscription['trial_end'], BX_FORMAT_DATE_TIME, true) : $sNone,
            'cperiod_start' => !empty($aSubscription['current_period_start']) ? bx_time_js($aSubscription['current_period_start'], BX_FORMAT_DATE_TIME, true) : $sNone,
            'cperiod_end' => !empty($aSubscription['current_period_end']) ? bx_time_js($aSubscription['current_period_end'], BX_FORMAT_DATE_TIME, true) : $sNone,
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

            $sItems = $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $aItem['id'], $iItemCount));
            if(!$this->_oModule->_oDb->updateOrderPending($iPendingId, array('items' => $sItems)))
                return $aResultError;

            $this->_oModule->callReregisterSubscriptionItem($iModuleId, array($aPending['client_id'], $aPending['seller_id'], $iItemId, $aItem['id'], $aPending['order']));

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
        $aCard = ['brand' => '', 'country' => '', 'funding' => '', 'last4' => '', 'exp_month' => '', 'exp_year' => '', 'cvc_check' => ''];

        $oCard = $this->_retrieveCard($sCustomerId);
        if($oCard === false && ($oSubscription = $this->_retrieveSubscription($sCustomerId, $sSubscriptionId)) !== false) {
            $sPaymentMethodId = $oSubscription->default_payment_method;
            if(!empty($sPaymentMethodId) && ($oPaymentMethod = $this->_retrievePaymentMethod($sPaymentMethodId)) !== false)
                $oCard = $oPaymentMethod->card;
        }

        if(!empty($oCard))
            $aCard = $oCard->jsonSerialize();

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

    public function getSubscription($iPendingId, $sCustomerId, $sSubscriptionId)
    {
        $oSubscription = $this->_retrieveSubscription($sCustomerId, $sSubscriptionId);
        if($oSubscription === false)
            return array();

        $aSubscription = $oSubscription->jsonSerialize();
        if(empty($aSubscription) || !is_array($aSubscription))
            return array();

        $sStatus = isset($this->_aSbsStatuses[$aSubscription['status']]) ? $this->_aSbsStatuses[$aSubscription['status']] : BX_PAYMENT_SBS_STATUS_UNKNOWN;

        return array(
            'status' => $sStatus,
            'created' => $aSubscription['created'],
            'started' => !empty($aSubscription['start']) ? $aSubscription['start'] : 0,
            'trial_start' => !empty($aSubscription['trial_start']) ? $aSubscription['trial_start'] : 0,
            'trial_end' => !empty($aSubscription['trial_end']) ? $aSubscription['trial_end'] : 0,
            'cperiod_start' => !empty($aSubscription['current_period_start']) ? $aSubscription['current_period_start'] : 0,
            'cperiod_end' => !empty($aSubscription['current_period_end']) ? $aSubscription['current_period_end'] : 0,
        );
    }

    protected function _getPublicKey()
    {
        return $this->_iMode == STRP_MODE_LIVE ? $this->getOption('live_pub_key') : $this->getOption('test_pub_key');
    }

    protected function _getSecretKey()
    {
        return $this->_iMode == STRP_MODE_LIVE ? $this->getOption('live_sec_key') : $this->getOption('test_sec_key');
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

    protected function _retrieveCharge($sId)
    {
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

    protected function _retrievePaymentMethod($sPaymentMethodId)
    {
        return false;
    }

    protected function _retrieveProduct($sId)
    {
        $oProduct = null;
        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_retrieve_product', 0, false, array(
            'product_id' => &$sId,
            'product_object' => &$oProduct
        ));

        if(!empty($oProduct))
            return $oProduct;

        try {
            $oProduct = \Stripe\Product::retrieve($sId);
        }
        catch (Exception $oException) {
            return $this->_processException('Retrieve Product Error: ', $oException);
        }

        return $oProduct;
    }

    protected function _listPlans($iLimit = 100)
    {
        if($iLimit <= 0)
            $iLimit = 1;
        if($iLimit > 100)
            $iLimit = 100;

        try {
            $oPlans = \Stripe\Plan::all(array('limit' => $iLimit));
        }
        catch (Exception $oException) {
            return $this->_processException('List Plans Error: ', $oException);
        }

        return $oPlans;
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
            $this->_oModule->getObjectSubscriptions()->prolong($aPending);

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

        return $this->_oModule->getObjectSubscriptions()->cancelLocal($aPending);
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

    protected function _getButton($sType, $iClientId, $iVendorId, $aParams = array())
    {
        list($sJsCode, $sJsMethod) = $this->_getButtonJs($sType, $iClientId, $iVendorId, $aParams);

    	return $this->_oModule->_oTemplate->parseHtmlByName('strp_button_' . $sType . '.html', array(
            'type' => $sType,
            'caption' => _t($this->_sLangsPrefix . 'strp_txt_checkout_with_' . $sType, $this->_sCaption),  
            'onclick' => $sJsMethod,
            'js_object' => $this->_oModule->_oConfig->getJsObject($this->_sName),
            'js_code' => $sJsCode,
    	));
    }
}

/** @} */
