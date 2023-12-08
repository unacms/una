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
        
        if(isset($CNF['OBJECT_CONNECTIONS']) && ($oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS'])) !== false) {
            $iProfileId = bx_get_logged_profile_id();
            $iGroupProfileId = $this->_oProfile->id();
            $sOnclickAddFan = "bx_conn_action(this, 'bx_events_fans', 'add', '" . $iGroupProfileId . "')";

            $aMarkers = [];
            if($oConn->isConnectedNotMutual($iProfileId, $iGroupProfileId)) {
                if(isset($CNF['T']['menu_item_title_become_fan_sent'], $CNF['T']['menu_item_title_leave_group_cancel_request']))
                    $aMarkers = [
                        'title_add_fan' => _t($CNF['T']['menu_item_title_become_fan_sent']),
                        'onclick_add_fan' => $sOnclickAddFan,
                        'title_remove_fan' => _t($CNF['T']['menu_item_title_leave_group_cancel_request']),
                    ];
            }
            else {
                if(!empty($CNF['FIELD_JOIN_CONFIRMATION']) && (int)$this->_aContentInfo[$CNF['FIELD_JOIN_CONFIRMATION']] != 0)
                    $sOnclickAddFan = $this->_oModule->_oConfig->getJsObject('entry') . ".connAction(this, 'bx_events_fans', 'add', '" . $iGroupProfileId . "')";

                if(isset($CNF['T']['menu_item_title_become_fan'], $CNF['T']['menu_item_title_leave_group']))
                    $aMarkers = [
                        'title_add_fan' => _t($CNF['T']['menu_item_title_become_fan']),
                        'onclick_add_fan' => $sOnclickAddFan,
                        'title_remove_fan' => _t($CNF['T']['menu_item_title_leave_group']),
                    ];
            }
            $this->addMarkers($aMarkers);

            if ($this->_oModule->isFan($this->_aContentInfo[$CNF['FIELD_ID']])) {
                $a = $oConn->getConnectedInitiators($iGroupProfileId);
                $this->addMarkers(array('recipients' => implode(',', $a)));
            }
        }
    }
}

/** @} */
