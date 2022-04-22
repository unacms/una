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

class BxBaseModProfilePrivacyPost extends BxTemplPrivacy
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    protected function getObjectInfo($sAction, $iObjectId)
    {
        $oProfile = BxDolProfile::getInstanceByContentAndType($iObjectId, $this->_sModule);
        if(!$oProfile)
            return false;

        $a = $this->_oDb->getObjectInfo($sAction, $iObjectId);
        $a['owner_id'] = $oProfile->id();

        return $a;
    }

    protected function isSelectGroupCustomUsers($aParams)
    {
        return $this->_isSelectGroupCustomItems($aParams);
    }

    protected function isSelectGroupCustomMemberships($aParams)
    {
        return $this->_isSelectGroupCustomItems($aParams);
    }

    protected function _isSelectGroupCustomItems($aParams)
    {
        if($this->_oModule->serviceActAsProfile() && empty($aParams['content_id']))
            return _t('_sys_ps_ferr_incorrect_gc_not_allowed');

        return true;
    }
}

/** @} */
