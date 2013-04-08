<?php

// TODO: reconsider almost all functionality in this file, according to new concept it should NOT be separate page with all categories, every module has its own page with tags

/***************************************************************************
*                            Dolphin Smart Community Builder
*                              -------------------
*     begin                : Mon Mar 23 2006
*     copyright            : (C) 2007 BoonEx Group
*     website              : http://www.boonex.com
* This file is part of Dolphin - Smart Community Builder
*
* Dolphin is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License as published by the
* Free Software Foundation; either version 2 of the
* License, or  any later version.
*
* Dolphin is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU General Public License for more details.
* You should have received a copy of the GNU General Public License along with Dolphin,
* see license.txt file; if not, write to marketing@boonex.com
***************************************************************************/

define('BX_CATEG_ACTION_ALL', 'all');
define('BX_CATEG_ACTION_COMMON', 'common');
define('BX_CATEG_ACTION_USERS', 'users');
define('BX_CATEG_ACTION_CALENDAR', 'calendar');
define('BX_CATEG_ACTION_SEARCH', 'search');

require_once( 'inc/header.inc.php' );
require_once( BX_DIRECTORY_PATH_INC . 'design.inc.php' );

bx_import('BxDolTemplate');
bx_import('BxTemplCategories');
bx_import('BxDolPageView');
bx_import('BxTemplCalendar');

$bAjaxMode = isset($_SERVER['HTTP_X_REQUESTED_WITH']) and $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' ? true : false;

function showCategories($aParam = array(), $iBoxId = 1, $sAction = '', $isBox = false, $bOrderPanel = false, $sTitle)
{
    $oCateg = new BxTemplCategories();

    $oCateg->getTagObjectConfig($aParam);

    if (empty($oCateg->aTagObjects))
    {
        if ($isBox)
            return DesignBoxContent($sTitle, MsgBox(_t('_Empty')), 1);
        else
            return MsgBox(_t('_Empty'));
    }

    $aParam['type'] = isset($_GET['tags_mode']) && isset($oCateg->aTagObjects[$_GET['tags_mode']]) ? $_GET['tags_mode'] : $oCateg->getFirstObject();

    $sCode = $oCateg->display($aParam, $iBoxId, $sAction, $bOrderPanel, getParam('categ_show_columns'));

    if ($isBox)
    {
        $aCaptionMenu = $oCateg->getCategTopMenuHtml($aParam, $iBoxId, $sAction);
        $sCode = DesignBoxContent($sTitle, $sCode, 1, $aCaptionMenu);
        $sCode = '<div id="page_block_' . $iBoxId . '">' . $sCode . '<div class="clear_both"></div></div>';
        return $sCode;
    }
    else
        return array(
            $sCode,
            $oCateg->getCategTopMenu($aParam, $sAction),
            array(),
            ($sDate ? _t('_categ_by_day') . $sDate : '')
        );
}


class CategoriesCalendar extends BxTemplCalendar
{
    function CategoriesCalendar($iYear, $iMonth)
    {
        parent::BxTemplCalendar($iYear, $iMonth);
    }

    function display() {
        $aVars = array (
            'bx_repeat:week_names' => $this->_getWeekNames (),
            'bx_repeat:calendar_row' => $this->_getCalendar (),
            'month_prev_url' => $this->getBaseUri () . "&year={$this->iPrevYear}&month={$this->iPrevMonth}",
            'month_prev_name' => _t('_month_prev'),
            'month_prev_icon' => getTemplateIcon('sys_back.png'),
            'month_next_url' => $this->getBaseUri () . "&year={$this->iNextYear}&month={$this->iNextMonth}",
            'month_next_name' => _t('_month_next'),
            'month_next_icon' => getTemplateIcon('sys_next.png'),
            'month_current' => $this->getTitle(),
        );

        $oSysTemplate = BxDolTemplate::getInstance();

        $sHtml = $oSysTemplate->parseHtmlByName('calendar.html', $aVars);
        $sHtml = preg_replace ('#<bx_repeat:events>.*?</bx_repeat:events>#s', '', $sHtml);
        $oSysTemplate->addCss('calendar.css');
        return $sHtml;
    }

