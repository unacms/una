<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseNotifications Base classes for Notifications like modules
 * @ingroup     UnaModules
 *
 * @{
 */

class BxBaseModNotificationsSearchResult extends BxBaseModGeneralSearchResult
{
    function __construct($sMode = '', $aParams = array())
    {
        parent::__construct($sMode, $aParams);
    }
}

/** @} */
