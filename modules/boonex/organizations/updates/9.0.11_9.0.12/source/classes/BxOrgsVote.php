<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Organizations Organizations
 * @ingroup     UnaModules
 *
 * @{
 */

class BxOrgsVote extends BxBaseModProfileVote
{
    function __construct($sSystem, $iId, $iInit = 1)
    {
    	$this->_sModule = 'bx_organizations';

        parent::__construct($sSystem, $iId, $iInit);        
    }
}

/** @} */