    function getData()
    {
        $oDb = new BxDolDb();

        return $oDb->getAll("SELECT *, DAYOFMONTH(`Date`) AS `Day`
            FROM `sys_categories`
            WHERE `Date` >= TIMESTAMP('{$this->iYear}-{$this->iMonth}-1')
            AND `Date` < TIMESTAMP('{$this->iNextYear}-{$this->iNextMonth}-1')
            AND `Owner` <> 0 AND `Status` = 'active'");
    }

    function getBaseUri()
    {
        return BX_DOL_URL_ROOT . 'categories.php?action=calendar';
    }

    function getBrowseUri()
    {
        return BX_DOL_URL_ROOT . 'categories.php?action=calendar';
    }

    function getEntriesNames ()
    {
        return array(_t('_categ_single'), _t('_categ_plural'));
    }

    function _getCalendar ()
    {
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
                        'href' => $sBrowseUri . '&year=' . $this->iYear . '&month=' . $this->iMonth . '&day=' . $aCell['day'],
                        'entries' => 1 == $aCalendarGrid[$i][$j]['num'] ? $sEntriesSingle : $sEntriesMul,
                    ));
                    $isRowEmpty = false;
                } elseif (isset($aCalendarGrid[$i][$j]['day'])) {
                    $aCell['class'] = 'sys_cal_cell';
                    $aCell['day'] = $aCalendarGrid[$i][$j]['day'];
                    $aCell['bx_if:num'] = array ('condition' => $aCalendarGrid[$i][$j]['num'], 'content' => array(
                        'num' => $aCalendarGrid[$i][$j]['num'],
                        'href' => $sBrowseUri . '&year=' . $this->iYear . '&month=' . $this->iMonth . '&day=' . $aCell['day'],
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

}

class CategoriesCalendarPage extends BxDolPageView
{
    var $sPage;

    function CategoriesCalendarPage()
    {
        $this->sPage = 'categ_calendar';
        parent::BxDolPageView($this->sPage);
    }

    function getBlockCode_Calendar($iBlockId)
    {
        $sYear = isset($_GET['year']) ? (int)$_GET['year'] : '';
        $sMonth = isset($_GET['month']) ? (int)$_GET['month'] : '';
        $oCalendar = new CategoriesCalendar($sYear, $sMonth);

        return $oCalendar->display();
    }

    function getBlockCode_CategoriesDate($iBlockId)
    {
        if (isset($_GET['year']) && isset($_GET['month']) && isset($_GET['day']))
        {
            $aParam = array(
                'pagination' => getParam('categ_perpage_browse'),
                'date' => array(
                    'year' => (int)$_GET['year'],
                    'month' => (int)$_GET['month'],
                    'day' => (int)$_GET['day']
                )
            );

            return showCategories($aParam, $iBlockId, BX_CATEG_ACTION_CALENDAR, false, false, _t('_categ_caption_calendar'));
        }
        else
            return MsgBox(_t('_Empty'));
    }
}

class CategoriesSearchPage extends BxDolPageView {

    var $aSearchForm;
    var $oForm;
    var $sPage;

    function CategoriesSearchPage()
    {
        $this->sPage = 'categ_search';
        parent::BxDolPageView($this->sPage);

        bx_import('BxTemplFormView');
        $this->aSearchForm = array(
            'form_attrs' => array(
                'name'     => 'form_search_categories',
                'action'   => '',
                'method'   => 'post',
            ),

            'params' => array (
                'db' => array(
                    'submit_name' => 'submit_form',
                ),
            ),

            'inputs' => array(
                'Keyword' => array(
                    'type' => 'text',
                    'name' => 'Keyword',
                    'caption' => _t('_categ_caption_keyword'),
                    'required' => true,
                    'checker' => array (
                        'func' => 'length',
                        'params' => array(1, 100),
                        'error' => _t ('_categ_err_keyword'),
                    ),
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'Submit' => array (
                    'type' => 'submit',
                    'name' => 'submit_form',
                    'value' => _t('_Submit'),
                    'colspan' => true,
                ),
            ),
        );

        $this->oForm = new BxTemplFormView($this->aSearchForm);
        $this->oForm->initChecker();
    }

    function getBlockCode_Form() {
        return BxDolTemplate::getInstance()->parseHtmlByName('search_tags_box.html', array('form' => $this->oForm->getCode()));
    }

    function getBlockCode_Founded($iBlockId)
    {
        $aParam = array(
            'pagination' => getParam('categ_perpage_browse')
        );
        $sFilter = bx_get('filter');
        if ($sFilter !== false)
            $aParam['filter'] = process_db_input($sFilter);
        else if ($this->oForm->isSubmittedAndValid())
            $aParam['filter'] = $this->oForm->getCleanValue('Keyword');

        if (isset($aParam['filter']))
            return showCategories($aParam, $iBlockId, BX_CATEG_ACTION_SEARCH, false, false, _t('_categ_caption_founded_categ'));
        else
            return MsgBox(_t('_Empty'));
    }
}

function getPage_All()
{
    $aParam = array(
        'pagination' => getParam('categ_perpage_browse')
    );

    return showCategories($aParam, 1, BX_CATEG_ACTION_ALL, true, true, _t('_categ_caption_all'));
}

function getPage_Common()
{
    $aParam = array(
        'pagination' => getParam('categ_perpage_browse'),
        'common' => true,
    );

    return showCategories($aParam, 1, BX_CATEG_ACTION_COMMON, true, false, _t('_categ_caption_common'));
}

function getPage_Users()
{
    $aParam = array(
        'pagination' => getParam('categ_perpage_browse'),
        'common' => false
    );

    return showCategories($aParam, 1, BX_CATEG_ACTION_USERS, true, false, _t('_categ_caption_users'));
}

function getPage_Calendar()
{
    $oCalendarPage = new CategoriesCalendarPage();

    return $oCalendarPage->getCode();
}

function getPage_Search()
{
    $oSearchPage = new CategoriesSearchPage();

    return $oSearchPage->getCode();
}

$sAction = empty($_GET['action']) ? '' : $_GET['action'];
switch ($sAction)
{
    case BX_CATEG_ACTION_COMMON:
        $sContent = getPage_Common();
        break;

    case BX_CATEG_ACTION_USERS:
        $sContent = getPage_Users();
        break;

    case BX_CATEG_ACTION_CALENDAR:
        $sContent = getPage_Calendar();
        break;

    case BX_CATEG_ACTION_SEARCH:
        $sContent = getPage_Search();
        break;

    default:
        $sContent = getPage_All();
}

if (!$bAjaxMode) {
    $oTemplate = BxDolTemplate::getInstance();
    $oTemplate->setPageNameIndex(25);
    $oTemplate->setPageParams(array(
        'header' => _t('_Categories'),
        'header_text' => _t('_Categories'),
    ));
    $oTemplate->setPageContent('page_main_code', $sContent);

    check_logged();
    PageCode();
}
else
    echo $sContent;
