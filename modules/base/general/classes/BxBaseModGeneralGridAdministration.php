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

    public function _getFilterSelectOne($sFilterName, $aFilterValues)
    {
        if(empty($sFilterName) || empty($aFilterValues))
            return '';

		$sJsObject = $this->_oModule->_oConfig->getJsObject($this->_sManageType);

		$sFilterField = '_s' . str_replace(' ', '', ucwords(str_replace('_', ' ', $sFilterName)));
		foreach($aFilterValues as $sKey => $sValue)
			$aFilterValues[$sKey] = _t($sValue);

        $aInputModules = array(
            'type' => 'select',
            'name' => $sFilterName,
            'attrs' => array(
                'id' => 'bx-grid-' . $sFilterName . '-' . $this->_sObject,
                'onChange' => 'javascript:' . $sJsObject . '.onChangeFilter(this)'
            ),
            'value' => $this->$sFilterField,
            'values' => array_merge(array('' => _t('_bx_persons_grid_filter_item_title_adm_select_one')), $aFilterValues)
        );

        bx_import('BxTemplFormView');
        $oForm = new BxTemplFormView(array());
        return $oForm->genRow($aInputModules);
    }

	protected function _getSearchInput()
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject($this->_sManageType);

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
}

/** @} */
