<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Payment Payment
 * @ingroup     TridentModules
 *
 * @{
 */

class BxPaymentTemplate extends BxBaseModPaymentTemplate
{
	protected $_sLangsPrefix;

    function __construct(&$oConfig, &$oDb)
    {
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

    public function addJsCssCart()
    {
    	$this->addJsTranslation(array('_bx_payment_err_nothing_selected'));
    	$this->addJs(array('jquery.anim.js', 'cart.js'));
    	$this->addCss(array('orders.css', 'cart.css'));
    }

	public function displayCartJs($bWrapped = true)
    {
        $this->addJsCssCart();
        return $this->displayJsCode('cart');
    }

    public function displayAddToCartJs($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect = false, $bWrapped = true)
    {
        $sJsCode = $this->displayCartJs($bWrapped);
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
        $this->addJsCssCart();
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

	public function displaySubscribeJs($iVendorId, $iModuleId, $iItemId, $iItemCount = 1, $bWrapped = true)
    {
        $sJsCode = $this->displayCartJs($bWrapped);
        $sJsMethod = $this->parseHtmlByName('subscribe_js.html', array(
            'js_object' => $this->_oConfig->getJsObject('cart'),
            'vendor_id' => $iVendorId,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount
        ));

        return array($sJsCode, $sJsMethod);
    }

    public function displaySubscribeLink($iVendorId, $iModuleId, $iItemId, $iItemCount = 1)
    {
        $this->addJsCssCart();
        return $this->parseHtmlByName('subscribe.html', array(
        	'js_object' => $this->_oConfig->getJsObject('cart'),
        	'js_content' => $this->displayJsCode('cart'),
        	'txt_add_to_cart' => _t($this->_sLangsPrefix . 'txt_subscribe'),
            'vendor_id' => $iVendorId,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,
        ));
    }

	public function displayBlockCart($aCartInfo, $iVendorId = BX_PAYMENT_EMPTY_ID)
    {
    	if(empty($aCartInfo))
    		return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

        $sJsObject = $this->_oConfig->getJsObject('cart');

        if($iVendorId != BX_PAYMENT_EMPTY_ID)
            $aCartInfo = array($aCartInfo);

        $aVendors = array();
        foreach($aCartInfo as $aVendor) {
            //--- Get Providers ---//
            $aProviders = array();
            $aVendorProviders = $this->_oDb->getVendorInfoProvidersCart($aVendor['vendor_id']);

            if(!empty($aVendorProviders) && is_array($aVendorProviders))
	            foreach($aVendorProviders as $aProvider)
	                $aProviders[] = array(
	                    'name' => $aProvider['name'],
	                    'caption' => _t($this->_sLangsPrefix . 'txt_cart_' . $aProvider['name']),
	                    'checked' => empty($aProviders) ? 'checked="checked"' : ''
	                );

            //--- Get Items ---//
            $aItems = array();
            foreach($aVendor['items'] as $aItem)
                $aItems[] = array(
                    'vendor_id' => $aVendor['vendor_id'],
                    'vendor_currency_code' => $aVendor['vendor_currency_code'],
                    'module_id' => $aItem['module_id'],
                    'item_id' => $aItem['id'],
                    'item_title' => $aItem['title'],
                    'item_url' => $aItem['url'],
                    'item_quantity' => $aItem['quantity'],
                	'bx_if:show_price_paid' => array(
                		'condition' => (int)$aItem['price'] != 0,
                		'content' => array(
                			'item_price' => $aItem['quantity'] * $aItem['price'],
                			'vendor_currency_code' => $aVendor['vendor_currency_code'],
                		)
                	),
                	'bx_if:show_price_free' => array(
                		'condition' => (int)$aItem['price'] == 0,
                		'content' => array()
                	),
                    'js_object' => $sJsObject
                );

            //--- Get Control Panel ---//
            $aButtons = array(
                'bx-payment-checkout' => _t($this->_sLangsPrefix . 'form_cart_input_do_checkout'),
                'bx-payment-delete' => _t($this->_sLangsPrefix . 'form_cart_input_do_delete')
            );
            $oSearchResult = new BxTemplSearchResult();
            $sControlPanel = $oSearchResult->showAdminActionsPanel('items_from_' . $aVendor['vendor_id'], $aButtons, 'items', true, true);

            //--- Get General ---//
            $bVendorLink = !empty($aVendor['vendor_link']);
            $sTxtShoppingCart = _t($this->_sLangsPrefix . 'txt_shopping_cart', $aVendor['vendor_name']);

            $aVendors[] = array(
                'vendor_id' => $aVendor['vendor_id'],
                'bx_if:show_link' => array(
                    'condition' => $bVendorLink,
                    'content' => array(
            			'txt_shopping_cart' => $sTxtShoppingCart,
                        'vendor_url' => $aVendor['vendor_link'],
                        'vendor_currency_code' => $aVendor['vendor_currency_code'],
                        'items_count' => $aVendor['items_count'],
                        'items_price' => $aVendor['items_price']
                    )
                ),
                'bx_if:show_text' => array(
                    'condition' => !$bVendorLink,
                    'content' => array(
                		'txt_shopping_cart' => $sTxtShoppingCart,
                        'vendor_currency_code' => $aVendor['vendor_currency_code'],
                        'items_count' => $aVendor['items_count'],
                        'items_price' => $aVendor['items_price']
                    )
                ),
                'vendor_icon' => $aVendor['vendor_icon'],
                'bx_repeat:providers' => $aProviders,
                'bx_repeat:items' => $aItems,
                'js_object' => $sJsObject,
                'process_url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'cart_submit/',
                'control_panel' => $sControlPanel
            );
        }

        $this->addJsCssCart();
        return $this->parseHtmlByName('cart.html', array(
        	'js_content' => $this->displayJsCode('cart'),
        	'bx_repeat:vendors' => $aVendors
        ));
    }

    public function displayBlockHistory($iClientId, $iSellerId)
    {
    	$oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_history'));
        if(!$oGrid || empty($iClientId))
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$oGrid->addQueryParam('client_id', $iClientId);
		if(!empty($iSellerId))
			$oGrid->addQueryParam('seller_id', $iSellerId);

		$this->addJsCssOrders();
        return $this->displayJsCode('history') . $oGrid->getCode();
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

        $oModule = $this->_getModule();
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
            'provider' => $aOrder['provider'],
            'error' => $aOrder['error_msg'],
            'date' => bx_time_js($aOrder['date']),
            'bx_repeat:items' => array()
        );

        if($sType == BX_PAYMENT_ORDERS_TYPE_PENDING)
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
	                'price' => $aInfo['price'],
	                'currency_code' => $aSeller['currency_code']
	            );
        }

        return $this->parseHtmlByName('order_' . $sType . '.html', $aResult);
    }

    public function displayItems($aItems = array())
    {
        $aTmplVarsItems = array();

        foreach($aItems as $aItem) {
            $aTmplVarsItems[] = array(
                'id' => $aItem['id'],
                'price' => $aItem['price'],
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

	protected function _getModule()
    {
        $sName = $this->_oConfig->getName();
        return BxDolModule::getInstance($sName);
    }

    protected function _isSubscription($aOrder)
    {
    	return '';
    }
}

/** @} */
