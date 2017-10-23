<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Decorous Decorous template
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxBaseModGeneralTemplate');

class BxDecorousTemplate extends BxBaseModGeneralTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_decorous';
        parent::__construct($oConfig, $oDb);

        $oMenu = BxDolMenu::getObjectInstance('sys_site');
        if($oMenu === false) {
            BxDolTemplate::getInstance()->addCssStyle('.cd-main-content .content-wrapper', array(
                'margin-left' => '0px !important'
            ));
        }
    }
}

/** @} */
