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

class BxGroupsConnectionFans extends BxBaseModGroupsConnectionFans
{
    public function __construct($aObject)
    {
        $this->_sModule = 'bx_groups';

        parent::__construct($aObject);

        $this->_bBan = true;
    }
}

/** @} */
