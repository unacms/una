<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Groups Groups
 * @ingroup     UnaModules
 *
 * @{
 */

class BxGroupsCmts extends BxBaseModGroupsCmts
{
    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->_sModule = 'bx_groups';

        parent::__construct($sSystem, $iId, $iInit);
    }
}

/** @} */
