<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * Group profile forms functions
 */
class BxGroupsFormsEntryHelper extends BxBaseModProfileFormsEntryHelper
{
    public function __construct($oModule)
    {
        parent::__construct($oModule);
    }

    protected function _processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile)
    {
        $sMsg = parent::_processPermissionsCheckForViewDataForm ($aContentInfo, $oProfile);
        if ($sMsg && 'c' == $aContentInfo[$this->_oModule->_oConfig->CNF['FIELD_ALLOW_VIEW_TO']])
            return '';

        return $sMsg;
    }

    public function onDataAddAfter ($iAccountId, $iContentId)
    {
        if ($s = parent::onDataAddAfter($iAccountId, $iContentId))
            return $s;

        // insert invited members, so they will join without confirmation
        $aInitialProfiles = bx_get('initial_members');
        foreach ($aInitialProfiles as $iProfileId) {
            if (!($oProfile = BxDolProfile::getInstance($iProfileId)))
                continue;
            $this->_oModule->serviceAddMutualConnection ($iContentId, $oProfile->id(), true);
        }
        
        return '';
    }
}

/** @} */
