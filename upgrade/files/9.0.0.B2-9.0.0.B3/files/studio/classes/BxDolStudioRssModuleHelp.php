<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

class BxDolStudioRssModuleHelp extends BxTemplRss
{
    function __construct($aObject)
    {
        parent::__construct($aObject);
    }

    public function getUrl($mixedId)
    {
		return BxDolStudioModule::getObjectInstance($mixedId)->getRssHelpUrl();
    }
}

/** @} */
