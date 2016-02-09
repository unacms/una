<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DataFox Data Fox API integration
 * @ingroup     TridentModules
 *
 * @{
 */

class BxDataFoxStudioPage extends BxTemplStudioModule
{
    protected $oModule;

    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        $this->oModule = BxDolModule::getInstance('bx_datafox');

        $this->aMenuItems = array(
            array('name' => 'general', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
        );
    }
}

/** @} */
