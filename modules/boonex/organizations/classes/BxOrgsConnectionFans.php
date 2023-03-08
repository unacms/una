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

class BxOrgsConnectionFans extends BxBaseModGroupsConnectionFans
{
    public function __construct($aObject)
    {
        $this->_sModule = 'bx_organizations';

        parent::__construct($aObject);
    }
}

/** @} */
