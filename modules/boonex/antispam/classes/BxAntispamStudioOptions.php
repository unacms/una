<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Antispam Antispam
 * @ingroup     UnaModules
 *
 * @{
 */

class BxAntispamStudioOptions extends BxTemplStudioOptions
{
    protected function getCustomValueLassoModerationWebhookUrl($aItem, $mixedValue)
    {
        return bx_replace_markers($mixedValue, [
            'site_url' => BX_DOL_URL_ROOT
        ]);
    }
}