<?php

// TODO: decide later what to do with twig* classes and module, it looks like they will stay and 'complex' modules will be still based on it, but some refactoring is needed

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

bx_import('BxTemplPage');

/**
 * Base module homepage class for modules like events/groups/store
 */
class BxDolTwigPageMain extends BxTemplPage {

    var $oMain;
    var $oTemplate;
    var $oConfig;
    var $oDb;
    var $sUrlStart;
    var $sSearchResultClassName;
    var $sFilterName;

    function __construct($aObject, $oTemplate) {
        //$this->sUrlStart = BX_DOL_URL_ROOT . $this->oMain->_oConfig->getBaseUri();
        //$this->sUrlStart .= (false === strpos($this->sUrlStart, '?') ? '?' : '&');
        parent::__construct($aObject, $oTemplate);
    }

    function ajaxBrowse($sMode, $iPerPage, $aMenu = array(), $sValue = '', $isDisableRss = false, $isPublicOnly = true) {

        bx_import ('SearchResult', $this->oMain->_aModule);
        $sClassName = $this->sSearchResultClassName;
        $o = new $sClassName($sMode, $sValue);
        $o->aCurrent['paginate']['perPage'] = $iPerPage;
        $o->setPublicUnitsOnly($isPublicOnly);

        if (!$aMenu)
            $aMenu = ($isDisableRss ? '' : array(_t('RSS') => array('href' => $o->aCurrent['rss']['link'] . (false === strpos($o->aCurrent['rss']['link'], '?') ? '?' : '&') . 'rss=1', 'icon' => getTemplateIcon('rss.png'))));

        if ($o->isError)
            return array(MsgBox(_t('_Error Occured')), $aMenu);

        if (!($s = $o->displayResultBlock()))
            return $isPublicOnly ? array(MsgBox(_t('_Empty')), $aMenu) : '';


        $sFilter = (false !== bx_get($this->sFilterName)) ? $this->sFilterName . '=' . bx_get($this->sFilterName) . '&' : '';

        bx_import('BxTemplPaginate');
        $oPaginate = new BxTemplPaginate(array(
            'page_url' => 'javascript:void(0);',
            'num' => $o->aCurrent['paginate']['totalNum'],
            'per_page' => $o->aCurrent['paginate']['perPage'],
            'page' => $o->aCurrent['paginate']['page'],
            'on_change_page' => 'return !loadDynamicBlock({id}, \'' . $this->sUrlStart . $sFilter . 'page={page}&per_page={per_page}\');',
        ));
        $sAjaxPaginate = $oPaginate->getSimplePaginate($this->oMain->_oConfig->getBaseUri() . $o->sBrowseUrl);
        
/*
        return array(
            $s,
            $aMenu,
            $sAjaxPaginate,
            '');
*/
        return $s . $sAjaxPaginate; // TODO:
    }

}

?>
