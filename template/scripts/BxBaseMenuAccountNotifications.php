<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

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
        if (!BxDolAcl::getInstance()->isMemberLevelInSet($a['visible_for_levels']))
            return false;

        switch ($a['name']) {
        	case 'cart':
        		$oPayments = BxDolPayments::getInstance();
        		if(!$oPayments->isActive())
        			return false;
        		break;

        	case 'orders':
        		$oPayments = BxDolPayments::getInstance();
        		if(!$oPayments->isActive())
        			return false;
        		break;

			// show only friends for currently active profile for friend request notification
        	case 'notifications-friend-requests':
        	case 'profile-stats-friend-requests':
	            $aInfo = BxDolProfile::getInstance()->getInfo();
	            if($a['module'] != $aInfo['type'])
	                return false;
				break;
        }

        return true;
    }
}

/** @} */
