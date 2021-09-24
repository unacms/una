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

        return $aAttrs;
    }

    protected function _genWrapperInputAttrsPassword(&$aAttrs)
    {
        if(!isset($aAttrs['class']))
            $aAttrs['class'] = '';

        $aAttrs['class'] .= 'relative block w-full py-1.5 border border-gray-300 dark:border-gray-700 rounded-md leading-5 bg-white dark:bg-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:text-gray-900 dark:focus:text-gray-100 focus:ring-blue-500 focus:border-opacity-70 focus:ring-opacity-20 focus:border-blue-500 text-sm text-gray-700 dark:text-gray-300';

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

        $aAttrs['class'] .= ' block w-full pl-3 pr-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-md leading-5 bg-white dark:bg-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:text-gray-900 dark:focus:text-gray-100 focus:ring-blue-500 focus:border-opacity-70 focus:ring-opacity-20 focus:border-blue-500 text-sm text-gray-700 dark:text-gray-300';

        return $aAttrs;
    }

    protected function _updateInputAttrsCheckbox(&$aAttrs)
    {
        if(!isset($aAttrs['class']))
            $aAttrs['class'] = '';

        $aAttrs['class'] .= ' block pl-3 pr-3 py-1.5 border border-gray-300 dark:border-gray-700 rounded-md leading-5 bg-white dark:bg-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:text-gray-900 dark:focus:text-gray-100 focus:ring-blue-500 focus:border-opacity-70 focus:ring-opacity-20 focus:border-blue-500 text-sm text-gray-700 dark:text-gray-300';

        return $aAttrs;
    }
}

/** @} */
