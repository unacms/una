<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

class BxDolCronTranscoder extends BxDolCron
{
    public function processing()
    {
        set_time_limit(10800);
        ignore_user_abort();

        if (!defined('BX_TRANSCODER_NO_TRANSCODING'))
            BxDolTranscoder::processQueue();

        if (defined('BX_TRANSCODER_PROCESS_COMPLETED'))
            BxDolTranscoder::processCompleted(); 
    }
}

/** @} */
