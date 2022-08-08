<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseGroups Base classes for groups modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModGroupsGridAdministration extends BxBaseModProfileGridAdministration
{
    protected $_sFilter2Name;
    protected $_sFilter2Value;
    protected $_aFilter2Values;
    protected $_bContentFilter;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_sStatusField = $CNF['FIELD_STATUS_ADMIN'];
        $this->_aStatusValues = [
            BX_BASE_MOD_GENERAL_STATUS_ACTIVE, 
            BX_BASE_MOD_GENERAL_STATUS_HIDDEN, 
            BX_BASE_MOD_GENERAL_STATUS_PENDING
        ];

        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = [
            BX_BASE_MOD_GENERAL_STATUS_ACTIVE => $CNF['T']['filter_item_active'],
            BX_BASE_MOD_GENERAL_STATUS_HIDDEN => $CNF['T']['filter_item_hidden'],
        ];
        if($this->_oModule->_oConfig->isAutoApprove())
            $this->_aFilter1Values[BX_BASE_MOD_GENERAL_STATUS_PENDING] = $CNF['T']['filter_item_pending'];

        $oCf = BxDolContentFilter::getInstance();
        if(($this->_bContentFilter = ($oCf->isEnabled() && !empty($CNF['FIELD_CF']))) !== false) {
            $this->_sFilter2Name = 'filter2';
            $this->_aFilter2Values = $oCf->getValues();

            if(($sFilter2 = bx_get($this->_sFilter2Name)) !== false) {
                $this->_sFilter2Value = bx_process_input($sFilter2);
                $this->_aQueryAppend[$this->_sFilter2Name] = $this->_sFilter2Value;
            }
        }
    }

    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? BX_BASE_MOD_GENERAL_STATUS_ACTIVE : BX_BASE_MOD_GENERAL_STATUS_HIDDEN;
    }

    protected function _switcherState2Checked($mixedState)
    {
        return BX_BASE_MOD_GENERAL_STATUS_ACTIVE == $mixedState ? true : false;
    }

    protected function _enable ($mixedId, $isChecked)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $bStatusAdmin = $this->_sStatusField == $CNF['FIELD_STATUS_ADMIN'];

        $sStatusBefore = '';
        if($bStatusAdmin) {
            $aContentInfo = $this->_oModule->_oDb->getContentInfoById($mixedId);
            if(!empty($aContentInfo) && is_array($aContentInfo))
                $sStatusBefore = $aContentInfo[$this->_sStatusField];
        }

        $mixedResult = parent::__enable($mixedId, $isChecked);
        if((int)$mixedResult > 0) {
            if($bStatusAdmin && $sStatusBefore == BX_BASE_MOD_GENERAL_STATUS_PENDING) {
                $aContentInfo = $this->_oModule->_oDb->getContentInfoById($mixedId);
                if($aContentInfo[$this->_sStatusField] == BX_BASE_MOD_GENERAL_STATUS_ACTIVE)
                    $this->_oModule->onApprove($aContentInfo);
            }
        }

        return $mixedResult;
    }

    protected function _getActionAuditContext($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!getParam('sys_audit_enable') || getParam('sys_audit_acl_levels') == '')
            return;
        
        $iProfileId = bx_get_logged_profile_id();
        if (!BxDolAcl::getInstance()->isMemberLevelInSet(explode(',', getParam('sys_audit_acl_levels')), $iProfileId))
            return;
    	
    	$CNF = &$this->_oModule->_oConfig->CNF;
        $oProfile = $this->_getProfileObject($aRow[$CNF['FIELD_ID']]);
        $sUrl = BX_DOL_URL_ROOT . 'page/audit-administration?context_id=' . $oProfile->id();

    	$a['attr'] = array_merge($a['attr'], array(
            "onclick" => "window.open('" . $sUrl . "','_audit');"
    	));

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getCellName($mixedValue, $sKey, $aField, $aRow)
    {
        $oProfile = $this->_getProfileObject($aRow['id']);

        return parent::_getCellDefault($oProfile->getUnit(0, array('template' => 'unit_wo_cover')), $sKey, $aField, $aRow);
    }

    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sContent = $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values);
        if($this->_bContentFilter)
            $sContent .= $this->_getFilterSelectOne($this->_sFilter2Name, $this->_sFilter2Value, $this->_aFilter2Values);
        $sContent .= $this->_getSearchInput();

        return $sContent;
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        $CNF = &$this->_oModule->_oConfig->CNF;

        $aFilterParts = explode($this->_sParamsDivider, $sFilter);
        switch (substr_count($sFilter, $this->_sParamsDivider)) {
            case 1:
                list($this->_sFilter1Value, $sFilter) = $aFilterParts;
                break;

            case 2:
                list($this->_sFilter1Value, $this->_sFilter2Value, $sFilter) = $aFilterParts;
                break;
        }

    	if(!empty($this->_sFilter1Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `" . $this->_sStatusField . "`=?", $this->_sFilter1Value);

        if($this->_bContentFilter && !empty($this->_sFilter2Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `" . $CNF['FIELD_CF'] . "`=?", $this->_sFilter2Value);

        return parent::_getDataSqlInner($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
