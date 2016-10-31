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
        return $this->_getBlock('list');
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

    protected function _getBlock($sType)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $sMethod = 'displayBlockSbs' . bx_gen_method_name($sType);
        if(!method_exists($this->_oModule->_oTemplate, $sMethod))
            return MsgBox(_t('_Empty'));

    	$iUserId = $this->_oModule->getProfileId();
        if(empty($iUserId))
            return MsgBox(_t($CNF['T']['ERR_REQUIRED_LOGIN']));

        $this->_oModule->setSiteSubmenu('menu_sbs_submenu', 'sbs-' . $sType);
        

        return array(
        	'content' => $this->_oModule->_oTemplate->$sMethod($iUserId)
        );
    }
}

/** @} */
