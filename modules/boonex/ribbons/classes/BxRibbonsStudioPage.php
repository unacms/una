<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Ribbons Ribbons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxRibbonsStudioPage extends BxTemplStudioModule
{
    function __construct($sModule = "", $sPage = "")
    {
        parent::__construct($sModule, $sPage);
        $oPermalink = BxDolPermalinks::getInstance();
        $this->aMenuItems[] = array('name' => 'ribbons' , 'icon' => 'bars', 'title' => '_bx_ribbons_lmi_cpt_quotes', 'link' => BX_DOL_URL_ROOT . $oPermalink->permalink('page.php?i=ribbons-manage'));
    }
}

/** @} */
