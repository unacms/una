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

class BxPaymentTemplate extends BxBaseModPaymentTemplate
{
	protected $_sLangsPrefix;

    function __construct(&$oConfig, &$oDb)
    {
    	$this->MODULE = 'bx_payment';

        parent::__construct($oConfig, $oDb);

        $this->_sLangsPrefix = $this->_oConfig->getPrefix('langs');
    }

    public function addJsCssOrders()
    {
    	$this->addJs(array(
    		'jquery-ui/jquery.ui.widget.min.js',
    		'jquery-ui/jquery.ui.menu.min.js', 
    		'jquery-ui/jquery.ui.autocomplete.min.js', 
    		'jquery.form.min.js', 
    		'jquery.anim.js', 
    		'jquery.webForms.js',
    		'orders.js'
    	));
    	$this->addCss(array('orders.css'));
    }
    public function addJsCssSubscriptions()
    {
        $this->addJs(array('jquery.form.min.js', 'jquery.anim.js', 'jquery.webForms.js', 'main.js', 'subscriptions.js'));
        $this->addCss(array('orders.css', 'subscriptions.css'));
    }

    public function addJsCssCart($sType = '', $iVendorId = 0)
    {
    	$this->addJsTranslation(array('_bx_payment_err_nothing_selected'));
    	$this->addJs(array('jquery.anim.js', 'main.js', 'cart.js'));
    	$this->addCss(array('orders.css', 'cart.css'));

    	$oModule = $this->getModule();
    	if(!empty($iVendorId)) {
    		$sMethod = 'getVendorInfoProviders' . bx_gen_method_name($sType);
    		$aProviders = $this->_oDb->$sMethod($iVendorId);
    		foreach($aProviders as $sProvider => $aProvider)
				$oModule->getObjectProvider($sProvider, $iVendorId)->addJsCss();
    	}
    }

	public function displayCartJs($sType = '', $iVendorId = 0)
    {
        $this->addJsCssCart($sType, $iVendorId);
        return $this->displayJsCode('cart');
    }

    public function displayAddToCartJs($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect = false)
    {
        $sJsCode = $this->displayCartJs(BX_PAYMENT_TYPE_SINGLE, $iVendorId);
        $sJsMethod = $this->parseHtmlByName('add_to_cart_js.html', array(
            'js_object' => $this->_oConfig->getJsObject('cart'),
            'vendor_id' => $iVendorId,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,
            'need_redirect' => (int)$bNeedRedirect
        ));

        return array($sJsCode, $sJsMethod);
    }

    public function displayAddToCartLink($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect = false)
    {
        $this->addJsCssCart(BX_PAYMENT_TYPE_SINGLE, $iVendorId);
        return $this->parseHtmlByName('add_to_cart.html', array(
        	'js_object' => $this->_oConfig->getJsObject('cart'),
        	'js_content' => $this->displayJsCode('cart'),
        	'txt_add_to_cart' => _t($this->_sLangsPrefix . 'txt_add_to_cart'),
            'vendor_id' => $iVendorId,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,
            'need_redirect' => (int)$bNeedRedirect
        ));
    }

	public function displaySubscribeJs($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount = 1, $sRedirect = '')
    {
        $sJsCode = $this->displayCartJs(BX_PAYMENT_TYPE_RECURRING, $iVendorId);
        $sJsMethod = $this->parseHtmlByName('subscribe_js.html', array(
            'js_object' => $this->_oConfig->getJsObject('cart'),
            'vendor_id' => $iVendorId,
        	'vendor_provider' => $sVendorProvider,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,
            'bx_if:show_redirect' => array(
                'condition' => !empty($sRedirect),
                'content' => array(
                    'redirect' => $sRedirect
                )
            )
        ));

        return array($sJsCode, $sJsMethod);
    }

