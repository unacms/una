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

bx_import('BxDolModule');

/**
 * Profiler module by BoonEx
 *
 * This module estimate timining, like page openings, mysql queries execution and service calls.
 * Also it can log too long queries, so you can later investigate these bottle necks and speedup whole script.
 *
 * To enable profiler you need install it and add the following lines to the beginning of inc/header.inc.php file
 *
 * define ('BX_PROFILER', true);
 * if (BX_PROFILER && !isset($GLOBALS['bx_profiler_start']))
 *     $GLOBALS['bx_profiler_start'] = microtime ();
 *
 */
class BxProfilerModule extends BxDolModule {


    function BxProfilerModule(&$aModule) {
        parent::BxDolModule($aModule);
    }

    function actionHome () {
        $this->_oTemplate->pageStart();
        echo $this->_aModule['title'];
        $this->_oTemplate->pageCode($this->_aModule['title']);
    }

    function actionAdministration () {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $this->_oTemplate->pageStart();

        $iId = $this->_oDb->getSettingsCategory();
        if(empty($iId)) {
            echo MsgBox(_t('_sys_request_page_not_found_cpt'));
            $this->_oTemplate->pageCodeAdmin (_t('_bx_profiler_administration'));
            return;
        }

        bx_import('BxDolAdminSettings');

        $mixedResult = '';
        if(isset($_POST['save']) && isset($_POST['cat'])) {
            $oSettings = new BxDolAdminSettings($iId);
            $mixedResult = $oSettings->saveChanges($_POST);
        }

        $oSettings = new BxDolAdminSettings($iId);
        $sResult = $oSettings->getForm();

        if($mixedResult !== true && !empty($mixedResult))
            $sResult = $mixedResult . $sResult;

        $aVars = array (
            'content' => $sResult,
        );
        echo $this->_oTemplate->adminBlock ($this->_oTemplate->parseHtmlByName('default_padding', $aVars), _t('_bx_profiler_administration'));

        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_adv.css');
        $this->_oTemplate->pageCodeAdmin (_t('_bx_profiler_administration'));
    }

    function isAdmin () {
        return $GLOBALS['logged']['admin'];
    }
}

?>
