<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Profiler Profiler
 * @ingroup     TridentModules
 *
 * @{
 */

/**
 * alerts handler
 */
class BxProfilerAlertsResponse extends BxDolAlertsResponse
{
    public function response($oAlert)
    {
        if ('system' == $oAlert->sUnit && 'begin' == $oAlert->sAction) {
            require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/profiler/classes/BxProfiler.php');
        }
    }
}

/** @} */