    public function displaySubscribeLink($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount = 1, $sRedirect = '')
    {
        $this->addJsCssCart(BX_PAYMENT_TYPE_RECURRING, $iVendorId);
        return $this->parseHtmlByName('subscribe.html', array(
        	'js_object' => $this->_oConfig->getJsObject('cart'),
        	'js_content' => $this->displayJsCode('cart'),
        	'txt_add_to_cart' => _t($this->_sLangsPrefix . 'txt_subscribe'),
            'vendor_id' => $iVendorId,
        	'vendor_provider' => $sVendorProvider,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,
            'bx_if:show_redirect' => array(
                'condition' => !empty($sRedirect),
                'content' => array(
                    'redirect' => $sRedirect
                )
            )
        ));
    }

    public function displaySubscriptionGetDetails($iId)
    {
    	return $this->_displaySubscriptionData('get_details', $iId);
    }

    public function displaySubscriptionChangeDetails($iId)
    {
    	return $this->_displaySubscriptionData('change_details', $iId);
    }

    public function displaySubscriptionGetBilling($iId)
    {
    	return $this->_displaySubscriptionData('get_billing', $iId);
    }

    public function displaySubscriptionChangeBilling($iId)
    {
    	return $this->_displaySubscriptionData('change_billing', $iId);
    }

	public function displayBlockCarts($iClientId)
    {
    	$oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_carts'));
        if(!$oGrid)
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$oGrid->addQueryParam('client_id', $iClientId);

		$this->addJsCssCart(BX_PAYMENT_TYPE_SINGLE);
        return $this->displayJsCode('cart') . $oGrid->getCode();
    }

	public function displayBlockCart($iClientId, $iSellerId = 0)
    {
    	$oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_cart'));
        if(!$oGrid || empty($iSellerId))
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$oGrid->addQueryParam('client_id', $iClientId);
		$oGrid->addQueryParam('seller_id', $iSellerId);

		$this->addJsCssCart(BX_PAYMENT_TYPE_SINGLE, $iSellerId);
        return $this->displayJsCode('cart') . $oGrid->getCode();
    }

    public function displayBlockHistory($iClientId, $iSellerId)
    {
        return $this->_displayBlockHistory('grid_history', $iClientId, $iSellerId);
    }

    public function displayBlockSbsListMy($iClientId)
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_sbs_list_my'), $this->getModule()->_oTemplate);
        if(!$oGrid || empty($iClientId))
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$oGrid->addQueryParam('client_id', $iClientId);

