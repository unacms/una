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
 * 'View group' menu.
 */
class BxGroupsMenuView extends BxBaseModProfileMenuView
{

    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_groups';
        parent::__construct($aObject, $oTemplate);

        if ($this->_oProfile && isLogged()) {

            $oConn = BxDolConnection::getObjectInstance($this->_oModule->_oConfig->CNF['OBJECT_CONNECTIONS']);
            if ($oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $this->_aProfileInfo['content_id'])) {
                $this->addMarkers(array(
                    'title_add_fan' => _t('_bx_groups_menu_item_title_become_fan_sent'),
                    'title_remove_fan' => _t('_bx_groups_menu_item_title_leave_group_cancel_request'),
                ));
            } else {
                $this->addMarkers(array(
                    'title_add_fan' => _t('_bx_groups_menu_item_title_become_fan'),
                    'title_remove_fan' => _t('_bx_groups_menu_item_title_leave_group'),
                ));
            }

        }
    }
}

/** @} */
