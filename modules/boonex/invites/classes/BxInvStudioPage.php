<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Accounts Accounts
 * @ingroup     TridentModules
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
