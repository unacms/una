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

use Spatie\CalendarLinks\Link;

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

    public function setContentId($iContentId)
    {
        parent::setContentId($iContentId);

        if (!$this->_aContentInfo['date_start'] || !$this->_aContentInfo['date_end'])
            return;

        $oDateStart = new DateTime('@' . $this->_aContentInfo['date_start']);
        $oDateEnd = new DateTime('@' . ($this->_aContentInfo['date_end'] > $this->_aContentInfo['repeat_stop'] ? $this->_aContentInfo['date_end'] : $this->_aContentInfo['repeat_stop']));

        $CNF = $this->_oModule->_oConfig->CNF;

        if ($this->_aContentInfo[$CNF['FIELD_TIMEZONE']]) {
            $oTz = new DateTimeZone($this->_aContentInfo[$CNF['FIELD_TIMEZONE']]);
            $oDateStart->setTimezone($oTz);
            $oDateEnd->setTimezone($oTz);
        }

        $oICalLink = $oDateStart && $oDateEnd ? Link::create(
            $this->_aContentInfo[$CNF['FIELD_TITLE']],
            $oDateStart,
            $oDateEnd
        ) : null;

        if (!empty($CNF['OBJECT_METATAGS'])) {
            $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
            if ($oMetatags->locationsIsEnabled()) {
                $sLocation = $oMetatags->locationsString($this->_aContentInfo[$CNF['FIELD_ID']], false);
                if ($sLocation)
                    $oICalLink->address($sLocation);
            }
        }

        $this->addMarkers([
            'ical_url' => $oICalLink ? $oICalLink->ics() : '',
        ]);
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

    protected function _getMenuItemProfileSetBadges($aItem)
    {
        return $this->_getMenuItemByNameActions($aItem);
    }
}

/** @} */
