<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DataFox Data Fox API integration
 * @ingroup     TridentModules
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
