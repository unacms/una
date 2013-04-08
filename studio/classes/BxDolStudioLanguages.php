<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxTemplStudioModules');
bx_import('BxTemplStudioFunctions');
bx_import('BxDolStudioTemplate');
bx_import('BxDolStudioLanguagesQuery');

class BxDolStudioLanguages extends BxTemplStudioModules {
    function BxDolStudioLanguages() {
        parent::BxTemplStudioModules();

        $this->oDb = new BxDolStudioLanguagesQuery();

        $this->sJsObject = 'oBxDolStudioLanguages';
        $this->sLangPrefix = 'lang';
        $this->sTemplPrefix = 'lang';
        $this->sParamPrefix = 'lang';
    }
}
/** @} */