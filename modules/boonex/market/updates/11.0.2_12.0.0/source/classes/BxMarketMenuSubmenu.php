<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Market Market
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * General class for module menu.
 */
class BxMarketMenuSubmenu extends BxBaseModGeneralMenuSubmenuMoreAuto
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->_sModule = 'bx_market';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
