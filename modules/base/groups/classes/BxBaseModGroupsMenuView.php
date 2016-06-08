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
 * 'View group' menu.
 */
class BxBaseModGroupsMenuView extends BxBaseModProfileMenuView
{

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);

        if ($this->_oProfile && isLogged()) {
            $CNF = &$this->_oModule->_oConfig->CNF;
            
            $oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
            if ($oConn && $oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $this->_oProfile->id())) {
                $this->addMarkers(array(
                    'title_add_fan' => _t($CNF['T']['menu_item_title_become_fan_sent']),
                    'title_remove_fan' => _t($CNF['T']['menu_item_title_leave_group_cancel_request']),
                ));
            } else {
                $this->addMarkers(array(
                    'title_add_fan' => _t($CNF['T']['menu_item_title_become_fan']),
                    'title_remove_fan' => _t($CNF['T']['menu_item_title_leave_group']),
                ));
            }

            if ($oConn && $this->_oModule->isFan($this->_aContentInfo[$CNF['FIELD_ID']])) {
                $a = $oConn->getConnectedInitiators($this->_oProfile->id());
                $this->addMarkers(array('recipients' => implode(',', $a)));
            }
        }
    }
}

/** @} */
