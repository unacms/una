<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Timeline Timeline
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import('BxBaseModNotificationsInstaller');

class BxTimelineInstaller extends BxBaseModNotificationsInstaller
{
    function __construct($aConfig)
    {
        parent::__construct($aConfig);
    }
}

/** @} */
