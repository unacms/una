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

class BxGroupsGridConnections extends BxBaseModGroupsGridConnections
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sContentModule = 'bx_groups';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
