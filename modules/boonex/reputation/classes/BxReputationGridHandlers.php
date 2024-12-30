<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Reputation Reputation
 * @ingroup     UnaModules
 *
 * @{
 */

class BxReputationGridHandlers extends BxTemplGrid
{
    protected $_sModule;
    protected $_oModule;

    protected $_sFilter;
    protected $_sFilter1Name;
    protected $_sFilter1Value;
    protected $_aFilter1Values;

    protected $_sParamsDivider;
    
    public function __construct ($aOptions, $oTemplate = false)
    {
        $this->_sModule = 'bx_reputation';
        $this->_oModule = BxDolModule::getInstance($this->_sModule);

        parent::__construct ($aOptions, $oTemplate);

        $this->_aQueryReset = array($this->_aOptions['filter_get'], $this->_aOptions['paginate_get_start'], $this->_aOptions['paginate_get_per_page']);

        $this->_sParamsDivider = '#-#';

        $this->_sFilter = '';
        if(($sFilter = $this->_getFilterValue()))
            $this->_sFilter = $sFilter;

        $this->_sFilter1Name = 'filter1';
        $this->_aFilter1Values = [];

        $aUnits = $this->_oModule->_oDb->getHandlers(['type' => 'alert_units_list']);
        if(!empty($aUnits) && is_array($aUnits))
            foreach($aUnits as $sUnit)
                $this->_aFilter1Values[$sUnit] = _t('_' . $sUnit);

    	$sFilter1 = bx_get($this->_sFilter1Name);
        if(!empty($sFilter1)) {
            $this->_sFilter1Value = bx_process_input($sFilter1);
            $this->_aQueryAppend['filter1'] = $this->_sFilter1Value;
        }
    }

    public function getCode($isDisplayHeader = true)
    {
        $mixedResult = parent::getCode($isDisplayHeader);
        if(!$mixedResult)
            return $mixedResult;

        return $this->_oModule->_oTemplate->getJsCode('handlers', ['sObjNameGrid' => $this->_sObject]) . $mixedResult;
    }   

    public function performActionEdit()
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

        $sAction = 'edit';

        $aIds = $this->_getIds();
        if($aIds === false)
            return $this->_getActionResult([]);

        $iHandler = array_shift($aIds);
        $aHandler = $this->_oModule->_oDb->getHandlers(['type' => 'by_id', 'value' => $iHandler]);
        if(!is_array($aHandler) || empty($aHandler))
            return $this->_getActionResult([]);

        $sForm = $CNF['OBJECT_FORM_HANDLER_DISPLAY_EDIT'];
        $oForm = BxDolForm::getObjectInstance($CNF['OBJECT_FORM_HANDLER'], $CNF['OBJECT_FORM_HANDLER_DISPLAY_EDIT']);
        $oForm->setId($sForm);
        $oForm->setName($sForm);
    	$oForm->setAction(BX_DOL_URL_ROOT . bx_append_url_params('grid.php', [
            'o' => $this->_sObject, 
            'a' => $sAction,
            $this->_aOptions['filter_get'] => $this->_sFilter,
            'id' => $iHandler
        ]));

        $oForm->initChecker($aHandler);
        if($oForm->isSubmittedAndValid()) {
            if(!$oForm->update($iHandler))
                return $this->_getActionResult(['msg' => _t($CNF['T']['err_cannot_perform'])]);

            return $this->_bIsApi ? [] : echoJson(['grid' => $this->getCode(false), 'blink' => $iHandler]);    
        }

        if($this->_bIsApi)
            return $this->getFormBlockAPI($oForm, $sAction, $iHandler);

        $sContent = BxTemplStudioFunctions::getInstance()->popupBox($this->_oModule->_oConfig->getHtmlIds('handler_popup'), _t($CNF['T']['popup_title_handler_edit']), $this->_oModule->_oTemplate->parseHtmlByName('popup_handler.html', [
            'form_id' => $oForm->getId(),
            'form' => $oForm->getCode(true),
            'object' => $this->_sObject,
            'action' => $sAction
        ]));

        return echoJson(['popup' => ['html' => $sContent, 'options' => ['closeOnOuterClick' => false]]]);
    }

    public function performActionActivate()
    {
    	$this->_performActionEnable(true);
    }

    public function performActionDeactivate()
    {
    	$this->_performActionEnable(false);
    }

    protected function _isCheckboxDisabled($aRow)
    {
        return false;
    }

    protected function _getCellAlertUnit($mixedValue, $sKey, $aField, $aRow)
    {
        return self::_getCellDefault(_t('_' . $mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellAlertAction($mixedValue, $sKey, $aField, $aRow)
    {
        return self::_getCellDefault(_t('_bx_reputation_grid_column_value_hdr_aa_' . $mixedValue), $sKey, $aField, $aRow);
    }

    protected function _getFilterControls()
    {
        parent::_getFilterControls();

        $sContent = $this->_getFilterSelectOne($this->_sFilter1Name, $this->_sFilter1Value, $this->_aFilter1Values);
        $sContent .= $this->_getSearchInput();
        return $sContent;
    }

    protected function _getFilterSelectOne($sFilterName, $sFilterValue, $aFilterValues, $bAddSelectOne = true)
    {
        if(empty($sFilterName) || empty($aFilterValues))
            return '';

        $CNF = &$this->_oModule->_oConfig->CNF;
        $sJsObject = $this->_oModule->_oConfig->getJsObject('handlers');

        $aInputValues = [];
        if($bAddSelectOne)
            $aInputValues[''] = _t($CNF['T']['filter_item_select_one_' . $sFilterName]);

        foreach($aFilterValues as $sKey => $sValue)
            $aInputValues[$sKey] = _t($sValue);

        $aInputModules = [
            'type' => 'select',
            'name' => $sFilterName,
            'attrs' => [
                'id' => 'bx-grid-' . $sFilterName . '-' . $this->_sObject,
                'onChange' => 'javascript:' . $sJsObject . '.onChangeFilter(this)'
            ],
            'value' => $sFilterValue,
            'values' => $aInputValues
        ];

        $oForm = new BxTemplFormView([]);
        return $oForm->genRow($aInputModules);
    }

    protected function _getSearchInput()
    {
        $sJsObject = $this->_oModule->_oConfig->getJsObject('handlers');

        $aInputSearch = [
            'type' => 'text',
            'name' => 'search',
            'attrs' => [
                'id' => 'bx-grid-search-' . $this->_sObject,
                'onKeyup' => 'javascript:$(this).off(\'keyup focusout\'); ' . $sJsObject . '.onChangeFilter(this)',
                'onBlur' => 'javascript:' . $sJsObject . '.onChangeFilter(this)',
            ]
        ];

        $oForm = new BxTemplFormView([]);
        return $oForm->genRow($aInputSearch);
    }

    protected function _getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage)
    {
        if(strpos($sFilter, $this->_sParamsDivider) !== false)
            list($this->_sFilter1Value, $sFilter) = explode($this->_sParamsDivider, $sFilter);

    	if(!empty($this->_sFilter1Value))
            $this->_aOptions['source'] .= $this->_oModule->_oDb->prepareAsString(" AND `alert_unit`=?", $this->_sFilter1Value);

        return parent::_getDataSql($sFilter, $sOrderField, $sOrderDir, $iStart, $iPerPage);
    }

    protected function _performActionEnable($isChecked)
    {
    	$CNF = &$this->_oModule->_oConfig->CNF;

    	$aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds))
            return $this->_getActionResult([]);

        $iAffected = 0;
        $aIdsAffected = array();
        foreach($aIds as $iId)
            if($this->_enable($iId, $isChecked)) {
                $aIdsAffected[] = $iId;
                $iAffected++;
            }

        if(!$iAffected)
            return $this->_getActionResult(['msg' => _t($CNF['T']['grid_action_err_perform'])]);

        return $this->_bIsApi ? [] : echoJson(['grid' => $this->getCode(false), 'blink' => $aIdsAffected]);
    }

    protected function _getIds()
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId) 
                return false;

            $aIds = [$iId];
        }

        return $aIds;
    }
}

/** @} */
