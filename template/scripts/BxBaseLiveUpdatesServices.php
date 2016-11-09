<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * System services related to Live Updates.
 */
class BxBaseLiveUpdatesServices extends BxDol
{
    public function __construct()
    {
        parent::__construct();
    }

    public function serviceInit()
    {
        return BxDolLiveUpdates::getInstance()->init();
    }
}

/** @} */
