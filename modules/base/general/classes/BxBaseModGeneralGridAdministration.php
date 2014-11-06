<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    BaseGeneral Base classes for modules
 * @ingroup     DolphinModules
 * @{
 */

bx_import('BxDolModule');
bx_import('BxTemplGrid');

class BxBaseModGeneralGridAdministration extends BxTemplGrid
{
	protected $MODULE;
	protected $_oModule;

	protected $_sManageType;
	protected $_sParamsDivider;

    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);

        $this->_oModule = BxDolModule::getInstance($this->MODULE);
        
        $this->_sManageType = 'administration';
        $this->_sParamsDivider = '#-#';
    }

	public function performActionDelete($aParams = array())
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	//TODO: remove this check when 'delete with content' feature will be realized in onDelete method.
    	if(isset($aParams['with_content']) && $aParams['with_content'] === true) {
			$this->_echoResultJson(array('msg' => 'TODO: delete with content'));
	    	return;
    	}

        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        bx_import('FormsEntryHelper', $this->_oModule->_aModule);
        $sClass = $this->_oModule->_oConfig->getClassPrefix() . 'FormsEntryHelper';
        $oFormsHelper = new $sClass($this->_oModule);

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
			$aContentInfo = $this->_oModule->_oDb->getContentInfoById($iId);
	    	if($this->_oModule->checkAllowedDelete($aContentInfo) !== CHECK_ACTION_RESULT_ALLOWED)
	    		continue;

        	if($oFormsHelper->deleteData($iId) != '')
                continue;

			if(!$this->_onDelete($iId, $aParams))
				continue;

			$this->_oModule->checkAllowedDelete($aContentInfo, true);

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        $this->_echoResultJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t($CNF['T']['grid_action_err_delete'])));
    }

    protected function _getActionSettings($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
    	$sJsObject = $this->_oModule->_oConfig->getJsObject('manage_tools');
    	$sMenuName = $this->_oModule->_oConfig->CNF['OBJECT_MENU_MANAGE_TOOLS'];

    	bx_import('BxDolMenu');
    	$sMenu = BxDolMenu::getObjectInstance($sMenuName)->getCode();
    	if(empty($sMenu))
    		return '';

    	$a['attr'] = array_merge($a['attr'], array(
    		"bx-popup-id" => $sMenuName . "-" . $aRow['id'],
    		"onclick" => "$(this).off('click'); " . $sJsObject . ".onClickSettings('" . $sMenuName . "', this);"
    	));

    	return $this->_getActionDefault ($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getFilterSelectOne($sFilterName, $sFilterValue, $aFilterValues)
    {
        if(empty($sFilterName) || empty($aFilterValues))
            return '';

		$CNF = &$this->_oModule->_oConfig->CNF;
		$sJsObject = $this->_oModule->_oConfig->getJsObject('manage_tools');

		foreach($aFilterValues as $sKey => $sValue)
			$aFilterValues[$sKey] = _t($sValue);

        $aInputModules = array(
            'type' => 'select',
            'name' => $sFilterName,
            'attrs' => array(
                'id' => 'bx-grid-' . $sFilterName . '-' . $this->_sObject,
                'onChange' => 'javascript:' . $sJsObject . '.onChangeFilter(this)'
            ),
            'value' => $sFilterValue,
            'values' => array_merge(array('' => _t($CNF['T']['filter_item_select_one_' . $sFilterName])), $aFilterValues)
        );

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules);
    }

	protected function _getSearchInput()
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject('manage_tools');

        $aInputSearch = array(
            'type' => 'text',
            'name' => 'search',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
                'onKeyup' => 'javascript:$(this).off(\'keyup\'); ' . $sJsObject . '.onChangeFilter(this)'
            )
        );

		bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputSearch);
    }

    protected function _onDelete($iId, $aParams = array())
    {
    	return true;
    }
}

/** @} */
