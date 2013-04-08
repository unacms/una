<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

/**
 * Calendar for your module content
 *
 * Related classes:
 *  BxBaseCalendar - calendar base representation
 *  BxTemplCalendar - custom template representation
 *
 *
 *
 * To add calendar to your content you need to inherit this class and override the following methods:
 * \code
 *  getEntriesNames ()
 *  getData ()
 *  getBaseUri ()
 *  getBrowseUri ()
 * \endcode
 *
 * see each function documentation for more information.
 *
 *
 *
 * Example of usage:
 * After overriding this class and adding necessary methods use thje following code to disp;lay calendar:
 * This code is called from module class:
 *
 * \code
 * function actionCalendar ($iYear = '', $iMonth = '') {
 *     bx_import ('Calendar', $this->_aModule);
 *     $oCalendar = bx_instance ($this->_aModule['class_prefix'] . 'Calendar', array ($iYear, $iMonth, $this->_oDb, $this->_oTemplate, $this->_oConfig));
 *     echo $oCalendar->display();
 * }
 * \endcode
 *
 *
 *
 * Memberships/ACL:
 * vote - ACTION_ID_VOTE
 *
 *
 *
 * Alerts:
 * this class don't risa any alert
 *
 */
class BxDolCalendar extends BxDol {

    var $iYear, $iMonth, $iPrevYear, $iPrevMonth, $iNextYear, $iNextMonth;
    var $iFirstWeekDay, $iNumDaysInMonth, $sMonthName, $iWeekStart, $iWeekEnd;

    function BxDolCalendar ($iYear, $iMonth) {
        parent::BxDol();

        // input values
        $this->iYear = (int)$iYear ? (int)$iYear : date('Y');
        $this->iMonth = (int)$iMonth ? (int)$iMonth : date('m');
        $this->iDay = date('d');

        $this->sMonthName = strftime('%B', mktime(0, 0, 0, $this->iMonth+1, $iDay, $this->iYear));
        $this->iNumDaysInMonth = date('t', mktime(0, 0, 0, $this->iMonth+1, $iDay, $this->iYear));
        $this->iFirstWeekDay = (int)date('w', mktime(0, 0, 0, $this->iMonth, 1, $this->iYear));

        // previous month year, month
        $this->iPrevYear = $this->iYear;
        $this->iPrevMonth = $this->iMonth - 1;
        if ( $this->iPrevMonth <= 0 ) {
            $this->iPrevMonth = 12;
            $this->iPrevYear--;
        }
        // next month year, month
        $this->iNextYear = $this->iYear;
        $this->iNextMonth = $this->iMonth + 1;
        if ( $this->iNextMonth > 12 ) {
            $this->iNextMonth = 1;
            $this->iNextYear++;
        }

        // week config
        list ($this->iWeekStart, $this->iWeekEnd) = (getParam('sys_calendar_starts_sunday') == 'on') ? array (0, 7) : array (1, 8);
        if ($this->iFirstWeekDay < $this->iWeekStart)
            $this->iFirstWeekDay = $this->iWeekEnd-1;
    }

    function _getWeekNames () {
        if(0 == $this->iWeekStart)
            $aWeek[] = array('name' => _t('_week_sun'));
        $aWeek[] = array('name' => _t('_week_mon'));
        $aWeek[] = array('name' => _t('_week_tue'));
        $aWeek[] = array('name' => _t('_week_wed'));
        $aWeek[] = array('name' => _t('_week_thu'));
        $aWeek[] = array('name' => _t('_week_fri'));
        $aWeek[] = array('name' => _t('_week_sat'));
        if(8 == $this->iWeekEnd)
            $aWeek[] = array('name' => _t('_week_sun'));
        return $aWeek;
    }

    function _getCalendarGrid (&$aCalendarGrid) {

        // fill calendar with events
        $aEvents = $this->getData();
        $aCalendar = array ();

        foreach ($aEvents as $a) {
            $aCalendar[$a['Day']] += 1;
        }

        // make calendar grid
        $aCalendarGrid = array ();
        $iCurrentDay = 0;
        for ($i = 0; $i < 6; $i++) {
            for ($j = $this->iWeekStart; $j < $this->iWeekEnd; $j++) {
                if ($this->iFirstWeekDay == $j || $i > 0 || $iCurrentDay > 0) {
                    ++$iCurrentDay;
                    if ($iCurrentDay > $this->iNumDaysInMonth) break 2;
                    $aCalendarGrid[$i][$j]['day'] = $iCurrentDay;
                    $aCalendarGrid[$i][$j]['today'] = ($this->iYear == date('Y') && $this->iMonth == date('m') && $iCurrentDay == $this->iDay);
                    $aCalendarGrid[$i][$j]['num'] = isset($aCalendar[$iCurrentDay]) && $aCalendar[$iCurrentDay] > 0 ? $aCalendar[$iCurrentDay] : '';
                }
            }
        }
    }

