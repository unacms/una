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
 * Groups profiles module.
 */
class BxEventsModule extends BxBaseModGroupsModule
{
    function __construct(&$aModule)
    {
        parent::__construct($aModule);
        
        $CNF = &$this->_oConfig->CNF;
        $this->_aSearchableNamesExcept = array_merge($this->_aSearchableNamesExcept, array(
            $CNF['FIELD_TIMEZONE'],
            $CNF['FIELD_JOIN_CONFIRMATION'],
            $CNF['FIELD_REMINDER']
        ));
    }

    public function actionCalendarData()
    {
        // check permissions
        $iContentId = (int)bx_get('event');
        if ($iContentId) {
            $aContentInfo = $this->_oDb->getContentInfoById($iContentId);
            if (CHECK_ACTION_RESULT_ALLOWED !== $this->checkAllowedView($aContentInfo)) {
                $this->_oTemplate->displayAccessDenied();
                exit;
            }
            $aSQLPart = array();
        }
        else {
            $oPrivacy = BxDolPrivacy::getObjectInstance($this->_oConfig->CNF['OBJECT_PRIVACY_VIEW']);
            $aSQLPart = $oPrivacy ? $oPrivacy->getContentPublicAsSQLPart(0, $oPrivacy->getPartiallyVisiblePrivacyGroups()) : array();
        }

        // get entries
        $aEntries = $this->_oDb->getEntriesByDate(bx_get('start'), bx_get('end'), bx_get('event'), $aSQLPart);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($aEntries);
    }

    /**
     * @page service Service Calls
     * @section Events Events
     * @subsection calendar
     * @see BxEventsModule::serviceCalendar
     * 
     * Shows event or events calendar
     * 
     * @param $aData additional data to point which events to show, leave empty to show all events, specify event's ID in 'event' array key to show calendar for one event only. If only one event is specified then it will show calendar only if it's repeating event.
     * @param $sTemplate template to use to show calendar, or leave empty for default template, possible options: calendar.html, calendar_compact.html
     * @return HTML string with calendar to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. On error empty string is returned.
     */ 
    public function serviceCalendar($aData = array(), $sTemplate = 'calendar.html')
    {
        if (isset($aData['event'])) {
            $aContentInfo = $this->_oDb->getContentInfoById ((int)$aData['event']);
            if ('' == $aContentInfo['repeat_stop']) // don't display calendar for non repeating events
                return '';
        } 

        $o = new BxTemplCalendar(array(
            'eventSources' => array (
                'url' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'calendar_data',
                'data' => $aData,
            ),
        ), $this->_oTemplate);
        return $o->display($sTemplate);
    }

    /**
     * @page service Service Calls
     * @section Events Events
     * @subsection process_reminders
     * @see BxEventsModule::serviceProcessReminders
     * 
     * Send remiders to event's participants.
     *
     * It must be processed once every hour for the proper processing.
     * @return nothing
     */ 
    public function serviceProcessReminders()
    {
        $iNow = time();

        // get all events for today and tomorrow, since the max reminder is 24 hours
        $aEntries = $this->_oDb->getEntriesByDate('@' . time(), '@' . (time() + 86400));

        foreach ($aEntries as $a) {
            if (!$a['reminder'])
                continue;
            $iTimestamp = $a['start_utc'] - (3600 * $a['reminder']);
            if ($iNow > ($iTimestamp - 3600) && $iNow < $iTimestamp)
                $this->sendReminders($a);
        }
    }

