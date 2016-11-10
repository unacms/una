<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    RocketChat Rocket.Chat integration module
 * @ingroup     UnaModules
 *
 * @{
 */

class BxChatPlusAlerts extends BxDolAlertsResponse
{
    function __construct()
    {
        parent::__construct();
        //$this -> oModule = BxDolModule::getInstance('BxChatPlusModule');
    }
}

/** @} */
