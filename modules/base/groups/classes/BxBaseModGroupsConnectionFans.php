<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModGroupsConnectionFans extends BxTemplConnection
{
    protected $_sModule;
    protected $_oModule;

    public function __construct($aObject)
    {
        parent::__construct($aObject);

        $this->_oModule = BxDolModule::getInstance($this->_sModule);
    }

    protected function _checkAllowedConnectInitiator ($oInitiator, $isPerformAction = false)
    {
        if(!bx_srv($oInitiator->getModule(), 'act_as_profile'))
            return CHECK_ACTION_RESULT_ALLOWED;

        return parent::_checkAllowedConnectInitiator($oInitiator, $isPerformAction);
    }

    public function _checkAllowedConnectContent ($oContent)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(bx_srv($oContent->getModule(), 'act_as_profile'))
            return CHECK_ACTION_RESULT_ALLOWED;

        if(!empty($CNF['OBJECT_PRIVACY_VIEW']) && ($oPrivacy = BxDolPrivacy::getObjectInstance($CNF['OBJECT_PRIVACY_VIEW'])) !== false) {
            $iContentId = $oContent->getContentId();
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($iContentId);

            if(in_array($aContentInfo[$CNF['FIELD_ALLOW_VIEW_TO']], array_merge($oPrivacy->getPartiallyVisiblePrivacyGroups(), ['s'])))
                return CHECK_ACTION_RESULT_ALLOWED;
        }

        return parent::_checkAllowedConnectContent($oContent);
    }
}

/** @} */
