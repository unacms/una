<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseProfile Base classes for profile modules
 * @ingroup     TridentModules
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
            $this->_echoResultJson(array());
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
            $this->_echoResultJson(array());
            return;
        }

		if(count($aIdsResult) == 1)
			$aIdsResult = $aIdsResult[0];

		$sContent = $this->_oTemplate->parseHtmlByName('set_acl_popup.html', array(
			'content' => $oMenu->getCode($aIdsResult)
		));

		$sContent = BxTemplFunctions::getInstance()->transBox($this->_oModule->_oConfig->getName() . 'set_acl_level_popup', $sContent);

    	$this->_echoResultJson(array('popup' => $sContent), true);
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
    	return $isChecked ? $oProfile->activate($iAction) : $oProfile->suspend($iAction);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1Value))
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepare(" AND `tp`.`status`=?", $this->_sFilter1Value);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    //--- Layout methods ---//
	protected function _getFilterControls()
    {
        parent::_getFilterControls();

        return  $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values) . $this->_getSearchInput();
    }

    protected function _getCellFullname($mixedValue, $sKey, $aField, $aRow)
    {
    	$oProfile = $this->_getProfileObject($aRow['id']);

        $mixedValue = $this->_oTemplate->parseHtmlByName('name_link.html', array(
            'href' => $oProfile->getUrl(),
            'title' => $mixedValue,
            'content' => $mixedValue
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellLastOnline($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

	protected function _getCellAccount($mixedValue, $sKey, $aField, $aRow)
    {
    	$sManageAccountUrl = $this->_getManageAccountUrl($aRow[$sKey]);
    	if(!empty($sManageAccountUrl)) {
    		$mixedValue = $this->_oTemplate->parseHtmlByName('account_link.html', array(
    			'href' => $sManageAccountUrl,
    			'title' => _t($this->_oModule->_oConfig->CNF['T']['grid_txt_account_manager']),
    			'content' => $mixedValue, 
    		));
    	}

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getProfileObject($iId)
    {
    	$oProfile = BxDolProfile::getInstanceByContentAndType((int)$iId, $this->_oModule->_oConfig->getName());
        if (!$oProfile) 
            $oProfile = BxDolProfileUndefined::getInstance();

        return $oProfile;
    }

    protected function _getProfileId($iId)
    {
    	return $this->_getProfileObject($iId)->id();
    }

	protected function _doDelete($iId, $aParams = array())
    {
    	if($this->_oModule->checkMyself($iId))
    		return false;

    	if(isset($aParams['with_content']) && $aParams['with_content'] === true) {
    		$oProfile = $this->_getProfileObject($iId);

    		if($oProfile instanceof BxDolProfileUndefined)
    			return false;

	    	return $oProfile->delete($oProfile->id(), true);
    	}

    	return parent::_doDelete($iId, $aParams);
    }
}

/** @} */
