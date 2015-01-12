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

bx_import('BxDolModule');

/**
 * Profiler module by BoonEx
 *
 * This module estimate timining, like page openings, mysql queries execution and service calls.
 * Also it can log too long queries, so you can later investigate these bottle necks and speedup whole script.
 */
class BxProfilerModule extends BxDolModule
{
    function __construct($aModule)
    {
        parent::__construct($aModule);
    }

    function actionHome ()
    {
        $this->_oTemplate->pageStart();
        echo $this->_aModule['title'];
        $this->_oTemplate->pageCode($this->_aModule['title']);
    }

}

/** @} */
