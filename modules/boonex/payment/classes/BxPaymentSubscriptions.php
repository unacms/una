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
    public function serviceGetBlockListMy()
    {
        return $this->_getBlock('list_my');
    }

    public function serviceGetBlockListAll()
    {
        return $this->_getBlock('list_all');
    }

    public function serviceGetBlockHistory()
    {
        return $this->_getBlock('history');
    }

	public function serviceSubscribe($iSellerId, $sSellerProvider, $iModuleId, $iItemId, $iItemCount)
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
			return array('popup' => BxTemplStudioFunctions::getInstance()->popupBox($sId, $sTitle, $this->_oModule->_oTemplate->displayProvidersSelector($aCartItem, $aSellerProviders)));
		}

		$aProvider = $aSellerProviders[$sSellerProvider];
        $mixedResult = $this->_oModule->serviceInitializeCheckout(BX_PAYMENT_TYPE_RECURRING, $iSellerId, $aProvider['name'], array($sCartItem));
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
            return MsgBox(_t('_Empty'));

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']));

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
