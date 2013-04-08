<?

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

class BxPmtCart {
    var $_oDb;
    var $_oConfig;
    var $_oTemplate;

    /*
     * Constructor.
     */
    function BxPmtCart(&$oDb, &$oConfig, &$oTemplate) {
        $this->_oDb = &$oDb;
        $this->_oConfig = &$oConfig;
        $this->_oTemplate = &$oTemplate;
    }
    function getHistoryBlock($iUserId, $iSellerId) {
        return $this->_oTemplate->displayHistoryBlock($iUserId, $iSellerId);
    }
    function getCartJs($bWrapped = true) {
        return $this->_oTemplate->displayCartJs($bWrapped);
    }
    function getAddToCartJs($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect = false, $bWrapped = true) {
        return $this->_oTemplate->displayAddToCartJs($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect, $bWrapped);
    }
    function getAddToCartLink($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect = false) {
        return $this->_oTemplate->displayAddToCartLink($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect);
    }
    function addToCart($iClientId, $iVendorId, $iModuleId, $iItemId, $iItemCount) {
        if($iVendorId == BX_PMT_EMPTY_ID || empty($iModuleId) || empty($iItemId) || empty($iItemCount))
            return array('code' => 1, 'message' => _t('_payment_err_wrong_data'));

        if(empty($iClientId))
            return array('code' => 2, 'message' => _t('_payment_err_required_login'));

        if($iClientId == $iVendorId)
            return array('code' => 3, 'message' => _t('_payment_err_purchase_from_yourself'));

        $aVendor = $this->_oDb->getVendorInfoProfile($iVendorId);
        if($aVendor['status'] != 'Active')
            return array('code' => 4, 'message' => _t('_payment_err_inactive_vendor'));

        $aVendorProviders = $this->_oDb->getVendorInfoProviders($iVendorId);
        if(empty($aVendorProviders))
            return array('code' => 5, 'message' => _t('_payment_err_not_accept_payments'));

        $sCartItem = $iVendorId . '_' . $iModuleId . "_" . $iItemId . "_" . $iItemCount;
        $sCartItems = $this->_oDb->getCartItems($iClientId);

        if(strpos($sCartItems, $iVendorId . "_" . $iModuleId . "_" . $iItemId . "_") !== false)
            $sCartItems = preg_replace("'" . $iVendorId . "_" . $iModuleId . "_" . $iItemId . "_([0-9])+'e", "'" . $iVendorId . "_" . $iModuleId . "_" . $iItemId . "_' . (\\1 + " . $iItemCount . ")",  $sCartItems);
        else
            $sCartItems = empty($sCartItems) ? $sCartItem : $sCartItems . ":" . $sCartItem;

        $this->_oDb->setCartItems($iClientId, $sCartItems);

        $aInfo = $this->getInfo($iClientId);
        $iTotalQuantity = 0;
        foreach($aInfo as $aCart)
           $iTotalQuantity += $aCart['items_count'];

        return array('code' => 0, 'message' => _t('_payment_inf_successfully_added'), 'total_quantity' => $iTotalQuantity, 'content' => $this->_oTemplate->displayToolbarSubmenu($aInfo));
    }
    function deleteFromCart($iClientId, $iVendorId, $iModuleId = 0, $iItemId = 0) {
        if($iVendorId == BX_PMT_EMPTY_ID)
            return array('code' => 1, 'message' => _t('_payment_err_wrong_data'));

        if(empty($iClientId))
            return array('code' => 2, 'message' => _t('_payment_err_required_login'));

        if(!empty($iModuleId) && !empty($iItemId))
            $sPattern = "'" . $iVendorId . "_" . $iModuleId . "_" . $iItemId . "_[0-9]+:?'";
        else
            $sPattern = "'" . $iVendorId . "_[0-9]+_[0-9]+_[0-9]+:?'";

        $sCartItems = $this->_oDb->getCartItems($iClientId);
        $sCartItems = trim(preg_replace($sPattern, "", $sCartItems), ":");
        $this->_oDb->setCartItems($iClientId, $sCartItems);

        return array('code' => 0, 'message' => _t('_payment_inf_successfully_deleted'));
    }
    function getInfo($iUserId, $iVendorId = BX_PMT_EMPTY_ID, $aItems = array()) {
        if($iVendorId != BX_PMT_EMPTY_ID && !empty($aItems))
            return $this->_getInfo($iUserId, $iVendorId, $this->items2array($aItems));

        $aContent = $this->parseByVendor($iUserId);

        if($iVendorId != BX_PMT_EMPTY_ID)
            return isset($aContent[$iVendorId]) ? $this->_getInfo($iUserId, $iVendorId, $aContent[$iVendorId]) : array();

        $aResult = array();
        foreach($aContent as $iVendorId => $aVendorItems)
            $aResult[$iVendorId] = $this->_getInfo($iUserId, $iVendorId, $aVendorItems);

        return $aResult;
    }
    function updateInfo($iPendingId) {
        $aPending = $this->_oDb->getPending(array('type' => 'id', 'id' => $iPendingId));

        $sCartItems = $this->_oDb->getCartItems((int)$aPending['client_id']);

        $sOrderId = $this->_getLicense();
        $aItems = $this->items2array($aPending['items']);
        foreach($aItems as $aItem) {
            $aItemInfo = BxDolService::call((int)$aItem['module_id'], 'register_cart_item', array($aPending['client_id'], $aPending['seller_id'], $aItem['item_id'], $aItem['item_count'], $sOrderId));
            if(!is_array($aItemInfo) || empty($aItemInfo))
                continue;

            $this->_oDb->insertTransaction(array(
                'pending_id' => $aPending['id'],
                'order_id' => $sOrderId,
                'client_id' => $aPending['client_id'],
                'seller_id' => $aPending['seller_id'],
                'module_id' => $aItem['module_id'],
                'item_id' => $aItem['item_id'],
                'item_count' => $aItem['item_count'],
                'amount' => $aItemInfo['price'] * $aItem['item_count'],
            ));

            $sCartItems = trim(preg_replace("'" . implode('_', $aItem) . ":?'", "", $sCartItems), ":");
        }
        $this->_oDb->setCartItems((int)$aPending['client_id'], $sCartItems);
    }
    function parseByVendor($iUserId){
        $sItems = $this->_oDb->getCartItems($iUserId);
        return $this->_reparseBy($this->items2array($sItems), 'vendor_id');
    }
    function parseByModule($iUserId){
        $sItems = $this->_oDb->getCartItems($iUserId);
        return $this->_reparseBy($this->items2array($sItems), 'module_id');
    }
    function _reparseBy($aItems, $sKey) {
        $aResult = array();
        foreach($aItems as $aItem)
            if(isset($aItem[$sKey]))
                $aResult[$aItem[$sKey]][] = $aItem;

        return $aResult;
    }
    /**
     * Enter description here...
     *
     * @param integer $iClientId client's ID
     * @param integer $iVendorId vendor's ID
     * @param array $aItems item descriptors(quaternions) from shopping cart.
     * @return array with full info about vendor and items.
     */
    function _getInfo($iClientId, $iVendorId, $aItems) {
        $iItemsCount = 0;
        $fItemsPrice = 0;
        $aItemsInfo = array();
        foreach($aItems as $aItem) {
            $aItemInfo = BxDolService::call((int)$aItem['module_id'], 'get_cart_item', array($iClientId, $aItem['item_id']));
            $aItemInfo['module_id'] = (int)$aItem['module_id'];
            $aItemInfo['quantity'] = (int)$aItem['item_count'];

            $iItemsCount += $aItem['item_count'];
            $fItemsPrice += $aItem['item_count'] * $aItemInfo['price'];
            $aItemsInfo[] = $aItemInfo;
        }

        $aVendor = $this->_oDb->getVendorInfoProfile((int)$iVendorId);
        return array(
            'vendor_id' => $aVendor['id'],
            'vendor_username' => $aVendor['username'],
            'vendor_profile_url' => $aVendor['profile_url'],
            'vendor_currency_code' => $aVendor['currency_code'],
            'vendor_currency_sign' => $aVendor['currency_sign'],
            'items_count' => $iItemsCount,
            'items_price' => $fItemsPrice,
            'items' => $aItemsInfo
        );
    }
    function _getLicense() {
        list($fMilliSec, $iSec) = explode(' ', microtime());
        $fSeed = (float)$iSec + ((float)$fMilliSec * 100000);
        srand($fSeed);

        $sResult = '';
        for($i=0; $i < 16; ++$i) {
            switch(rand(1,2)) {
                case 1:
                    $c = chr(rand(ord('A'),ord('Z')));
                    break;
                case 2:
                    $c = chr(rand(ord('0'),ord('9')));
                    break;
            }
            $sResult .= $c;
        }
        return $sResult;
    }

    /**
     * Static method.
     * Conver items to array with necessary structure.
     *
     * @param string/array $mixed - string with cart items divided with (:) or an array of cart items.
     * @return array with items.
     */
    function items2array($mixed) {
        $aResult = array();

        if(is_string($mixed))
           $aItems = explode(':', $mixed);
        else if(is_array($mixed))
           $aItems = $mixed;
        else
            $aItems = array();

        foreach($aItems as $sItem) {
            $aItem = explode('_', $sItem);
            $aResult[] = array('vendor_id' => $aItem[0], 'module_id' => $aItem[1], 'item_id' => $aItem[2], 'item_count' => $aItem[3]);
        }

        return $aResult;
    }
}
?>