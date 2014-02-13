<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Antispam Antispam
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxTemplStudioModule');

class BxAntispamStudioPage extends BxTemplStudioModule 
{
    protected $oModule;

    function __construct($sModule = "", $sPage = "") 
    {
        parent::__construct($sModule, $sPage);

        bx_import('BxDolModule');
        $this->oModule = BxDolModule::getInstance('bx_antispam');

        $this->aMenuItems = array(
    	    array('name' => 'general', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
        );
    }

    function getHelp ()
    {
        return _t('_bx_antispam_help_text');
    }

    protected function getPageCaptionHelp() {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_antispam&page=help">' . _t('_bx_antispam_help') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
