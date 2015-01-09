<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

bx_import('BxTemplMenu');

/**
 * Menu representation.
 * @see BxDolMenu
 */
class BxBaseMenuAccountNotifications extends BxTemplMenu
{
    public function __construct ($aObject, $oTemplate)
    {
        parent::__construct ($aObject, $oTemplate);
    }

    /**
     * Check if menu items is visible with extended checking for friends notifications
     * @param $a menu item array
     * @return boolean
     */
    protected function _isVisible ($a)
    {
        // default visible settings
        bx_import('BxDolAcl');
        if (!BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']))
            return false;

        // show only friends for currently active profile for friend request notification
        if ('notifications-friend-requests' == $a['name'] || 'profile-stats-friend-requests' == $a['name']) {
            $oProfile = BxDolProfile::getInstance();
            $aInfo = $oProfile->getInfo();
            if ($a['module'] != $aInfo['type'])
                return false;
        }

        return true;
    }
}

/** @} */
