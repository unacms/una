<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaCore UNA Core
 * @{
 */

/**
 * Nested form. It is used in file uploaders to show uploaded file as nested form.
 * @see BxDolUploader
 * @see BxDolForm
 */
class BxDolFormNested extends BxTemplFormView
{
    public function __construct ($aInfo, $oTemplate)
    {
        parent::__construct($aInfo, $oTemplate);
    }
    
    function genInputStandard(&$aInput)
    {
        if($aInput['type'] != 'checkbox')
            return parent::genInputStandard($aInput);

        $aInputHidden = $aInput;
        
        $aInputHidden['type'] = 'hidden';
        if (isset($aInput['checked']) and $aInput['checked'])
            $aInputHidden['value'] = '1';
        else
            $aInputHidden['value'] = '0';
        
        $sRv = parent::genInputStandard($aInputHidden);
        $aInput['name'] = 'chk_' . $aInput['name'];
        $aInput['attrs']['onchange'] = 'BxDolForm.setCheckBoxValue(this)';
        $sRv .= parent::genInputStandard($aInput);
   
        return $sRv;
    }
}

/** @} */
