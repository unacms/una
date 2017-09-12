<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModProfilePrivacy extends BxTemplPrivacy
{
	protected $MODULE;
    protected $_oModule;
    protected $_aPrivacyParticallyVisible = array ();

    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

    	$this->_oModule = BxDolModule::getInstance($this->MODULE);
    	if(!$oTemplate)
			$oTemplate = $this->_oModule->_oTemplate;
    }

    public function isPartiallyVisible ($mixedPrivacy)
    {
        return in_array($mixedPrivacy, $this->_aPrivacyParticallyVisible);
    }

    public function getPartiallyVisiblePrivacyGroups ()
    {
        return $this->_aPrivacyParticallyVisible;
    }

    protected function getObjectInfo($sAction, $iObjectId)
    {
        $a = $this->_oDb->getObjectInfo($sAction, $iObjectId);
        $oProfile = BxDolProfile::getInstanceByContentAndType($iObjectId, $this->MODULE);
        if (!$oProfile)
            return false;

        $a['owner_id'] = $oProfile->id();        
        return $a;
    }
}

/** @} */
