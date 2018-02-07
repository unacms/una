<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronQueuePush extends BxDolCron
{
    public function processing()
    {
    	BxDolQueuePush::getInstance()->send((int)getParam('sys_push_queue_send_per_start'));
    }
}

/** @} */
