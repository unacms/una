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

        if(($this->_bContentFilter = !empty($CNF['FIELD_CF'])) !== false) {
            $this->_sFilter2Name = 'filter2';
            $this->_aFilter2Values = BxDolFormQuery::getDataItems('sys_content_filter');

            if(($sFilter2 = bx_get($this->_sFilter2Name)) !== false) {
                $this->_sFilter2Value = bx_process_input($sFilter2);
                $this->_aQueryAppend[$this->_sFilter2Name] = $this->_sFilter2Value;
            }
        }
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

        if(strpos($sFilter, $this->_sParamsDivider) !== false) {
            $aFilters = explode($this->_sParamsDivider, $sFilter);
            if($this->_bContentFilter)
                list($this->_sFilter1Value, $this->_sFilter2Value, $sFilter) = $aFilters;
            else
                list($this->_sFilter1Value, $sFilter) = $aFilters;
        }

    	if(!empty($this->_sFilter1Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`status`=?", $this->_sFilter1Value);

        if(!empty($this->_sFilter2Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `" . $CNF['FIELD_CF'] . "`=?", $this->_sFilter2Value);

        return parent::_getDataSqlInner($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
}

/** @} */
