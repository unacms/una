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

function bx_gsearch_import ($sClassPostfix, $aModuleOverwright = array()) {
    global $aModule;
    $a = $aModuleOverwright ? $aModuleOverwright : $aModule;
    if (!$a || $a['uri'] != 'google_search') {
        $oMain = BxDolModule::getInstance('bx_google_search');
        $a = $oMain->_aModule;
    }
    bx_import ($sClassPostfix, $a) ;
}

bx_import('BxDolModule');
bx_import('BxDolPaginate');
bx_import('BxDolAlerts');

/**
 * Google Site Search module by BoonEx
 *
 * This module allow user to search the site using Google Site Search
 *
 *
 *
 * Profile's Wall:
 * no wall events
 *
 *
 *
 * Spy:
 * no spy events
 *
 *
 *
 * Memberships/ACL:
 * no acl's  - everybody can use it
 *
 *
 *
 * Service methods:
 *
 * Get search control html
 * @see BxGSearchModule::serviceGetSearchControl
 * BxDolService::call('google_search', 'get_search_control', array());
 *
 *
 *
 * Alerts:
 * no alerts
 *
 */
class BxGSearchModule extends BxDolModule {

    var $_iProfileId;

    function BxGSearchModule(&$aModule) {
        parent::BxDolModule($aModule);
        $GLOBALS['aModule'] = $aModule;
        $this->_iProfileId = $GLOBALS['logged']['member'] || $GLOBALS['logged']['admin'] ? $_COOKIE['memberID'] : 0;
        $GLOBALS['oBxGSearchModule'] = &$this;
    }

    function actionHome () {
        bx_gsearch_import ('PageMain');
        $oPage = new BxGSearchPageMain ($this);
        $this->_oTemplate->pageStart();
        echo $oPage->getCode();
        $this->_oTemplate->addJs ('http://www.google.com/jsapi');
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->pageCode(_t('_bx_gsearch'), false, false);
    }

    function actionAdministration () {

        if (!$this->isAdmin()) {
            $this->_oTemplate->displayAccessDenied ();
            return;
        }

        $iId = $this->_oDb->getSettingsCategory('Google Search');
        if(empty($iId)) {
            $this->_oTemplate->displayPageNotFound ();
            return;
        }

        $this->_oTemplate->pageStart();

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
        echo $this->_oTemplate->adminBlock ($this->_oTemplate->parseHtmlByName('default_padding', $aVars), _t('_bx_gsearch_administration'));

        $this->_oTemplate->addCssAdmin ('main.css');
        $this->_oTemplate->addCssAdmin ('forms_adv.css');
        $this->_oTemplate->pageCodeAdmin (_t('_bx_gsearch_administration'));
    }

    // ================================== service actions

    /**
     * Get search control html.
     * @return html with google search control
     */
    function serviceGetSearchControl () {
        $this->_oTemplate->addCss ('main.css');
        $this->_oTemplate->addJs ('http://www.google.com/jsapi');
        $a = parse_url ($GLOBALS['site']['url']);
        $aVars = array (
            'is_image_search' => 'on' == getParam('bx_gsearch_block_images') ? 1 : 0,
            'is_tabbed_search' => 'on' == getParam('bx_gsearch_block_tabbed') ? 1 : 0,
            'domain' => $a['host'],
            'keyword' => '',
            'suffix' => 'simple',
            'separate_search_form' => 0,
        );
        return $this->_oTemplate->parseHtmlByName('search', $aVars);
    }

    // ================================== other functions

    function isAdmin () {
        return $GLOBALS['logged']['admin'] || $GLOBALS['logged']['moderator'];
    }
}

?>
