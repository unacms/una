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

    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->oModule = BxDolModule::getInstance('bx_oauth');

        $this->aMenuItems = array_merge($this->aMenuItems, [
            'keys' => ['name' => 'keys', 'icon' => 'key', 'title' => '_bx_oauth_lmi_cpt_keys'],
            'help' => ['name' => 'help', 'icon' => 'question', 'title' => '_bx_oauth_help']
        ]);
    }

    function getKeys ()
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
