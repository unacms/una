<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Accounts Accounts
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAccntStudioPage extends BxTemplStudioModule
{
    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        $oPermalink = BxDolPermalinks::getInstance();

        $this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            array('name' => 'manage', 'icon' => 'wrench', 'title' => '_bx_accnt_menu_item_title_manage', 'link' => BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=accounts-administration')),
        );
    }
}

/** @} */
