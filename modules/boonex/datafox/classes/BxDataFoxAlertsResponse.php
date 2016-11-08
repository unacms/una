<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    DataFox Data Fox API integration
 * @ingroup     UnaModules
 *
 * @{
 */

/**
 * alerts handler
 */
class BxDataFoxAlertsResponse extends BxDolAlertsResponse
{
    public function response($oAlert)
    {
        if ('system' != $oAlert->sUnit || 'clear_xss' != $oAlert->sAction || !getParam('bx_datafox_id'))
            return;

        $a = $oAlert->aExtras;
        $a['return_data'] = BxDolService::call('bx_datafox', 'parse_text', array($a['return_data']));
    }
}

/** @} */
