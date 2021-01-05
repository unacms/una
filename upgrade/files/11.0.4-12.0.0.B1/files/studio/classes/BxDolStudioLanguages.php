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

        $this->sLangPrefix = 'lang';
        $this->sParamPrefix = 'lang';

        $this->sActionUri = 'language.php';
        $this->sJsClass = 'BxDolStudioLanguage';
        $this->sJsObject = 'oBxDolStudioLanguage';

        $this->_oDb = new BxDolStudioLanguagesQuery();
    }
}

/** @} */
