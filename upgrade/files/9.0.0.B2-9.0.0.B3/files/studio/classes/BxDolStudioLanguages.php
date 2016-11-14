<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioLanguages extends BxTemplStudioModules
{
    function __construct()
    {
        parent::__construct();

        $this->oDb = new BxDolStudioLanguagesQuery();

        $this->sJsObject = 'oBxDolStudioLanguages';
        $this->sLangPrefix = 'lang';
        $this->sTemplPrefix = 'lang';
        $this->sParamPrefix = 'lang';
    }
}

/** @} */
