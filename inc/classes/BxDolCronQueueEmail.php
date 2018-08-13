<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronQueueEmail extends BxDolCron
{
    public function processing()
    {
    	BxDolQueueEmail::getInstance()->send();
    }
}

/** @} */
