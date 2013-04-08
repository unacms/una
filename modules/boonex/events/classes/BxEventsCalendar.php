<?php
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

bx_import ('BxDolTwigCalendar');

class BxEventsCalendar extends BxDolTwigCalendar {

    var $oTemplate;

    function BxEventsCalendar ($iYear, $iMonth, &$oDb, &$oTemplate, &$oConfig) {
        parent::BxDolTwigCalendar($iYear, $iMonth, $oDb, $oConfig);
        $this->oTemplate = &$oTemplate;
    }

    function getEntriesNames () {
        return array(_t('_bx_events_single'), _t('_bx_events_plural'));
    }

    /**
     * return html for data unit for some day, it is:
     * - icon 32x32 with link if data have associated image, use $GLOBALS['oFunctions']->sysIcon() to return small icon
     * - data title with link if data have no associated image
     */
/*
    function getUnit (&$aData) {
        if (!isset($this->sNoPicIcon))
            $sNoPicIcon = $this->oTemplate->getIconUrl('no-photo-32.png');

        $sIcon = $sNoPicIcon;
        if ($aData['PrimPhoto']) {
            $aa = array ('ID' => $aData['ResponsibleID'], 'Avatar' => $aData['PrimPhoto']);
            $aIcon = BxDolService::call('photos', 'get_image', array($aa, 'icon'), 'Search');
            if (!$aIcon['no_image'])
                $sIcon = $aIcon['file'];
        }

        $sUrl = BX_DOL_URL_ROOT . $this->oConfig->getBaseUri() . 'view/' . $aData['EntryUri'];
        $sName = $aData['Title'];

        return $GLOBALS['oFunctions']->sysIcon ($sIcon, $sName, $sUrl);
    }
*/

}

?>
