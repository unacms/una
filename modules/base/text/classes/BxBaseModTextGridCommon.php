<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseText Base classes for text modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModTextGridCommon extends BxBaseModTextGridAdministration
{
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sStatusField = $CNF['FIELD_STATUS'];
        $this->_aStatusValues = array('active', 'hidden');

        if($this->_oModule->_oConfig->isAutoApprove() && isset($this->_aFilter1Values[BX_BASE_MOD_GENERAL_STATUS_PENDING]))
            unset($this->_aFilter1Values[BX_BASE_MOD_GENERAL_STATUS_PENDING]);

        $this->_sManageType = BX_DOL_MANAGE_TOOLS_COMMON;
    }

    protected function _getCellSwitcher ($mixedValue, $sKey, $aField, $aRow)
    {
        if(!$this->_switcherState2Checked($aRow['status_admin']))
            return parent::_getCellDefault('', $sKey, $aField, $aRow);

        return parent::_getCellSwitcher ($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellStatusAdmin($mixedValue, $sKey, $aField, $aRow)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        if(!empty($CNF['T']['filter_item_' . $mixedValue]))
            $mixedValue = $CNF['T']['filter_item_' . $mixedValue];
        else
            $mixedValue = '_undefined';

        return parent::_getCellDefault(_t($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _isRowDisabled($aRow)
    {
        if(parent::_isRowDisabled($aRow))
            return true;

        return !$this->_switcherState2Checked($aRow['status_admin']);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `author`=?", bx_get_logged_profile_id());

        return $this->__getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function __getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
