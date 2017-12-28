<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    RocketChat Rocket.Chat integration module
 * @ingroup     UnaModules
 *
 * @{
 */

class BxChatPlusStudioPage extends BxTemplStudioModule
{
    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        $this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            array('name' => 'help', 'icon' => 'question', 'title' => '_bx_chat_plus_information'),
        );
    }
    
    function getHelp ()
    {
        return _t('_bx_chat_plus_information_block');
    }
}

/** @} */
