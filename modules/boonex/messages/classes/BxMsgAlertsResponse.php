<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Messages Messages
 * @ingroup     DolphinModules
 *
 * @{
 */

/**
 * alerts handler
 */
class BxMsgAlertsResponse extends BxDolAlertsResponse 
{
    public function response($oAlert) 
    {
        if ('bx_messages' == $oAlert->sUnit) {

            switch ($oAlert->sAction) {
                case 'commentPost':
                    BxDolService::call('bx_messages', 'trigger_comment_post', array($oAlert->iObject, $oAlert->iSender));
                    break;
            }

        }
    }
}

/** @} */
