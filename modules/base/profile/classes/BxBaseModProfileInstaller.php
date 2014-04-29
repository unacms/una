<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxBaseModGeneralInstaller');

class BxBaseModProfileInstaller extends BxBaseModGeneralInstaller 
{
    function __construct($aConfig) 
    {
        parent::__construct($aConfig);
    }
}

/** @} */ 
