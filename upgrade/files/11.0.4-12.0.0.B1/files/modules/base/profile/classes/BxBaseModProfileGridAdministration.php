<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     UnaModules
 * 
 * @{
 */

class BxBaseModProfileGridAdministration extends BxBaseModGeneralGridAdministration
{
	protected $_sFilter1Name;
	protected $_sFilter1Value;
	protected $_aFilter1Values;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $CNF = &$this->_oModule->_oConfig->CNF;

        $this->_aQueryReset = array('order_field', 'order_dir', $this->_aOptions['paginate_get_start'], $this->_aOptions['paginate_get_per_page']);

        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = array(
			'active' => $CNF['T']['filter_item_active'],
            'pending' => $CNF['T']['filter_item_pending'],
            'suspended' => $CNF['T']['filter_item_suspended'],
		);

    	$sFilter1 = bx_get($this->_sFilter1Name);
        if(!empty($sFilter1)) {
            $this->_sFilter1Value = bx_process_input($sFilter1);
            $this->_aQueryAppend[$this->_sFilter1Name] = $this->_sFilter1Value;
        }
    }

    public function performActionSetAclLevel()
    {
    	$oMenu = BxDolMenu::getObjectInstance('sys_set_acl_level');

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds) || !$oMenu) {
            echoJson(array());
            return;
        }

        $aIdsResult = array();
        foreach($aIds as $iId) {
        	$aContentInfo = $this->_oModule->_oDb->getContentInfoById($iId);
	    	if($this->_oModule->checkAllowedSetMembership($aContentInfo) !== CHECK_ACTION_RESULT_ALLOWED)
	    		continue;

        	$aIdsResult[] = $this->_getProfileId($iId);
        }

        if(empty($aIdsResult)) {
            echoJson(array());
            return;
        }

		if(count($aIdsResult) == 1)
			$aIdsResult = $aIdsResult[0];

		$sContent = $this->_oTemplate->parseHtmlByName('set_acl_popup.html', array(
			'content' => $oMenu->getCode($aIdsResult)
		));

		$sContent = BxTemplFunctions::getInstance()->transBox($this->_oModule->_oConfig->getName() . 'set_acl_level_popup', $sContent);

    	echoJson(array('popup' => $sContent));
    }

	public function performActionDeleteWithContent()
    {
    	$this->performActionDelete(array('with_content' => true));
    }

    protected function _getActionSetAclLevel($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	if($this->_sManageType == BX_DOL_MANAGE_TOOLS_ADMINISTRATION && $this->_oModule->checkAllowedSetMembership($aRow) !== CHECK_ACTION_RESULT_ALLOWED)
			return '';

		return $this->_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);	
    }
    
    protected function _getActionAuditProfile($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if (!getParam('sys_audit_enable') || getParam('sys_audit_acl_levels') == '')
            return;
        
        $iProfileId = bx_get_logged_profile_id();
        if (!BxDolAcl::getInstance()->isMemberLevelInSet(explode(',', getParam('sys_audit_acl_levels')), $iProfileId))
            return;
    	
    	$CNF = &$this->_oModule->_oConfig->CNF;
        $oProfile = $this->_getProfileObject($aRow[$CNF['FIELD_ID']]);
        $sUrl = BX_DOL_URL_ROOT . 'page/audit-administration?actor_id=' . $oProfile->id();

    	$a['attr'] = array_merge($a['attr'], array(
    		"onclick" => "window.open('" . $sUrl . "','_audit');"
    	));

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

	protected function _getActionDeleteWithContent($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
		if($this->_sManageType == BX_DOL_MANAGE_TOOLS_ADMINISTRATION && $this->_oModule->checkAllowedEditAnyEntry() !== CHECK_ACTION_RESULT_ALLOWED)
			return '';

    	return $this->_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 'active' : 'suspended';
    }

    protected function _switcherState2Checked($mixedState)
    {
        return 'active' == $mixedState ? true : false;
    }

    protected function _enable ($mixedId, $isChecked)
    {
    	$oProfile = $this->_getProfileObject($mixedId);

    	if($oProfile instanceof BxDolProfileUndefined)
    		return false;

		$iAction = BX_PROFILE_ACTION_MANUAL;
        $bSendEmailNotification = $this->_oModule->serviceActAsProfile();
    	return $isChecked ? $oProfile->activate($iAction, 0, $bSendEmailNotification) : $oProfile->suspend($iAction, 0, $bSendEmailNotification);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    { 
        $iFilterPartsCount = substr_count($sFilter, $this->_sParamsDivider);
        switch ($iFilterPartsCount) {
            case 1:
                list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);
                break;
            case 2:
                list($this->_sFilter1Value, $this->_sFilter2Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);
                break;
        }
        
    	if(!empty($this->_sFilter1Value))
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `tp`.`status`=?", $this->_sFilter1Value);

        return $this->_getDataSqlInner($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }
    
    protected function _getDataSqlInner($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    //--- Layout methods ---//
	protected function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getSearchInput();
    }

    protected function _getCellSwitcher ($mixedValue, $sKey, $aField, $aRow)
    {
        if (isset($aRow['profile_id']))
            if (!$this->_oModule->isAllowDeleteOrDisable(bx_get_logged_profile_id(), $aRow['profile_id']))
                return parent::_getCellDefault('', $sKey, $aField, $aRow);    
        
        return parent::_getCellSwitcher ($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellFullname($mixedValue, $sKey, $aField, $aRow)
    {
    	$oProfile = $this->_getProfileObject($aRow['id']);

        return parent::_getCellDefault($oProfile->getUnit(), $sKey, $aField, $aRow);
    }

    protected function _getCellLastOnline($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getCellAddedTs($mixedValue, $sKey, $aField, $aRow)
    {
        return $this->_getCellLastOnline($mixedValue, $sKey, $aField, $aRow);
    }

	protected function _getCellAccount($mixedValue, $sKey, $aField, $aRow)
    {
    	$sManageAccountUrl = $this->_getManageAccountUrl($aRow[$sKey]);
    	if(!empty($sManageAccountUrl)) {
    		$mixedValue = $this->_oTemplate->parseHtmlByName('account_link.html', array(
    			'href' => $sManageAccountUrl,
    			'title' => _t($this->_oModule->_oConfig->CNF['T']['grid_txt_account_manager']),
    			'content' => $mixedValue, 
                'class' => ''
    		));
    	}

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getProfileObject($iId)
    {
        return $this->_oModule->getProfileObject($iId);
    }

    protected function _getProfileId($iId)
    {
    	return $this->_getProfileObject($iId)->id();
    }

	protected function _doDelete($iId, $aParams = array())
    {
        $oProfile = $this->_getProfileObject($iId);
        
        if (!$this->_oModule->isAllowDeleteOrDisable(bx_get_logged_profile_id(), $oProfile->id()))
            return false;
        
    	if($this->_oModule->checkMyself($iId))
    		return false;

    	if(isset($aParams['with_content']) && $aParams['with_content'] === true) {

    		if($oProfile instanceof BxDolProfileUndefined)
    			return false;

	    	return $oProfile->delete($oProfile->id(), true);
    	}

    	return parent::_doDelete($iId, $aParams);
    }
}

/** @} */
