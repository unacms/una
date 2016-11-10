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

/**
 * alerts handler
 */
class BxAntispamAlertsResponse extends BxDolAlertsResponse
{
    public function response($oAlert)
    {
        if ('account' == $oAlert->sUnit) {

            switch ($oAlert->sAction) {
                case 'check_login':
                    $oAlert->aExtras['error_msg'] = BxDolService::call('bx_antispam', 'check_login');
                    break;
                case 'check_join';
                    $oAlert->aExtras['error_msg'] = BxDolService::call('bx_antispam', 'check_join', array($oAlert->aExtras['email'], &$oAlert->aExtras['approve']));
                    break;
            }

        } elseif ('system' == $oAlert->sUnit) {

            switch ($oAlert->sAction) {
                case 'check_spam':
                    $oAlert->aExtras['is_spam'] = BxDolService::call('bx_antispam', 'is_spam', array($oAlert->aExtras['content']));
                    break;
            }

        }
    }
}

/** @} */
