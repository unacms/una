<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
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
