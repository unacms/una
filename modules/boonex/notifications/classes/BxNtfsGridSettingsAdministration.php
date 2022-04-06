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

    protected $_sSource;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_sModule = 'bx_notifications';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_bAdministration = true;      

        $this->_bGrouped = $this->_oModule->_oConfig->isSettingsGrouped();
        if($this->_bGrouped)
            $this->_aOptions['show_total_count'] = 0;

        $this->_sDeliveryType = BX_BASE_MOD_NTFS_DTYPE_SITE;
        $this->_sTitleMask = _t('_bx_ntfs_setting_title_mask', '%s', '%s');

        $this->_sSource = '';

        $sDeliveryType = bx_get('delivery_type');
        if(!empty($sDeliveryType))
            $this->setDeliveryType($sDeliveryType);

        $this->init();
    }

    public function init()
    {
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
        $this->_sDeliveryType = bx_process_input($sType);
        $this->_aQueryAppend['delivery_type'] = $this->_sDeliveryType;
    }

    public function performActionActivate()
    {
        parent::performActionEnable(1);
    }

    public function performActionDeactivate()
    {
        parent::performActionEnable(0);
    }
    
    public function performActionChangeValue()
    {
        $aIds = bx_get('ids');
        if(empty($aIds) || !is_array($aIds))
            return echoJson(array());

        $iId = array_shift($aIds);
        $iValue = $this->_switcherChecked2State((int)bx_get('checked'));

        if(!$this->_bGrouped)
            $mixedResult = $this->_oModule->_oDb->updateSetting(array('value' => $iValue), array('id' => $iId));
        else 
            $mixedResult = $this->_oModule->_changeSettingsValueLike($iId, 'value', $iValue, $this->_bAdministration);

        $aResult = array();
        if($mixedResult === false)
            $aResult = array('msg' => _t('_bx_ntfs_txt_err_cannot_perform'));
        else if((int)$mixedResult == 0)
            $aResult = array('msg' => _t('_bx_ntfs_txt_err_nothing_changed'));

        return echoJson($aResult);
    }

    protected function _enable ($mixedId, $isChecked)
    {
        if(!$this->_bGrouped)
            return parent::_enable($mixedId, $isChecked);

        return $this->_oModule->enableSettingsLike($mixedId, $isChecked, $this->_bAdministration);
    }

    protected function _isCheckboxDisabled($aRow)
    {
        return false;
    }

    protected function _getFilterControls()
    {
        if($this->_bGrouped)
            return '';

        return parent::_getFilterControls();
    }

    protected function _getCellValue($mixedValue, $sKey, $aField, $aRow)
    {
        $sAttr = $this->_convertAttrs(
            $aField, 'attr_cell',
            'bx-def-padding-sec-bottom bx-def-padding-sec-top',
            isset($aField['width']) ? 'width:' . $aField['width'] : false
        );

        $oForm = new BxTemplFormView(array(), $this->_oTemplate);
        $oForm->addCssJs();
        $aInput = array(
            'type' => 'switcher',
            'name' => $this->_sObject . '_change_value_' . $aRow[$this->_aOptions['field_id']],
            'caption' => '',
            'attrs' => array (
                'bx_grid_action_single' => 'change_value',
                'bx_grid_action_confirm' => '',
                'bx_grid_action_data' => $aRow[$this->_aOptions['field_id']],
            ),
            'value' => $aRow[$this->_aOptions['field_id']],
            'checked' => $this->_isSwitcherOn($mixedValue, $sKey, $aField, $aRow),
        );
        $sSwitcher = $oForm->genInput($aInput);
        return '<td ' . $sAttr . '>' . $sSwitcher . '</td>';
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

        if($this->_bGrouped) {
            $sValueKey = '_bx_ntfs_alert_action_group_' . $aRow['group'];
            $sValue = _t($sValueKey);
            if(strcmp($sValueKey, $sValue) !== 0)
                $mixedValue = $sValue;
            else if($aRow['type'] == BX_NTFS_STYPE_OTHER)
                $mixedValue = sprintf($this->_sTitleMask, _t($this->_oModule->_oConfig->getHandlersUnitTitle($aRow['unit'])), $mixedValue);

            if(!$this->_isSettingsGroupValid($aRow))
                $mixedValue = $this->_oTemplate->parseIcon('exclamation-triangle', array('class' => 'bx-def-margin-sec-right', 'title' => _t('_bx_ntfs_grid_column_title_title_warning'))) . $mixedValue;
        }            

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionActivate($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getActionDeactivate($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `ts`.`delivery`=?", $this->_sDeliveryType);
        if(!$this->_bGrouped)
            return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $aResult = array();
        $this->_sSource = $this->_aOptions['source'];

        $aTypes = $this->_oModule->_oConfig->getSettingsTypes();
        foreach($aTypes as $sType) {
            $this->_aOptions['source'] = $this->_sSource . $this->_oModule->_oDb->prepareAsString(" AND `ts`.`type`=?", $sType) . " GROUP BY `ts`.`group`";

            $aRows = parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
            if(empty($aRows) || !is_array($aRows)) 
                continue;

            $aResult[] = _t($CNF['T']['setting_' . $sType]);
            $aResult = array_merge($aResult, $aRows);
        }

        return $aResult;
    }

    protected function _updateSettingTitle($sTitle, &$aRow)
    {
        return $this->_oModule->_oDb->updateSetting(array('title' => $sTitle), array('id' => $aRow['id']));
    }
    
    protected function _isSettingsGroupValid(&$aRow)
    {
        if(!$this->_bGrouped)
            return true;

        $iEnabled = $iDisabled = 0;

        $sSql = $this->_sSource . $this->_oModule->_oDb->prepareAsString(" AND `ts`.`group`=? AND `ts`.`type`=?", $aRow['group'], $aRow['type']);
        $aItems = $this->_oModule->_oDb->getAll($sSql);
        foreach($aItems as $aItem) 
            if((int)$aItem['active'] != 0)
                $iEnabled += 1;
            else 
                $iDisabled += 1;

        return $iEnabled == 0 || $iDisabled == 0;
    }
}

/** @} */
