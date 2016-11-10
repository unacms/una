<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Convos Convos
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * View entry menu
 */
class BxCnvMenuView extends BxBaseModTextMenuView
{
    public function __construct($aObject, $oTemplate = false)
    {
        $this->MODULE = 'bx_convos';
        parent::__construct($aObject, $oTemplate);
    }
}

/** @} */
