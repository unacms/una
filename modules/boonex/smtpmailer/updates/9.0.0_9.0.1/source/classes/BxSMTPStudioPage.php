<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    SMTPMailer SMTP Mailer
 * @ingroup     UnaModules
 *
 * @{
 */

class BxSMTPStudioPage extends BxTemplStudioModule
{
    protected $oModule;

    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        $this->oModule = BxDolModule::getInstance('bx_smtp');

        $this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            array('name' => 'tester', 'icon' => 'envelope', 'title' => '_bx_smtp_tester'),
        );
    }

    function getTester ()
    {
        return $this->oModule->formTester();
    }

    function getHelp ()
    {
        return _t('_bx_smtp_help_text');
    }

    protected function getPageCaptionHelp()
    {
        $oTemplate = BxDolStudioTemplate::getInstance();
        $sContent = '<a href="' . BX_DOL_URL_STUDIO . 'module.php?name=bx_smtp&page=help">' . _t('_bx_smtp_help') . "</a>";
        return $oTemplate->parseHtmlByName('page_caption_help.html', array('content' => $sContent));
    }
}

/** @} */
