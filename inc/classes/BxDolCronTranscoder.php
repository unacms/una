<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinCore Dolphin Core
 * @{
 */

bx_import('BxDolCron');

class BxDolCronTranscoder extends BxDolCron
{
    public function processing()
    {
        set_time_limit(10800);
        ignore_user_abort();

        bx_import('BxDolTranscoder');
        BxDolTranscoder::processQueue();
    }
}

/** @} */
