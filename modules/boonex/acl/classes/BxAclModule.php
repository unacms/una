<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    ACL ACL
 * @ingroup     TridentModules
 *
 * @{
 */

bx_import('BxDolAcl');

define('BX_ACL_LICENSE_TYPE_SINGLE', 'single'); //--- one-time payment license
define('BX_ACL_LICENSE_TYPE_RECURRING', 'recurring'); //--- recurring payment license

class BxAclModule extends BxDolModule
{
    /**
     * Constructor
     */
    function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);
    }

	/**
     * ACTION METHODS
     */

    /**
     * SERVICE METHODS
     */
	public function serviceGetBlockView()
	{
		$sGrid = $this->_oConfig->getGridObject('view');
		$oGrid = BxDolGrid::getObjectInstance($sGrid);
        if(!$oGrid)
            return '';

		return array(
            'content' => $oGrid->getCode()
        );
	}

	public function serviceGetMembershipActions($iProfileId)
	{
		if($iProfileId != $this->getUserId())
			return '';

		return $this->_oTemplate->displayMembershipActions($iProfileId);
	}

	/**
     * Integration with Payment based modules.  
     */
	public function serviceGetPaymentData()
    {
        return $this->_aModule;
    }

    public function serviceGetCartItem($iItemId)
    {
    	$CNF = &$this->_oConfig->CNF;

        if(!$iItemId)
			return array();

		$aItem = $this->_oDb->getPrices(array('type' => 'by_id_full', 'value' => $iItemId));
        if(empty($aItem) || !is_array($aItem))
			return array();

		return array (
			'id' => $aItem['id'],
			'author_id' => $this->_oConfig->getOwner(),
			'name' => $aItem['name'],
			'title' => _t('_bx_acl_txt_cart_item_title', _t($aItem['level_name']), $aItem['period'], $aItem['period_unit']),
			'description' => _t($aItem['level_description']),
			'url' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_VIEW']),
			'price_single' => $aItem['price'],
			'price_recurring' => $aItem['price'],
        );
    }

    public function serviceGetCartItems($iSellerId)
    {
    	$CNF = &$this->_oConfig->CNF;

    	$iSellerId = $this->_oConfig->getOwner();
        $aItems = $this->_oDb->getPrices(array('type' => 'all_full'));
        $sUrl = BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink($CNF['URL_VIEW']);

        $aResult = array();
        foreach($aItems as $aItem)
            $aResult[] = array(
				'id' => $aItem['id'],
				'author_id' => $iSellerId,
            	'name' => $aItem['name'],
				'title' => _t('_bx_acl_txt_cart_item_title', _t($aItem['level_name']), $aItem['period'], $aItem['period_unit']),
				'description' => _t($aItem['level_description']),
				'url' => $sUrl,
				'price_single' => $aItem['price'],
            	'price_recurring' => $aItem['price'],
           );

        return $aResult;
    }

    public function serviceRegisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_ACL_LICENSE_TYPE_SINGLE);
    }

    public function serviceRegisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
		return $this->_serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_ACL_LICENSE_TYPE_RECURRING);
    }

    public function serviceUnregisterCartItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
        return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_ACL_LICENSE_TYPE_SINGLE);
    }

    public function serviceUnregisterSubscriptionItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense)
    {
    	return $this->_serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, BX_ACL_LICENSE_TYPE_RECURRING); 
    }

    protected function _serviceRegisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, $sType)
    {
    	$aItem = $this->serviceGetCartItem($iItemId);
        if(empty($aItem) || !is_array($aItem))
			return array();

		$aItemInfo = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => $iItemId));
        if(!BxDolAcl::getInstance()->setMembership($iClientId, $aItemInfo['level_id'], array('period' => $aItemInfo['period'], 'period_unit' => $aItemInfo['period_unit']), false, $sLicense))
            return array();

        return $aItem;
    }

    protected function _serviceUnregisterItem($iClientId, $iSellerId, $iItemId, $iItemCount, $sOrder, $sLicense, $sType)
    {
    	$aItemInfo = $this->_oDb->getPrices(array('type' => 'by_id', 'value' => $iItemId));
    	if(empty($aItemInfo) || !is_array($aItemInfo))
			return false;

    	return BxDolAcl::getInstance()->unsetMembership($iClientId, $aItemInfo['level_id'], $sLicense);
    }

    /**
     * COMMON METHODS
     */
	public function getUserId()
    {
        return isLogged() ? bx_get_logged_profile_id() : 0;
    }

    public function getUserInfo($iUserId = 0)
    {
        $oProfile = BxDolProfile::getInstance($iUserId);
        if (!$oProfile)
            $oProfile = BxDolProfileUndefined::getInstance();

        return array(
            $oProfile->getDisplayName(),
            $oProfile->getUrl(),
            $oProfile->getThumb(),
            $oProfile->getUnit()
        );
    }
}

/** @} */
