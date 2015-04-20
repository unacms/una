<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioFormsPreValues extends BxDolStudioFormsPreValues
{
    protected $sUrlPage;

    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_aOptions['actions_single']['edit']['attr']['title'] = _t('_adm_form_btn_pre_values_edit');
        $this->_aOptions['actions_single']['delete']['attr']['title'] = _t('_adm_form_btn_pre_values_delete');

        $this->sUrlPage = BX_DOL_URL_STUDIO . 'builder_forms.php?page=pre_values';
    }

    public function performActionAdd()
    {
        $sAction = 'add';

        $aList = array();
        $this->oDb->getLists(array('type' => 'by_key', 'value' => $this->sList), $aList, false);

        $bUseInSets = (int)$aList['use_for_sets'] == 1;

        bx_import('BxTemplStudioFormView');

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-form-pre-value-create',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&list=' . $this->sList,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_form_pre_values',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array (
                'Key' => array(
                    'type' => 'hidden',
                    'name' => 'Key',
                    'value' => $this->sList,
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                ),
                'Value' => array(
                    'type' => 'hidden',
                    'name' => 'Value',
                    'value' => $this->_getAvailableSetValue($this->sList),
                    'db' => array (
                        'pass' => 'Int',
                    )
                ),
                'LKey' => array(
                    'type' => 'text_translatable',
                    'name' => 'LKey',
                    'caption' => _t('_adm_form_txt_pre_values_lkey'),
                    'info' => _t('_adm_form_dsc_pre_values_lkey'),
                    'value' => '_adm_form_txt_pre_value',
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'LengthTranslatable',
                        'params' => array(3,100, 'LKey'),
                        'error' => _t('_adm_form_err_pre_values_lkey'),
                    ),
                ),
                'Empty' => array(
                    'type' => 'checkbox',
                    'name' => 'Empty',
                    'caption' => _t('_adm_form_txt_pre_values_empty'),
                    'info' => _t('_adm_form_dsc_pre_values_empty'),
                    'value' => 'on',
                    'required' => '0'
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_form_btn_pre_values_add'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_form_btn_pre_values_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            if($oForm->getCleanValue('Empty') == 'on')
                BxDolForm::setSubmittedValue('Value', '', $oForm->aFormAttrs['method']);

            if($bUseInSets) {
                $mixedValue = $oForm->getCleanValue('Value');
                if(!$this->canUseForSet($mixedValue)) {
                    if($this->oDb->isListUsedInSet($this->sList)) {
                        $this->_echoResultJson(array('msg' => _t('_adm_form_err_pre_values_create_forbidden', BX_DOL_STUDIO_FIELD_PRE_VALUE_INT_MAX)), true);
                        return;
                    }

                    $this->oDb->updateList($aList['id'], array('use_for_sets' => '0'));
                }
            }

            $iId = (int)$oForm->insert(array('LKey2' => '', 'Order' => $this->oDb->getValuesOrderMax($this->sList) + 1));
            if($iId != 0)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $iId);
            else
                $aRes = array('msg' => _t('_adm_form_err_pre_values_create'));

            $this->_echoResultJson($aRes, true);
        }
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-form-pre-value-create-popup', _t('_adm_form_txt_pre_values_create_popup'), $this->_oTemplate->parseHtmlByName('form_add_value.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    public function performActionEdit()
    {
        $sAction = 'edit';

        $aValue = $this->_getItem('getValues');
        if($aValue === false) {
            $this->_echoResultJson(array());
            exit;
        }

        $aList = array();
        $this->oDb->getLists(array('type' => 'by_key', 'value' => $this->sList), $aList, false);
        $bUseInSets = (int)$aList['use_for_sets'] == 1;

        bx_import('BxTemplStudioFormView');

        $aForm = array(
            'form_attrs' => array(
                'id' => 'adm-form-list-edit',
                'action' => BX_DOL_URL_ROOT . 'grid.php?o=' . $this->_sObject . '&a=' . $sAction . '&list=' . $this->sList,
                'method' => BX_DOL_STUDIO_METHOD_DEFAULT
            ),
            'params' => array (
                'db' => array(
                    'table' => 'sys_form_pre_values',
                    'key' => 'id',
                    'uri' => '',
                    'uri_title' => '',
                    'submit_name' => 'do_submit'
                ),
            ),
            'inputs' => array(
                'id' => array(
                    'type' => 'hidden',
                    'name' => 'id',
                    'value' => $aValue['id'],
                    'db' => array (
                        'pass' => 'Int',
                    ),
                ),
                'LKey' => array(
                    'type' => 'text_translatable',
                    'name' => 'LKey',
                    'caption' => _t('_adm_form_txt_pre_values_lkey'),
                    'info' => _t('_adm_form_dsc_pre_values_lkey'),
                    'value' => $aValue['lkey'],
                    'required' => '1',
                    'db' => array (
                        'pass' => 'Xss',
                    ),
                    'checker' => array (
                        'func' => 'LengthTranslatable',
                        'params' => array(3,100, 'LKey'),
                        'error' => _t('_adm_form_err_pre_values_lkey'),
                    ),
                ),
                'Empty' => array(
                    'type' => 'checkbox',
                    'name' => 'Empty',
                    'caption' => _t('_adm_form_txt_pre_values_empty'),
                    'info' => _t('_adm_form_dsc_pre_values_empty'),
                    'value' => 'on',
                    'checked' => empty($aValue['value']) ? '1' : '0',
                    'required' => '0'
                ),
                'controls' => array(
                    'name' => 'controls',
                    'type' => 'input_set',
                    array(
                        'type' => 'submit',
                        'name' => 'do_submit',
                        'value' => _t('_adm_form_btn_pre_lists_save'),
                    ),
                    array (
                        'type' => 'reset',
                        'name' => 'close',
                        'value' => _t('_adm_form_btn_pre_lists_cancel'),
                        'attrs' => array(
                            'onclick' => "$('.bx-popup-applied:visible').dolPopupHide()",
                            'class' => 'bx-def-margin-sec-left',
                        ),
                    )
                )
            )
        );

        $oForm = new BxTemplStudioFormView($aForm);
        $oForm->initChecker();

        if($oForm->isSubmittedAndValid()) {
            $aAdd = array();

            $bEmpty = $oForm->getCleanValue('Empty') == 'on';
            if($bEmpty && !empty($aValue['value']))
                $aAdd['Value'] = 0;
            else if(!$bEmpty && empty($aValue['value']))
                $aAdd['Value'] = $this->_getAvailableSetValue($this->sList);

            if(!empty($aAdd) && $bUseInSets) {
                $mixedValue = $aAdd['Value'];
                if(!$this->canUseForSet($mixedValue)) {
                    if($this->oDb->isListUsedInSet($this->sList)) {
                        $this->_echoResultJson(array('msg' => _t('_adm_form_err_pre_values_create_forbidden', BX_DOL_STUDIO_FIELD_PRE_VALUE_INT_MAX)), true);
                        return;
                    }

                    $this->oDb->updateList($aList['id'], array('use_for_sets' => '0'));
                }
            }

            if($oForm->update($aValue['id'], $aAdd) !== false)
                $aRes = array('grid' => $this->getCode(false), 'blink' => $aValue['id']);
            else
                $aRes = array('msg' => _t('_adm_form_err_pre_values_edit'));

            $this->_echoResultJson($aRes, true);
        } 
        else {
            $sContent = BxTemplStudioFunctions::getInstance()->popupBox('adm-form-pre-value-edit-popup', _t('_adm_form_txt_pre_values_edit_popup', _t($aValue['lkey'])), $this->_oTemplate->parseHtmlByName('form_add_value.html', array(
                'form_id' => $aForm['form_attrs']['id'],
                'form' => $oForm->getCode(true),
                'object' => $this->_sObject,
                'action' => $sAction
            )));

            $this->_echoResultJson(array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false))), true);
        }
    }

    public function performActionDelete()
    {
        $sAction = 'delete';

        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        $oLanguage = BxDolStudioLanguagesUtils::getInstance();

        $aList = array();
        $this->oDb->getLists(array('type' => 'by_key', 'value' => $this->sList), $aList, false);

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            $aValue = array();
            $this->oDb->getValues(array('type' => 'by_id', 'value' => (int)$iId), $aValue);
            if(!is_array($aValue) || empty($aValue))
                continue;

            if((int)$this->_delete($iId) <= 0)
                continue;

            $oLanguage->deleteLanguageString($aValue['lkey']);
            $oLanguage->deleteLanguageString($aValue['lkey2']);

            if((int)$aList['use_for_sets'] != 1) {
                $bUseInSets = 1;
                $aValues = BxDolForm::getDataItems($this->sList);
                foreach($aValues as $mixedValue => $sTitle)
                    if(!$this->canUseForSet($mixedValue)) {
                        $bUseInSets = 0;
                        break;
                    }

                if($bUseInSets == 1)
                    $this->oDb->updateList($aList['id'], array('use_for_sets' => $bUseInSets));
            }

            $aIdsAffected[] = $iId;
            $iAffected++;
        }

        $this->_echoResultJson($iAffected ? array('grid' => $this->getCode(false), 'blink' => $aIdsAffected) : array('msg' => _t('_adm_form_err_pre_values_delete')));
    }

    function getJsObject()
    {
        return 'oBxDolStudioFormsValues';
    }

    function getListsSelector($sModule = '')
    {
        $oForm = new BxTemplStudioFormView(array());

        $aInputLists = array(
            'type' => 'select',
            'name' => 'list',
            'attrs' => array(
                'id' => 'bx-grid-list-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeList()'
            ),
            'value' => $this->sList,
            'values' => array()
        );

        $aLists = array();
        if(!empty($sModule))
            $this->oDb->getLists(array('type' => 'by_module', 'value' => $sModule), $aLists, false);
        else
            $aInputLists['attrs']['disabled'] = 'disabled';

        if(!empty($aLists)) {
            $aCounter = array();
            $this->oDb->getValues(array('type' => 'counter_by_lists'), $aCounter, false);
            foreach($aLists as $aList)
                $aInputLists['values'][$aList['key']] = _t($aList['title']) . " (" . (isset($aCounter[$aList['key']]) ? $aCounter[$aList['key']] : "0") . ")";

            asort($aInputLists['values']);
        }

        $aInputLists['values'] = array_merge(array('' => _t('_adm_form_txt_select_list')), $aInputLists['values']);

        return $oForm->genRow($aInputLists);
    }

    function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('forms_values.html', array(
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
        $this->_oTemplate->addJs(array('jquery.form.min.js', 'forms_values.js'));

        $oForm = new BxTemplStudioFormView(array());
        $oForm->addCssJs();
    }

    protected function _getActionAdd($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        if($this->sList == '')
            $isDisabled = true;

        return parent::_getActionDefault($sType, $sKey, $a, $isSmall, $isDisabled, $aRow);
    }

    protected function _getFilterControls ()
    {
        parent::_getFilterControls();

        $sContent = $this->getModulesSelectOne('getValues') . $this->getListsSelector($this->sModule);

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

    protected function _getAvailableSetValue($sList)
    {
        $aValues = array();
        $this->oDb->getValues(array('type' =>'by_key_key_value', 'value' => $sList), $aValues, false);
        ksort($aValues);

        $iValue = 1;
        foreach($aValues as $aValue) {
            if((int)$aValue['value'] == 0)
                continue;

            if((int)$aValue['value'] != $iValue)
                break;

            $iValue++;
        }

        return $iValue;
    }

    protected function canUseForSet($mixedValue)
    {
        return is_numeric($mixedValue) && (int)$mixedValue >= 1 && (int)$mixedValue <= BX_DOL_STUDIO_FIELD_PRE_VALUE_INT_MAX;
    }
}

/** @} */
