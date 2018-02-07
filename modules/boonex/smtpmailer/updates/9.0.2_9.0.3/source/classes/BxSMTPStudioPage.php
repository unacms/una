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
            array('name' => 'help', 'icon' => 'question', 'title' => '_bx_smtp_help'),
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


}

/** @} */
