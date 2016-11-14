<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioDesigns extends BxTemplStudioModules
{
    function __construct()
    {
        parent::__construct();

        $this->oDb = new BxDolStudioDesignsQuery();

        $this->sJsObject = 'oBxDolStudioDesigns';
        $this->sLangPrefix = 'dsn';
        $this->sTemplPrefix = 'dsn';
        $this->sParamPrefix = 'dsn';
    }
}

/** @} */
