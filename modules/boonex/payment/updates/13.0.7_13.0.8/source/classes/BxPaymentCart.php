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
    
    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_carts get_block_carts
     * 
     * @code bx_srv('bx_payment', 'get_block_carts', [...], 'Cart'); @endcode
     * 
     * Get page block with shopping carts by vendors.
     *
     * @return an array describing a block to display on the site or an empty string if something is wrong. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentCart::serviceGetBlockCarts
     */
    /** 
     * @ref bx_payment-get_block_carts "get_block_carts"
     */
    public function serviceGetBlockCarts()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']));

        if($this->_oModule->_oConfig->isSingleSeller())
            return MsgBox(_t($CNF['T']['MSG_SINGLE_SELLER_MODE'], $this->serviceGetCartUrl()));

    	if(bx_get('seller_id') !== false)
            return '';

        return array(
            'content' => $this->_oModule->_oTemplate->displayBlockCarts($iUserId)
        );
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_cart get_block_cart
     * 
     * @code bx_srv('bx_payment', 'get_block_cart', [...], 'Cart'); @endcode
     * 
     * Get page block with content of selected shopping cart.
     *
     * @return an array describing a block to display on the site or an empty string if something is wrong. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentCart::serviceGetBlockCart
     */
    /** 
     * @ref bx_payment-get_block_cart "get_block_cart"
     */
    public function serviceGetBlockCart()
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

    	if(!$this->_bSingleSeller && bx_get('seller_id') === false)
            return '';

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return array(
            	'content' => MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']))
            );

    	$iSellerId = !$this->_bSingleSeller ? bx_process_input(bx_get('seller_id'), BX_DATA_INT) : $this->_oModule->_oConfig->getSiteAdmin();
    	if(empty($iSellerId))
            return array(
                'content' => MsgBox(_t($CNF['T']['ERR_UNKNOWN_VENDOR']))
            );

        $aSeller = $this->_oModule->getProfileInfo($iSellerId);
        return array(
            'title' => _t($CNF['T']['BLOCK_TITLE_CART'], $aSeller['name']),
            'content' => $this->_oModule->_oTemplate->displayBlockCart($iUserId, $iSellerId)
        );
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_cart_history get_block_cart_history
     * 
     * @code bx_srv('bx_payment', 'get_block_cart_history', [...], 'Cart'); @endcode
     * 
     * Get page block with shopping cart history.
     *
     * @return an array describing a block to display on the site or an empty string if something is wrong. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentCart::serviceGetBlockCartHistory
     */
    /** 
     * @ref bx_payment-get_block_cart_history "get_block_cart_history"
     */
    public function serviceGetBlockCartHistory()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

		$iSellerId = bx_get('vendor') !== false ? (int)bx_get('vendor') : 0;

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return array(
            	'content' => MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']))
            );

        return array(
        	'content' => $this->_oModule->_oTemplate->displayBlockHistory($iUserId, $iSellerId),
		);
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-purchase_processing Purchase Processing
     * @subsubsection bx_payment-add_to_cart add_to_cart
     * 
     * @code bx_srv('bx_payment', 'add_to_cart', [...], 'Cart'); @endcode
     * 
     * Add an item described with method's params to shopping cart.
     *
     * @param $iSellerId integer value with seller ID.
     * @param $mixedModuleId mixed value (ID, Name or URI) determining a module from which the action was initiated.
     * @param $iItemId integer value with item ID.
     * @param $iItemCount integer value with a number of items for purchasing.
     * @param $aCustom array with custom data.
     * @return an array with special format which describes the result of operation.
     * 
     * @see BxPaymentCart::serviceAddToCart
     */
    /** 
     * @ref bx_payment-add_to_cart "add_to_cart"
     */
    public function serviceAddToCart($iSellerId, $mixedModuleId, $iItemId, $iItemCount, $aCustom = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$iModuleId = $this->_oModule->_oConfig->getModuleId($mixedModuleId);
    	$iClientId = $this->_oModule->getProfileId();

    	$mixedResult = $this->_oModule->checkData($iClientId, $iSellerId, $iModuleId, $iItemId, $iItemCount, $aCustom);
    	if($mixedResult !== true)
    		return $mixedResult;

        $aSellerProviders = $this->_oModule->_oDb->getVendorInfoProvidersSingle($iSellerId);
        if(empty($aSellerProviders))
            return array('code' => 5, 'message' => _t($CNF['T']['ERR_NOT_ACCEPT_PAYMENTS']));

        $aCart = $this->_oModule->_oDb->getCartContent($iClientId);
        $sCartItems = !empty($aCart['items']) ? $aCart['items'] : '';

        $sCartItemsResult = false;
        $this->_oModule->alert('before_add_to_cart', 0, $iClientId, [
            'client_id' => $iClientId,
            'seller_id' => $iSellerId, 
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
            'item_count' => $iItemCount,

            'cart' => &$aCart,
            'cart_items' => &$sCartItems,
            'override_result' => &$sCartItemsResult,
        ]);

        $sCiDsc = $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId));
        if($sCartItemsResult === false) {            
        if(strpos($sCartItems, $sCiDsc) !== false)
                $sCartItemsResult = preg_replace_callback(
                "/" . $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId, '([0-9]+)')) . "/", function($aMatches) use($iSellerId, $iModuleId, $iItemId, $iItemCount) {
                    return $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId, $aMatches[1] + $iItemCount));
                },
            	$sCartItems
            );
        else {
            $sCartItem = $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId, $iItemCount));
                $sCartItemsResult = empty($sCartItems) ? $sCartItem : $sCartItems . $this->_oModule->_oConfig->getDivider('DIVIDER_DESCRIPTORS') . $sCartItem;
            }
        }

        $aCartCustom = array();
        if(!empty($aCart['customs']))
            $aCartCustom = unserialize($aCart['customs']);

        if(!empty($aCustom) && is_array($aCustom))
            $aCartCustom[$sCiDsc] = !empty($aCartCustom[$sCiDsc]) && is_array($aCartCustom[$sCiDsc]) ? array_merge($aCartCustom[$sCiDsc], $aCustom) : $aCustom;

        $this->_oModule->_oDb->setCartItems($iClientId, $sCartItemsResult, $aCartCustom);

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

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-purchase_processing Purchase Processing
     * @subsubsection bx_payment-delete_from_cart delete_from_cart
     * 
     * @code bx_srv('bx_payment', 'delete_from_cart', [...], 'Cart'); @endcode
     * 
     * Delete an item(s) from shopping cart.
     *
     * @param $iSellerId integer value with seller ID. The items owned by this seller will be removed only.
     * @param $iModuleId (optional) integer value with module ID. If specified, the items related to this module will be removed only.
     * @param $iItemId (optional) integer value with item ID. If specified, the item with this ID will be removed only.
     * @return an array with special format which describes the result of operation.
     * 
     * @see BxPaymentCart::serviceDeleteFromCart
     */
    /** 
     * @ref bx_payment-delete_from_cart "delete_from_cart"
     */
    public function serviceDeleteFromCart($iSellerId, $iModuleId = 0, $iItemId = 0)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        if($iSellerId == BX_PAYMENT_EMPTY_ID)
            return array('code' => 1, 'message' => _t($CNF['T']['ERR_WRONG_DATA']));

		$iClientId = $this->_oModule->getProfileId();
        if(empty($iClientId))
            return array('code' => 2, 'message' => _t($CNF['T']['ERR_REQUIRED_LOGIN']));

        $aCart = $this->_oModule->_oDb->getCartContent($iClientId);

        $aCartCustom = array();
        if(!empty($aCart['customs']))
            $aCartCustom = unserialize($aCart['customs']);

        if(!empty($iModuleId) && !empty($iItemId)) {
            $sCiDsc = $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId));
            if(!empty($aCartCustom[$sCiDsc]))
                unset($aCartCustom[$sCiDsc]);
        }
        else {
            $aCiDscs = array_keys($aCartCustom);
            foreach($aCiDscs as $sCiDsc)
                if(strpos($sCiDsc, $iSellerId . '_') === 0)
                    unset($aCartCustom[$sCiDsc]);

            $iModuleId = $iItemId = '[0-9\-]+';
        }

        $aCart['items'] = trim(preg_replace("'" . $this->_oModule->_oConfig->descriptorA2S(array($iSellerId, $iModuleId, $iItemId, '[0-9]+:?')) . "'", "", $aCart['items']), ":");
        $this->_oModule->_oDb->setCartItems($iClientId, $aCart['items'], $aCartCustom);

        $this->_oModule->alert('delete_from_cart', 0, 0, array(
            'seller_id' => $iSellerId,
            'module_id' => $iModuleId,
            'item_id' => $iItemId,
        ));
        
        return array('code' => 0, 'message' => _t($CNF['T']['MSG_ITEM_DELETED']));
    }

    public function getInfo($sType, $iUserId, $iSellerId = BX_PAYMENT_EMPTY_ID, $aItems = array())
    {
        if($iSellerId != BX_PAYMENT_EMPTY_ID && !empty($aItems))
            return $this->_getInfo($sType, $iUserId, $iSellerId, $this->_oModule->_oConfig->descriptorsM2A($aItems));

        $aContent = $this->_parseByVendor($iUserId);
        if($iSellerId != BX_PAYMENT_EMPTY_ID)
            return $this->_getInfo($sType, $iUserId, $iSellerId, (isset($aContent[$iSellerId]) ? $aContent[$iSellerId] : []));

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
            $aCustom = isset($aItem['custom']) ? $aItem['custom'] : [];

            //--- Get item main info
            $aItemInfo = $this->_oModule->callGetCartItem((int)$aItem['module_id'], array($aItem['item_id'], $iClientId, $aCustom));
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

            //--- Get item addons' info
            $aItemAddons = array();
            if(!empty($aItem['item_addons'])) {
                $aAddons = $this->_oModule->_oConfig->s2a($aItem['item_addons']);
                foreach($aAddons as $sAddon) {
                    if(isset($aItemAddons[$sAddon])) {
                        $aItemAddons[$sAddon]['quantity'] += 1;
                        continue;
                    }

                    $aAddonInfo = $this->_oModule->callGetCartItem((int)$aItem['module_id'], array($sAddon, $iClientId, $aCustom));
                    if(empty($aAddonInfo) || !is_array($aAddonInfo))
                        continue;

                    $aAddonInfo['module_id'] = (int)$aItem['module_id'];
                    $aAddonInfo['quantity'] = 1;

                    $aItemAddons[$sAddon] = $aAddonInfo;
                }
            }

            $aItemInfo['addons'] = $aItemAddons;
            $aItemsInfo[] = $aItemInfo;

            //--- Update items' summary
            $fAddonsPrice = 0;
            foreach($aItemInfo['addons'] as $aAddonInfo)
                $fAddonsPrice += $aAddonInfo['quantity'] * $this->_oModule->_oConfig->getPrice($sType, $aAddonInfo);

            $iItemsCount += $aItemInfo['quantity'];
            $fItemsPrice += $aItemInfo['quantity'] * ($this->_oModule->_oConfig->getPrice($sType, $aItemInfo) + $fAddonsPrice);
        }

        $aSeller = $this->_oModule->getVendorInfo((int)$iSellerId);
        $aResult = array(
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

        $this->_oModule->alert('get_cart_info', 0, 0, array(
           'type' => $sType,
           'client_id' => $iClientId,
           'seller_id' => $iSellerId,
           'items' => $aItems,
           'override_result' => &$aResult
        ));

        return $aResult;
    }
}

/** @} */