    /**
     * @page service Service Calls
     * @section Events Events
     * @subsection get_timeline_post
     * @see BxEventsModule::serviceGetTimelinePost
     *
     * Get Timeline post. It's needed for Timeline module.
     * 
     * @param $aEvent timeline event array from Timeline module
     * @return array in special format which is needed specifically for Timeline module to display the data.
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $a = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);

        if (!($aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id'])))
            return $a;

        $CNF = $this->_oConfig->CNF;
        
        $oDateStart = date_create('@' . $aContentInfo['date_start'], new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));
        $oDateEnd = date_create('@' . ($aContentInfo['date_start'] > $aContentInfo['repeat_stop'] ? $aContentInfo['date_start'] : $aContentInfo['repeat_stop']), new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        $sLocationString = $oMetatags ? $oMetatags->locationsString($aContentInfo[$CNF['FIELD_ID']], false) : false;

        $a['content']['raw'] = $this->_oTemplate->parseHtmlByName('timeline_post.html', array(
            'title' => $a['content']['title'],
            'title_attr' => bx_html_attribute($a['content']['title']),
            'url' => $a['content']['url'],
            'date' => $oDateStart->format(getParam('bx_events_short_date_format')) . ($oDateStart->format('ymd') == $oDateEnd->format('ymd') ? '' : ' - ' . $oDateEnd->format(getParam('bx_events_short_date_format'))),
            'date_c' => $oDateStart->format('c'),
            'bx_if:location' => array(
                'condition' => !!$sLocationString,
                'content' => array('location' => $sLocationString),
            ),
        ));

        $a['content']['title'] = '';
        $a['content']['text'] = '';

        return $a;
    }
    
    public function actionIntervals()
    {
        $sAction = bx_get('a');
        $sMethodName = 'subaction' . ucfirst($sAction);
        if (!method_exists($this, $sMethodName)) {
            $this->_oTemplate->pageNotFound();
            return;
        }
        $this->$sMethodName();
    }

    public function subactionRestore($iContentId = 0)
    {
        if (!$iContentId)
            $iContentId = (int)bx_get('c');

        $aContentInfo = $this->_oDb->getContentInfoById($iContentId);

        if (
            ($aContentInfo && CHECK_ACTION_RESULT_ALLOWED !== $this->checkAllowedEdit ($aContentInfo))
            ||
            (!$aContentInfo && CHECK_ACTION_RESULT_ALLOWED !== $this->checkAllowedAdd ())
        ) {
            $this->_oTemplate->displayAccessDenied();
            exit;
        }

        $a = $this->_oDb->getIntervals($iContentId);
        foreach ($a as $iIntervalId => $r) {
            $a[$iIntervalId]['file_id'] = $r['interval_id'];
            $a[$iIntervalId]['repeat_stop'] = bx_process_output ($r['repeat_stop'], BX_DATA_DATE_TS);
        }

        if ('json' == bx_get('f')) {
            header('Content-type: text/html; charset=utf-8');
            echo json_encode($a);
        }
    }

    public function subactionDelete()
    {
        header('Content-type: text/html; charset=utf-8');

        $iIntervalId = (int)bx_get('id');

        if (!($aContentInfo = $this->_oDb->getContentInfoByIntervalId($iIntervalId))) {
            echo _t('_sys_request_page_not_found_cpt');
        }
        elseif (CHECK_ACTION_RESULT_ALLOWED !== ($sMsg = $this->checkAllowedEdit ($aContentInfo))) {
            echo $sMsg;
        } 
        elseif (!$this->_oDb->deleteIntervalById($iIntervalId)) {
            echo _t('_sys_txt_error_occured');
        } 
        else {
            echo 'ok';
        }
    }

    /**
     * Send reminder to event's paritcipants
     * @param $aContentInfo content info
     * @return number of sent reminders
     */ 
    protected function sendReminders($aContentInfo)
    {
        $CNF = $this->_oConfig->CNF;

        $oConnection = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
        if (!$oConnection)
            return false;

        $oGroupProfile = BxDolProfile::getInstanceByContentAndType($aContentInfo[$CNF['FIELD_ID']], $this->getName());
        if (!$oGroupProfile)
            return false;

        $aParticipants = $oConnection->getConnectedInitiators($oGroupProfile->id(), true);
        if (!$aParticipants)
            return true;

        $oDateBegin = new DateTime();
        $oDateBegin->setTimestamp($aContentInfo['start_utc']);
        $oDateBegin->setTimezone(new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));
        $sEntryBegin = $oDateBegin->format('r');
        $sEntryBeginShort = $oDateBegin->format(getParam('bx_events_short_date_format'));
        $oDateBegin->setTimezone(new DateTimeZone('UTC'));
        $sEntryBeginUTC = $oDateBegin->format('c');

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        $sLocationString = $oMetatags->locationsString($aContentInfo[$CNF['FIELD_ID']], false);

        $iCounter = 0;
        foreach ($aParticipants as $iProfileId) {

            $b = sendMailTemplate($CNF['EMAIL_REMINDER'], 0, $iProfileId, array(
                'EntryUrl' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=' . $CNF['URI_VIEW_ENTRY'] . '&id=' . $aContentInfo[$CNF['FIELD_ID']]),
                'EntryTitle' => $aContentInfo['title'],
                'EntryBegin' => $sEntryBegin,
                'EntryBeginShort' => $sEntryBeginShort,
                'EntryBeginUTC' => $sEntryBeginUTC,
                'EntryLocation' => $sLocationString,
            ), BX_EMAIL_NOTIFY);

            $iCounter += $b ? 1 : 0;
        }
        return $iCounter;
    }
}

/** @} */
