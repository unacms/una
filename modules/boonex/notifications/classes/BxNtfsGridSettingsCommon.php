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

    protected function _updateSettingTitle($sTitle, &$aRow)
    {
        return $this->_oModule->_oDb->updateSetting(array('title' => $sTitle), array('id' => $aRow['setting_id']));
    }
}

/** @} */
