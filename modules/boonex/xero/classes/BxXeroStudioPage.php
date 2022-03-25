<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Xero Xero
 * @ingroup     UnaModules
 *
 * @{
 */

require_once ('BxXeroStudioOptions.php');

class BxXeroStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_xero';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems['authorize'] = ['name' => 'authorize', 'icon' => 'sign-in-alt', 'title' => '_bx_xero_lmi_cpt_authorize'];
    }

    protected function getSettings()
    {
        $oOptions = new BxXeroStudioOptions($this->sModule);

        $this->aPageCss = array_merge($this->aPageCss, $oOptions->getCss());
        $this->aPageJs = array_merge($this->aPageJs, $oOptions->getJs());
        return BxDolStudioTemplate::getInstance()->parseHtmlByName('module.html', array(
            'content' => $oOptions->getCode(),
        ));
    }

    protected function getAuthorize()
    {
        $this->_oModule->_oTemplate->addStudioCss(['main.css']);
        $this->_oModule->_oTemplate->addStudioJs(['main.js']);
        return $this->_oModule->_oTemplate->getBlockAuthorize(bx_get('code'));
    }
}

/** @} */
