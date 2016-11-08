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

class BxOrgsGridCommon extends BxBaseModProfileGridCommon
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_organizations';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
