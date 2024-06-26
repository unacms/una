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

require_once('BxPaymentProviderChargebee.php');

use ChargeBee\ChargeBee\Environment;
use ChargeBee\ChargeBee\Models\PortalSession;

class BxPaymentProviderChargebeeV3 extends BxPaymentProviderChargebee
{
    function __construct($aConfig)
    {
        $this->MODULE = 'bx_payment';

        parent::__construct($aConfig);

        $this->_aIncludeJs = array(
            'https://js.chargebee.com/v2/chargebee.js',
            'main.js',
            'chargebee_v3.js'
        );

        $this->_aIncludeCss = array(
            'chargebee_v3.css'
        );
    }

    public function actionGetHostedPageSingle($iClientId, $iVendorId)
    {
        $this->initOptionsByVendor($iVendorId);

        $aCartInfo = $this->_oModule->getObjectCart()->getInfo(BX_PAYMENT_TYPE_SINGLE, $iClientId, $iVendorId);

        $aItem = [
            'amount' => 100 * $aCartInfo['items_price'], 
            'description' => _t($this->_sLangsPrefix . 'txt_payment_to', $aCartInfo['vendor_name'])
        ];

        $mixedItemAddons = bx_process_input(bx_get('addons'));
        if(!empty($mixedItemAddons)) {
            $aItemAddons = is_array($mixedItemAddons) ? $mixedItemAddons : $this->_oModule->_oConfig->s2a($mixedItemAddons);

            foreach($aItemAddons as $sItemAddon)
                if(!isset($aItem['addons'][$sItemAddon]))
                    $aItem['addons'][$sItemAddon] = array(
                        'id' => $sItemAddon,
                        'quantity' => 1
                    );
                else 
                    $aItem['addons'][$sItemAddon]['quantity'] += 1;

            $aItem['addons'] = array_values($aItem['addons']);
        }

        $aClient = $this->_oModule->getProfileInfo($iClientId);

        $oPage = $this->createHostedPageSingle($aItem, $aClient);
        if($oPage === false)
            return echoJson(array());

        header('Content-type: text/html; charset=utf-8');
        echo $oPage->toJson();
    }

    public function actionGetHostedPageRecurring($iClientId, $iVendorId, $sItemName)
    {
        $this->initOptionsByVendor($iVendorId);

        $aItem = array('name' => $sItemName);

        $mixedItemAddons = bx_process_input(bx_get('addons'));
        if(!empty($mixedItemAddons)) {
            $aItemAddons = is_array($mixedItemAddons) ? $mixedItemAddons : $this->_oModule->_oConfig->s2a($mixedItemAddons);

            foreach($aItemAddons as $sItemAddon)
                if(!isset($aItem['addons'][$sItemAddon]))
                    $aItem['addons'][$sItemAddon] = array(
                        'id' => $sItemAddon,
                        'quantity' => 1
                    );
                else 
                    $aItem['addons'][$sItemAddon]['quantity'] += 1;

            $aItem['addons'] = array_values($aItem['addons']);
        }
        $aClient = $this->_oModule->getProfileInfo($iClientId);

        $oPage = $this->createHostedPageRecurring($aItem, $aClient);
        if($oPage === false)
            return echoJson(array());

        header('Content-type: text/html; charset=utf-8');
        echo $oPage->toJson();
    }

    public function actionGetPortal($iPendingId)
    {
    	if(!isLogged())
            return echoJson(array());

    	$aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
    	if(empty($aPending) || !is_array($aPending))
            return echoJson(array());

    	$this->initOptionsByVendor((int)$aPending['seller_id']);

    	$aSubscription = $this->_oModule->_oDb->getSubscription(array('type' => 'pending_id', 'pending_id' => $iPendingId));
    	if(empty($aSubscription) || !is_array($aSubscription))
            return echoJson(array());

    	$oPortal = $this->getPortal($aSubscription['customer_id'], $aSubscription['subscription_id']);
    	if($oPortal === false)
            return echoJson(array());

    	header('Content-type: text/html; charset=utf-8');
    	echo $oPortal->toJson();
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

    public function initializeCheckout($iPendingId, $aCartInfo, $sRedirect = '')
    {
        $sPageId = bx_process_input(bx_get('page_id'));
        if(empty($sPageId) || empty($iPendingId))
            return $this->_sLangsPrefix . 'err_wrong_data';

    	$aItem = array_shift($aCartInfo['items']);
    	if(empty($aItem) || !is_array($aItem))
            return $this->_sLangsPrefix . 'err_empty_items';

        $aClient = $this->_oModule->getProfileInfo();
        $aVendor = $this->_oModule->getProfileInfo($aCartInfo['vendor_id']);

        $oPage = $this->retreiveHostedPage($sPageId);
        if($oPage === false)
            return $this->_sLangsPrefix . 'err_cannot_perform';

        $aPending = $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => $iPendingId));
        if(!empty($aPending['order']) || !empty($aPending['error_code']) || !empty($aPending['error_msg']) || (int)$aPending['processed'] != 0)
            return $this->_sLangsPrefix . 'err_already_processed';

