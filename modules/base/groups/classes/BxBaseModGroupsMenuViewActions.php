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

/**
 * 'View group' actions menu.
 */
class BxBaseModGroupsMenuViewActions extends BxBaseModProfileMenuViewActions
{

    public function __construct($aObject, $oTemplate = false)
    {
        parent::__construct($aObject, $oTemplate);
    }
    
    public function setContentId($iContentId)
    {
        parent::setContentId($iContentId);

        if(!$this->_oProfile || !isLogged())
            return;

        $CNF = &$this->_oModule->_oConfig->CNF;

        $oConn = isset($CNF['OBJECT_CONNECTIONS']) ? BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']) : false;
        if ($oConn && $oConn->isConnectedNotMutual(bx_get_logged_profile_id(), $this->_oProfile->id())) {
            $this->addMarkers(array(
                'title_add_fan' => _t($CNF['T']['menu_item_title_become_fan_sent']),
                'title_remove_fan' => _t($CNF['T']['menu_item_title_leave_group_cancel_request']),
            ));
        } 
        else {
            if (isset($CNF['T']['menu_item_title_become_fan']) && isset($CNF['T']['menu_item_title_leave_group'])){
                $this->addMarkers(array(
                    'title_add_fan' => _t($CNF['T']['menu_item_title_become_fan']),
                    'title_remove_fan' => _t($CNF['T']['menu_item_title_leave_group']),
                ));
            }
        }

        if ($oConn && $this->_oModule->isFan($this->_aContentInfo[$CNF['FIELD_ID']])) {
            $a = $oConn->getConnectedInitiators($this->_oProfile->id());
            $this->addMarkers(array('recipients' => implode(',', $a)));
        }
    }
}

/** @} */
