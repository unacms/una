<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    MediaManager MediaManager
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxMediaConfig extends BxDolModuleConfig
{
    function __construct($aModule)
    {
        parent::__construct($aModule);
        $this->CNF = array ();
    }
}

/** @} */
