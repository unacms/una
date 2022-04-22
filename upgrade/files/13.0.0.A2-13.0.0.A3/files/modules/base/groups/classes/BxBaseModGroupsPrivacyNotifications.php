<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModGroupsPrivacyNotifications extends BxBaseModGroupsPrivacy
{
    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);
    }

    function check($iObjectId, $iViewerId = 0)
    {
        if (!parent::check($iObjectId, $iViewerId))
            return false;

        if (!($aEvent = BxDolService::call('bx_notifications', 'get_event_by_id', array($iObjectId))))
            return false;

        return $this->checkGroupMember($aEvent['object_owner_id'], $iViewerId ? $iViewerId : bx_get_logged_profile_id());
    }

    public function checkGroupMember($iGroupProfileId, $iViewerId)
    {
        return $this->_oModule->_oDb->isAdmin($iGroupProfileId, $iViewerId);
    }
}

/** @} */
