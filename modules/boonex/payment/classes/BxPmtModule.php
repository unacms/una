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

bx_import('BxDolModule');
bx_import('BxDolAdminSettings');

require_once( BX_DIRECTORY_PATH_PLUGINS . 'Services_JSON.php' );

require_once('BxPmtCart.php');
require_once('BxPmtDetails.php');
require_once('BxPmtOrders.php');

define('BX_PMT_ORDERS_TYPE_PENDING', 'pending');
define('BX_PMT_ORDERS_TYPE_PROCESSED', 'processed');
define('BX_PMT_ORDERS_TYPE_HISTORY', 'history');

define('BX_PMT_EMPTY_ID', -1);
define('BX_PMT_ADMINISTRATOR_ID', 0);
define('BX_PMT_ADMINISTRATOR_USERNAME', 'administrator');

/**
 * Payment module by BoonEx
 *
 * This module is needed to work with payment providers and organize the process
 * of some item purchasing. Shopping Cart and Orders Manager are included.
 *
 * Integration notes:
 * To integrate your module with this one, you need:
 * 1. Get 'Add To Cart' button using serviceGetAddToCartLink service.
 * 2. Add info about your module in the 'bx_pmt_modules' table.
 * 3. Realize the following service methods in your Module class.
 *   a. serviceGetItems($iVendorId) - Is used in Orders Administration to get all products of the requested seller(vendor).
 *   b. serviceGetCartItem($iClientId, $iItemId) - Is used in Shopping Cart to get one product by specified id.
 *   c. serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrderId) - Register purchased product.
 *   d. serviceUnregisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrderId) - Unregister the product purchased earlier.
 * @see You may see an example of integration in Membership module.
 *
 *
 * Profile's Wall:
 * no spy events
 *
 *
 *
 * Spy:
 * no spy events
 *
 *
 *
 * Memberships/ACL:
 * Doesn't depend on user's membership.
 *
 *
 *
 * Service methods:
 *
 * Is used to get "Add to cart" link for some item(s) in your module.
 * @see BxPmtModule::serviceGetAddToCartLink
 * BxDolService::call('payment', 'get_add_to_cart_link', array($iVendorId, $mixedModuleId, $iItemId, $iItemCount));
 *
 * Check transaction(s) in database which satisty all conditions.
 * @see BxPmtModule::serviceGetTransactionsInfo
 * BxDolService::call('payment', 'get_transactions_info', array($aConditions));
 *
 * Get total count of items in Shopping Cart.
 * @see BxPmtModule::serviceGetCartItemCount
 * BxDolService::call('payment', 'get_cart_item_count', array($iUserId, $iOldCount));
 * @note is needed for internal usage(integration with member tool bar).
 *
 * Get Shopping cart content.
 * @see BxPmtModule::serviceGetCartItems
 * BxDolService::call('payment', 'get_cart_items');
 * @note is needed for internal usage(integration with member tool bar).
 *
 *
 * Alerts:
 * no alerts available
 *
 */
class BxPmtModule extends BxDolModule {
    var $_iUserId;
    var $_oDetails;
    var $_oCart;
    var $_oOrders;
    var $_aOrderTypes;

    /**
     * Constructor
     */
    function BxPmtModule($aModule) {
        parent::BxDolModule($aModule);
        $this->_oConfig->init($this->_oDb);

        $this->_iUserId = $this->getUserId();
        $this->_oDetails = new BxPmtDetails($this->_oDb, $this->_oConfig);
        $this->_oCart = new BxPmtCart($this->_oDb, $this->_oConfig, $this->_oTemplate);
        $this->_oOrders = new BxPmtOrders($this->_iUserId, $this->_oDb, $this->_oConfig, $this->_oTemplate);
        $this->_aOrderTypes = array(BX_PMT_ORDERS_TYPE_PENDING, BX_PMT_ORDERS_TYPE_PROCESSED, BX_PMT_ORDERS_TYPE_HISTORY);
    }



