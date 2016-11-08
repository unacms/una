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
}

/** @} */
