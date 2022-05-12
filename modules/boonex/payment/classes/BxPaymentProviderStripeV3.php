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

require_once('BxPaymentProviderStripeBasic.php');

class BxPaymentProviderStripeV3 extends BxPaymentProviderStripeBasic implements iBxBaseModPaymentProvider
{
    protected $_oStripe;

    function __construct($aConfig)
    {
        parent::__construct($aConfig);

        $this->_aIncludeJs = array(
            'stripe_v3.js'
        );

        $this->_oStripe = null;
    }

    public function actionGetSessionRecurring()
    {
        $aClient = $this->_oModule->getProfileInfo();

        $aParams = array(
            'seller_id' => (int)bx_get('seller_id'),
            'seller_provider' => bx_process_input(bx_get('seller_provider')),
            'module_id' => (int)bx_get('module_id'),
            'item_id' => (int)bx_get('item_id'),
            'item_count' => (int)bx_get('item_count'),
            'item_addons' => bx_get('item_addons'),
            'redirect' => bx_process_input(bx_get('redirect')),
            'custom' => bx_process_input(bx_get('custom')),
        );

        $aItems = array($this->_oModule->_oConfig->descriptorA2S(array(
            'seller_id' => $aParams['seller_id'],
            'module_id' => $aParams['module_id'],
            'item_id' => $aParams['item_id'],
            'item_count' => $aParams['item_count'],
        )));

        $oCart = $this->_oModule->getObjectCart();
        $aCartInfo = $oCart->getInfo(BX_PAYMENT_TYPE_RECURRING, $aClient['id'], $aParams['seller_id'], $aItems);
        if(empty($aCartInfo) || !is_array($aCartInfo))
            return echoJson(array('msg' => _t('_bx_payment_err_nothing_selected')));

        $aItem = reset($aCartInfo['items']);

        $aSessionParams = array(
            'cancel_url' => $aItem['url'],
            'success_url' => bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_SUBSCRIBE_JSON'), array_merge($aParams, array(
                'session_id' => '{CHECKOUT_SESSION_ID}'
            )))
        );

        $sSessionId = $this->_createSession(BX_PAYMENT_TYPE_RECURRING, $aSessionParams, $aClient, $aCartInfo);
        if($sSessionId === false)
            return echoJson(array('msg' => _t('_bx_payment_err_cannot_perform')));

        $sJsObject = $this->getJsObject(array(
            'iModuleId' => $aParams['module_id'],
            'iSellerId' => $aParams['seller_id'],
            'iItemId' => $aParams['item_id']
        ));

        return echoJson(array(
            'code' => 0, 
            'session_id' => $sSessionId,
            'eval' => $sJsObject . '.onSubscribe(oData);'
        ));
    }

    public function initializeCheckout($iPendingId, $aCartInfo, $sRedirect = '')
    {
        $sSessionId = bx_process_input(bx_get('session_id'));

    	if(empty($aCartInfo['items']) || !is_array($aCartInfo['items']))
            return $this->_sLangsPrefix . 'err_empty_items';

        $aClient = $this->_oModule->getProfileInfo();
        $aVendor = $this->_oModule->getProfileInfo($aCartInfo['vendor_id']);

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return $this->_sLangsPrefix . 'err_already_processed';

        switch($aPending['type']) {
            case BX_PAYMENT_TYPE_SINGLE:
                $mixedResult = $this->_getSession(BX_PAYMENT_TYPE_SINGLE, $sSessionId);
                if($mixedResult === false || $mixedResult['status'] != 'paid')
                    return $this->_sLangsPrefix . 'err_cannot_perform';

                header("Location: " . $this->getReturnDataUrl($aVendor['id'], array(
                    'order_id' => $mixedResult['order_id'],
                    'customer_id' => $mixedResult['customer_id'], 
                    'pending_id' => $aPending['id'],
                    'redirect' => $sRedirect
                )));
                exit;

            case BX_PAYMENT_TYPE_RECURRING:
                $mixedResult = $this->_getSession(BX_PAYMENT_TYPE_RECURRING, $sSessionId);
                if($mixedResult === false)
                    return $this->_sLangsPrefix . 'err_cannot_perform';

                header("Location: " . $this->getReturnDataUrl($aVendor['id'], array(
                    'order_id' => $mixedResult['order_id'],
                    'customer_id' => $mixedResult['customer_id'],
                    'pending_id' => $aPending['id'],
                    'redirect' => $sRedirect
                )));
                exit;
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
                $oPaymentIntent = $this->_retrievePaymentIntent($sOrderId);
                if($oCustomer === false || $oPaymentIntent === false)
                    return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

                $aCustomer = $oCustomer->jsonSerialize();
                $aPaymentIntent = $oPaymentIntent->jsonSerialize();
                if(empty($aCustomer) || !is_array($aCustomer) || empty($aPaymentIntent) || !is_array($aPaymentIntent))
                    return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

                $aResult = array_merge($aResult, array(
                    'message' => $this->_sLangsPrefix . 'strp_msg_charged',
                    'client_email' => $aCustomer['email'],
                    'paid' => $aPaymentIntent['status'] == 'succeeded'
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
                    'paid' => $this->isSubscriptionStatus(BX_PAYMENT_SBS_STATUS_ACTIVE, $aSubscription),
                    'trial' => $this->isSubscriptionStatus(BX_PAYMENT_SBS_STATUS_TRIAL, $aSubscription)
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

    public function getButtonSingle($iClientId, $iVendorId, $aParams = array())
    {
        if(!isset($aParams['sAction']))
            return '';

        return $aParams['sAction'] . parent::getButtonSingle($iClientId, $iVendorId, $aParams);
    }

    public function overwriteCheckoutParamsSingle($aParams, &$oGrid)
    {
        $aClient = $this->_oModule->getProfileInfo();

        $oCart = $this->_oModule->getObjectCart();
        $aCartInfo = $oCart->getInfo(BX_PAYMENT_TYPE_SINGLE, $aClient['id'], $aParams['seller_id'], $aParams['items']);
        if(empty($aCartInfo) || !is_array($aCartInfo))
            return array('msg' => _t('_bx_payment_err_nothing_selected'));

        $aSessionParams = array(
            'cancel_url' => $oCart->serviceGetCartUrl($aParams['seller_id']),
            'success_url' => bx_append_url_params($this->_oModule->_oConfig->getUrl('URL_CART_CHECKOUT'), array_merge($aParams, array(
                'session_id' => '{CHECKOUT_SESSION_ID}'
            )))
        );

        $sSessionId = $this->_createSession(BX_PAYMENT_TYPE_SINGLE, $aSessionParams, $aClient, $aCartInfo);
        if($sSessionId === false)
            return array('msg' => _t('_bx_payment_err_cannot_perform'));

        return array(
            'eval' => $this->_oModule->_oConfig->getJsObject($this->_sName) . '.onCartCheckout(oData);', 
            'session_id' => $sSessionId
        );
    }

    public function createTax($sName, $fPercentage, $bInclusive = false)
    {
        $oTax = null;
        $aTax = [
            'display_name' => $sName,
            'percentage' => $fPercentage,
            'inclusive' => $bInclusive,
        ];

        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_create_tax', 0, false, array(
            'tax_object' => &$oTax, 
            'tax_params' => &$aTax
        ));

        try {
            if(empty($oTax))
                $oTax = $this->_getStripe()->taxRates->create($aTax);
        }
        catch (Exception $oException) {
            return $this->_processException('Create Tax Error: ', $oException);
        }

        $aResult = $oTax->jsonSerialize();
        if(empty($aResult) || !is_array($aResult))
            return false;

        return $aResult['id'];
    }

    public function retrieveTax($sId)
    {
        $oTax = null;
        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_retrieve_tax', 0, false, array(
            'tax_id' => &$sId,
            'tax_object' => &$oTax
        ));

        try {
            if(empty($oTax))
                $oTax = $this->_getStripe()->taxRates->retrieve($sId);
        }
        catch (Exception $oException) {
            return $this->_processException('Retrieve Tax Error: ', $oException);
        }

        return $oTax;
    }

    public function getVerificationCodeSession($iVendorId, $iCustomerId, $fAmount, $sCurrency)
    {
        $sCode = $this->_getVerificationCodeSession($iVendorId, $iCustomerId, $fAmount, $sCurrency);

        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_get_code_session', 0, false, array(
            'provider' => $this,
            'vendor_id' => $iVendorId, 
            'customer_id' => $iCustomerId,
            'amount' => $fAmount,
            'currency' => $sCurrency,
            'override_result' => &$sCode
        ));

        return $sCode;
    }

    public function checkVerificationCodeSession($iVendorId, $iCustomerId, $aResult)
    {
        $bCheckResult = $this->_checkVerificationCodeSession($iVendorId, $iCustomerId, $aResult);

        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_verify_session', 0, false, array(
            'provider' => $this,
            'vendor_id' => $iVendorId, 
            'customer_id' => $iCustomerId,
            'result' => $aResult,
            'override_result' => &$bCheckResult
        ));

        return $bCheckResult;
    }

    protected function _getStripe()
    {
        if(empty($this->_oStripe))
            $this->_oStripe = new \Stripe\StripeClient($this->_getSecretKey());

        return $this->_oStripe;
    }
            
    /*
     * Related Docs: https://stripe.com/docs/api/customers/retrieve
     */
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
                $oCustomer = $this->_getStripe()->customers->retrieve($sId);
        }
        catch (Exception $oException) {
            return $this->_processException('Retrieve Customer Error: ', $oException);
        }

        return $oCustomer;
    }

    /*
     * Related Docs: https://stripe.com/docs/api/checkout/sessions/create
     */
    protected function _createSession($sType, $aParams, &$aClient, &$aCartInfo)
    {
        $iAmountPrecision = 2;
        $fAmount = 100 * round((float)$aCartInfo['items_price'], $iAmountPrecision);

        $sMode = '';
        $aLineItems = array();
        $aMetaItems = array();

        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                $sMode = 'payment';

                foreach($aCartInfo['items'] as $aItem) {
                    $aProductData = array(
                        'name' => $aItem['title']
                    );
                    if(!empty($aItem['description']))
                        $aProductData['description'] = strmaxtextlen(strip_tags($aItem['description']), 60, '...');

                    $aLineItems[] = array(
                        'price_data' => array(
                            'currency' => $aCartInfo['vendor_currency_code'],
                            'product_data' => $aProductData,
                            'unit_amount' => 100 * round($this->_oModule->_oConfig->getPrice($sType, $aItem), $iAmountPrecision),
                        ),
                        'quantity' => $aItem['quantity'],
                    );

                    $aMetaItems[] = $this->_oModule->_oConfig->descriptorA2S([$aItem['module_id'], $aItem['id']]);
                }
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $sMode = 'subscription';

                foreach($aCartInfo['items'] as $aItem) {
                    $aLineItems[] = array(
                        'price' => $aItem['name'],
                        'quantity' => $aItem['quantity'],
                    );

                    $aMetaItems[] = $this->_oModule->_oConfig->descriptorA2S([$aItem['module_id'], $aItem['id']]);
                }
                break;
        }

        $oSession = null;
        $aSession = [
            'payment_method_types' => ['card'],
            'customer_email' => !empty($aClient['email']) ? $aClient['email'] : '',
            'line_items' => $aLineItems,
            'mode' => $sMode,
            'success_url' => $aParams['success_url'],
            'cancel_url' => $aParams['cancel_url'],
            'metadata' => [
                'vendor' => $aCartInfo['vendor_id'],
                'client' => $aClient['id'],
                'type' => $sType, 
                'items' => $this->_oModule->_oConfig->descriptorsA2S($aMetaItems),
                'verification' => $this->getVerificationCodeSession($aCartInfo['vendor_id'], $aClient['id'], $fAmount, $aCartInfo['vendor_currency_code'])
            ]
        ];

        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_create_session', 0, false, array(
            'session_object' => &$oSession, 
            'session_params' => &$aSession
        ));

        try {
            if(empty($oSession))
                $oSession = $this->_getStripe()->checkout->sessions->create($aSession);
        }
        catch (Exception $oException) {
            return $this->_processException('Create Session Error: ', $oException);
        }

        $aResult = $oSession->jsonSerialize();
        if(empty($aResult) || !is_array($aResult))
            return false;

        if(!$this->checkVerificationCodeSession($aCartInfo['vendor_id'], $aClient['id'], $aResult))
            return false;

        return $aResult['id'];
    }

    protected function _getSession($sType, $sId)
    {
        $oSession = $this->_retrieveSession($sId);
        if($oSession === false)
            return array();

        $aSession = $oSession->jsonSerialize();
        if(empty($aSession) || !is_array($aSession))
            return array();

        $aResult = array(
            'id' => $aSession['id'],
            'order_id' => $aSession['payment_intent'],
            'customer_id' => $aSession['customer'],
            'status' => $aSession['payment_status']
        );

        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                $aResult['order_id'] = $aSession['payment_intent'];
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $aResult['order_id'] = $aSession['subscription'];
                break;
        }

        return $aResult;
    }

    /*
     * Related Docs: https://stripe.com/docs/api/checkout/sessions/retrieve
     */
    protected function _retrieveSession($sId) {
        $oSession = null;
        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_retrieve_session', 0, false, array(
            'session_id' => &$sId,
            'session_object' => &$oSession
        ));

        try {
            if(empty($oSession))
                $oSession = $this->_getStripe()->checkout->sessions->retrieve($sId);
        }
        catch (Exception $oException) {
            return $this->_processException('Retrieve Session Error: ', $oException);
        }

        return $oSession;
    }
    
    /*
     * Related Docs: https://stripe.com/docs/api/payment_intents/retrieve
     */
    protected function _retrievePaymentIntent($sId) {
        $oPaymentIntent = null;
        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_retrieve_payment_intent', 0, false, array(
            'payment_intent_id' => &$sId,
            'payment_intent_object' => &$oPaymentIntent
        ));

        try {
            if(empty($oPaymentIntent))
                $oPaymentIntent = $this->_getStripe()->paymentIntents->retrieve($sId);
        }
        catch (Exception $oException) {
            return $this->_processException('Retrieve Payment Intent Error: ', $oException);
        }

        return $oPaymentIntent;
    }
    
    /*
     * Related Docs: https://stripe.com/docs/api/subscriptions/retrieve
     */
    protected function _retrieveSubscription($sCustomerId, $sSubscriptionId)
    {
        $oSubscription = null;
        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_retrieve_subscription', 0, false, array(
            'subscription_id' => &$sId,
            'subscription_object' => &$oSubscription
        ));

        try {
            if(empty($oSubscription))
                $oSubscription = $this->_getStripe()->subscriptions->retrieve($sSubscriptionId);
        }
        catch (Exception $oException) {
            return $this->_processException('Retrieve Subscription Error: ', $oException);
        }

        return $oSubscription;
    }

    protected function _getButton($sType, $iClientId, $iVendorId, $aParams = array())
    {
        list($sJsCode, $sJsMethod) = $this->_getButtonJs($sType, $iClientId, $iVendorId, $aParams);

    	if($sType == BX_PAYMENT_TYPE_SINGLE)
            return $sJsCode;

        return parent::_getButton($sType, $iClientId, $iVendorId);
    }

    protected function _getButtonJs($sType, $iClientId, $iVendorId, $aParams = array())
    {
        $sClientEmail = '';
    	if(!empty($iClientId) && ($oClient = BxDolProfile::getInstance($iClientId)) !== false)
            $sClientEmail = $oClient->getAccountObject()->getEmail();

        $sPublicKey = '';
    	bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_get_button', 0, $iClientId, array(
            'type' => &$sType, 
            'public_key' => &$sPublicKey
        ));

        $sJsMethod = '';
        $sJsObject = $this->getJsObject($aParams);
        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                $sJsMethod = 'void(0)';
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $sJsMethod = $sJsObject . '.subscribe(this)';
                break;
        }

        return array($this->_oModule->_oTemplate->getJsCode($this->_sName, array_merge(array(
            'js_object' => $sJsObject,
            'sProvider' => $this->_sName,
            'sPublicKey' => !empty($sPublicKey) ? $sPublicKey : $this->_getPublicKey(),
            'sVendorName' => '',
            'sVendorCurrency' => '',
            'sVendorIcon' => '',
            'sClientEmail' => $sClientEmail,
        ), $aParams)), $sJsMethod);
    }

    protected function _getVerificationCodeSession($iVendorId, $iCustomerId, $fAmount, $sCurrency)
    {
        return md5(implode('#-#', array(
            (int)$iVendorId,
            (int)$iCustomerId,
            (float)$fAmount,
            strtoupper($sCurrency)
        )));
    }

    protected function _checkVerificationCodeSession($iVendorId, $iCustomerId, $aResult)
    {
        return !empty($aResult['metadata']['verification']) && $aResult['metadata']['verification'] == $this->getVerificationCodeSession($iVendorId, $iCustomerId, $aResult['amount_total'], $aResult['currency']);
    }

    protected function _processException($sMessage, &$oException)
    {
        if(method_exists($oException, 'getError')) {
            $sError = $oException->getError()->message;
            $aError = $oException->getError()->toArray();
        }
        else { 
            $sError = $oException->getMessage();
            $aError = array();
        }

        $this->log($sMessage . $sError);
        if(!empty($aError))
            $this->log($aError);

        return false;
    }
}

/** @} */
