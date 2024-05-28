<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_AGENTS_TYPE_SETTINGS', 'settings');
define('BX_DOL_STUDIO_AGENTS_TYPE_AUTOMATORS', 'automators');
define('BX_DOL_STUDIO_AGENTS_TYPE_PROVIDERS', 'providers');

define('BX_DOL_STUDIO_AGENTS_TYPE_DEFAULT', BX_DOL_STUDIO_AGENTS_TYPE_SETTINGS);

class BxDolStudioAgents extends BxTemplStudioWidget
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('agents');

        $this->sPage = BX_DOL_STUDIO_AGENTS_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }
}

/** @} */
