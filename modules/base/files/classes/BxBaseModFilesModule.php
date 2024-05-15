<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseFile Base classes for files modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModFilesModule extends BxBaseModTextModule
{
    public function __construct(&$oConfig)
    {
        parent::__construct($oConfig);
    }
}

/** @} */
