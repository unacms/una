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

    protected function _genWrapperInputAttrs(&$aInput)
    {
        $aAttrs = parent::_genWrapperInputAttrs($aInput);

        if(in_array($aInput['type'], ['password']))
            $this->_genWrapperInputAttrsPassword($aAttrs);
        
        if(in_array($aInput['type'], ['checkbox_set', 'radio_set']))
            $this->_genWrapperInputAttrsSet($aAttrs);

        return $aAttrs;
    }

    protected function _genWrapperInputAttrsPassword(&$aAttrs)
    {
        if(!isset($aAttrs['class']))
            $aAttrs['class'] = '';

        $aAttrs['class'] .= 'relative block w-full py-2 pl-3 pr-8 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:text-gray-900 dark:focus:text-gray-100 focus:ring-blue-500 focus:border-opacity-70 focus:ring-opacity-20 focus:border-blue-500 text-sm text-gray-700 dark:text-gray-300';

        return $aAttrs;
    }
    
    protected function _genWrapperInputAttrsSet(&$aAttrs)
    {
        if(!isset($aAttrs['class']))
            $aAttrs['class'] = '';

        $aAttrs['class'] .= 'bx-form-input-wrapper-set relative block w-full py-2 pl-3 pr-8 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400  dark:focus:text-gray-100 text-sm text-gray-700 dark:text-gray-300';

        return $aAttrs;
    }

    protected function _genInputStandardAttrs(&$aInput)
    {
        $aAttrs = parent::_genInputStandardAttrs($aInput);
        if(in_array($aInput['type'], ['hidden', 'password']))
            return $aAttrs;

        if(in_array($aInput['type'], ['checkbox', 'radio']))
            return $this->_updateInputAttrsCheckbox($aAttrs);

        return $this->_updateInputAttrs($aAttrs);
    }

    protected function _genInputSelectAttrs(&$aInput, $isMultiple)
    {
        $aAttrs = parent::_genInputSelectAttrs($aInput, $isMultiple);
        return $this->_updateInputAttrs($aAttrs);
    }

    protected function _genInputTextareaAttrs(&$aInput)
    {
        $aAttrs = parent::_genInputTextareaAttrs($aInput);
        return $this->_updateInputAttrs($aAttrs);
    }

    protected function _genCustomInputUsernamesSuggestionsAttrs (&$aInput, $bDisabled = false)
    {
        $aAttrs = parent::_genCustomInputUsernamesSuggestionsAttrs($aInput, $bDisabled);

        $aAttrs = $this->_updateInputAttrs($aAttrs);
        $aAttrs['class'] .= ' flex flex-wrap items-stretch';

        return $aAttrs;
    }

    protected function _genCustomInputUsernamesSuggestionsTextAttrs (&$aInput, $bDisabled = false)
    {
        $aAttrs = parent::_genCustomInputUsernamesSuggestionsTextAttrs($aInput, $bDisabled);

        if(!isset($aAttrs['class']))
            $aAttrs['class'] = '';

        $aAttrs['class'] .= ' leading-10';

        return $aAttrs;
    }

    protected function _updateInputAttrs(&$aAttrs)
    {
        if(!isset($aAttrs['class']))
            $aAttrs['class'] = '';

        $aAttrs['class'] .= ' block w-full py-2 px-3 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:text-gray-900 dark:focus:text-gray-100 focus:ring-blue-500 focus:border-opacity-70 focus:ring-opacity-20 focus:border-blue-500 text-sm text-gray-700 dark:text-gray-300 appearance-none';
 
        return $aAttrs;
    }

    protected function _updateInputAttrsCheckbox(&$aAttrs)
    {
        if(!isset($aAttrs['class']))
            $aAttrs['class'] = '';

        $aAttrs['class'] .= ' block py-2 px-3 border border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:text-gray-900 dark:focus:text-gray-100 focus:ring-blue-500 focus:border-opacity-70 focus:ring-opacity-20 focus:border-blue-500 text-sm text-gray-700 dark:text-gray-300';

        return $aAttrs;
    }
    
    function genInputSwitcher(&$aInput)
    {
        $aInput['type'] = 'checkbox';
        $sCheckbox = $this->genInputStandard($aInput);

        $aInput['type'] = 'switcher';
        
        $sClass = 'off';    
        $sClassLight = 'bg-gray-200';
        $sClassDark = 'bg-gray-900';
        $sClass2 = 'translate-x-0';
        $sClassLight2 = 'bg-white';
        $sClassDark2 = 'bg-gray-500';
        if(isset($aInput['checked']) && $aInput['checked']){
            $sClass = 'on';
            $sClassLight = 'bg-white';
            $sClassDark = 'bg-gray-500';
            $sClass2 = 'translate-x-5';
            $sClassLight2 = 'bg-green-500';
            $sClassDark2 = 'bg-green-500';
        }

        return $this->oTemplate->parseHtmlByName('form_field_switcher.html', [
            'class' => $sClass,
            'class_light' => $sClassLight,
            'class_dark' => $sClassDark,
            'class_light2' => $sClassLight2,
            'class_dark2' => $sClassDark2,
            'class2' => $sClass2,
            'checkbox' => $sCheckbox
        ]);
    }
}

/** @} */
