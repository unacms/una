<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Persons Persons
 * @ingroup     UnaModules
 *
 * @{
 */

class BxPersonsCmts extends BxBaseModProfileCmts
{
    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->_sModule = 'bx_persons';

        parent::__construct($sSystem, $iId, $iInit);
    }
}

/** @} */
