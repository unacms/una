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

class BxPaymentSubscriptions extends BxBaseModPaymentSubscriptions
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
     * @subsubsection bx_payment-get_block_list_my get_block_list_my
     * 
     * @code bx_srv('bx_payment', 'get_block_list_my', [...], 'Subscriptions'); @endcode
     * 
     * Get page block with a list of currently logged in member's subscriptions.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentSubscriptions::serviceGetBlockListMy
     */
    /** 
     * @ref bx_payment-get_block_list_my "get_block_list_my"
     */
    public function serviceGetBlockListMy()
    {
        return $this->_getBlock('list_my');
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_list_all get_block_list_all
     * 
     * @code bx_srv('bx_payment', 'get_block_list_all', [...], 'Subscriptions'); @endcode
     * 
     * Get page block with a list of all subscriptions. It's available for authorized members only.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentSubscriptions::serviceGetBlockListAll
     */
    /** 
     * @ref bx_payment-get_block_list_all "get_block_list_all"
     */
    public function serviceGetBlockListAll()
    {
        return $this->_getBlock('list_all');
    }

    /**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-page_blocks Page Blocks
     * @subsubsection bx_payment-get_block_history get_block_history
     * 
     * @code bx_srv('bx_payment', 'get_block_history', [...], 'Subscriptions'); @endcode
     * 
     * Get page block with a list of payments related to subscriptions of currently logged in member.
     *
     * @return an array describing a block to display on the site. All necessary CSS and JS files are automatically added to the HEAD section of the site HTML.
     * 
     * @see BxPaymentSubscriptions::serviceGetBlockHistory
     */
    /** 
     * @ref bx_payment-get_block_history "get_block_history"
     */
    public function serviceGetBlockHistory()
    {
        return $this->_getBlock('history');
    }

	/**
     * @page service Service Calls
     * @section bx_payment Payment
     * @subsection bx_payment-purchase_processing Purchase Processing
     * @subsubsection bx_payment-subscribe subscribe
     * 
     * @code bx_srv('bx_payment', 'subscribe', [...], 'Subscriptions'); @endcode
     * 
     * Initialize subscription for specified item.
     *
     * @param $iSellerId integer value with seller ID.
     * @param $sSellerProvider string value with a name of payment provider to be used for processing. Empty value means that payment provider selector should be shown.
     * @param $iModuleId integer value with module ID.
     * @param $iItemId integer value with item ID.
     * @param $iItemCount integer value with a number of items for purchasing. It's equal to 1 in case of subscription.
     * @param $sRedirect (optional) string value with redirect URL, if it's needed. 
     * @return an array with special format which describes the result of operation.
     * 
     * @see BxPaymentSubscriptions::serviceSubscribe
     */
    /** 
     * @ref bx_payment-subscribe "subscribe"
     */
	public function serviceSubscribe($iSellerId, $sSellerProvider, $iModuleId, $iItemId, $iItemCount, $sRedirect = '', $aCustom = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$iClientId = $this->_oModule->getProfileId();

    	$mixedResult = $this->_oModule->checkData($iClientId, $iSellerId, $iModuleId, $iItemId, $iItemCount);
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
				'html' => BxTemplStudioFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->displayProvidersSelector($aCartItem, $aSellerProviders, $sRedirect, $aCustom)), 
				'options' => array('closeOnOuterClick' => true)
			));
		}

		$aCustoms = array();
		$this->_oModule->_oConfig->putCustom($aCartItem, $aCustom, $aCustoms);

        $mixedResult = $this->_oModule->serviceInitializeCheckout(BX_PAYMENT_TYPE_RECURRING, $iSellerId, $aSellerProviders[$sSellerProvider]['name'], array($sCartItem), $sRedirect, $aCustoms);
        if(is_string($mixedResult))
        	return array('code' => 6, 'message' => _t($mixedResult));

		return $mixedResult;
    }

    public function cancel($iPendingId)
    {
    	$aSubscription = $this->_oModule->_oDb->getSubscription(array('type' => 'pending_id', 'pending_id' => $iPendingId));
		if(empty($aSubscription) || !is_array($aSubscription))
	    	return true;

        $aOrder = $this->_oModule->_oDb->getOrderSubscription(array('type' => 'id', 'id' => $iPendingId));
        if(empty($aOrder) || !is_array($aOrder))
	    	return false;

        $iSellerId = (int)$aOrder['seller_id'];
        $oProvider = $this->_oModule->getObjectProvider($aOrder['provider'], $iSellerId);
        if($oProvider === false || !$oProvider->isActive())
        	return false;

        if(!$oProvider->cancelRecurring($iPendingId, $aSubscription['customer_id'], $aSubscription['subscription_id']))
            return false;

        list($iSellerId, $iModuleId, $iItemId, $iItemCount) = $this->_oModule->_oConfig->descriptorS2A($aOrder['items']);
		if(!$this->_oModule->callCancelSubscriptionItem((int)$iModuleId, array($aOrder['client_id'], $iSellerId, $iItemId, $iItemCount, $aOrder['order'])))
			return false;

		if(!$this->_oModule->_oDb->deleteSubscription($aSubscription['id'], 'cancel'))
			return false;

        return true;
    }

    protected function _getBlock($sType)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sMethod = 'displayBlockSbs' . bx_gen_method_name($sType);
        if(!method_exists($this->_oModule->_oTemplate, $sMethod))
            return array(
        		'content' => MsgBox(_t('_Empty'))
            );

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return array(
        		'content' => MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']))
            );

        $this->_oModule->setSiteSubmenu('menu_dashboard', 'system', 'dashboard-subscriptions');

        $sBlockSubmenu = $this->_oModule->_oConfig->getObject('menu_sbs_submenu');
        $oBlockSubmenu = BxDolMenu::getObjectInstance($sBlockSubmenu);
        if($oBlockSubmenu) 
            $oBlockSubmenu->setSelected($this->MODULE, 'sbs-' . str_replace('_', '-', $sType));     

        return array(
        	'content' => $this->_oModule->_oTemplate->$sMethod($iUserId),
        	'menu' => $this->_oModule->_oConfig->getObject('menu_sbs_submenu')
        );
    }
}

/** @} */
