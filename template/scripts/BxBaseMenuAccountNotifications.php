<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
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

        $this->addMarkers(array(
        	'studio_url' => BX_DOL_URL_STUDIO
        ));        
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
            case 'studio':
                if (!isAdmin())
                    return false;
                break;
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
        	case 'profile-stats-subscriptions':
            case 'profile-stats-subscribed-me':
	            $aInfo = BxDolProfile::getInstance()->getInfo();
	            if($a['module'] != $aInfo['type'])
	                return false;
				break;
        }

        return true;
    }

    protected function _getTmplVarsAddon($mixedAddon, $aMenuItem)
    {
        return array(
            'addon' => $mixedAddon,
            'addonf' => $this->_oTemplate->parseHtmlByTemplateName('menu_item_addon', array(
		        'content' => $mixedAddon
		    ))
			
        );
    }
}

/** @} */
