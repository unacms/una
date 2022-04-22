<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioFormsSearchSortableFields extends BxDolStudioFormsSearchFields
{
    protected $sUrlPage;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->sUrlPage = BX_DOL_URL_STUDIO . 'builder_forms.php?page=search_sortable_fields';
    }


    public function performActionReset()
    {
        $mixedResult = BxDolSearchExtended::getObjectInstance($this->sForm)->resetSortableFields();
        if($mixedResult === false)
            return echoJson(array('msg' => _t('_adm_from_err_search_forms_sortable_fields_reset')));

        echoJson(array('grid' => $this->getCode(false)));
    }

    function getJsObject()
    {
        return 'oBxDolStudioFormsSearchFields';
    }

    function getFormsSelector($sModule = '')
    {
        $oForm = new BxTemplStudioFormView(array());

        $aInputForms = array(
            'type' => 'select',
            'name' => 'form',
            'attrs' => array(
                'id' => 'bx-grid-form-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeForm()'
            ),
            'value' => $this->sForm,
            'values' => array(
                '' => _t('_adm_form_txt_search_forms_fields_select_object'),
            )
        );

        if(!empty($sModule)) {
            $aForms = array();
            $this->oDb->getSearchForms(array('type' => 'by_module', 'module' => $sModule), $aForms, false);

            foreach($aForms as $aForm)
                 $aInputForms['values'][] = array(
                 	'key' => $aForm['object'], 
                 	'value' => _t($aForm['title'])
                 );
        }
        else
            $aInputForms['attrs']['disabled'] = 'disabled';

        return $oForm->genRow($aInputForms);
    }

    function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('forms_search_fields.html', array(
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'page_url' => $this->sUrlPage,
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ));
    }

    protected function _addJsCss()
    {
        parent::_addJsCss();
        $this->_oTemplate->addCss(array('menu.css'));
        $this->_oTemplate->addJs(array('jquery.form.min.js', 'forms_sortable_fields.js'));

        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }

    protected function _getCellSwitcher ($mixedValue, $sKey, $aField, $aRow)
    {
        if(!$this->_isEditable($aRow))
            return parent::_getCellDefault('', $sKey, $aField, $aRow);

        return parent::_getCellSwitcher($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionReset ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if(!$this->_isResetable($aRow))
            return '';

        if($this->sForm == '')
            $isDisabled = true;

        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getFilterControls ()
    {
        parent::_getFilterControls();

        $sContent = $this->getModulesSelectOne('getSearchForms') . $this->getFormsSelector($this->sModule);

        $oForm = new BxTemplStudioFormView(array());

        $aInputSearch = array(
            'type' => 'text',
            'name' => 'keyword',
            'attrs' => array(
                'id' => 'bx-grid-search-' . $this->_sObject,
            ),
            'tr_attrs' => array(
                'style' => empty($this->sModule) || empty($this->sForm) ? 'display:none;' : ''
            )
        );
        $sContent .= $oForm->genRow($aInputSearch);

        return  $sContent;
    }

    protected function _isEditable(&$aRow)
    {
    	return true;
    }

    protected function _isResetable(&$aRow)
    {
    	return true;
    }
}

/** @} */
