<?php defined('BX_DOL') or die('hack attempt');
/**
* Copyright (c) UNA, Inc - https://una.io
* MIT License - https://opensource.org/licenses/MIT
*
* @defgroup    UnaCore UNA Core
* @{
*/

class BxDolCronBackgroundJobs extends BxDolCron
{
    public function processing()
    {
        BxDolBackgroundJobs::getInstance()->processAll();
    }
}

/** @} */