        $aResult = [];
        switch($aPending['type']) {
            case BX_PAYMENT_TYPE_SINGLE:
                $oInvoice = $oPage->content()->invoice();

                $aResult = [
                    'code' => 0,
                    'eval' => $this->_oModule->_oConfig->getJsObject('cart') . '.onCartCheckout(oData);',
                    'link' => $this->getReturnDataUrl($aVendor['id'], [
                        'order_id' => $oInvoice->id,
                        'customer_id' => $oInvoice->customerId,
                        'pending_id' => $aPending['id'],
                        'redirect' => $sRedirect
                    ])
                ];
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $aResult = [
                    'code' => 0,
                    'eval' => $this->_oModule->_oConfig->getJsObject('cart') . '.onSubscribeSubmit(oData);',
                    'redirect' => $this->getReturnDataUrl($aVendor['id'], [
                        'order_id' => $oPage->content()->subscription()->id,
                        'customer_id' => $oPage->content()->customer()->id,
                        'pending_id' => $aPending['id'],
                        'redirect' => $sRedirect
                    ])
                ];
                break;
        }

        return $aResult;
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

        $oCustomer = $this->retrieveCustomer($sCustomerId);
        if($oCustomer === false)
            return array('code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform');

        $sOrder = '';
        $aResult = [
            'code' => BX_PAYMENT_RESULT_SUCCESS,
            'message' => '',
            'pending_id' => $iPendingId,
            'client_name' => _t($this->_sLangsPrefix . 'txt_buyer_name_mask', $oCustomer->firstName, $oCustomer->lastName),
            'client_email' => $oCustomer->email,
            'paid' => false,
            'redirect' => $sRedirect
        ];

        switch($aPending['type']) {
            case BX_PAYMENT_TYPE_SINGLE:
                $oInvoice = $this->retrieveInvoice($sOrderId);
                if($oInvoice === false || empty($oInvoice->linkedPayments))
                    return ['code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform'];

                $sOrder = $oInvoice->linkedPayments[0]->txnId;
                $aResult = array_merge($aResult, [
                    'message' => $this->_sLangsPrefix . 'cbee_msg_charged',
                    'paid' => $oInvoice->status == 'paid',
                ]);
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $oSubscription = $this->retrieveSubscription($sOrderId);
                if($oSubscription === false)
                    return ['code' => 4, 'message' => $this->_sLangsPrefix . 'err_cannot_perform'];

                $sOrder = $oSubscription->id;
                $aResult = array_merge($aResult, [
                    'message' => $this->_sLangsPrefix . 'cbee_msg_subscribed',
                    'customer_id' => $oCustomer->id,
                    'subscription_id' => $oSubscription->id,
                    'trial' => $oSubscription->status == 'in_trial',
                ]);

                break;
        }

        //--- Update pending transaction ---//
        $this->_oModule->_oDb->updateOrderPending($iPendingId, [
            'order' => $sOrder,
            'error_code' => $aResult['code'],
            'error_msg' => _t($aResult['message'])
        ]);

