<?php

// TODO: decide later what to do with text* classes and module, it looks like they will stay and text modules will be still based on it, but some refactoring is needed

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

bx_import('BxTemplCalendar');

class BxDolTextCalendar extends BxTemplCalendar {
    var $_oDb;
    var $_oConfig;
    var $sCssPrefix;

    var $iBlockID = 0;
    var $sDynamicUrl = '';

    function BxDolTextCalendar($iYear, $iMonth, &$oDb, &$oConfig) {
        parent::BxTemplCalendar($iYear, $iMonth);

        $this->_oDb = &$oDb;
        $this->_oConfig = &$oConfig;

        $this->sCssPrefix = '';
    }
    /**
     * return records for current month
     */
    function getData () {
        return $this->_oDb->getByMonth($this->iYear, $this->iMonth, $this->iNextYear, $this->iNextMonth);
    }

    /**
     * return html for data unit for some day.
     */
    function getUnit (&$aData) {
        $sUrl = BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'view/' . $aData['uri'];
        return '<div class="' . $this->sCssPrefix . '-calendar-unit"><a href="' . $sUrl . '" title="' . $aData['caption'] . '">' . $aData['caption'] . '</a></div>';
    }

    /**
     * return base calendar url
     */
    function getBaseUri () {
        return BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . "calendar/";
    }

    function getBrowseUri () {
        return BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'calendar/';
    }

    function getEntriesNames () {
        $sModuleUri = $this->_oConfig->getUri();
        return array(_t('_' . $sModuleUri . '_entry_single'), _t('_' . $sModuleUri . '_entry_plural'));
    }
}
?>
