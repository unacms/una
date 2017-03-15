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
    public function serviceGetBlockList()
    {
        $this->_oModule->setSiteSubmenu('menu_sbs_submenu', 'sbs-list');

        return $this->_getBlockSubscriptions('list');
    }

    public function serviceGetBlockHistory()
    {
        $this->_oModule->setSiteSubmenu('menu_sbs_submenu', 'sbs-history');

        return $this->_getBlock('history');
    }

    public function serviceGetBlockAdministration()
    {
        $this->_oModule->setSiteSubmenu('menu_sbs_submenu', 'sbs-list');

        return $this->_getBlockSubscriptions('administration');
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

    protected function _getBlock($sType)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sMethod = 'displayBlockSbs' . bx_gen_method_name($sType);
        if(!method_exists($this->_oModule->_oTemplate, $sMethod))
            return MsgBox(_t('_Empty'));

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']));

        return array(
        	'content' => $this->_oModule->_oTemplate->$sMethod($iUserId)
        );
    }

    protected function _getBlockSubscriptions($sType)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = $this->_getBlock($sType);
        if(!is_array($aResult))
            return $aResult;

        $sMenu = '';
		if(BxDolAcl::getInstance()->isMemberLevelInSet(192)) {
			$oPermalink = BxDolPermalinks::getInstance();

            $oMenu = new BxTemplMenu(array(
            	'template' => 'menu_vertical.html', 
            	'menu_items' => array(
    			    array('id' => 'sbs-list', 'name' => 'sbs-list', 'class' => '', 'link' => $oPermalink->permalink($CNF['URL_SUBSCRIPTIONS']), 'target' => '_self', 'title' => _t('_bx_payment_menu_item_title_sbs_list_my'), 'active' => 1),
    			    array('id' => 'sbs-administration', 'name' => 'sbs-administration', 'class' => '', 'link' => $oPermalink->permalink($CNF['URL_SUBSCRIPTIONS_ADM']), 'target' => '_self', 'title' => _t('_bx_payment_menu_item_title_sbs_list_administration'), 'active' => 1)
    			)
            ), $this->_oModule->_oTemplate);
            $oMenu->setSelected($this->_oModule->getName(), 'sbs-' . $sType);

            $aResult['menu'] = $oMenu->getCode();
		}

		return $aResult;
    }
}

/** @} */
