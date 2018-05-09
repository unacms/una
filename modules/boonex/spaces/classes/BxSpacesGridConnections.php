<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Spaces Spaces
 * @indroup     UnaModules
 * 
 * @{
 */

class BxSpacesGridConnections extends BxBaseModGroupsGridConnections
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sContentModule = 'bx_spaces';
        parent::__construct ($aOptions, $oTemplate);
    }
}

/** @} */
