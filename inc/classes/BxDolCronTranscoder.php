<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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

        if (!defined('BX_TRANSCODER_NO_TRANSCODING'))
            BxDolTranscoder::processQueue();

        if (defined('BX_TRANSCODER_PROCESS_COMPLETED'))
            BxDolTranscoder::processCompleted(); 
    }
}

/** @} */