    function _getCalendar () {

        $sBrowseUri = $this->getBrowseUri();
        list ($sEntriesSingle, $sEntriesMul) = $this->getEntriesNames ();

        $this->_getCalendarGrid($aCalendarGrid);
        $aRet = array ();
        for ($i = 0; $i < 6; $i++) {

            $aRow = array ('bx_repeat:cell');
            $isRowEmpty = true;

            for ($j = $this->iWeekStart; $j < $this->iWeekEnd; $j++) {

                $aCell = array ();

                if ($aCalendarGrid[$i][$j]['today']) {
                    $aCell['class'] = 'sys_cal_cell sys_cal_today';
                    $aCell['day'] = $aCalendarGrid[$i][$j]['day'];
                    $aCell['bx_if:num'] = array ('condition' => $aCalendarGrid[$i][$j]['num'], 'content' => array(
                        'num' => $aCalendarGrid[$i][$j]['num'],
                        'href' => $sBrowseUri . $this->iYear . '/' . $this->iMonth . '/' . $aCell['day'],
                        'entries' => 1 == $aCalendarGrid[$i][$j]['num'] ? $sEntriesSingle : $sEntriesMul,
                    ));
                    $isRowEmpty = false;
                } elseif (isset($aCalendarGrid[$i][$j]['day'])) {
                    $aCell['class'] = 'sys_cal_cell';
                    $aCell['day'] = $aCalendarGrid[$i][$j]['day'];
                    $aCell['bx_if:num'] = array ('condition' => $aCalendarGrid[$i][$j]['num'], 'content' => array(
                        'num' => $aCalendarGrid[$i][$j]['num'],
                        'href' => $sBrowseUri . $this->iYear . '/' . $this->iMonth . '/' . $aCell['day'],
                        'entries' => 1 == $aCalendarGrid[$i][$j]['num'] ? $sEntriesSingle : $sEntriesMul,
                    ));
                    $isRowEmpty = false;
                } else {
                    $aCell['class'] = 'sys_cal_cell_blank';
                    $aCell['day'] = '';
                    $aCell['bx_if:num'] = array ('condition' => false, 'content' => array(
                        'num' => '',
                        'href' => '',
                        'entries' => '',
                    ));
                }

                if ($aCell)
                    $aRow['bx_repeat:cell'][] = $aCell;
            }

            if ($aRow['bx_repeat:cell'] && !$isRowEmpty) {
                $aRet[] = $aRow;
            }
        }
        return $aRet;
    }


    function getTitle () {
        return $this->sMonthName . ', ' . $this->iYear;
    }


    // override function below to implement your own calendar

    /**
     * return records for current month, there is mandatory field `Day` - a day for current row
     * use the following class variables to pass to your database query
     * $this->iYear, $this->iMonth, $this->iNextYear, $this->iNextMonth
     *
     * for example:
     * \code
     * return $db->getAll ("
     *  SELECT *, DAYOFMONTH(FROM_UNIXTIME(`EventStart`)) AS `Day`
     *  FROM `my_table`
     *  WHERE `Date` >= UNIX_TIMESTAMP('{$this->iYear}-{$this->iMonth}-1') AND `Date` < UNIX_TIMESTAMP('{$this->iNextYear}-{$this->iNextMonth}-1') AND `Status` = 'approved'");
     * \endcode
     */
    function getData () {
        // override this func
        return array();
    }

    /**
     * deprecated
     *
     * return html for data unit for some day, it is:
     * - icon 32x32 with link if data have associated image, use $GLOBALS['oFunctions']->sysIcon() to return small icon
     * - data title with link if data have no associated image
     */
    function getUnit (&$aData) {
        // override this func
        return '';
    }

    /**
     * return base calendar url
     * year and month will be added to this url automatically
     * so if your base url is /m/some_module/calendar/, it will be transormed to
     * /m/some_module/calendar/YEAR/MONTH, like /m/some_module/calendar/2009/3
     */
    function getBaseUri () {
        // override this func
        return BX_DOL_URL_ROOT;
    }

    /**
     * return browse entries url
     * year and month and day will be added to this url automatically
     * so if your base url is /m/some_module/browse/calendar/, it will be transormed to
     * /m/some_module/browse/calendar/YEAR/MONTH/DAY, like /m/some_module/browse/calendar/2009/3/15
     */
    function getBrowseUri () {
        // override this func
        return '';
    }

    /**
     * return entries names in single and plural forms,
     * for example: ('event', 'events') or ('profile', 'profiles')
     */
    function getEntriesNames () {
        // override this func
        return array(_t('_item'), _t('_items'));
    }
}

