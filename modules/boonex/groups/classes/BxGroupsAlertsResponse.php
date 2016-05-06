<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Groups Groups
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * alerts handler
 */
class BxGroupsAlertsResponse extends BxDolAlertsResponse
{
    public function response($oAlert)
    {
        if ('bx_groups_fans' == $oAlert->sUnit && 'connection_added' == $oAlert->sAction && !$oAlert->aExtras['mutual'])
            BxDolService::call('bx_groups', 'add_mutual_connection', array($oAlert->aExtras['content'], $oAlert->aExtras['initiator']));
    }
}

/** @} */
