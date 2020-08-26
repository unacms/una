<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaBaseView UNA Base Representation Classes
 * @{
 */

class BxBaseLabelForm extends BxTemplFormView
{
    protected $_oObject;

    public function __construct($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);

        $this->_oObject = BxDolLabel::getInstance();

        switch($this->aParams['display']) {
            case 'sys_labels_select':
                $this->aInputs['action']['value'] = 'select_labels';
                break;
        }
    }

    public function initChecker($aValues = array(), $aSpecificValues = array())
    {
        return parent::initChecker($aValues, $aSpecificValues);
    }

    public function getFormElement($aInput = array())
    {
        $sName = !empty($aInput['name']) ? $aInput['name'] : 'labels';
        $sHtmlId = !empty($aInput['attrs']['id']) ? $aInput['attrs']['id'] : 'bx-form-element-labels';

        $sValue = '';
        if(!empty($aInput['value']) && is_array($aInput['value']))
            foreach($aInput['value'] as $sLabel)
                $sValue .= $this->_oObject->getLabel($sName, $sLabel);

        $sKeyPlaceholder = $aInput['caption_src'] . '_placeholder';
        if(strcmp($sKeyPlaceholder, _t($sKeyPlaceholder)) != 0)
            $sValue .= $this->_oObject->getLabelPlaceholder($sKeyPlaceholder);           

        $aInputLabels = array(
            'type' => 'custom',
            'name' => $sName,
            'caption' => '',
            'value' => $sValue,
            'ajax_get_suggestions' => BX_DOL_URL_ROOT . bx_append_url_params('label.php', array(
                'action' => 'labels_list',
            )),
            'attrs' => array(
                'class' => 'bx-form-input-labels',
                'disabled' => 'disabled'
            )
        );

        return $this->oTemplate->parseHtmlByName('label_select_field.html', array(
            'js_object' => $this->_oObject->getJsObjectName(),
            'js_code' => $this->_oObject->getJsCodeForm(),
            'html_id' => $sHtmlId,
            'name' => $sName,
            'input_labels' => $this->genCustomInputUsernamesSuggestions($aInputLabels)
        ));
    }

    protected function genCustomInputSearch($aInput)
    {
        $aInput = array_merge($aInput, array(
            'custom' => array(
                'b_img' => false
            ),
            'ajax_get_suggestions' => BX_DOL_URL_ROOT . bx_append_url_params('label.php', array(
              'action' => 'labels_list',
            ))
        ));

        return $this->genCustomInputUsernamesSuggestions($aInput);
    }
    
    protected function genCustomInputList($aInput)
    {
        return $this->_oObject->getLabelsList($aInput, $this);
    }
}

/** @} */
