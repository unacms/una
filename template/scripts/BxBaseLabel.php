<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

/**
 * Labels objects representation.
 * 
 * @see BxDolLabel
 */
class BxBaseLabel extends BxDolLabel
{
    protected $_oTemplate;

    protected $_sJsObjClass;
    protected $_sJsObjName;

    public function __construct($oTemplate = false)
    {
        parent::__construct();

        if($oTemplate)
            $this->_oTemplate = $oTemplate;
        else
            $this->_oTemplate = BxDolTemplate::getInstance();

        $this->_sJsObjClass = 'BxDolLabel';
        $this->_sJsObjName = 'oBxDolLabel';

        $this->_aHtmlIds = array(
            'labels_element' => 'sys-labels',
            'labels_select_popup' => 'sys-labels-select-popup',
        );
    }

    public function getJsObjectName()
    {
        return $this->_sJsObjName;
    }

    public function getJsCode($bDynamicMode = false)
    {
        $sCode = $this->_sJsObjName . " = new " . $this->_sJsObjClass . "(" . json_encode(array(
            'sObjName' => $this->_sJsObjName,
            'sRootUrl' => BX_DOL_URL_ROOT,
            'aHtmlIds' => $this->_aHtmlIds
        )) . ");";

        if($bDynamicMode) {
            $sCode = "var " . $this->_sJsObjName . " = null;
            if(typeof(jQuery.ui.core) == 'undefined')
                $.getScript('" . bx_js_string($this->_oTemplate->getJsUrl('jquery-ui/jquery.ui.core.min.js'), BX_ESCAPE_STR_APOS) . "');
            if(typeof(jQuery.ui.widget) == 'undefined')
                $.getScript('" . bx_js_string($this->_oTemplate->getJsUrl('jquery-ui/jquery.ui.widget.min.js'), BX_ESCAPE_STR_APOS) . "');
            if(typeof(jQuery.ui.menu) == 'undefined')
                $.getScript('" . bx_js_string($this->_oTemplate->getJsUrl('jquery-ui/jquery.ui.menu.min.js'), BX_ESCAPE_STR_APOS) . "');
            if(typeof(jQuery.ui.autocomplete) == 'undefined')
                $.getScript('" . bx_js_string($this->_oTemplate->getJsUrl('jquery-ui/jquery.ui.autocomplete.min.js'), BX_ESCAPE_STR_APOS) . "');
            if(window['" . $this->_sJsObjName . "'] === null)
                $.getScript('" . bx_js_string($this->_oTemplate->getJsUrl('BxDolLabel.js'), BX_ESCAPE_STR_APOS) . "', function(data, textStatus, jqxhr) {
                    " . $sCode . "
                });";
        }
        else
            $sCode = "if(window['" . $this->_sJsObjName . "'] == undefined) var " . $sCode;

        return $this->addCssJs($bDynamicMode) . $this->_oTemplate->_wrapInTagJsCode($sCode);
    }

    protected function selectLabels($aValues = array())
    {
        $sJsObject = $this->getJsObjectName();

        $oForm = BxDolForm::getObjectInstance($this->_sForm, $this->_sFormDisplaySelect);
        $oForm->initChecker($aValues);

        if($oForm->isSubmittedAndValid()) {
            $aLabels = array();
            if(($aLabelsSearch = $oForm->getCleanValue('search')) !== false)
                $aLabels = array_merge($aLabels, $aLabelsSearch);

            if(($aLabelsList = $oForm->getCleanValue('list')) !== false)
                $aLabels = array_merge($aLabels, $aLabelsList);

            $aLabels = array_unique($aLabels);

            return array('eval' => $sJsObject . '.onSelectLabels(oData);', 'content' => $this->getElementLabels(array(
                'value' => $aLabels, 
            )));
        }

        $sContent = BxTemplFunctions::getInstance()->transBox($this->_aHtmlIds['labels_select_popup'], $this->_oTemplate->parseHtmlByName('label_select_popup.html', array(
            'js_object' => $sJsObject,
            'form' => $oForm->getCode(),
            'form_id' => $oForm->getId()
        )));

        return array('popup' => array('html' => $sContent, 'options' => array('closeOnOuterClick' => false)));
    }