		$this->addJsCssSubscriptions();
        return $this->displayJsCode(BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION) . $oGrid->getCode();
    }

    public function displayBlockSbsListAll()
    {
        $oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_sbs_list_all'), $this->getModule()->_oTemplate);
        if(!$oGrid)
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$this->addJsCssSubscriptions();
        return $this->displayJsCode(BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION) . $oGrid->getCode();
    }

    public function displayBlockSbsHistory($iClientId)
    {
        return $this->_displayBlockHistory('grid_sbs_history', $iClientId);
    }

    public function displayBlockSbsDetails($iClientId)
    {
        return 'Details would be here';
    }

	public function displayBlockOrders($sType, $iSellerId)
    {
    	$oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_' . $sType));
        if(!$oGrid || empty($iSellerId))
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$oGrid->addQueryParam('seller_id', $iSellerId);

		$this->addJsCssOrders();
        return $this->displayJsCode($sType) . $oGrid->getCode();
    }

	public function displayOrder($sType, $iId)
    {
        $sMethodName = 'getOrder' . bx_gen_method_name($sType);
        $aOrder = $this->_oDb->$sMethodName(array('type' => 'id', 'id' => $iId));

        $oModule = $this->getModule();
        $aSeller = $oModule->getVendorInfo((int)$aOrder['seller_id']);
        $aClient = $oModule->getProfileInfo((int)$aOrder['client_id']);

        $aResult = array(
        	'txt_client' => _t($this->_sLangsPrefix . 'txt_client'),
        	'txt_seller' => _t($this->_sLangsPrefix . 'txt_seller'),
        	'txt_order' => _t($this->_sLangsPrefix . 'txt_order'),
        	'txt_processed_with' => _t($this->_sLangsPrefix . 'txt_processed_with'),
        	'txt_message' => _t($this->_sLangsPrefix . 'txt_message'),
        	'txt_date' => _t($this->_sLangsPrefix . 'txt_date'),
        	'txt_products' => _t($this->_sLangsPrefix . 'txt_products'),
            'client_name' => $aClient['name'],
            'client_url' => $aClient['link'],
            'bx_if:show_link' => array(
                'condition' => !empty($aSeller['link']),
                'content' => array(
                    'seller_name' => $aSeller['name'],
                    'seller_url' => $aSeller['link'],
                )
            ),
            'bx_if:show_text' => array(
                'condition' => empty($aSeller['link']),
                'content' => array(
                    'seller_name' => $aSeller['name']
                )
            ),
            'order' => $aOrder['order'],
            'provider' => _t('_bx_payment_txt_name_' . $aOrder['provider']),
            'error' => $aOrder['error_msg'],
            'date' => bx_time_js($aOrder['date']),
            'bx_repeat:items' => array()
        );

        if(in_array($sType, array(BX_PAYMENT_ORDERS_TYPE_PENDING, BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION)))
            $aItems = $this->_oConfig->descriptorsM2A($aOrder['items']);
        else
            $aItems = $this->_oConfig->descriptorsM2A($this->_oConfig->descriptorA2S(array($aOrder['seller_id'], $aOrder['module_id'], $aOrder['item_id'], $aOrder['item_count'])));

        foreach($aItems as $aItem) {
            $aInfo = $oModule->callGetCartItem((int)$aItem['module_id'], array($aItem['item_id']));
            if(!empty($aInfo) && is_array($aInfo))
	            $aResult['bx_repeat:items'][] = array(
	                'bx_if:link' => array(
	                    'condition' => !empty($aInfo['url']),
	                    'content' => array(
	                        'title' => $aInfo['title'],
	                        'url' => $aInfo['url']
	                    )
	                ),
	                'bx_if:text' => array(
	                    'condition' => empty($aInfo['url']),
	                    'content' => array(
	                        'title' => $aInfo['title'],
	                    )
	                ),
	                'quantity' => $aItem['item_count'],
	                'price' => $this->_oConfig->getPrice($aOrder['type'], $aInfo),
	                'currency_code' => $aSeller['currency_code']
	            );
        }

        return $this->parseHtmlByName('order_' . $sType . '.html', $aResult);
    }

    public function displayItems($sType, $aItems = array())
    {
        $aTmplVarsItems = array();

        foreach($aItems as $aItem) {
            $aTmplVarsItems[] = array(
                'id' => $aItem['id'],
                'price' => $this->_oConfig->getPrice($sType, $aItem),
                'bx_if:link' => array(
                    'condition' => !empty($aItem['url']),
                    'content' => array(
                        'url' => $aItem['url'],
                        'title' => $aItem['title']
                    )
                ),
                'bx_if:text' => array(
                    'condition' => empty($aItem['url']),
                    'content' => array(
                        'title' => $aItem['title']
                    )
                ),
            );
        }

        if(empty($aTmplVarsItems))
        	$aTmplVarsItems = MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

        return $this->parseHtmlByName('items.html', array(
        	'html_id' => $this->_oConfig->getHtmlIds('processed', 'order_processed_items'),
        	'bx_repeat:items' => $aTmplVarsItems
        ));
    }

    public function displayProvidersSelector($aCartItem, $aProviders, $sRedirect = '')
    {
    	$oModule = $this->getModule();
    	$iClientId = $oModule->getProfileId();

		$oCart = $oModule->getObjectCart();
		list($iSellerId, $iModuleId, $iItemId, $iItemCount) = $aCartItem;

		$aTmplVarsProviders = array();
		foreach($aProviders as $sProvider => $aProvider) {
			$oProvider = $oModule->getObjectProvider($sProvider, $iSellerId);
			if($oProvider !== false && method_exists($oProvider, 'getButtonRecurring')) {
			    $aParams = array(
					'sObjNameCart' => $oModule->_oConfig->getJsObject('cart'),
					'iSellerId' => $iSellerId,
					'iModuleId' => $iModuleId,
					'iItemId' => $iItemId,
					'iItemCount' => $iItemCount,
				    'sRedirect' => $sRedirect
				);

			    $aCartInfo = $oCart->getInfo(BX_PAYMENT_TYPE_RECURRING, $iClientId, $iSellerId, $this->_oConfig->descriptorA2S($aCartItem));
			    if(!empty($aCartInfo['items_price']) && !empty($aCartInfo['items']) && is_array($aCartInfo['items'])) {
			        $aTitles = array();
			        foreach ($aCartInfo['items'] as $aItem)
			            $aTitles[] = $aItem['title'];

			        $aParams = array_merge($aParams, array(
			            'iAmount' => (int)round(100 * (float)$aCartInfo['items_price']),
			        	'sItemTitle' => implode(', ', $aTitles)
			        ));
			    }

				$sButton = $oProvider->getButtonRecurring($iClientId, $iSellerId, $aParams);
			}
			else {
				list($sJsCode, $sJsOnclick) = $oCart->serviceGetSubscribeJs($iSellerId, $aProvider['name'], $iModuleId, $iItemId, $iItemCount, $sRedirect);

				$sButton = $this->parsePageByName('providers_select_button.html', array(
					'onclick' => $sJsOnclick,
					'title' => _t('_bx_payment_txt_checkout_with', _t($aProvider['caption']))
				));
			}

			$aTmplVarsProviders[] = array(
				'button' => $sButton
			);
		}

		return $this->parseHtmlByName('providers_select.html', array(
			'bx_repeat:providers' => $aTmplVarsProviders
		));
    }

    protected function _displayBlockHistory($sObject, $iClientId, $iSellerId = 0)
    {
    	$oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject($sObject), $this->getModule()->_oTemplate);
        if(!$oGrid || empty($iClientId))
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$oGrid->addQueryParam('client_id', $iClientId);
		if(!empty($iSellerId))
			$oGrid->addQueryParam('seller_id', $iSellerId);

		$this->addJsCssOrders();
        return $this->displayJsCode(BX_PAYMENT_ORDERS_TYPE_HISTORY) . $oGrid->getCode();
    }

    protected function _displaySubscriptionData($sType, $iId)
    {
        $aPending = $this->_oDb->getOrderPending(array('type' => 'id', 'id' => $iId));
        if(empty($aPending) && !is_array($aPending))
            return array();

        $aSubscription = $this->_oDb->getSubscription(array('type' => 'pending_id', 'pending_id' => $iId));
        if(empty($aSubscription) && !is_array($aSubscription))
            return array();

        $sMethod = bx_gen_method_name($sType) . 'Recurring';
        $oProvider = $this->getModule()->getObjectProvider($aPending['provider'], $aPending['seller_id']);
        if($oProvider === false || !$oProvider->isActive() || !method_exists($oProvider, $sMethod))
        	return array();

        $mixedContent = $oProvider->$sMethod($iId, $aSubscription['customer_id'], $aSubscription['subscription_id']);
        if(empty($mixedContent))
            return array();
        else if(is_array($mixedContent))
            return $mixedContent;
//TODO: Continue from here. Popup Should use correct HTML ID to avoid duplocations. 
        $sKey = 'order_' . BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION . '_' . $sType;
    	$sId = $this->_oConfig->getHtmlIds(BX_PAYMENT_ORDERS_TYPE_SUBSCRIPTION, $sKey);
    	$sTitle = _t($this->_sLangsPrefix . 'popup_title_ods_' . $sKey);
    	return array('popup' => array(
    		'html' => BxTemplFunctions::getInstance()->popupBox($sId, $sTitle, $mixedContent), 
    		'options' => array('closeOnOuterClick' => 1)
    	));
    }
}

/** @} */
