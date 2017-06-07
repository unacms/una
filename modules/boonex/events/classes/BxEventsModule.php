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
     * Send remoiders to event's participants.
     * It must be processed once every hour for the proper processing.
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

    public function serviceGetTimelinePost($aEvent)
    {
        $a = parent::serviceGetTimelinePost($aEvent);

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
