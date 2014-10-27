<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 * 
 * @{
 */

bx_import('BxBaseModGeneralGridAdministration');

class BxBaseModProfileGridAdministration extends BxBaseModGeneralGridAdministration
{
	protected $_sFilter1;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

    	$sFilter1 = bx_get('filter1');
        if(!empty($sFilter1)) {
            $this->_sFilter1 = bx_process_input($sFilter1);
            $this->_aQueryAppend['filter1'] = $this->_sFilter1;
        }
    }

    public function performActionSetAclLevel()
    {
    	bx_import('BxDolMenu');
    	$oMenu = BxDolMenu::getObjectInstance('sys_set_acl_level');

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds) || !$oMenu) {
            $this->_echoResultJson(array());
            exit;
        }

        foreach($aIds as $iKey => $iId)
        	$aIds[$iKey] = $this->_getProfileId($iId);

		if(count($aIds) == 1)
			$aIds = $aIds[0];

		$sContent = $this->_oTemplate->parseHtmlByName('bx_div.html', array(
			'bx_repeat:attrs' => array(
				array('key' => 'class', 'value' => 'bx-def-padding')
			),
			'content' => $oMenu->getCode($aIds)
		));

    	bx_import('BxTemplFunctions');
		$sContent = BxTemplFunctions::getInstance()->transBox($this->_oModule->_oConfig->getName() . 'set_acl_level_popup', $sContent);

    	$this->_echoResultJson(array('popup' => $sContent), true);
    }

	public function performActionDelete($bWithContent = false)
    {
    	if($bWithContent) {
			$this->_echoResultJson(array('msg' => 'TODO: delete with content'));
	    	return;
    	}

        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
        	$oProfile = $this->_getProfileObject(iId);

        	if((int)$this->_delete($iId) == 0)
                continue;

        	if(!$oProfile->delete())
        		continue;

        	if($bWithContent)	{
        		//TODO: delete content after profile deletion
        	}

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        $this->_echoResultJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_adm_nav_err_menus_delete')));
    }

	public function performActionDeleteSpammer()
    {
    	$this->performActionDelete(true);
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
    	$iAction = BX_PROFILE_ACTION_MANUAL;
    	$oProfile = $this->_getProfileObject($mixedId);
    	return $isChecked ? $oProfile->activate($iAction) : $oProfile->suspend($iAction);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1))
        	$this->_aOptions['source'] .= $this->_oModule->_oDb->prepare(" AND `tp`.`status`=?", $this->_sFilter1);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    //--- Layout methods ---//
	protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sFilterName = 'filter1';
        $aFilterValues = array(
			'active' => '_bx_persons_grid_filter_item_title_adm_active',
            'pending' => '_bx_persons_grid_filter_item_title_adm_pending',
            'suspended' => '_bx_persons_grid_filter_item_title_adm_suspended',
		);

        return  $this->_getFilterSelectOne($sFilterName, $aFilterValues) . $this->_getSearchInput();
    }

    protected function _getCellFullname($mixedValue, $sKey, $aField, $aRow)
    {
    	$oProfile = $this->_getProfileObject($aRow['id']);

        $mixedValue = $this->_oTemplate->parseHtmlByName('bx_a.html', array(
            'href' => $oProfile->getUrl(),
            'title' => $mixedValue,
            'bx_repeat:attrs' => array(),
            'content' => $mixedValue
        ));

        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionSettings($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	$sName = $this->_oModule->_oConfig->getName() . "_menu_manage_tools";
    	$sJsObject = $this->_oModule->_oConfig->getJsObject('manage_tools');

    	$sMenu = BxDolMenu::getObjectInstance($sName)->getCode();
    	if(empty($sMenu))
    		return '';

    	$a['attr'] = array_merge($a['attr'], array(
    		"bx-popup-id" => $sName . "-" . $aRow['id'],
    		"onclick" => "$(this).off('click'); " . $sJsObject . ".onClickSettings('" . $sName . "', this);"
    	));

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getCellLastOnline($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getProfileObject($iId)
    {
    	bx_import('BxDolProfile');
    	return  BxDolProfile::getInstanceByContentAndType((int)$iId, $this->_oModule->_oConfig->getName());
    }

    protected function _getProfileId($iId)
    {
    	return $this->_getProfileObject($iId)->id();
    }
}

/** @} */
