<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Group profile forms functions
 */
class BxBaseModGroupsFormsEntryHelper extends BxBaseModProfileFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    protected function _processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile)
    {
        $sMsg = parent::_processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile);

        $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);
        if ($sMsg && $oPrivacy->isPartiallyVisible($aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']]))
            return '';

        return $sMsg;
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        if ($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        if (!($oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName())))
            return '';

        // insert invited members, so they will join without confirmation
        $aInitialProfiles = bx_get('initial_members');
        foreach ($aInitialProfiles as $iProfileId) {
            if (!($oProfile = BxDolProfile::getInstance($iProfileId)))
                continue;
            $this->_oModule->serviceAddMutualConnection ($oGroupProfile->id(), $oProfile->id(), true);            
        }
        
        return '';
    }

    public function onDataDeleteAfter ($iContentId, $aContentInfo, $oProfile)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;
        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($iContentId, $this->_oModule->_oConfig->getName());

        if ($oGroupProfile)
            $this->_oModule->_oDb->deleteAdminsByGroupId($oGroupProfile->id());

        if (isset($CNF['OBJECT_CONNECTIONS']) && $oGroupProfile && ($oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])))
            $oConnection->onDeleteInitiatorAndContent($oGroupProfile->id());

        return '';
    }
}

/** @} */
