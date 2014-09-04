<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplStudioModule');

class BxDevStudioPage extends BxTemplStudioModule
{
    protected $oModule;
    protected $sUrl;

    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        bx_import('BxDolModule');
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

        bx_import('BxTemplStudioMenu');
        $oMenu = new BxTemplStudioMenu(array('template' => 'menu_main.html', 'menu_items' => $this->aMenuItems), $this->oModule->_oTemplate);
        return $oMenu->getCode();
    }

    protected function getSettings()
    {
        bx_import('BxTemplStudioSettings');
        $oContent = new BxTemplStudioSettings($this->sModule);

        return $this->oModule->_oTemplate->displayPageContent($this->sPage, $oContent);
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
        $oContent->init();
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
        return $this->oModule->_oTemplate->displayPageContent($this->sPage, $oContent);
    }
}

/** @} */