    /**
     *
     * Public Methods of Common Usage
     *
     */
    function getExtraJs($sType) {
        $sResult = "";
        switch($sType) {
            case 'orders':
                $sResult = $this->_oOrders->getExtraJs();
                break;
        }
        return $sResult;
    }



    /**
     *
     * Manage Orders Methods
     *
     */
    function getMoreWindow() {
        return $this->_oOrders->getMoreWindow();
    }
    function getManualOrderWindow() {
        return $this->_oOrders->getManualOrderWindow();
    }
    function getOrdersBlock($sType, $iUserId = BX_PMT_EMPTY_ID) {
        if(!$this->isLogged())
            return MsgBox(_t('_payment_err_required_login'));

        $sBaseUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri();

        $sJsObject = $this->_oConfig->getJsObject('orders');
        $aTopMenu = array(
            'pmt-orders-processed-lnk' => array('href' => $sBaseUrl . 'orders/processed/', 'title' => _t('_payment_btn_orders_processed'), 'active' => $sType == BX_PMT_ORDERS_TYPE_PROCESSED ? 1 : 0),
            'pmt-orders-pending-lnk' => array('href' => $sBaseUrl . 'orders/pending/', 'title' => _t('_payment_btn_orders_pending'), 'active' => $sType == BX_PMT_ORDERS_TYPE_PENDING ? 1 : 0),
            'pmt-payment-settings-lnk' => array('href' =>  $sBaseUrl . 'details/', 'title' => _t('_payment_btn_settings'))
        );

        $sTitle = $sType == 'processed' ? '_payment_bcaption_processed_orders' : '_payment_bcaption_pending_orders';
        return array($this->_oOrders->getOrdersBlock($sType, $iUserId), $aTopMenu, array(), _t($sTitle), 'getBlockCaptionMenu');
    }
    function actionGetItems($iModuleId) {
        $aItems = BxDolService::call((int)$iModuleId, 'get_items', array($this->_iUserId));
        if(is_array($aItems) && !empty($aItems))
            $aResult = array('code' => 0, 'message' => '', 'data' => $this->_oTemplate->displayItems($aItems));
        else
            $aResult = array('code' => 1, 'message' => MsgBox(_t('_payment_txt_empty')));


        header('Content-Type:text/javascript');
        $oJson = new Services_JSON();
        return $oJson->encode($aResult);
    }
    function actionGetOrder() {
        $aData = &$_POST;

        header('Content-Type:text/javascript');
        $oJson = new Services_JSON();

        if(!isset($aData['type']) || !in_array($aData['type'], $this->_aOrderTypes))
           return $oJson->encode(array('code' => 1, 'message' => '_payment_err_wrong_data'));

        $iId = 0;
        if(isset($aData['id']))
            $iId = (int)$aData['id'];

        $sData = $this->_oOrders->getOrder($aData['type'], $iId);
        return $oJson->encode(array('code' => 0, 'message' => '', 'data' => $sData));
    }
    function actionGetOrders() {
        $aData = &$_POST;

        header('Content-Type:text/javascript');
        $oJson = new Services_JSON();

        if(!isset($aData['type']) || !in_array($aData['type'], $this->_aOrderTypes))
           return $oJson->encode(array('code' => 1, 'message' => '_payment_err_wrong_data'));

        $iStart = 0;
        if(isset($aData['start']))
            $iStart = (int)$aData['start'];

        $iPerPage = 0;
        if(isset($aData['per_page']))
            $iPerPage = (int)$aData['per_page'];

        $sFilter = "";
        if(isset($aData['filter']))
            $sFilter = $aData['filter'];

        if($aData['type'] == BX_PMT_ORDERS_TYPE_HISTORY)
            $aParams = array('user_id' => (int)$this->_iUserId, 'seller_id' => (int)$aData['seller_id']);
        else
            $aParams = array('seller_id' => (int)$this->_iUserId);
        $aParams = array_merge($aParams, array('start' => $iStart, 'per_page' => $iPerPage, 'filter' => $sFilter));

        $sData = $this->_oOrders->getOrders($aData['type'], $aParams);
        return $oJson->encode(array('code' => 0, 'message' => '', 'data' => $sData));
    }
    function actionManualOrderSubmit() {
        $aResult = array(
            'js_object' => $this->_oConfig->getJsObject('orders'),
            'parent_id' => 'pmt-mo-content'
        );

        if(!$this->isLogged())
            return $this->_onResultInline(array_merge($aResult, array('code' => 1, 'message' => '_payment_err_required_login')));

        $mixedResult = $this->_oOrders->addManualOrder($_POST);
        if(is_array($mixedResult))
            return $this->_onResultInline(array_merge($aResult, $mixedResult));

        $this->_oCart->updateInfo((int)$mixedResult);
        return $this->_onResultInline(array_merge($aResult, array('code' => 0, 'message' => '')));
    }
    function actionOrdersSubmit($sType) {
        $aResult = array(
            'js_object' => $this->_oConfig->getJsObject('orders'),
            'parent_id' => 'pmt-form-' . $sType
        );

        if(!$this->isLogged())
            return $this->_onResultInline(array_merge($aResult, array('code' => 1, 'message' => '_payment_err_required_login')));

        $aData = &$_POST;

        if(!isset($aData['orders']) || !is_array($aData['orders']) || empty($aData['orders']))
            return $this->_onResultInline(array_merge($aResult, array('code' => 2, 'message' => '_payment_err_nothing_selected')));

        $mixedResult = true;
        $sType = $aData['type'];
        if(isset($aData['pmt-report']) && !empty($aData['pmt-report']))
            $mixedResult = $this->_oOrders->report($sType, $aData['orders']);
        else if(isset($aData['pmt-cancel']) && !empty($aData['pmt-cancel']))
            $mixedResult = $this->_oOrders->cancel($sType, $aData['orders']);
        else if(isset($aData['pmt-process']) && !empty($aData['pmt-process']) && $sType == BX_PMT_ORDERS_TYPE_PENDING)
            foreach($aData['orders'] as $iOrderId) {
                $sKey = 'order-data-' . $iOrderId;
                if(!isset($aData[$sKey]) || empty($aData[$sKey])) {
                    $mixedResult = array('code' => 4, 'message' => '_payment_err_empty_orders');
                    break;
                }
                $this->_oDb->updatePending($iOrderId, array(
                    'order' => $aData[$sKey],
                    'error_code' => 0,
                    'error_msg' => 'Manually processed'
                ));
                $this->_oCart->updateInfo($iOrderId);
            }
        else
            $mixedResult = array('code' => 3, 'message' => '_payment_err_unknown');

        if(is_array($mixedResult))
            return $this->_onResultInline(array_merge($aResult, $mixedResult));

        return $this->_onResultInline(array_merge($aResult, array('code' => 0, 'message' => '')));
    }



