<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry all actions menu
 */
class BxEventsMenuViewActionsAll extends BxBaseModGroupsMenuViewActionsAll
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_events';

        parent::__construct($aObject, $oTemplate);
    }

    protected function _getMenuAttrs ($aMenuItem)
    {
        $s = parent::_getMenuAttrs ($aMenuItem);
        if ('ical-export' == $aMenuItem['name']) {
            $CNF = $this->_oModule->_oConfig->CNF;
            $s .= ' download="' . title2uri($this->_aContentInfo[$CNF['FIELD_TITLE']]) . '.ics"';
        }
        return $s;
    }

    protected function _getMenuItemJoinEventProfile($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }

    protected function _getMenuItemEditEventCover($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemEditEventProfile($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemEditEventQuestionnaire($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemEditEventSessions($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemEditEventPricing($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemInviteToEvent($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemDeleteEventProfile($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemApproveEventProfile($aItem)
    {
        return $this->_getMenuItemByNameActionsMore($aItem);
    }

    protected function _getMenuItemProfileCheckIn($aItem)
    {
        $a = $this->_getMenuItemByNameActions($aItem);
        if (bx_is_api()){

          //  print_r($this );
            $a['display_type'] = 'callback';
            $a['data'] = ['request_url' => $this->_oModule->_aModule['name'] . '/check_in/&params[]=' . $this->_aMarkers['content_id'], 'on_callback' => 'hide'];
            return $a;
        }
        return $a;
    }

    protected function _getMenuItemProfileSetBadges($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
