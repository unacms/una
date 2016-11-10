<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    LinkedInConnect LinkedIn Connect
 * @ingroup     UnaModules
 *
 * @{
 */

class BxLinkedinAlerts extends BxBaseModConnectAlerts
{
    function __construct()
    {
        parent::__construct();
        $this -> oModule = BxDolModule::getInstance('bx_linkedin');
    }
}

/** @} */
