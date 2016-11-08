<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DataFox Data Fox API integration
 * @ingroup     UnaModules
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
