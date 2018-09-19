<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

class BxTemplFormView extends BxBaseFormView
{
    function __construct($aInfo, $oTemplate = false)
    {
        parent::__construct($aInfo, $oTemplate);
    }

    //TODO: genWrapperInput and isHtmlEditor methods can be removed after RC9 is released. 
    function genWrapperInput($aInput, $sContent)
    {
    	$sAttr = isset($aInput['attrs_wrapper']) && is_array($aInput['attrs_wrapper']) ? bx_convert_array2attrs($aInput['attrs_wrapper']) : '';
    
    	return "<div class=\"bx-form-input-wrapper bx-form-input-wrapper-{$aInput['type']}" . ((isset($aInput['html']) && $aInput['html'] && $this->isHtmlEditor($aInput['html'], $aInput)) ? ' bx-form-input-wrapper-html' : '') . "\" $sAttr>$sContent</div>";
    }

    function isHtmlEditor($iViewMode, &$aInput)
    {
    	return BxDolEditor::getObjectInstance(false, $this->oTemplate) !== false;
    }
}

/** @} */
