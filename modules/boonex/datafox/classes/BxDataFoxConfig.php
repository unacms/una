<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DataFox Data Fox API integration
 * @ingroup     UnaModules
 *
 * @{
 */

class BxDataFoxConfig extends BxDolModuleConfig
{
    public $sApiUrl = 'https://api.datafox.co/1.0/';

    function __construct($aModule)
    {
        parent::__construct($aModule);
    }
}

/** @} */
