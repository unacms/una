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

require_once('BxTimelineGridManageTools.php');

class BxTimelineGridCommon extends BxTimelineGridManageTools
{
    public function __construct ($aOptions, $oTemplate = false)
    {
    	$this->MODULE = 'bx_timeline';
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sStatusField = $CNF['FIELD_STATUS'];
        $this->_aStatusValues = array('active', 'hidden');

        $this->_sManageType = BX_DOL_MANAGE_TOOLS_COMMON;
    }

    protected function _enable ($mixedId, $isChecked)
    {
        $bResult = parent::_enable($mixedId, $isChecked);
        if(!$bResult) 
            return $bResult;
        
        $aEvent = $this->_oModule->_oDb->getEvents(['browse' => 'id', 'value' => (int)$mixedId]);
        if(empty($aEvent) || !is_array($aEvent))
            return $bResult;

        $this->_oModule->{$isChecked ? 'onUnhide' : 'onHide'}($aEvent);

        return $bResult;
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
        $iProfileId = bx_get_logged_profile_id();

        $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND (`owner_id`=? OR (`system`='0' AND `object_owner_id`=?))", $iProfileId, $iProfileId);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
