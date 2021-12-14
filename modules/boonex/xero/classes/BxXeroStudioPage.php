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

require_once ('BxXeroStudioSettings.php');

class BxXeroStudioPage extends BxTemplStudioModule
{
    protected $_sModule;
    protected $_oModule;

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
    	$this->_sModule = 'bx_xero';
    	$this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems['authorize'] = array('name' => 'authorize', 'icon' => 'sign-in-alt', 'title' => '_bx_xero_lmi_cpt_authorize');
    }

    protected function getSettings()
    {
        $oPage = new BxXeroStudioSettings($this->sModule);

        return BxDolStudioTemplate::getInstance()->parseHtmlByName('module.html', array(
            'content' => $oPage->getFormCode(),
        ));
    }

    protected function getAuthorize()
    {
        $this->_oModule->_oTemplate->addStudioCss(array('main.css'));
        $this->_oModule->_oTemplate->addStudioJs(array('main.js'));
        return $this->_oModule->_oTemplate->getBlockAuthorize(bx_get('code'));
    }
}

/** @} */
