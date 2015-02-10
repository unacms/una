<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Antispam Antispam
 * @ingroup     TridentModules
 *
 * @{
 */

class BxAntispamStudioPage extends BxTemplStudioModule
{
    protected $oModule;

    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        $this->oModule = BxDolModule::getInstance('bx_antispam');

        $this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            array('name' => 'ip_table', 'icon' => 'align-justify', 'title' => '_bx_antispam_ip_table', 'link' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=antispam-ip-table')),
            array('name' => 'dnsbl_list', 'icon' => 'align-justify', 'title' => '_bx_antispam_dnsbl_list', 'link' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=antispam-dnsbl-list')),
            array('name' => 'block_log', 'icon' => 'clock-o', 'title' => '_bx_antispam_block_log', 'link' => BX_DOL_URL_ROOT . BxDolPermalinks::getInstance()->permalink('page.php?i=antispam-block-log')),
        );
    }

    function getHelp ()
    {
        return _t('_bx_antispam_help_text');
    }

    protected function getPageCaptionHelp()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_antispam&page=help">' . _t('_bx_antispam_help') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
