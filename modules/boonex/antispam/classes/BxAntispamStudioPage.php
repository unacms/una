<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
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
            array('name' => 'help', 'icon' => 'question', 'title' => '_bx_antispam_help'),
        );
    }

    function getHelp ()
    {
        return _t('_bx_antispam_help_text');
    }
}

/** @} */
