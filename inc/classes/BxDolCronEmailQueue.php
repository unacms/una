<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronEmailQueue extends BxDolCron
{
    public function processing()
    {
    	BxDolEmailQueue::getInstance()->send((int)getParam('sys_eq_send_per_start'));
    }
}

/** @} */
