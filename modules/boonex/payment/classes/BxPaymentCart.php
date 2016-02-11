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
    protected $_sLangsPrefix;

    function __construct()
    {
    	$this->MODULE = 'bx_payment';

    	parent::__construct();

        $this->_sLangsPrefix = $this->_oModule->_oConfig->getPrefix('langs');
    }

    /*
     * Service methods
     */
	public function serviceGetBlockCart($iVendor = BX_PAYMENT_EMPTY_ID)
    {
    	$iVendor = (int)$iVendor;

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($this->_sLangsPrefix . 'err_required_login'));

        $aCartInfo = $this->getInfo($iUserId, $iVendor);
        return array(
        	'content' => $this->_oModule->_oTemplate->displayBlockCart($aCartInfo, $iVendor),
        	'menu' => $this->_oModule->_oConfig->getObject('menu_cart_submenu')
        );
    }

    public function serviceGetBlockCartHistory()
    {
		$iVendorId = bx_get('vendor') !== false ? (int)bx_get('vendor') : 0;

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($this->_sLangsPrefix . 'err_required_login'));

        return array(
        	'content' => $this->_oModule->_oTemplate->displayBlockHistory($iUserId, $iVendorId),
        	'menu' => $this->_oModule->_oConfig->getObject('menu_cart_submenu')
		);
    }

    public function serviceAddToCart($iVendorId, $iModuleId, $iItemId, $iItemCount)
    {
    	if($iVendorId == BX_PAYMENT_EMPTY_ID || empty($iModuleId) || empty($iItemId) || empty($iItemCount))
            return array('code' => 1, 'message' => _t($this->_sLangsPrefix . 'err_wrong_data'));

		$iClientId = $this->_oModule->getProfileId();
        if(empty($iClientId))
            return array('code' => 2, 'message' => _t($this->_sLangsPrefix . 'err_required_login'));

        if($iClientId == $iVendorId)
            return array('code' => 3, 'message' => _t($this->_sLangsPrefix . 'err_purchase_from_yourself'));

        $aVendor = $this->_oModule->getVendorInfo($iVendorId);
        if(!$aVendor['active'])
            return array('code' => 4, 'message' => _t($this->_sLangsPrefix . 'err_inactive_vendor'));

        $aVendorProviders = $this->_oModule->_oDb->getVendorInfoProviders($iVendorId);
        if(empty($aVendorProviders))
            return array('code' => 5, 'message' => _t($this->_sLangsPrefix . 'err_not_accept_payments'));

        $sCartItem = $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $iItemId, $iItemCount));
        $sCartItems = $this->_oModule->_oDb->getCartItems($iClientId);

        if(strpos($sCartItems, $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $iItemId))) !== false)
            $sCartItems = preg_replace_callback(
            	"/" . $this->_oModule->_oConfig->descriptorA2S(array($iVendorId, $iModuleId, $iItemId, '([0-9])+')) . "/", 
            	create_function('$aMatches', 'return ' . $this->_oModule->_oConfig->descriptorA2S(array("'" . $iVendorId, $iModuleId, $iItemId, "' . (\$aMatches[1] + " . $iItemCount . ")")) . ';'),
            	$sCartItems
			);
        else
            $sCartItems = empty($sCartItems) ? $sCartItem : $sCartItems . $this->_oModule->_oConfig->getDivider('descriptors') . $sCartItem;

        $this->_oModule->_oDb->setCartItems($iClientId, $sCartItems);

        $aInfo = $this->getInfo($iClientId);
        $iTotalQuantity = 0;
        foreach($aInfo as $aCart)
           $iTotalQuantity += $aCart['items_count'];

        return array(
        	'code' => 0, 
        	'message' => _t($this->_sLangsPrefix . 'msg_successfully_added'), 
        	'total_quantity' => $iTotalQuantity,
	        //TODO: Update account submenu if it's needed.  
        	'content' => '' //$this->_oModule->_oTemplate->displayToolbarSubmenu($aInfo)
        );
    }

    public function serviceDeleteFromCart($iVendorId, $iModuleId = 0, $iItemId = 0)
    {
        if($iVendorId == BX_PAYMENT_EMPTY_ID)
            return array('code' => 1, 'message' => _t($this->_sLangsPrefix . 'err_wrong_data'));

		$iClientId = $this->_oModule->getProfileId();
        if(empty($iClientId))
            return array('code' => 2, 'message' => _t($this->_sLangsPrefix . 'err_required_login'));

        if(!empty($iModuleId) && !empty($iItemId))
            $sPattern = "'" . $iVendorId . "_" . $iModuleId . "_" . $iItemId . "_[0-9]+:?'";
        else
            $sPattern = "'" . $iVendorId . "_[0-9]+_[0-9]+_[0-9]+:?'";

        $sCartItems = $this->_oModule->_oDb->getCartItems($iClientId);
        $sCartItems = trim(preg_replace($sPattern, "", $sCartItems), ":");
        $this->_oModule->_oDb->setCartItems($iClientId, $sCartItems);

        return array('code' => 0, 'message' => _t($this->_sLangsPrefix . 'inf_successfully_deleted'));
    }

    public function getInfo($iUserId, $iVendorId = BX_PAYMENT_EMPTY_ID, $aItems = array())
    {
        if($iVendorId != BX_PAYMENT_EMPTY_ID && !empty($aItems))
            return $this->_getInfo($iUserId, $iVendorId, $this->_oModule->_oConfig->descriptorsM2A($aItems));

        $aContent = $this->_parseByVendor($iUserId);

        if($iVendorId != BX_PAYMENT_EMPTY_ID)
            return isset($aContent[$iVendorId]) ? $this->_getInfo($iUserId, $iVendorId, $aContent[$iVendorId]) : array();

        $aResult = array();
        foreach($aContent as $iVendorId => $aVendorItems)
            $aResult[$iVendorId] = $this->_getInfo($iUserId, $iVendorId, $aVendorItems);

        return $aResult;
    }

    public function updateInfo($mixedPending)
    {
    	$aPending = is_array($mixedPending) ? $mixedPending : $this->_oModule->_oDb->getOrderPending(array('type' => 'id', 'id' => (int)$mixedPending));
    	if(empty($aPending) || !is_array($aPending))
    		return false;

		if((int)$aPending['processed'] == 1)
			return true;

		$iClientId = (int)$aPending['client_id'];
		$sOrderId = $this->_oModule->_oConfig->getLicense();

        $sCartItems = $this->_oModule->_oDb->getCartItems($iClientId);
        $aItems = $this->_oModule->_oConfig->descriptorsM2A($aPending['items']);

        foreach($aItems as $aItem) {
            $aItemInfo = $this->_oModule->callRegisterCartItem((int)$aItem['module_id'], array($aPending['client_id'], $aPending['seller_id'], $aItem['item_id'], $aItem['item_count'], $sOrderId));
            if(!is_array($aItemInfo) || empty($aItemInfo))
                continue;

            $this->_oModule->_oDb->insertOrderProcessed(array(
                'pending_id' => $aPending['id'],
                'order_id' => $sOrderId,
                'client_id' => $aPending['client_id'],
                'seller_id' => $aPending['seller_id'],
                'module_id' => $aItem['module_id'],
                'item_id' => $aItem['item_id'],
                'item_count' => $aItem['item_count'],
                'amount' => $aItemInfo['price'] * $aItem['item_count'],
            ));

            $sCartItems = trim(preg_replace("'" . $this->_oModule->_oConfig->descriptorA2S($aItem) . ":?'", "", $sCartItems), ":");
        }

		$this->_oModule->_oDb->setCartItems($iClientId, $sCartItems);
        return $this->_oModule->_oDb->updateOrderPending($aPending['id'], array('processed' => 1));
    }

    /**
     * Enter description here...
     *
     * @param  integer $iClientId client's ID
     * @param  integer $iVendorId vendor's ID
     * @param  array   $aItems    item descriptors(quaternions) from shopping cart.
     * @return array   with full info about vendor and items.
     */
    protected function _getInfo($iClientId, $iVendorId, $aItems)
    {
        $iItemsCount = 0;
        $fItemsPrice = 0;
        $aItemsInfo = array();
        foreach($aItems as $aItem) {
            $aItemInfo = $this->_oModule->callGetCartItem((int)$aItem['module_id'], array($aItem['item_id']));
            $aItemInfo['module_id'] = (int)$aItem['module_id'];
            $aItemInfo['quantity'] = (int)$aItem['item_count'];

            $iItemsCount += $aItem['item_count'];
            $fItemsPrice += $aItem['item_count'] * $aItemInfo['price'];
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
