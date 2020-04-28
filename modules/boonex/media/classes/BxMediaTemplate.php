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
class BxMediaTemplate extends BxDolModuleTemplate
{
    function __construct(&$oConfig, &$oDb)
    {
        $this->MODULE = 'bx_media';
        parent::__construct($oConfig, $oDb);
    }
}

/** @} */
