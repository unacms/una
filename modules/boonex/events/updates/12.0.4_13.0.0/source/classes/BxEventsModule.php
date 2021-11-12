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
 * Events profiles module.
 */
class BxEventsModule extends BxBaseModGroupsModule implements iBxDolCalendarService 
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
        $aSQLPart = array();
        $iContentId = (int)bx_get('event');
        $iContextId = (int)bx_get('context_id');
        
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
            if($iContextId){
                if (!$this->serviceIsEnableForContext($iContextId)){
                    exit;
                }
                else{
                    $aSQLPart = $oPrivacy ? $oPrivacy->getContentByGroupAsSQLPart(- $iContextId) : array();
                }
            }
            else{
                $aSQLPart = $oPrivacy ? $oPrivacy->getContentPublicAsSQLPart(0, $oPrivacy->getPartiallyVisiblePrivacyGroups()) : array();
            }
        }

        // get entries
        $aEntries = $this->_oDb->getEntriesByDate(bx_get('start'), bx_get('end'), bx_get('event'), $aSQLPart);
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($aEntries);
    }
    
    public function serviceGetCalendarEntries($iProfileId)
    {
        $CNF = &$this->_oConfig->CNF;
        $oConn = BxDolConnection::getObjectInstance($CNF['OBJECT_CONNECTIONS']);
        $aData = $oConn->getConnectedContent($iProfileId);
        $aData2 = array(0);
        foreach($aData as $iProfileId2){
            $oProfile = BxDolProfile::getInstance($iProfileId2);
            array_push($aData2, $oProfile->getContentId());
        }
        $aSQLPart['where'] = " AND `bx_events_data`.`id` IN(" . implode(',', $aData2) . ")";
        return $this->_oDb->getEntriesByDate(bx_get('start'), bx_get('end'), null, $aSQLPart);
	}

    public function serviceGetSafeServices()
    {
        $a = parent::serviceGetSafeServices();
        return array_merge($a, array (
            'BrowsePastProfiles' => '',
            'Calendar' => '',
        ));
    }

    /**
     * @page service Service Calls
     * @section bx_events Events
     * @subsection bx_events-browse Browse
     * @subsubsection bx_events-browse_upcoming_profiles browse_upcoming_profiles
     * 
     * @code bx_srv('bx_events', 'browse_upcoming_profiles', [...]); @endcode
     * 
     * Browse upcoming events
     * 
     * @param $bDisplayEmptyMsg show "empty" message or not if nothing found
     * @param $bAjaxPaginate use AJAX paginate or not
     *
     * @see BxEventsModule::serviceBrowseUpcomingProfiles
     */
    /** 
     * @ref bx_events-browse_upcoming_profiles "browse_upcoming_profiles"
     */
    public function serviceBrowseUpcomingProfiles ($aParams = false)
    {
        $bDisplayEmptyMsg = false;
        if(isset($aParams['empty_message'])) {
            $bDisplayEmptyMsg = (bool)$aParams['empty_message'];
            unset($aParams['empty_message']);
        }

        $bAjaxPaginate = true;
        if(isset($aParams['ajax_paginate'])) {
            $bAjaxPaginate = (bool)$aParams['ajax_paginate'];
            unset($aParams['ajax_paginate']);
        }

        return $this->_serviceBrowse ('upcoming', $aParams, BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate);
    }

    /**
     * @page service Service Calls
     * @section bx_events Events
     * @subsection bx_events-browse Browse
     * @subsubsection bx_events-browse_upcoming_connected_profiles browse_upcoming_connected_profiles
     * 
     * @code bx_srv('bx_events', 'browse_upcoming_connected_profiles', [...]); @endcode
     * 
     * Browse upcoming connected (followed) events
     * 
     * @param $bDisplayEmptyMsg show "empty" message or not if nothing found
     * @param $bAjaxPaginate use AJAX paginate or not
     *
     * @see BxEventsModule::serviceBrowseUpcomingConnectedProfiles
     */
    /** 
     * @ref bx_events-browse_upcoming_connected_profiles "browse_upcoming_connected_profiles"
     */
    public function serviceBrowseUpcomingConnectedProfiles ($aParams = false)
    {
        if(!is_array($aParams) || empty($aParams['object']) || empty($aParams['profile']))
            return '';

        $bDisplayEmptyMsg = false;
        if(isset($aParams['empty_message'])) {
            $bDisplayEmptyMsg = (bool)$aParams['empty_message'];
            unset($aParams['empty_message']);
        }

        $bAjaxPaginate = true;
        if(isset($aParams['ajax_paginate'])) {
            $bAjaxPaginate = (bool)$aParams['ajax_paginate'];
            unset($aParams['ajax_paginate']);
        }

        $aDefaults = array(
            'type' => 'content',
            'mutual' => false,
            'profile2' => 0
        );

        return $this->_serviceBrowse ('upcoming_connected', array_merge($aDefaults, $aParams), BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate);
    }

    /**
     * @page service Service Calls
     * @section bx_events Events
     * @subsection bx_events-browse Browse
     * @subsubsection bx_events-browse_past_profiles browse_past_profiles
     * 
     * @code bx_srv('bx_events', 'browse_past_profiles', [...]); @endcode
     * 
     * Browse past events
     * 
     * @param $bDisplayEmptyMsg show "empty" message or not if nothing found
     * @param $bAjaxPaginate use AJAX paginate or not
     *
     * @see BxEventsModule::serviceBrowsePastProfiles
     */
    /** 
     * @ref bx_events-browse_past_profiles "browse_past_profiles"
     */
    public function serviceBrowsePastProfiles ($bDisplayEmptyMsg = false, $bAjaxPaginate = true)
    {
        return $this->_serviceBrowse ('past', false, BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate);
    }
    
    /**
     * @page service Service Calls
     * @section bx_events Events
     * @subsection bx_events-browse Browse
     * @subsubsection bx_events-browse_past_profiles browse_past_profiles_by_params
     * 
     * @code bx_srv('bx_events', 'browse_past_profiles_by_params', [...]); @endcode
     * 
     * Browse past events
     * 
     * @param $aParams array with all parameters
     *
     * @see BxEventsModule::serviceBrowsePastProfilesByParams
     */
    /** 
     * @ref bx_events-browse_past_profiles_by_params "browse_past_profiles_by_params"
     */
    public function serviceBrowsePastProfilesByParams ($aParams)
    {
        $bDisplayEmptyMsg = false;
        if(isset($aParams['empty_message'])) {
            $bDisplayEmptyMsg = (bool)$aParams['empty_message'];
            unset($aParams['empty_message']);
        }

        $bAjaxPaginate = true;
        if(isset($aParams['ajax_paginate'])) {
            $bAjaxPaginate = (bool)$aParams['ajax_paginate'];
            unset($aParams['ajax_paginate']);
        }
        return $this->_serviceBrowse ('past', $aParams, BX_DB_PADDING_DEF, $bDisplayEmptyMsg, $bAjaxPaginate);
    }

    /**
     * @page service Service Calls
     * @section bx_events Events
     * @subsection bx_events-page_blocks Page Blocks
     * @subsubsection bx_events-calendar calendar
     * 
     * @code bx_srv('bx_events', 'calendar', [...]); @endcode
     * 
     * Shows event or events calendar
     * 
     * @param $aData additional data to point which events to show, leave empty to show all events, specify event's ID in 'event' array key to show calendar for one event only, specify context's ID in 'context_id' array key to show calendar for one context events only. If only one event is specified then it will show calendar only if it's repeating event.
     * @param $sTemplate template to use to show calendar, or leave empty for default template, possible options: calendar.html, calendar_compact.html
     * @return HTML string with calendar to display on the site, all necessary CSS and JS files are automatically added to the HEAD section of the site HTML. On error empty string is returned.
     *
     * @see BxEventsModule::serviceCalendar
     */
    /** 
     * @ref bx_events-calendar "calendar"
     */
    public function serviceCalendar($aData = array(), $sTemplate = 'calendar.html')
    {
        if (isset($aData['event'])) {
            $aContentInfo = $this->_oDb->getContentInfoById ((int)$aData['event']);
            if ('' == $aContentInfo['repeat_stop']) // don't display calendar for non repeating events
                return '';
        } 

        if (isset($aData['context_id'])) {
            if (!$this->serviceIsEnableForContext($aData['context_id'])){
                return '';
            }                
        } 

        $oCalendar = new BxTemplCalendar(array(
            'eventSources' => array (
                bx_append_url_params(BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'calendar_data', $aData),
            ),
        ), $this->_oTemplate);

        $this->_oTemplate->addCss(array('main.css'));
        return $oCalendar->display($sTemplate);
    }

    /**
     * @page service Service Calls
     * @section bx_events Events
     * @subsection bx_events-other Other
     * @subsubsection bx_events-process_reminders process_reminders
     * 
     * @code bx_srv('bx_events', 'process_reminders'); @endcode
     * 
     * Send remiders to event's participants.
     *
     * It must be processed once every hour for the proper processing.
     * @return nothing
     *
     * @see BxEventsModule::serviceProcessReminders
     */
    /** 
     * @ref bx_events-process_reminders "process_reminders"
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
     * @section bx_events Events
     * @subsection bx_events-internal Internal
     * @subsubsection bx_events-get_timeline_post get_timeline_post
     *
     * @code bx_srv('bx_events', 'get_timeline_post', [...]); @endcode
     * 
     * Get Timeline post. It's needed for Timeline module.
     * 
     * @param $aEvent timeline event array from Timeline module
     * @return array in special format which is needed specifically for Timeline module to display the data.
     *
     * @see BxEventsModule::serviceGetTimelinePost
     */
    /** 
     * @ref bx_events-get_timeline_post "get_timeline_post"
     */
    public function serviceGetTimelinePost($aEvent, $aBrowseParams = array())
    {
        $a = parent::serviceGetTimelinePost($aEvent, $aBrowseParams);
        if($a === false)
            return false;

        $aContentInfo = $this->_oDb->getContentInfoById($aEvent['object_id']);
        if(empty($aContentInfo) || !is_array($aContentInfo))
            return $a;

        $CNF = $this->_oConfig->CNF;

        $oDateStart = date_create('@' . $aContentInfo['date_start']);
        $oDateStart->setTimezone(new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));
        $oDateEnd = date_create('@' . ($aContentInfo['date_start'] > $aContentInfo['repeat_stop'] ? $aContentInfo['date_start'] : $aContentInfo['repeat_stop']));
        $oDateEnd->setTimezone(new DateTimeZone($aContentInfo['timezone'] ? $aContentInfo['timezone'] : 'UTC'));

        $oMetatags = BxDolMetatags::getObjectInstance($CNF['OBJECT_METATAGS']);
        $sLocationString = $oMetatags ? $oMetatags->locationsString($aContentInfo[$CNF['FIELD_ID']], false) : false;

        $a['content']['raw'] = $this->_oTemplate->parseHtmlByName('timeline_post.html', array(
            'title' => $a['content']['title'],
            'title_attr' => bx_html_attribute($a['content']['title']),
            'url' => $a['content']['url'],
            'date' => strftime(getParam('bx_events_short_date_format'), $oDateStart->getTimestamp()) . ($oDateStart->format('ymd') == $oDateEnd->format('ymd') ? '' : ' - ' . strftime(getParam('bx_events_short_date_format'), $oDateEnd->getTimestamp())),
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
        $sEntryBeginShort = strftime(getParam('bx_events_short_date_format'), $oDateBegin->getTimestamp());
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
                'EntryLocation' => $sLocationString ? _t('_bx_events_email_reminder_location', $sLocationString) : '',
            ), BX_EMAIL_NOTIFY);

            $iCounter += $b ? 1 : 0;
        }
        return $iCounter;
    }
}

/** @} */
