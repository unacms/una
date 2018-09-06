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

require_once(BX_DOL_DIR_STUDIO_INC . 'utils.inc.php');

class BxNtfsGridSettingsAdministration extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_bAdministration;

    protected $_bGrouped;
    protected $_sDeliveryType;
    protected $_sTitleMask;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sModule = 'bx_notifications';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_bAdministration = true;      

        $this->_bGrouped = $this->_oModule->_oConfig->isSettingsGrouped();
        $this->_sDeliveryType = BX_BASE_MOD_NTFS_DTYPE_SITE;
        $this->_sTitleMask = _t('_bx_ntfs_setting_title_mask', '%s', '%s');

        $this->init();
    }

    public function init()
    {
        if($this->_bGrouped && isset($this->_aOptions['fields']['checkbox']))
            unset($this->_aOptions['fields']['checkbox']);

        if(!$this->_bGrouped && !isset($this->_aOptions['fields']['unit'], $this->_aOptions['fields']['type']))
            $this->_aOptions['fields'] = bx_array_insert_after(array(
                'unit' => array('title' => _t('_bx_ntfs_grid_column_title_unit'), 'width' => '10%', 'translatable' => 0 ,'chars_limit' => 0),
                'type' => array('title' => _t('_bx_ntfs_grid_column_title_type'), 'width' => '20%', 'translatable' => 0 ,'chars_limit' => 0),
            ), $this->_aOptions['fields'], 'switcher');
    }

    public function setGrouped($bGrouped)
    {
        $bReinit = $this->_bGrouped !== (boolean)$bGrouped;

        $this->_bGrouped = $bGrouped;

        if($bReinit)
            $this->init();
    }

    public function setDeliveryType($sType)
    {
        $this->_sDeliveryType = $sType;
    }

    public function performActionDeactivate()
    {
        parent::performActionEnable();
    }

    protected function _enable ($mixedId, $isChecked)
    {
        if(!$this->_bGrouped)
            return parent::_enable($mixedId, $isChecked);

        return $this->_oModule->enableSettingsLike($mixedId, $isChecked, $this->_bAdministration);
    }

    protected function _getFilterControls()
    {
        if($this->_bGrouped)
            return '';

        return parent::_getFilterControls();
    }

    protected function _getRowHead()
    {
        if($this->_bGrouped)
            return '';

        return parent::_getRowHead();
    }

    protected function _getCellUnit($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t($this->_oModule->_oConfig->getHandlersUnitTitle($mixedValue)), $sKey, $aField, $aRow);
    }

    protected function _getCellType($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        return parent::_getCellDefault(_t($CNF['T']['setting_' . $mixedValue]), $sKey, $aField, $aRow);
    }

    protected function _getCellTitle($mixedValue, $sKey, $aField, $aRow)
    {
        if(empty($mixedValue)) {
            $mixedValueKey = $this->_oModule->_oConfig->getHandlersActionTitle($aRow['unit'], $aRow['action'], $aRow['type']);
            $mixedValue = _t($mixedValueKey);

            if(strcmp($mixedValueKey, $mixedValue) !== 0)
                $this->_updateSettingTitle($mixedValueKey, $aRow);
        }

        if($this->_bGrouped && $aRow['type'] == BX_NTFS_STYPE_OTHER)
            $mixedValue = sprintf($this->_sTitleMask, _t($this->_oModule->_oConfig->getHandlersUnitTitle($aRow['unit'])), $mixedValue);

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionActivate($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	if($this->_bGrouped)
            return '';

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionDeactivate($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	if($this->_bGrouped)
            return '';

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `ts`.`delivery`=?", $this->_sDeliveryType);
        if(!$this->_bGrouped)
            return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = array();
        $sSource = $this->_aOptions['source'];

        $aTypes = $this->_oModule->_oConfig->getSettingsTypes();
        foreach($aTypes as $sType) {
            $aResult[] = _t($CNF['T']['setting_' . $sType]);

            $this->_aOptions['source'] = $sSource . $this->_oModule->_oDb->prepareAsString(" AND `ts`.`type`=?", $sType) . " GROUP BY `group`";

            $aRows = parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
            $aResult = array_merge($aResult, $aRows);
        }

        return $aResult;
    }

    protected function _updateSettingTitle($sTitle, &$aRow)
    {
        return $this->_oModule->_oDb->updateSetting(array('title' => $sTitle), array('id' => $aRow['id']));
    }
}

/** @} */
