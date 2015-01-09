<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
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
        bx_import('BxDolLiveUpdates');
        return BxDolLiveUpdates::getInstance()->init();
    }
}

/** @} */
