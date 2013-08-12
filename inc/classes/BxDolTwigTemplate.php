<?php
/**
 * @package     Dolphin Core
 * @copyright   Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * @license     CC-BY - http://creativecommons.org/licenses/by/3.0/
 */
defined('BX_DOL') or die('hack attempt');

bx_import ('BxDolModuleTemplate');

/**
 * Base template class for modules like events/groups/store
 */
class BxDolTwigTemplate extends BxDolModuleTemplate {
    var $_iPageIndex = 0;
    var $_oMain = null;    

    function BxDolTwigTemplate(&$oConfig, &$oDb, $sRootPath = BX_DIRECTORY_PATH_ROOT, $sRootUrl = BX_DOL_URL_ROOT) {
        parent::BxDolModuleTemplate($oConfig, $oDb, $sRootPath, $sRootUrl);

        if (isset($GLOBALS['oAdmTemplate']))
            $GLOBALS['oAdmTemplate']->addDynamicLocation($this->_oConfig->getHomePath(), $this->_oConfig->getHomeUrl());
    }

    // ======================= common functions

    function addCssAdmin ($sName) {
        if (empty($GLOBALS['oAdmTemplate']))
            return;
        BxDolStudioTemplate::getInstance()->addCss ($sName);
    }

    function addJsAdmin ($sName) {
        if (empty($GLOBALS['oAdmTemplate']))
            return;
        BxDolStudioTemplate::getInstance()->addJs ($sName);
    }

    function parseHtmlByName ($sName, $aVars, $mixedKeyWrapperHtml = null, $sCheckIn = BX_DOL_TEMPLATE_CHECK_IN_BOTH) {
        return parent::parseHtmlByName (strlen($sName) > 5 && strpos($sName, '.html', strlen($sName) - 5) ? $sName : $sName.'.html', $aVars);
    }

    // ======================= page generation functions

    function pageCode ($sTitle, $isDesignBox = true, $isWrap = true) {

        $sCode = $this->pageEnd();

        if (!$sTitle)
            $sTitle = getParam('site_title');

        if ($isDesignBox)
            $sCode = DesignBoxContent($sTitle, $sCode, $isWrap ? BX_DB_PADDING_DEF : BX_DB_DEF);

        $oTemplate = BxDolTemplate::getInstance();//$this;
        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);//$isDesignBox || !$this->_iPageIndex ? BX_PAGE_DEFAULT : $this->_iPageIndex);
        $oTemplate->setPageHeader ($sTitle);
        $oTemplate->setPageContent ('page_main_code', $sCode);

        $oTemplate->addDynamicLocation($this->_oConfig->getHomePath(), $this->_oConfig->getHomeUrl());
        $oTemplate->getPageCode();
    }

    function adminBlock ($sContent, $sTitle, $aMenu = array()) {
        return DesignBoxAdmin($sTitle, $sContent, $aMenu);
    }

    function pageCodeAdmin ($sTitle) {

        $oTemplate = BxDolStudioTemplate::getInstance();

        $oTemplate->setPageNameIndex (BX_PAGE_DEFAULT);
        $oTemplate->setPageHeader ($sTitle ? $sTitle : getParam('site_title'));
        $oTemplate->setPageContent ('page_main_code', $this->pageEnd());

        PageCodeAdmin();
    }

    // ======================= tags/cat parsing functions

    function parseTags ($s) {
        return $this->_parseAnything ($s, ',', BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/tag/');
    }

    function parseCategories ($s) {
        bx_import ('BxDolCategories');
        return $this->_parseAnything ($s, CATEGORIES_DIVIDER, BX_DOL_URL_ROOT . $this->_oConfig->getBaseUri() . 'browse/category/');
    }

    function _parseAnything ($s, $sDiv, $sLinkStart, $sClassName = '') {
        $sRet = '';
        $a = explode ($sDiv, $s);
        $sClass = $sClassName ? 'class="'.$sClassName.'"' : '';
        foreach ($a as $sName)
            $sRet .= '<a '.$sClass.' href="' . $sLinkStart . title2uri($sName) . '">'.$sName.'</a> ';
        return $sRet;
    }

    // ======================= display standard pages functions

    function displayAccessDenied () {
        $this->pageStart();
        echo MsgBox(_t('_Access denied'));
        $this->pageCode (_t('_Access denied'), true, false);
    }

    function displayNoData () {
        $this->pageStart();
        echo MsgBox(_t('_Empty'));
        $this->pageCode (_t('_Empty'), true, false);
    }

    function displayErrorOccured () {
        $this->pageStart();
        echo MsgBox(_t('_Error Occured'));
        $this->pageCode (_t('_Error Occured'), true, false);
    }

    function displayPageNotFound () {
        header("HTTP/1.0 404 Not Found");
        $this->pageStart();
        echo MsgBox(_t('_sys_request_page_not_found_cpt'));
        $this->pageCode (_t('_sys_request_page_not_found_cpt'), true, false);
    }

    function displayMsg ($s, $isTranslate = false) {
        $this->pageStart();
        echo MsgBox($isTranslate ? _t($s) : $s);
        $this->pageCode ($isTranslate ? _t($s) : $s, true);
    }

}

