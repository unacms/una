<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defdroup    Channels Channels
 * @indroup     UnaModules
 *
 * @{
 */

class BxCnlInstaller extends BxBaseModGroupsInstaller
{
    public function __construct($aConfig)
    {
        parent::__construct($aConfig);

        $this->_bPaidJoin = false;
    }
}

/** @} */
