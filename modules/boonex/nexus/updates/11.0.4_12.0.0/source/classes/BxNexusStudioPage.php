<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Nexus Nexus - Mobile Apps and Desktop apps connector
 * @ingroup     UnaModules
 *
 * @{
 */

class BxNexusStudioPage extends BxTemplStudioModule
{
    function __construct($sModule, $mixedPageName, $sPage = "")
    {
        parent::__construct($sModule, $mixedPageName, $sPage);

        $this->aMenuItems = array(
            'settings' => array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            'help' => array('name' => 'help', 'icon' => 'question', 'title' => '_bx_nexus_information'),
        );
    }

    function getHelp ()
    {
        return _t('_bx_nexus_information_block');
    }
}

/** @} */
