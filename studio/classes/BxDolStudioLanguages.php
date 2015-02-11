<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
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
