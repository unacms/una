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

bx_import('BxDolModuleTemplate');

class BxDolMobileTemplate extends BxDolModuleTemplate {

    var $_aMobileJs = array ('jquery.js');
    var $_aMobileCss = array ('mobile.css');

	/*
	 * Constructor.
	 */
	function BxDolMobileTemplate(&$oConfig, &$oDb, $sRootPath = BX_DIRECTORY_PATH_ROOT, $sRootUrl = BX_DOL_URL_ROOT) {
		parent::BxDolModuleTemplate($oConfig, $oDb, $sRootPath, $sRootUrl);
	}

	function addMobileCss($mixedFiles) {
        if (is_array($mixedFiles))
            $this->_aMobileJs = array_merge($this->_aMobileJs, $mixedFiles);
        else
            $this->_aMobileJs[] = $mixedFiles;
	}

	function addMobileJs($mixedFiles) {
        if (is_array($mixedFiles))
            $this->_aMobileCss = array_merge($this->_aMobileCss, $mixedFiles);
        else
            $this->_aMobileCss[] = $mixedFiles;
	}
    
    function pageCode ($sTitle, $isDesignBox = true, $isWrap = true) {

        global $_page;        
        global $_page_cont;

        $GLOBALS['BxDolTemplateJs'] = array ();
        $GLOBALS['BxDolTemplateCss'] = array ();
        $this->addCss($this->_aMobileCss);
        $this->addJs($this->_aMobileJs);

        $sOutput = $this->pageEnd();

        if ($isDesignBox) {
            $aVars = array ('content' => $sOutput);
            $sOutput = $this->parseHtmlByName('mobile_box.html', $aVars); 
        }

        if ($isWrap) {
            $aVars = array ('content' => $sOutput);
            $sOutput = $this->parseHtmlByName('mobile_page_padding.html', $aVars);
        }

        $iNameIndex = 11;
        $_page['name_index'] = $iNameIndex; 
        $_page['header'] = $sTitle ? $sTitle : $GLOBALS['site']['title'];
        $_page_cont[$iNameIndex]['page_main_code'] = $sOutput;

        PageCode($this);
    }

    function displayNoData($sCaption = false) {
        $this->displayMsg(_t('_Empty'), $sCaption);
    }

    function displayAccessDenied($sCaption = false) {
        $this->displayMsg(_t('_Access denied'), $sCaption);
    }

    function displayPageNotFound() {
        header("HTTP/1.0 404 Not Found");
        $this->displayMsg(_t('_sys_request_page_not_found_cpt'));
    }

    function displayMsg($sMsg, $bTranslateMsg = false, $sTitle = false, $bTranslateMsg = false) {
        $sMsg = $bTranslateMsg ? _t($sMsg) : $sMsg;
        $sTitle = $bTranslateMsg ? _t($sTitle) : $sTitle;
        echo $sMsg;        
	    $this->pageCode($sTitle ? $sTitle : $sMsg);
    }
}

