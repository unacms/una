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

bx_import('BxTemplSearchResult');

/**
 * Base data search class for modules like events/groups/store
 */
class BxDolTwigSearchResult extends BxTemplSearchResult  {

    var $sBrowseUrl;
    var $isError;
    var $aCurrent = array ();
    var $aGlParamsSettings = array();
    var $sProfileCatType;
    var $sUnitTemplate = 'unit.html';
    var $sFilterName;

    function __construct($oFunctions = false) {
        parent::__construct($oFunctions);
    }

    function getMain() {
        // override this to return main module class
    }

    function displaySearchUnit ($aData) {
        $oMain = $this->getMain();
        return $oMain->_oTemplate->unit($aData, $this->sUnitTemplate);
    }

    function showPagination($bAdmin = false, $bChangePage = true, $bPageReload = true) {

        $oMain = $this->getMain();
        $oConfig = $oMain->_oConfig;
        bx_import('BxDolPaginate');
        $sUrlStart = BX_DOL_URL_ROOT . $oConfig->getBaseUri() . $this->sBrowseUrl;
        $sUrlStart .= (false === strpos($sUrlStart, '?') ? '?' : '&');

        bx_import('BxTemplPaginate');
        $oPaginate = new BxTemplPaginate(array(
            'page_url' => $sUrlStart . 'start={start}&per_page={per_page}' . (false !== bx_get($this->sFilterName) ? '&' . $this->sFilterName . '=' . bx_get($this->sFilterName) : ''),
            'num' => $this->aCurrent['paginate']['num'],
            'per_page' => $this->aCurrent['paginate']['perPage'],
            'start' => $this->aCurrent['paginate']['start'],
        ));

        return '<div class="clear_both"></div>'.$oPaginate->getPaginate();
    }

    function showPaginationAjax($sBlockId) {

        $oMain = $this->getMain();
        $oConfig = $oMain->_oConfig;
        bx_import('BxDolPaginate');
        $sUrlStart = BX_DOL_URL_ROOT . $oConfig->getBaseUri() . $this->sBrowseUrl;
        $sUrlStart .= (false === strpos($sUrlStart, '?') ? '?' : '&');

        $oPaginate = new BxDolPaginate(array(
            'page_url' => 'javascript:void(0);',
            'num' => $this->aCurrent['paginate']['num'],
            'per_page' => $this->aCurrent['paginate']['perPage'],
            'start' => $this->aCurrent['paginate']['start'],            
        ));

        return $oPaginate->getSimplePaginate(false, -1, -1, false);
    }

    function rss () {
        return parent::rss();
    }
}

