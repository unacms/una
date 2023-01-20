<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

bx_import('BxTemplStudioFormView');

class BxStudioFormsCategoriesCheckerHelper extends BxDolFormCheckerHelper
{
    public function checkAvailUnique($s, $aExclude = array())
    {
        if(!is_array($aExclude))
            $aExclude = array($aExclude);
        
        $aLanguages = BxDolStudioLanguagesUtils::getInstance()->getLanguages();
        
        foreach($aLanguages as $sLangName => $sLangTitle) {
            $sValue = BxDolForm::getSubmittedValue('value' . '-' . $sLangName, BX_DOL_STUDIO_METHOD_DEFAULT);
            $aCategory = BxDolCategories::getInstance()->getData(array(
                'type' => 'value_and_module',
                'module' => bx_get('module'),
                'value' => $sValue
            ));
            $bNoInUsers = empty($aCategory) || !is_array($aCategory) || in_array($aCategory['id'], $aExclude);
            // also need check uniq in system categories to
            if(!$bNoInUsers)
                return false;
        }
        return true;
    }
}

class BxBaseStudioFormsCategories extends BxDolStudioFormsCategories
{    
    public function __construct ($aOptions, $oTemplate = false)
    {
        parent::__construct ($aOptions, $oTemplate);
        $this->sUrlPage = BX_DOL_URL_STUDIO . 'builder_forms.php?page=categories';
    }
    
    public function performActionAdd()
    {
        $sAction = 'add';
        
        if(!$this->canAdd()) {
            echoJson(array());
            exit;
        }
        
        $oForm = $this->_getForm($sAction);
     
        if (!$oForm)
            return '';
        $oForm->aFormAttrs['action'] = BX_DOL_URL_ROOT . 'grid.php?' . bx_encode_url_params($_GET, array('ids', '_r'));
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $mixedResult = $oForm->insert(array('added' => time(), 'module' => $this->sModule));
            if(is_numeric($mixedResult))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $mixedResult);
            else
                $aRes = array('msg' => $mixedResult);
            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-form-categories-add-popup', _t('_adm_form_txt_categories_add_popup'), $this->_oTemplate->parseHtmlByName('form_add_category.html', array(
               'form_id' => $oForm->aFormAttrs['id'],
               'form' => $oForm->getCode(true),
               'object' => $this->_sObject,
               'action' => $sAction
           )));

           echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
            
        }
    }
    
    public function performActionEdit()
    {
        $sAction = 'edit';
        
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId)
                return echoJson(array());

            $aIds = array($iId);
        }

        $iId = (int)array_shift($aIds);

        $aCategory = $this->_oCategory->getData(array('type' => 'id', 'id' => $iId));
        if(empty($aCategory) || !is_array($aCategory))
            return echoJson(array());
        
        $this->sModule = $aCategory['module'];
        $oForm = $this->_getForm($sAction, $aCategory);
        $oForm->initChecker();
        if($oForm->isSubmittedAndValid()) {
            $mixedResult = $oForm->update($iId);
            if(is_numeric($mixedResult))
                $aRes = array('grid' => $this->getCode(false), 'blink' => $mixedResult);
            else
                $aRes = array('msg' => $mixedResult);
            echoJson($aRes);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-form-categories-edit-popup', _t('_adm_form_txt_categories_edit_popup', _t($aCategory['value'])), $this->_oTemplate->parseHtmlByName('form_add_category.html', array(
               'form_id' => $oForm->aFormAttrs['id'],
               'form' => $oForm->getCode(true),
               'object' => $this->_sObject,
               'action' => $sAction
           )));

           echoJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))));
        }
    }

    public function performActionDelete()
    {
        $this->_replaceMarkers ();

        $iAffected = 0;
        $aIds = bx_get('ids');
        if (!$aIds || !is_array($aIds)) {
            echoJson(array());
            exit;
        }

        foreach ($aIds as $mixedId){
            if ($this->_delete($mixedId)){
                $iAffected ++;
                $this->oDb->deleteCategories($mixedId);
            }
        }

        echo echoJson(array_merge(
            array(
                'grid' => $this->getCode(false),
            ),
            $iAffected ? array() : array('msg' => _t("_sys_grid_delete_failed"))
        ));
    }
    
    protected function _switcherChecked2State($isChecked)
    {
        return $isChecked ? 'active' : 'hidden';
    }

    protected function _switcherState2Checked($mixedState)
    {
        return 'active' == $mixedState ? true : false;
    }
    
    protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellAuthor($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['author'] > 0){
    	    $oProfile = BxDolProfile::getInstanceMagic($aRow['author']);
    	    $sProfile = $oProfile->getDisplayName();

            $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                'href' => $oProfile->getUrl(),
                'title' => $sProfile,
                'content' => $sProfile,
                'class' => 'bx-def-font-grayed'
            ));
        }
        else{
            $mixedValue = '';
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellModule($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_limitMaxLength($this->getModuleTitle($aRow['module']), $sKey, $aField, $aRow, $this->_isDisplayPopupOnTextOverflow);
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellValue($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getForm($sAction, $aCategory = array())
    {
        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-form-cats-form-' . $sAction,
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&module=' . $this->sModule . '&a=' . $sAction,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_categories',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
                'checker_helper' => 'BxStudioFormsCategoriesCheckerHelper'
            ),
            'inputs' => array (
                'id' => array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'value' => isset($aCategory['id']) ? (int)$aCategory['id'] : 0,
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'value' => array(
                    'type' => 'text_translatable',
                    'name' => 'value',
                    'caption' => _t('_adm_form_txt_categories_value'),
                    'info' => '',
                    'value' => isset($aCategory['value']) ? $aCategory['value'] : '',
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'AvailUnique',
                        'params' => array(isset($aCategory['id']) ? (int)$aCategory['id'] : 0),
                        'error' => _t('_adm_form_err_categories_value'),
                    ),
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_form_btn_labels_submit'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_form_btn_labels_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );
        return new BxTemplStudioFormView($aForm);
    }
    
    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oTemplate->addJs(array('jquery.form.min.js', 'forms_categories.js'));

        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }
    
    protected function canAdd()
    {
        return $this->sModule != '';
    }
    
    protected function _getActionAdd($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->canAdd())
            $isDisabled = true;

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }
    
    protected function _getFilterControls ()
    {
        parent::_getFilterControls();

        $aInputModules = $this->getModulesSelectOneArray('getCategories', false, false);
		foreach($aInputModules['values'] as $sKey => $sValue){
			$bEnable = false;
			$oModule = BxDolModule::getInstance($sKey);
			if (isset($oModule->_oConfig) && isset($oModule->_oConfig->CNF)){
				$CNF = $oModule->_oConfig->CNF;
				if (isset($CNF['PARAM_MULTICAT_ENABLED']) && $CNF['PARAM_MULTICAT_ENABLED'] == true){
					$bEnable = true;
				}
			}
			if (!$bEnable)
				unset($aInputModules['values'][$sKey]);
		}
		$aInputModules['values'] = array_merge(array('' => _t('_adm_txt_select_module')), $aInputModules['values']);
		$oForm = new BxTemplStudioFormView(array());
        $sContent = $oForm->genRow($aInputModules);
        $oForm = new BxTemplStudioFormView(array());

        $aInputSearch = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
            ),
            'tr_attrs' => array(
                'style' => 'display:none;'
            )
        );
        $sContent .= $oForm->genRow($aInputSearch);

        return  $sContent;
    }
    
    function getJsObject()
    {
        return 'oBxDolStudioFormsCategories';
    }
    
    function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('forms_categories.html', array(
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ));
    }
}

/** @} */
