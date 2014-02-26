<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Antispam Antispam
 * @ingroup     DolphinModules
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
        if ('account' != $oAlert->sUnit)
            return;

        switch ($oAlert->sAction) {
            case 'check_login':
                $oAlert->aExtras['error_msg'] = BxDolService::call('bx_antispam', 'check_login');
                break;
            case ''; // TODO: check join
                break;
        }
        
    }
}

/** @} */
