<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOAuthStudioPage extends BxTemplStudioModule
{
    protected $oModule;

    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        $this->oModule = BxDolModule::getInstance('bx_oauth');

        $this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            array('name' => 'help', 'icon' => 'question', 'title' => '_bx_oauth_help'),
        );
    }

    function getSettings ()
    {
    	$this->oModule->_oTemplate->addStudioJs(array('BxDolGrid.js'));
    	$this->oModule->_oTemplate->addStudioCss(array('grid.css'));
        return $this->oModule->studioSettings();
    }

    function getHelp ()
    {
        return _t('_bx_oauth_help_text', BX_DOL_URL_ROOT);
    }
}

/** @} */
