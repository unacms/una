<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Profiler Profiler
 * @ingroup     UnaModules
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
            ob_start();
            require_once(BX_DIRECTORY_PATH_MODULES . 'boonex/profiler/classes/BxProfiler.php');
        }
    }
}

/** @} */