        return $aResult;
    }

    public function getPortal($sCustomerId, $sSubscriptionId)
    {
    	$oPortal = false;

    	try {
            Environment::configure($this->_getSite(), $this->_getApiKey());
            $oResult = PortalSession::create(array(
                'customer' => array(
                    'id' => $sCustomerId
                )
            ));

            $oPortal = $oResult->portalSession();
    	}
    	catch (Exception $oException) {
            $iError = $oException->getCode();
            $sError = $oException->getMessage();

            $this->log('Get Portal Error: ' . $sError . '(' . $iError . ')');

            return false;
    	}

    	return $oPortal;
    }

    public function getJsCode($aParams = array())
    {
    	$sSite = '';
        /**
         * @hooks
         * @hookdef hook-bx_payment-chargebee_v3_get_js_code 'bx_payment', 'chargebee_v3_get_js_code' - hook to override JavaScript code
         * - $unit_name - equals `bx_payment`
         * - $action - equals `chargebee_v3_get_js_code`
         * - $object_id - not used
         * - $sender_id - not used
         * - $extra_params - array of additional params with the following array keys:
         *      - `site` - [string] by ref, site name from ChargeBee account, can be overridden in hook processing
         *      - `params` - [array] by ref, params array as key&value pairs, can be overridden in hook processing
         * @hook @ref hook-bx_payment-chargebee_v3_get_js_code
         */
    	bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_get_js_code', 0, 0, [
            'site' => &$sSite,
            'params' => &$aParams
    	]);

    	return $this->_oModule->_oTemplate->getJsCode($this->_sName, array_merge(array(
            'sProvider' => $this->_sName,
            'sSite' => !empty($sSite) ? $sSite : $this->_getSite()
    	), $aParams));
    }

    public function getButtonSingle($iClientId, $iVendorId, $aParams = [])
    {
        $oCart = $this->_oModule->getObjectCart();
        $aCartInfo = $oCart->getInfo(BX_PAYMENT_TYPE_SINGLE, $iClientId, (int)$iVendorId);
        if(empty($aCartInfo) || !is_array($aCartInfo))
            return '';

        $aItems = [];
        $aCartItems = $oCart->getCartItems($iClientId, $iVendorId);
        foreach($aCartItems as $aCartItem)
            $aItems[] = $this->_oModule->_oConfig->descriptorA2S($aCartItem);

    	return $this->_getButton(BX_PAYMENT_TYPE_SINGLE, $iClientId, $iVendorId, array_merge($aParams, [
            'iSellerId' => $iVendorId,
            'aItems' => $aItems
    	]));
    }

    public function getButtonSingleJs($iClientId, $iVendorId, $aParams = [])
    {
        $oCart = $this->_oModule->getObjectCart();
        $aCartInfo = $oCart->getInfo(BX_PAYMENT_TYPE_SINGLE, $iClientId, (int)$iVendorId);
        if(empty($aCartInfo) || !is_array($aCartInfo))
            return '';

        $aItems = [];
        $aCartItems = $oCart->getCartItems($iClientId, $iVendorId);
        foreach($aCartItems as $aCartItem)
            $aItems[] = $this->_oModule->_oConfig->descriptorA2S($aCartItem);

    	return $this->_getButtonJs(BX_PAYMENT_TYPE_SINGLE, $iClientId, $iVendorId, array_merge($aParams, [
            'iSellerId' => $iVendorId,
            'aItems' => $aItems
    	]));
    }

    public function getButtonRecurring($iClientId, $iVendorId, $aParams = array())
    {
        return $this->_getButton(BX_PAYMENT_TYPE_RECURRING, $iClientId, $iVendorId, $aParams);
    }

    public function getButtonRecurringJs($iClientId, $iVendorId, $aParams = array())
    {
        return $this->_getButtonJs(BX_PAYMENT_TYPE_RECURRING, $iClientId, $iVendorId, $aParams);
    }

    protected function _getButton($sType, $iClientId, $iVendorId, $aParams = array())
    {
        list($sJsCode, $sJsMethod) = $this->_getButtonJs($sType, $iClientId, $iVendorId, $aParams);        

        return $this->_oModule->_oTemplate->parseHtmlByName('cbee_v3_button_' . $sType . '.html', array(
            'type' => $sType,
            'link' => 'javascript:void(0)',
            'caption' => _t($this->_sLangsPrefix . 'cbee_txt_checkout_with_' . $sType, $this->_sCaption),
            'onclick' => $sJsMethod,
            'js_object' => $this->_oModule->_oConfig->getJsObject($this->_sName),
            'js_code' => $sJsCode
        ));
    }
    
    protected function _getButtonJs($sType, $iClientId, $iVendorId, $aParams = array())
    {
        $sSite = '';

        /**
         * @hooks
         * @hookdef hook-bx_payment-chargebee_v3_get_button 'bx_payment', 'chargebee_v3_get_button' - hook to override checkout/subscibe button
         * - $unit_name - equals `bx_payment`
         * - $action - equals `chargebee_v3_get_button`
         * - $object_id - not used
         * - $sender_id - client (buyer) profile id
         * - $extra_params - array of additional params with the following array keys:
         *      - `type` - [string] by ref, payment type ('single' or 'recurring'), can be overridden in hook processing
         *      - `site` - [string] by ref, site name from ChargeBee account, can be overridden in hook processing
         *      - `params` - [array] by ref, params array as key&value pairs, can be overridden in hook processing
         * @hook @ref hook-bx_payment-chargebee_v3_get_button
         */
        bx_alert($this->_oModule->_oConfig->getName(), $this->_sName . '_get_button', 0, $iClientId, [
            'type' => &$sType, 
            'site' => &$sSite,
            'params' => &$aParams
        ]);

        $sJsMethod = '';
        $sJsObject = $this->getJsObject($aParams);
        switch($sType) {
            case BX_PAYMENT_TYPE_SINGLE:
                $sJsMethod = $sJsObject . '.checkout(this)';
                break;

            case BX_PAYMENT_TYPE_RECURRING:
                $sJsMethod = $sJsObject . '.subscribe(this)';
                break;
        }

        return array($this->_oModule->_oTemplate->getJsCode($this->_sName, array_merge(array(
            'js_object' => $sJsObject,
            'sProvider' => $this->_sName,
            'sSite' => !empty($sSite) ? $sSite : $this->_getSite(),
            'iClientId' => $iClientId
        ), $aParams)), $sJsMethod);
    }

    public function getMenuItemsActionsRecurring($iClientId, $iVendorId, $aParams = array())
    {
        if(empty($aParams['id']))
            return array();

        $sPrefix = 'bx-payment-strp-';
        $sJsObject = $this->_oModule->_oConfig->getJsObject($this->_sName);

        return array(
            array('id' => $sPrefix . 'manager', 'name' => $sPrefix . 'manager', 'class' => '', 'link' => 'javascript:void(0)', 'onclick' => "javascript:return " . $sJsObject . ".manage(this, '" . $aParams['id'] . "')", 'target' => '_self', 'title' => _t('_bx_payment_cbee_menu_item_title_manager'))
        );
    }
}

/** @} */
