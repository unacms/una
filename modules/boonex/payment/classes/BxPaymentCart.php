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
        	'content' => $this->_oModule->_oTemplate->displayBlockCarts($iUserId),
        	'menu' => $this->_oModule->_oConfig->getObject('menu_cart_submenu')
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

		$aSeller = $this->_oModule->getProfileInfo();
        return array(
        	'title' => _t($CNF['T']['BLOCK_TITLE_CART'], $aSeller['name']),
        	'content' => $this->_oModule->_oTemplate->displayBlockCart($iUserId, $iSellerId),
        	'menu' => $this->_oModule->_oConfig->getObject('menu_cart_submenu')
        );
    }

    public function serviceGetBlockCartHistory()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		$iVendorId = bx_get('vendor') !== false ? (int)bx_get('vendor') : 0;

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']));

        return array(
        	'content' => $this->_oModule->_oTemplate->displayBlockHistory($iUserId, $iVendorId),
        	'menu' => $this->_oModule->_oConfig->getObject('menu_cart_submenu')
		);
    }

    public function serviceAddToCart($iVendorId, $iModuleId, $iItemId, $iItemCount)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$iClientId = $this->_oModule->getProfileId();

    	$mixedResult = $this->_checkData($iClientId, $iVendorId, $iModuleId, $iItemId, $iItemCount);
    	if($mixedResult !== true)
    		return $mixedResult;

        $aVendorProviders = $this->_oModule->_oDb->getVendorInfoProvidersCart($iVendorId);
        if(empty($aVendorProviders))
            return array('code' => 5, 'message' => _t($CNF['T']['ERR_NOT_ACCEPT_PAYMENTS']));

        $sCartItem = $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $iItemId, $iItemCount));
        $sCartItems = $this->_oModule->_oDb->getCartItems($iClientId);

        if(strpos($sCartItems, $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $iItemId))) !== false)
            $sCartItems = preg_replace_callback(
            	"/" . $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $iItemId, '([0-9])+')) . "/", 
            	create_function('$aMatches', 'return ' . $this->_oModule->_oConfig->descriptorA2S(array("'" . $iVendorId, $iModuleId, $iItemId, "' . (\$aMatches[1] + " . $iItemCount . ")")) . ';'),
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

    public function serviceDeleteFromCart($iVendorId, $iModuleId = 0, $iItemId = 0)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if($iVendorId == BX_PAYMENT_EMPTY_ID)
            return array('code' => 1, 'message' => _t($CNF['T']['ERR_WRONG_DATA']));

		$iClientId = $this->_oModule->getProfileId();
        if(empty($iClientId))
            return array('code' => 2, 'message' => _t($CNF['T']['ERR_REQUIRED_LOGIN']));

        if(!empty($iModuleId) && !empty($iItemId))
            $sPattern = "'" . $iVendorId . "_" . $iModuleId . "_" . $iItemId . "_[0-9]+:?'";
        else
            $sPattern = "'" . $iVendorId . "_[0-9]+_[0-9]+_[0-9]+:?'";

        $sCartItems = $this->_oModule->_oDb->getCartItems($iClientId);
        $sCartItems = trim(preg_replace($sPattern, "", $sCartItems), ":");
        $this->_oModule->_oDb->setCartItems($iClientId, $sCartItems);

        return array('code' => 0, 'message' => _t($CNF['T']['MSG_ITEM_DELETED']));
    }

	public function serviceSubscribe($iVendorId, $iModuleId, $iItemId, $iItemCount)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$iClientId = $this->_oModule->getProfileId();

    	$mixedResult = $this->_checkData($iClientId, $iVendorId, $iModuleId, $iItemId, $iItemCount);
    	if($mixedResult !== true)
    		return $mixedResult;

        $aVendorProviders = $this->_oModule->_oDb->getVendorInfoProvidersSubscription($iVendorId);
        if(empty($aVendorProviders))
            return array('code' => 5, 'message' => _t($CNF['T']['ERR_NOT_ACCEPT_PAYMENTS']));

        $sCartItem = $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $iItemId, $iItemCount));

        if(count($aVendorProviders) > 1) {
        	//TODO: Show popup with selector and path Descriptior into it.
        	return array('popup' => '');
        }

        $aProvider = array_shift($aVendorProviders);

        $mixedResult = $this->_oModule->serviceInitializeCheckout(BX_PAYMENT_TYPE_RECURRING, $iVendorId, $aProvider['name'], array($sCartItem));
        if(is_string($mixedResult))
        	return array('code' => 6, 'message' => _t($mixedResult));

		return $mixedResult;
    }

    protected function _checkData($iClientId, $iVendorId, $iModuleId, $iItemId, $iItemCount)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		if($iVendorId == BX_PAYMENT_EMPTY_ID || empty($iModuleId) || empty($iItemId) || empty($iItemCount))
            return array('code' => 1, 'message' => _t($CNF['T']['ERR_WRONG_DATA']));

		$iClientId = $this->_oModule->getProfileId();
        if(empty($iClientId))
            return array('code' => 2, 'message' => _t($CNF['T']['ERR_REQUIRED_LOGIN']));

        if($iClientId == $iVendorId)
            return array('code' => 3, 'message' => _t($CNF['T']['ERR_SELF_PURCHASE']));

        $aVendor = $this->_oModule->getVendorInfo($iVendorId);
        if(!$aVendor['active'])
            return array('code' => 4, 'message' => _t($CNF['T']['ERR_INACTIVE_VENDOR']));

		return true;
    }

    public function getInfo($sType, $iUserId, $iVendorId = BX_PAYMENT_EMPTY_ID, $aItems = array())
    {
        if($iVendorId != BX_PAYMENT_EMPTY_ID && !empty($aItems))
            return $this->_getInfo($sType, $iUserId, $iVendorId, $this->_oModule->_oConfig->descriptorsM2A($aItems));

        $aContent = $this->_parseByVendor($iUserId);

        if($iVendorId != BX_PAYMENT_EMPTY_ID)
            return isset($aContent[$iVendorId]) ? $this->_getInfo($sType, $iUserId, $iVendorId, $aContent[$iVendorId]) : array();

        $aResult = array();
        foreach($aContent as $iVendorId => $aVendorItems)
            $aResult[$iVendorId] = $this->_getInfo($sType, $iUserId, $iVendorId, $aVendorItems);

        return $aResult;
    }

    /**
     * Enter description here...
     *
     * @param  integer $iClientId client's ID
     * @param  integer $iVendorId vendor's ID
     * @param  array   $aItems    item descriptors(quaternions) from shopping cart.
     * @return array   with full info about vendor and items.
     */
    protected function _getInfo($sType, $iClientId, $iVendorId, $aItems)
    {
        $iItemsCount = 0;
        $fItemsPrice = 0;
        $aItemsInfo = array();
        foreach($aItems as $aItem) {
            $aItemInfo = $this->_oModule->callGetCartItem((int)$aItem['module_id'], array($aItem['item_id']));
            $aItemInfo['module_id'] = (int)$aItem['module_id'];
            $aItemInfo['quantity'] = (int)$aItem['item_count'];

            $iItemsCount += $aItem['item_count'];
            $fItemsPrice += $aItem['item_count'] * $this->_oModule->_oConfig->getPrice($sType, $aItemInfo);
            $aItemsInfo[] = $aItemInfo;
        }

        $aVendor = $this->_oModule->getVendorInfo((int)$iVendorId);
        return array(
        	'client_id' => $iClientId,
            'vendor_id' => $aVendor['id'],
            'vendor_name' => $aVendor['name'],
        	'vendor_link' => $aVendor['link'],
            'vendor_icon' => $aVendor['icon'],
        	'vendor_unit' => $aVendor['unit'],
            'vendor_currency_code' => $aVendor['currency_code'],
            'vendor_currency_sign' => $aVendor['currency_sign'],
            'items_count' => $iItemsCount,
            'items_price' => $fItemsPrice,
            'items' => $aItemsInfo
        );
    }
}

/** @} */
