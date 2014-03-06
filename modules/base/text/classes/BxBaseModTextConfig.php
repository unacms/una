<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleConfig');

class BxBaseModTextConfig extends BxDolModuleConfig 
{
    public $CNF;

    function __construct($aModule) 
    {
        parent::__construct($aModule);
    }
}

/** @} */ 