    /**
     *
     * Payment Details Methods
     *
     */
    function getDetailsForm($iUserId = -1) {
        if(!$this->isLogged())
            return MsgBox(_t('_payment_err_required_login'));

        $sBaseUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri();

        $sResult = $this->_oDetails->getForm(is_numeric($iUserId) && $iUserId != -1 ? $iUserId : $this->_iUserId);

        $aTopMenu = array(
            'pmt-orders-processed-lnk' => array('href' => $sBaseUrl . 'orders/processed/', 'title' => _t('_payment_btn_orders_processed')),
            'pmt-orders-pending-lnk' => array('href' => $sBaseUrl . 'orders/pending/', 'title' => _t('_payment_btn_orders_pending')),
            'pmt-payment-settings-lnk' => array('href' =>  $sBaseUrl . 'details/', 'title' => _t('_payment_btn_settings'), 'active' => 1)
        );

        return array((!empty($sResult) ? $sResult : MsgBox(_t('_payment_msg_no_results'))), $aTopMenu, array(), true, 'getBlockCaptionMenu');
    }
    function serviceGetCurrencyInfo() {
        return array(
            'sign' => $this->_oConfig->getCurrencySign(),
            'code' => $this->_oConfig->getCurrencyCode()
        );
    }

    /**
     *
     * Admin Settings Methods
     *
     */
    function getSettingsForm($mixedResult) {
        $iId = (int)$this->_oDb->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name`='Payment'");
        if(empty($iId))
           return MsgBox('_payment_msg_no_results');

        $oSettings = new BxDolAdminSettings($iId);
        $sResult = $oSettings->getForm();

        if($mixedResult !== true && !empty($mixedResult))
            $sResult = $mixedResult . $sResult;

        return $sResult;
    }
    function setSettings($aData) {
        $iId = (int)$this->_oDb->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name`='Payment'");
        if(empty($iId))
           return MsgBox(_t('_payment_err_wrong_data'));

        $oSettings = new BxDolAdminSettings($iId);
        return $oSettings->saveChanges($_POST);
    }


