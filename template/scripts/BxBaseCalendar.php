<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import ('BxDolCalendar');

/**
 * @see BxDolCalendar
 */
class BxBaseCalendar extends BxDolCalendar {

    function BxBaseCalendar ($iYear, $iMonth) {
        parent::BxDolCalendar($iYear, $iMonth);
    }

    function display() {
        $oTemplate = BxDolTemplate::getInstance();
        $aVars = array (
            'bx_repeat:week_names' => $this->_getWeekNames (),
            'bx_repeat:calendar_row' => $this->_getCalendar (),
            'month_prev_url' => $this->getBaseUri () . "{$this->iPrevYear}/{$this->iPrevMonth}",
            'month_prev_name' => _t('_month_prev'),
            'month_prev_icon' => getTemplateIcon('sys_back.png'),
            'month_next_url' => $this->getBaseUri () . "{$this->iNextYear}/{$this->iNextMonth}",
            'month_next_name' => _t('_month_next'),
            'month_next_icon' => getTemplateIcon('sys_next.png'),
            'month_current' => $this->getTitle(),
        );
        $sHtml = $oTemplate->parseHtmlByName('calendar.html', $aVars);
        $sHtml = preg_replace ('#<bx_repeat:events>.*?</bx_repeat:events>#s', '', $sHtml); // TODO: check this line and remove if unused
        $oTemplate->addCss('calendar.css');
        return $sHtml;
    }
}

