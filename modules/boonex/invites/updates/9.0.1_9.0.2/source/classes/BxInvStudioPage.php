<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Invites Invites
 * @ingroup     UnaModules
 *
 * @{
 */

class BxInvStudioPage extends BxTemplStudioModule
{
    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);

        $oPermalink = BxDolPermalinks::getInstance();

        $this->aMenuItems = array(
            array('name' => 'settings', 'icon' => 'cogs', 'title' => '_adm_lmi_cpt_settings'),
            array('name' => 'manage', 'icon' => 'edit', 'title' => '_bx_invites_menu_item_title_requests', 'link' => BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=invites-requests')),
        );
    }
}

/** @} */
