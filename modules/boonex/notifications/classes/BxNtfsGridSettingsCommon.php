<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Notifications Notifications
 * @ingroup     UnaModules
 * 
 * @{
 */

require_once('BxNtfsGridSettingsAdministration.php');

class BxNtfsGridSettingsCommon extends BxNtfsGridSettingsAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_bAdministration = false;
    }
}

/** @} */
