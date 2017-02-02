<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Events Events
 * @ingroup     UnaModules
 *
 * @{
 */

class BxEventsCronProcessReminders extends BxDolCron
{
    function processing()
    {
        BxDolService::call('bx_events', 'process_reminders');
    }
}

/** @} */
