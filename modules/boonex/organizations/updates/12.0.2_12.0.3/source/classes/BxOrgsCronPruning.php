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

class BxOrgsCronPruning extends BxBaseModGroupsCronPruning
{
    public function __construct()
    {
        $this->_sModule = 'bx_organizations';

        parent::__construct();
    }
}

/** @} */
