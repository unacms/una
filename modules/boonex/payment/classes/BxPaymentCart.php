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

class BxPaymentCart extends BxBaseModPaymentCart
{
    function __construct()
    {
    	$this->MODULE = 'bx_payment';

    	parent::__construct();
    }

    /*
     * Service methods
     */
	public function serviceGetBlockCarts()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	if(bx_get('seller_id') !== false)
    		return '';

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']));

        return array(
        	'content' => $this->_oModule->_oTemplate->displayBlockCarts($iUserId)
        );
    }

	public function serviceGetBlockCart()
    {
    	// Don't show the block at all if 'seller_id' not exists.
    	if(bx_get('seller_id') === false)
    		return '';

    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']));

    	$iSellerId = bx_process_input(bx_get('seller_id'), BX_DATA_INT);
    	if(empty($iSellerId))
    		return MsgBox(_t($CNF['T']['ERR_UNKNOWN_VENDOR']));

		$aSeller = $this->_oModule->getProfileInfo($iSellerId);
        return array(
        	'title' => _t($CNF['T']['BLOCK_TITLE_CART'], $aSeller['name']),
        	'content' => $this->_oModule->_oTemplate->displayBlockCart($iUserId, $iSellerId)
        );
    }

    public function serviceGetBlockCartHistory()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		$iSellerId = bx_get('vendor') !== false ? (int)bx_get('vendor') : 0;

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']));

        return array(
        	'content' => $this->_oModule->_oTemplate->displayBlockHistory($iUserId, $iSellerId),
		);
    }

    public function serviceAddToCart($iSellerId, $mixedModuleId, $iItemId, $iItemCount)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
    	$iClientId = $this->_oModule->getProfileId();

    	$mixedResult = $this->_checkData($iClientId, $iSellerId, $iModuleId, $iItemId, $iItemCount);
    	if($mixedResult !== true)
    		return $mixedResult;

        $aSellerProviders = $this->_oModule->_oDb->getVendorInfoProvidersSingle($iSellerId);
        if(empty($aSellerProviders))
            return array('code' => 5, 'message' => _t($CNF['T']['ERR_NOT_ACCEPT_PAYMENTS']));

        $sCartItem = $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId, $iItemCount));
        $sCartItems = $this->_oModule->_oDb->getCartItems($iClientId);

        if(strpos($sCartItems, $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId))) !== false)
            $sCartItems = preg_replace_callback(
            	"/" . $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId, '([0-9])+')) . "/", 
            	create_function('$aMatches', 'return ' . $this->_oModule->_oConfig->descriptorA2S(array("'" . $iSellerId, $iModuleId, $iItemId, "' . (\$aMatches[1] + " . $iItemCount . ")")) . ';'),
            	$sCartItems
			);
        else
            $sCartItems = empty($sCartItems) ? $sCartItem : $sCartItems . $this->_oModule->_oConfig->getDivider('DIVIDER_DESCRIPTORS') . $sCartItem;

        $this->_oModule->_oDb->setCartItems($iClientId, $sCartItems);

        $aInfo = $this->getInfo(BX_PAYMENT_TYPE_SINGLE, $iClientId);
        $iTotalQuantity = 0;
        foreach($aInfo as $aCart)
           $iTotalQuantity += $aCart['items_count'];

        return array(
        	'code' => 0, 
        	'message' => _t($CNF['T']['MSG_ITEM_ADDED']), 
        	'total_quantity' => $iTotalQuantity,
	        //TODO: Update account submenu if it's needed.  
        	'content' => '' //$this->_oModule->_oTemplate->displayToolbarSubmenu($aInfo)
        );
    }

    public function serviceDeleteFromCart($iSellerId, $iModuleId = 0, $iItemId = 0)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if($iSellerId == BX_PAYMENT_EMPTY_ID)
            return array('code' => 1, 'message' => _t($CNF['T']['ERR_WRONG_DATA']));

		$iClientId = $this->_oModule->getProfileId();
        if(empty($iClientId))
            return array('code' => 2, 'message' => _t($CNF['T']['ERR_REQUIRED_LOGIN']));

        if(!empty($iModuleId) && !empty($iItemId))
            $sPattern = "'" . $iSellerId . "_" . $iModuleId . "_" . $iItemId . "_[0-9]+:?'";
        else
            $sPattern = "'" . $iSellerId . "_[0-9]+_[0-9]+_[0-9]+:?'";

        $sCartItems = $this->_oModule->_oDb->getCartItems($iClientId);
        $sCartItems = trim(preg_replace($sPattern, "", $sCartItems), ":");
        $this->_oModule->_oDb->setCartItems($iClientId, $sCartItems);

        return array('code' => 0, 'message' => _t($CNF['T']['MSG_ITEM_DELETED']));
    }

	public function serviceSubscribe($iSellerId, $sSellerProvider, $iModuleId, $iItemId, $iItemCount, $sRedirect = '')
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$iClientId = $this->_oModule->getProfileId();

    	$mixedResult = $this->_checkData($iClientId, $iSellerId, $iModuleId, $iItemId, $iItemCount);
    	if($mixedResult !== true)
    		return $mixedResult;

        $aSellerProviders = $this->_oModule->_oDb->getVendorInfoProvidersRecurring($iSellerId);
        if(empty($aSellerProviders))
            return array('code' => 5, 'message' => _t($CNF['T']['ERR_NOT_ACCEPT_PAYMENTS']));

        $aCartItem = array($iSellerId, $iModuleId, $iItemId, $iItemCount);
        $sCartItem = $this->_oModule->_oConfig->descriptorA2S($aCartItem);

		if(empty($sSellerProvider)) {
			$sId = $this->_oModule->_oConfig->getHtmlIds('cart', 'providers_select') . BX_PAYMENT_TYPE_RECURRING;
			$sTitle = _t($CNF['T']['POPUP_PROVIDERS_SELECT']);
			return array('popup' => array(
				'html' => BxTemplStudioFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->displayProvidersSelector($aCartItem, $aSellerProviders, $sRedirect)), 
				'options' => array('closeOnOuterClick' => true)
			));
		}

		$aProvider = $aSellerProviders[$sSellerProvider];
        $mixedResult = $this->_oModule->serviceInitializeCheckout(BX_PAYMENT_TYPE_RECURRING, $iSellerId, $aProvider['name'], array($sCartItem), $sRedirect);
        if(is_string($mixedResult))
        	return array('code' => 6, 'message' => _t($mixedResult));

		return $mixedResult;
    }

    public function getInfo($sType, $iUserId, $iSellerId = BX_PAYMENT_EMPTY_ID, $aItems = array())
    {
        if($iSellerId != BX_PAYMENT_EMPTY_ID && !empty($aItems))
            return $this->_getInfo($sType, $iUserId, $iSellerId, $this->_oModule->_oConfig->descriptorsM2A($aItems));

        $aContent = $this->_parseByVendor($iUserId);

        if($iSellerId != BX_PAYMENT_EMPTY_ID)
            return isset($aContent[$iSellerId]) ? $this->_getInfo($sType, $iUserId, $iSellerId, $aContent[$iSellerId]) : array();

        $aResult = array();
        foreach($aContent as $iSellerId => $aSellerItems)
            $aResult[$iSellerId] = $this->_getInfo($sType, $iUserId, $iSellerId, $aSellerItems);

        return $aResult;
    }

    /**
     * Enter description here...
     *
     * @param  integer $iClientId client's ID
     * @param  integer $iSellerId vendor's ID
     * @param  array   $aItems    item descriptors(quaternions) from shopping cart.
     * @return array   with full info about vendor and items.
     */
    protected function _getInfo($sType, $iClientId, $iSellerId, $aItems)
    {
        $bTypeSingle = $sType == BX_PAYMENT_TYPE_SINGLE;

        $iItemsCount = 0;
        $fItemsPrice = 0;
        $aItemsInfo = array();
        foreach($aItems as $aItem) {
            $aItemInfo = $this->_oModule->callGetCartItem((int)$aItem['module_id'], array($aItem['item_id']));
            if(empty($aItemInfo) || !is_array($aItemInfo)) {
                if($bTypeSingle) {
                    $sCartItems = $this->_oModule->_oDb->getCartItems($iClientId);
                    $sCartItems = trim(preg_replace("'" . $this->_oModule->_oConfig->descriptorA2S($aItem) . ":?'", "", $sCartItems), ":");
                    $this->_oModule->_oDb->setCartItems($iClientId, $sCartItems);
                }

                continue;
            }

            $aItemInfo['module_id'] = (int)$aItem['module_id'];
            $aItemInfo['quantity'] = (int)$aItem['item_count'];

            $iItemsCount += $aItem['item_count'];
            $fItemsPrice += $aItem['item_count'] * $this->_oModule->_oConfig->getPrice($sType, $aItemInfo);
            $aItemsInfo[] = $aItemInfo;
        }

        $aSeller = $this->_oModule->getVendorInfo((int)$iSellerId);
        return array(
        	'client_id' => $iClientId,
            'vendor_id' => $aSeller['id'],
            'vendor_name' => $aSeller['name'],
        	'vendor_link' => $aSeller['link'],
            'vendor_icon' => $aSeller['icon'],
        	'vendor_thumb' => $aSeller['thumb'],
        	'vendor_avatar' => $aSeller['avatar'],
        	'vendor_unit' => $aSeller['unit'],
            'vendor_currency_code' => $aSeller['currency_code'],
            'vendor_currency_sign' => $aSeller['currency_sign'],
            'items_count' => $iItemsCount,
            'items_price' => $fItemsPrice,
            'items' => $aItemsInfo
        );
    }

	protected function _checkData($iClientId, $iSellerId, $iModuleId, $iItemId, $iItemCount)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		if($iSellerId == BX_PAYMENT_EMPTY_ID || empty($iModuleId) || empty($iItemId) || empty($iItemCount))
            return array('code' => 1, 'message' => _t($CNF['T']['ERR_WRONG_DATA']));

		$iClientId = $this->_oModule->getProfileId();
        if(empty($iClientId)) {
        	$sLoginUrl = BxDolPermalinks::getInstance()->permalink('page.php?i=login');
            return array('code' => 2, 'eval' => 'window.open("' . $sLoginUrl . '", "_self");');
        }

        $mixedResult = $this->_oModule->isAllowedPurchase(array('module_id' => $iModuleId, 'item_id' => $iItemId));
        if($mixedResult !== true) {
            if(is_string($mixedResult) && !empty($mixedResult))
                return array('code' => 2, 'message' => $mixedResult);
            else 
                return array('code' => 1, 'message' => _t($CNF['T']['ERR_WRONG_DATA']));
        }

        if($iClientId == $iSellerId)
            return array('code' => 3, 'message' => _t($CNF['T']['ERR_SELF_PURCHASE']));

        $aSeller = $this->_oModule->getVendorInfo($iSellerId);
        if(!$aSeller['active'])
            return array('code' => 4, 'message' => _t($CNF['T']['ERR_INACTIVE_VENDOR']));

		return true;
    }
}

/** @} */
