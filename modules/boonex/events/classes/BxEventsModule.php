<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Events Events
 * @ingroup     TridentModules
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
        // TODO: check permissions
        
        $aEntries = $this->_oDb->getEntriesByDate(bx_get('start'), bx_get('end'));
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($aEntries);
    }

    public function serviceCalendar()
    {
        $o = new BxTemplCalendar(array('events' => BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'calendar_data'), $this->_oTemplate);
        return $o->display();
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

    public function subactionRestore()
    {
        // TODO: check permissions

        $iContentId = (int)bx_get('c');
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
        // TODO: check permissions        
    }
}

/** @} */
