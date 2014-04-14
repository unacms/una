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

bx_import('BxDolModuleDb');

/*
 * Profiler module data
 */
class BxProfilerDb extends BxDolModuleDb {
    var $_oConfig;

    function BxProfilerDb(&$oConfig) {
        parent::BxDolModuleDb();
        $this->_oConfig = $oConfig;
    }

    function getSettingsCategory() {
        return $this->getOne("SELECT `ID` FROM `sys_options_cats` WHERE `name` = 'Profiler' LIMIT 1");
    }
}

/** @} */
