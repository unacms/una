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

        return $this->_oModule->_oDb->isAdmin($aEvent['object_owner_id'], $iViewerId ? $iViewerId : bx_get_logged_profile_id());
    }
}

/** @} */
