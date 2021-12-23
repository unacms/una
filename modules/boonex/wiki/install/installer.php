<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Wiki Wiki
 * @ingroup     UnaModules
 *
 * @{
 */

class BxWikiInstaller extends BxDolStudioInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }

    public function uninstall($aParams, $bAutoDisable = false)
    {
        BxDolWiki::onModuleUninstall($this->_aConfig['name']);
        return parent::uninstall($aParams, $bAutoDisable);
    }
}

/** @} */