    public function getLabel($sField, $sLabel)
    {
        return $this->_oTemplate->parseHtmlByName('label_select_field_item.html', array(
            'field' => $sField,
            'label' => $sLabel
        ));
    }

    /**
     * Get 'List' custom form element.
     */
    public function getLabelsList(&$aInput, &$oForm)
    {
        $sContent = '';

        $bOpened = false;
        $iParentId = $iLevel = 0;
        $sContent = $this->_getLabelsList($iParentId, $iLevel, $bOpened, $aInput, $oForm);

        if(empty($sContent))
            $sContent = MsgBox(_t('_Empty'));

        return $this->_oTemplate->parseHtmlByName('label_select_list.html', array(
            'content' => $sContent
        ));
    }

    protected function _getLabelsList($iParentId, $iLevel, &$bOpened, &$aInput, &$oForm)
    {
        $aLabels = $this->getLabels(array('type' => 'parent', 'parent' => $iParentId));
        if(empty($aLabels) || !is_array($aLabels))
            return '';

        $sJsObject = $this->getJsObjectName();

        $bInputValue = !empty($aInput['value']) && is_array($aInput['value']);

        $aCheckbox = $aInput;
        $aCheckbox['type'] = 'checkbox';
        $aCheckbox['name'] .= '[]';

        $aTmplVarsLabels = array();
        foreach($aLabels as $aLabel) {
            $sHtmlId = 'sys_label_' . $aLabel['value'];
            $bChecked = $bInputValue && in_array($aLabel['value'], $aInput['value']);
            if($bChecked)
                $bOpened = true;

            $aCheckbox['value'] = $aLabel['value'];
            $aCheckbox['checked'] = $bChecked ? 1 : 0;
            $aCheckbox['attrs']['id'] = $sHtmlId;

            $bSublistOpened = false;
            $sSublist = $this->_getLabelsList($aLabel['id'], $iLevel + 1, $bSublistOpened, $aInput, $oForm);
            $bSublist = !empty($sSublist);

            $aTmplVarsLabels[] = array(
                'checkbox' => $oForm->genInput($aCheckbox),
                'html_id_label' => $sHtmlId,
                'title' => $aLabel['value'],
                'bx_if:show_sublist_link' => array(
                    'condition' => $bSublist,
                    'content' => array(
                        'js_object' => $sJsObject,
                        'bx_if:show_class_opened' => array(
                            'condition' => $bSublistOpened,
                            'content' => array()
                        ), 
                        'bx_if:show_class_closed' => array(
                            'condition' => !$bSublistOpened,
                            'content' => array()
                        )
                    )
                ),
                'bx_if:show_sublist' => array(
                    'condition' => $bSublist,
                    'content' => array(
                        'js_object' => $sJsObject,
                        'bx_if:show_class_opened' => array(
                            'condition' => $bSublistOpened,
                            'content' => array()
                        ),
                        'content' => $sSublist
                    ),
                )
            );
        }

        return $this->_oTemplate->parseHtmlByName('label_select_list_level.html', array(
            'level' => $iLevel,
            'bx_repeat:labels' => $aTmplVarsLabels
        ));
    }

    public function addCssJs($bDynamicMode = false)
    {
        $sInclude = '';

        if(!$bDynamicMode)
            $this->_oTemplate->addJs(array(
                'jquery-ui/jquery.ui.core.min.js',
                'jquery-ui/jquery.ui.widget.min.js',
                'jquery-ui/jquery.ui.menu.min.js',
                'jquery-ui/jquery.ui.autocomplete.min.js', 
                'BxDolLabel.js'
            ));

        $sInclude .= $this->_oTemplate->addCss(array(
            'forms.css', 
            'label.css'
        ), $bDynamicMode);

        return $bDynamicMode ? $sInclude : '';
    }
}

/** @} */
