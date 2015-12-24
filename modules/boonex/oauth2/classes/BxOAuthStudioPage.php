<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    OAuth2 OAuth2 server
 * @ingroup     TridentModules
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
        );
    }

    function getSettings ()
    {
        return $this->oModule->studioSettings();
    }

    function getHelp ()
    {
        return _t('_bx_oauth_help_text', BX_DOL_URL_ROOT);
    }

    protected function getPageCaptionHelp()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_oauth&page=help">' . _t('_bx_oauth_help') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
