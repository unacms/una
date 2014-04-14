<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Profiler Profiler
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxProfilerConfig extends BxDolModuleConfig {
    /**
     * Constructor
     */
    function BxProfilerConfig($aModule) {
        parent::BxDolModuleConfig($aModule);
    }
}

/** @} */
