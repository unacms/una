<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Snipcart Snipcart
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxSnipcartMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_snipcart';

        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
