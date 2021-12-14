<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Xero Xero
 * @ingroup     UnaModules
 *
 * @{
 */

class BxXeroStudioSettings extends BxTemplStudioSettings
{
    protected function getCustomValueRedirectUrl($aItem, $mixedValue)
    {
        return bx_replace_markers($mixedValue, array(
            'site_url' => BX_DOL_URL_ROOT
        ));
    }
    protected function getCustomValueWebhookUrl($aItem, $mixedValue)
    {
        return bx_replace_markers($mixedValue, array(
            'site_url' => BX_DOL_URL_ROOT
        ));
    }
}