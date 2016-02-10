<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BasePayment Base classes for Payment like modules
 * @ingroup     TridentModules
 *
 * @{
 */

class BxBaseModPaymentModule extends BxDolModule
{
	protected $_sLangsPrefix;

	function __construct($aModule)
    {
        parent::__construct($aModule);

        $this->_oConfig->init($this->_oDb);

        $this->_sLangsPrefix = $this->_oConfig->getPrefix('langs');
    }

	public function serviceUpdateDependentModules($sModule = 'all', $bInstall = true)
    {
    	$aModules = $sModule == 'all' ? $this->_oDb->getModulesBy(array('type' => 'modules')) : array($this->_oDb->getModuleByName($sModule));

        foreach($aModules as $aModule) {
        	$mixedData = $this->callGetPaymentData($aModule['name']);
        	if($mixedData === false)
        		continue;

			$sMethodName = $bInstall ? 'insertModule' : 'deleteModule';
			$this->_oDb->$sMethodName($mixedData);
        }
    }

    public function serviceGetCurrencyInfo()
    {
        return array(
            'sign' => $this->_oConfig->getCurrencySign(),
            'code' => $this->_oConfig->getCurrencyCode()
        );
    }

	public function serviceGetOption($sOption)
    {
    	return $this->_oDb->getParam($this->_oConfig->getPrefix('options') . $sOption);
    }

	public function serviceGetProviders($iVendorId, $sProvider = '')
	{
		$aVendorProviders = $this->_oDb->getVendorInfoProviders($iVendorId, $sProvider);

		$aResult = array();
		foreach($aVendorProviders as $aProvider) {
			$aProvider['caption_cart'] = _t($this->_sLangsPrefix . 'txt_cart_' . $aProvider['name']);

			$aResult[] = $aProvider;
		}

		return $aResult;
	}

	public function getVendorInfo($iUserId)
    {
        return array_merge($this->getProfileInfo($iUserId), array(
        	'currency_code' => $this->_oConfig->getCurrencyCode(),
			'currency_sign' => $this->_oConfig->getCurrencySign()
        ));
    }

    public function getProfileId()
    {
    	return bx_get_logged_profile_id();
    }

	public function getProfileInfo($iUserId = 0)
    {
        $oProfile = $this->getObjectUser($iUserId);

        return array(
        	'id' => $iUserId,
            'name' => $oProfile->getDisplayName(),
            'link' => $oProfile->getUrl(),
            'icon' => $oProfile->getIcon(),
            'unit' => $oProfile->getUnit(),
        	'active' => $oProfile->isActive(),
        );
    }
    
    public function getObjectUser($iUserId = 0)
    {
    	bx_import('BxDolProfile');
        $oProfile = BxDolProfile::getInstance($iUserId);
        if (!$oProfile) {
            bx_import('BxDolProfileUndefined');
            $oProfile = BxDolProfileUndefined::getInstance();
        }

        return $oProfile;
    }

	public function getObjectJoin()
    {
        $sClassName = $this->_oConfig->getClassPrefix() . 'Join';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Join', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

	public function getObjectCart()
    {
        $sClassName = $this->_oConfig->getClassPrefix() . 'Cart';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Cart', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

    public function getObjectOrders()
    {
    	$sClassName = $this->_oConfig->getClassPrefix() . 'Orders';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Orders', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

	public function getObjectDetails()
    {
    	$sClassName = $this->_oConfig->getClassPrefix() . 'Details';
        if(!isset($GLOBALS['bxDolClasses'][$sClassName])) {
        	bx_import('Details', $this->_aModule);
            $GLOBALS['bxDolClasses'][$sClassName] = new $sClassName();
        }

        return $GLOBALS['bxDolClasses'][$sClassName];
    }

	public function getObjectProvider($sProvider, $mixedVendorId = BX_PAYMENT_EMPTY_ID)
	{
		$aProvider = is_numeric($mixedVendorId) && (int)$mixedVendorId != BX_PAYMENT_EMPTY_ID ? $this->_oDb->getVendorInfoProviders((int)$mixedVendorId, $sProvider) : $this->_oDb->getProviders($sProvider);
        $sClassPath = !empty($aProvider['class_file']) ? BX_DIRECTORY_PATH_ROOT . $aProvider['class_file'] : $this->_oConfig->getClassPath() . $aProvider['class_name'] . '.php';
        if(empty($aProvider) || !file_exists($sClassPath))
        	return false;

        require_once($sClassPath);
        return new $aProvider['class_name']($aProvider);
	}

    public function callGetPaymentData($mixedModule)
    {
    	$sMethod = 'get_payment_data';
		if(!BxDolRequest::serviceExists($mixedModule, $sMethod)) 
			return false;

		return BxDolService::call($mixedModule, $sMethod);
    }

    public function callGetCartItem($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'get_cart_item', $aParams);
    }

    public function callGetCartItems($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'get_cart_items', $aParams);
    }

    public function callRegisterCartItem($mixedModule, $aParams)
    {
    	return BxDolService::call($mixedModule, 'register_cart_item', $aParams);
    }
}

/** @} */