    /**
     *
     * Cart Processing Methods
     *
     */
    function getCartHistory($iVendorId) {
        $aTopMenu = array(
            'pmt-cart' => array('href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'cart/', 'title' => _t('_payment_btn_cart')),
            'pmt-cart-history' => array('href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'history/' . ($iVendorId == BX_PMT_ADMINISTRATOR_ID ? 'site/' : ''), 'title' => _t('_payment_btn_history'), 'active' => 1)
        );

        $sResult = $this->_oCart->getHistoryBlock($this->_iUserId, $iVendorId);
        return array($sResult, $aTopMenu, array(), true, 'getBlockCaptionMenu');
    }
    function getCartContent($mixedVendor = null) {
        if(!$this->isLogged())
            return MsgBox(_t('_payment_err_required_login'));

        $iVendorId = BX_PMT_EMPTY_ID;
        if(is_string($mixedVendor))
            $iVendorId = $this->_oDb->getVendorId($mixedVendor);
        else if(is_int($mixedVendor))
            $iVendorId = $mixedVendor;

        $aCartInfo = $this->_oCart->getInfo($this->_iUserId, $iVendorId);
        if($iVendorId == BX_PMT_EMPTY_ID)
            unset($aCartInfo[$this->_oConfig->getAdminId()]);

        $aTopMenu = array(
            'pmt-cart' => array('href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'cart/', 'title' => _t('_payment_btn_cart'), 'active' => 1),
            'pmt-cart-history' => array('href' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'history/' . ($iVendorId == BX_PMT_ADMINISTRATOR_ID ? 'site/' : ''), 'title' => _t('_payment_btn_history'))
        );

        $sResult = !empty($aCartInfo) ? $this->_oTemplate->displayCartContent($aCartInfo, $iVendorId) : MsgBox(_t('_payment_txt_empty'));
        return array($sResult, $aTopMenu, array(), true, 'getBlockCaptionMenu');
    }
    function serviceGetCartJs($bWrapped = true) {
        return $this->_oCart->getCartJs($bWrapped);
    }
    function serviceGetAddToCartJs($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false, $bWrapped = true) {
        if(is_string($mixedModuleId)) {
            $aModuleInfo = $this->_oDb->getModuleByUri($mixedModuleId);
            $iModuleId = isset($aModuleInfo['id']) ? (int)$aModuleInfo['id'] : 0;
        }
        else
           $iModuleId = (int)$mixedModuleId;

        if(empty($iModuleId))
            return "";

        return $this->_oCart->getAddToCartJs($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect, $bWrapped);
    }
    function serviceGetAddToCartLink($iVendorId, $mixedModuleId, $iItemId, $iItemCount, $bNeedRedirect = false) {
        if(is_string($mixedModuleId)) {
            $aModuleInfo = $this->_oDb->getModuleByUri($mixedModuleId);
            $iModuleId = isset($aModuleInfo['id']) ? (int)$aModuleInfo['id'] : 0;
        }
        else
           $iModuleId = (int)$mixedModuleId;

        if(empty($iModuleId))
            return "";

        return $this->_oCart->getAddToCartLink($iVendorId, $iModuleId, $iItemId, $iItemCount, $bNeedRedirect);
    }
    function serviceGetCartItemCount($iUserId, $iOldCount = 0) {
        if(!$this->isLogged())
            return array('count' => 0, 'messages' => array());

        $aInfo = $this->_oCart->getInfo($this->_iUserId);

        $iCount = 0;
        foreach($aInfo as $iVendorId => $aVendorCart)
            $iCount += $aVendorCart['items_count'];

        $this->_oTemplate->addCss('toolbar.css');
        return array(
            'count' => $iCount,
            'messages' => array()
        );
    }
    function serviceGetCartItems() {
        if(!$this->isLogged())
            return MsgBox(_t('_payment_err_required_login'));

        $aInfo = $this->_oCart->getInfo($this->_iUserId);
        if(empty($aInfo))
            return MsgBox(_t('_payment_txt_empty'));

        return $this->_oTemplate->displayToolbarSubmenu($aInfo);
    }
    function actionCartSubmit() {
        $aData = &$_POST;

        if(isset($aData['pmt-delete']) && !empty($aData['items']))
            foreach($aData['items'] as $sItem) {
                list($iVendorId, $iModuleId, $iItemId, $iItemCount) = explode('_', $sItem);
                $this->_oCart->deleteFromCart($this->_iUserId, $iVendorId, $iModuleId, $iItemId);
            }
        else if(isset($aData['pmt-checkout']) && !empty($aData['items']))
            $this->initializeCheckout((int)$aData['vendor_id'], $aData['provider'], $aData['items']);

        header('Location: ' . BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'cart/');
        exit;
    }
    function actionAddToCart($iVendorId, $iModuleId, $iItemId, $iItemCount) {
        $aResult = $this->_oCart->addToCart($this->_iUserId, $iVendorId, $iModuleId, $iItemId, $iItemCount);

        header('Content-Type:text/javascript');
        $oJson = new Services_JSON();
        return $oJson->encode($aResult);
    }
    function serviceAddToCart($iVendorId, $iModuleId, $iItemId, $iItemCount) {
        return $this->_oCart->addToCart($this->_iUserId, $iVendorId, $iModuleId, $iItemId, $iItemCount);
    }
    /**
     * Isn't used yet.
     */
    function actionDeleteFromCart($iVendorId, $iModuleId, $iItemId) {
        $aResult = $this->_oCart->deleteFromCart($this->_iUserId, $iVendorId, $iModuleId, $iItemId);

        header('Content-Type:text/javascript');
        $oJson = new Services_JSON();
        return $oJson->encode($aResult);
    }
    /**
     * Isn't used yet.
     */
    function actionEmptyCart($iVendorId) {
        $aResult = $this->_oCart->deleteFromCart($this->_iUserId, $iVendorId);

        header('Content-Type:text/javascript');
        $oJson = new Services_JSON();
        return $oJson->encode($aResult);
    }



    /**
     *
     * Payment Processing Methods
     *
     */
    function initializeCheckout($iVendorId, $sProvider, $aItems = array()) {
        if(!$this->isLogged())
            return MsgBox(_t('_payment_err_required_login'));

        if($iVendorId == BX_PMT_EMPTY_ID)
            return MsgBox(_t('_payment_err_unknown_vendor'));

        $aProvider = $this->_oDb->getVendorInfoProviders($iVendorId, $sProvider);
        $sClassPath = $this->_oConfig->getClassPath() . $aProvider['class_name'] . '.php';
        if(empty($aProvider) || !file_exists($sClassPath))
            return MsgBox(_t('_payment_err_incorrect_provider'));

        require_once($sClassPath);
        $oProvider = new $aProvider['class_name']($this->_oDb, $this->_oConfig, $aProvider);

        $aInfo = $this->_oCart->getInfo($this->_iUserId, $iVendorId, $aItems);
        if(empty($aInfo) || $aInfo['vendor_id'] == BX_PMT_EMPTY_ID || empty($aInfo['items']))
            return MsgBox(_t('_payment_err_empty_order'));

        $iPendingId = $this->_oDb->insertPending($this->_iUserId, $aProvider['name'], $aInfo);
        if(empty($iPendingId))
            return MsgBox(_t('_payment_err_access_db'));

        return $oProvider->initializeCheckout($iPendingId, $aInfo);
    }
    function actionFinalizeCheckout($sProvider, $mixedVendorId = "") {
        $aData = &$_REQUEST;

        $aProvider = is_numeric($mixedVendorId) && (int)$mixedVendorId != BX_PMT_EMPTY_ID ? $this->_oDb->getVendorInfoProviders((int)$mixedVendorId, $sProvider) : $this->_oDb->getProviders($sProvider);
        $sClassPath = $this->_oConfig->getClassPath() . $aProvider['class_name'] . '.php';
        if(empty($aProvider) || !file_exists($sClassPath))
            return MsgBox(_t('_payment_err_incorrect_provider'));

        require_once($sClassPath);
        $oProvider = new $aProvider['class_name']($this->_oDb, $this->_oConfig, $aProvider);

        $aResult = $oProvider->finalizeCheckout($aData);
        if((int)$aResult['code'] == 1) {
            $this->_oCart->updateInfo((int)$aResult['pending_id']);

            if($oProvider->needRedirect()) {
                header('Location: ' . $this->_oConfig->getReturnUrl());
                exit;
            }
        }

        $this->_onResultPage($aResult);
        exit;
    }
    /**
     * Check transaction(s) in database which satisty all conditions.
     *
     * @param array $aConditions an array of pears('key' => 'value'). Available keys are the following:
     * a. order_id - internal order ID (string)
     * b. client_id - client's ID (integer)
     * c. seller_id - seller's ID (integer)
     * d. module_id - modules's where the purchased product is located. (integer)
     * e. item_id - item id in the database. (integer)
     * f. date - the date when the payment was processed(UNIXTIME STAMP)
     *
     * @return array of transactions. Each transaction has full info(client ID, seller ID, external transaction ID, date and so on)
     */
    function serviceGetTransactionsInfo($aConditions){
        return $this->_oDb->getProcessed(array('type' => 'mixed', 'conditions' => $aConditions));
    }



    /**
     *
     * Private Methods of Common Usage
     *
     */
    function _onResultAlert($aResult) {
        echo $this->_oTemplate->parseHtmlByTemplateName('on_result', array('message' => $aResult['message']));
    }
    function _onResultInline($aResult) {
        $oJson = new Services_JSON();

        return $this->_oTemplate->parseHtmlByTemplateName('on_result_inline', array(
            'js_object' => $aResult['js_object'],
            'params' => $oJson->encode(array(
                'code' => $aResult['code'],
                'message' => MsgBox(_t($aResult['message'])),
                'parent_id' => $aResult['parent_id']
            ))
        ));
    }
    function _onResultPage($aResult) {
    	bx_import('BxDolTemplate');
		$oTemplate = BxDolTemplate::getInstance();
		$oTemplate->setPageNameIndex(0);
		$oTemplate->setPageParams(array(
		    'header' => _t('_payment_pcaption_payment_result'),
		    'header_text' => _t('_payment_bcaption_payment_result'),
		));
		$oTemplate->setPageContent('page_main_code', MsgBox($aResult['message']));
        PageCode();
    }
}
?>
