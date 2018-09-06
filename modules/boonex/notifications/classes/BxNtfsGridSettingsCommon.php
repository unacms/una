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
    protected $_iUserId;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_bAdministration = false;
        $this->_iUserId = 0;

        $iUserId = bx_get('user_id');
        if(!empty($iUserId))
            $this->setUserId($iUserId);
    }

    public function setUserId($iUserId)
    {
        $this->_iUserId = (int)$iUserId;
        $this->_aQueryAppend['user_id'] = $this->_iUserId;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tsu`.`user_id`=?", $this->_iUserId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _updateSettingTitle($sTitle, &$aRow)
    {
        return $this->_oModule->_oDb->updateSetting(array('title' => $sTitle), array('id' => $aRow['setting_id']));
    }
}

/** @} */
