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

bx_import('BxDolPageView');

class BxGSearchPageMain extends BxDolPageView {

    var $_oTemplate;
    var $_oConfig;

    function BxGSearchPageMain(&$oModule) {
        $this->_oTemplate = $oModule->_oTemplate;
        $this->_oConfig = $oModule->_oConfig;
        parent::BxDolPageView('bx_gsearch');
    }

    function getBlockCode_SearchForm() {
        $aVars = array (
            'suffix' => 'adv',
            'empty' => MsgBox(_t('_Empty')),
        );
        return $this->_oTemplate->parseHtmlByName('search_form', $aVars);
    }

    function getBlockCode_SearchResults() {
        $this->_oTemplate->addJs ('http://www.google.com/jsapi');
        $a = parse_url ($GLOBALS['site']['url']);
        $aVars = array (
            'is_image_search' => 'on' == getParam('bx_gsearch_separate_images') ? 1 : 0,
            'is_tabbed_search' => 'on' == getParam('bx_gsearch_separate_tabbed') ? 1 : 0,
            'domain' => $a['host'],
            'keyword' => str_replace('"', '\\"', stripslashes($_GET['keyword'])),
            'suffix' => 'adv',
            'separate_search_form' => 1,
        );
        return $this->_oTemplate->parseHtmlByName('search', $aVars);
    }
}

?>
