<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Developer Developer
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDevStudioPage extends BxTemplStudioModule
{
    protected $oModule;
    protected $sUrl;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aPageCodeNoWrap = array('polyglot', 'forms', 'navigation', 'pages');

        $this->oModule = BxDolModule::getInstance($this->sModule);

        $this->sUrl = BX_DOL_URL_STUDIO . 'module.php?name=%s&page=%s';
    }

    function getPageMenu($aMenu = array(), $aMarkers = array())
    {
        $this->aMenuItems = array();
        foreach($this->oModule->aTools as $aTool)
            $this->aMenuItems[] = array(
                'name' => $aTool['name'],
                'icon' => $aTool['icon'],
                'link' => sprintf($this->sUrl, $this->sModule, $aTool['name']),
                'title' => $aTool['title'],
                'selected' => $aTool['name'] == $this->sPage
            );

        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_main_dev.html', 'menu_items' => $this->aMenuItems), $this->oModule->_oTemplate);
        return $oMenu->getCode();
    }

    protected function getSettings()
    {
        $oOptions = new BxTemplStudioOptions('system', 'hidden');
        $oOptions->enableManage(true);

        if(($mixedResult = $oOptions->checkAction()) !== false) {
            echoJson($mixedResult);
            exit;
        }

        $this->aPageCss = array_merge($this->aPageCss, $oOptions->getCss());
        $this->aPageJs = array_merge($this->aPageJs, $oOptions->getJs());
        return $this->oModule->_oTemplate->displayPageSettings($this->sPage, $oOptions);
    }

    protected function getForms()
    {
        $sPage = bx_get('form_page');
        $sPage = $sPage !== false ? bx_process_input($sPage) : '';

        bx_import('Forms', $this->aModule);
        $oContent = new BxDevForms(array(
            'page' => $sPage,
            'url' => sprintf($this->sUrl, $this->sModule, BX_DEV_TOOLS_FORMS),
        ));

        if(($mixedResult = $oContent->checkAction()) !== false) {
            echoJson($mixedResult);
            exit;
        }

        return $this->oModule->_oTemplate->displayPageContent($this->sPage, $oContent);
    }

    protected function getNavigation()
    {
        $sPage = bx_get('nav_page');
        $sPage = $sPage !== false ? bx_process_input($sPage) : '';

        bx_import('Navigation', $this->aModule);
        $oContent = new BxDevNavigation(array(
            'page' => $sPage,
            'url' => sprintf($this->sUrl, $this->sModule, BX_DEV_TOOLS_NAVIGATION),
        ));

        if(($mixedResult = $oContent->checkAction()) !== false) {
            echoJson($mixedResult);
            exit;
        }

        return $this->oModule->_oTemplate->displayPageContent($this->sPage, $oContent);
    }

    protected function getPages()
    {
        $sType = bx_get('bp_type');
        $sType = $sType !== false ? bx_process_input($sType) : '';

        $sPage = bx_get('bp_page');
        $sPage = $sPage !== false ? bx_process_input($sPage) : '';

        bx_import('BuilderPage', $this->aModule);
        $oContent = new BxDevBuilderPage(array(
            'type' => $sType,
            'page' => $sPage,
            'url' => sprintf($this->sUrl, $this->sModule, BX_DEV_TOOLS_PAGES),
        ));

        if(($mixedResult = $oContent->checkAction()) !== false) {
            echoJson($mixedResult);
            exit;
        }

        return $this->oModule->_oTemplate->displayPageContent($this->sPage, $oContent);
    }

    protected function getPolyglot()
    {
        $sType = bx_get('pgt_type');
        $sType = $sType !== false ? bx_process_input($sType) : '';

        $sPage = bx_get('pgt_page');
        $sPage = $sPage !== false ? bx_process_input($sPage) : 'manager';

        bx_import('Polyglot', $this->aModule);
        $oContent = new BxDevPolyglot(array(
            'page' => $sPage,
            'url' => sprintf($this->sUrl, $this->sModule, BX_DEV_TOOLS_POLYGLOT),
        ));

        if(($mixedResult = $oContent->checkAction()) !== false) {
            echoJson($mixedResult);
            exit;
        }

        return $this->oModule->_oTemplate->displayPageContent($this->sPage, $oContent);
    }

    protected function getPermissions()
    {
        $sPage = bx_get('prm_page');
        $sPage = $sPage !== false ? bx_process_input($sPage) : '';

        bx_import('Permissions', $this->aModule);
        $oContent = new BxDevPermissions(array(
            'page' => $sPage,
            'url' => sprintf($this->sUrl, $this->sModule, BX_DEV_TOOLS_PERMISSIONS),
        ));

        if(($mixedResult = $oContent->checkAction()) !== false) {
            echoJson($mixedResult);
            exit;
        }

        return $this->oModule->_oTemplate->displayPageContent($this->sPage, $oContent);
    }
}

/** @} */
