<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolModuleTemplate');

/**
 * Module representation.
 */
class BxBaseModGeneralTemplate extends BxDolModuleTemplate
{
    protected $MODULE;

    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
        $this->addCss ('main.css');
    }
}

/** @} */
