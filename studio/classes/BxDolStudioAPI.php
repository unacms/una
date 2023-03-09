<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaStudio UNA Studio
 * @{
 */

define('BX_DOL_STUDIO_API_TYPE_SETTINGS', 'settings');
define('BX_DOL_STUDIO_API_TYPE_KEYS', 'keys');
define('BX_DOL_STUDIO_API_TYPE_ORIGINS', 'origins');

define('BX_DOL_STUDIO_API_TYPE_DEFAULT', BX_DOL_STUDIO_API_TYPE_SETTINGS);

class BxDolStudioAPI extends BxTemplStudioWidget
{
    protected $sPage;

    function __construct($sPage = "")
    {
        parent::__construct('api');

        $this->sPage = BX_DOL_STUDIO_API_TYPE_DEFAULT;
        if(is_string($sPage) && !empty($sPage))
            $this->sPage = $sPage;
    }
}

/** @} */
