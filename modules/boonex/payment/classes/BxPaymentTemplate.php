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

    public function addJsCssCart($iVendorId = 0)
    {
    	$this->addJsTranslation(array('_bx_payment_err_nothing_selected'));
    	$this->addJs(array('jquery.anim.js', 'cart.js'));
    	$this->addCss(array('orders.css', 'cart.css'));

    	$oModule = $this->_getModule();
    	if(!empty($iVendorId)) {
    		$aProviders = $this->_oDb->getVendorInfoProvidersSubscription($iVendorId);
    		foreach($aProviders as $sProvider => $aProvider)
				$oModule->getObjectProvider($sProvider, $iVendorId)->addJsCss();
    	}
    }

	public function displayCartJs($iVendorId = 0)
    {
        $this->addJsCssCart($iVendorId);
        return $this->displayJsCode('cart');
    }

    public function displayAddToCartJs($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect = false, $bWrapped = true)
    {
        $sJsCode = $this->displayCartJs($iVendorId);
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

	public function displaySubscribeJs($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount = 1, $bWrapped = true)
    {
        $sJsCode = $this->displayCartJs($iVendorId);
        $sJsMethod = $this->parseHtmlByName('subscribe_js.html', array(
            'js_object' => $this->_oConfig->getJsObject('cart'),
            'vendor_id' => $iVendorId,
        	'vendor_provider' => $sVendorProvider,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount
        ));

        return array($sJsCode, $sJsMethod);
    }

    public function displaySubscribeLink($iVendorId, $sVendorProvider, $iModuleId, $iItemId, $iItemCount = 1)
    {
        $this->addJsCssCart();
        return $this->parseHtmlByName('subscribe.html', array(
        	'js_object' => $this->_oConfig->getJsObject('cart'),
        	'js_content' => $this->displayJsCode('cart'),
        	'txt_add_to_cart' => _t($this->_sLangsPrefix . 'txt_subscribe'),
            'vendor_id' => $iVendorId,
        	'vendor_provider' => $sVendorProvider,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,
        ));
    }

	public function displayBlockCarts($iClientId)
    {
    	$oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_carts'));
        if(!$oGrid)
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$oGrid->addQueryParam('client_id', $iClientId);

		$this->addJsCssCart();
        return $this->displayJsCode('cart') . $oGrid->getCode();
    }

	public function displayBlockCart($iClientId, $iSellerId = 0)
    {
    	$oGrid = BxDolGrid::getObjectInstance($this->_oConfig->getObject('grid_cart'));
        if(!$oGrid || empty($iSellerId))
            return MsgBox(_t($this->_sLangsPrefix . 'msg_no_results'));

		$oGrid->addQueryParam('client_id', $iClientId);
		$oGrid->addQueryParam('seller_id', $iSellerId);

		$this->addJsCssCart();
        return $this->displayJsCode('cart') . $oGrid->getCode();
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

    public function displayProvidersSelector($aCartItem, $aProviders)
    {
		$oCart = $this->_getModule()->getObjectCart();
		list($iSellerId, $iModuleId, $iItemId, $iItemCount) = $aCartItem;

		$aTmplVarsProviders = array();
		foreach($aProviders as $sProvider => $aProvider) {
			list($sJsCode, $sJsOnclick) = $oCart->serviceGetSubscribeJs($iSellerId, $aProvider['name'], $iModuleId, $iItemId, $iItemCount);

			$aTmplVarsProviders[] = array(
				'onclick' => $sJsOnclick,
				'title' => _t('_bx_payment_txt_checkout_with', _t($aProvider['caption']))
			);
		}

		return $this->parseHtmlByName('providers_select.html', array(
			'bx_repeat:providers' => $aTmplVarsProviders
		));
    }

	protected function _getModule()
    {
        $sName = $this->_oConfig->getName();
        return BxDolModule::getInstance($sName);
    }
}

/** @} */
