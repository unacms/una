<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    Developer Developer
 * @ingroup     TridentModules
 *
 * @{
 */

class BxDevFunctions
{
    function __construct() {}

    /*
     * Is needed to change fields in "Form's Field" creation form.
     *
     * @see BxDevFormsField
     */
    public static function changeFormField($aParams, &$aInputs, &$oDb)
    {
        $aInputs['module']['type'] = 'select';
        $aInputs['module']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_module')), BxDolStudioUtils::getModules());
        $aInputs['module']['value'] = $aParams['module'];

        $aForms = array();
        $oDb->getForms(array('type' => 'by_module', 'value' => $aParams['module']), $aForms, false);
        foreach($aForms as $aForm)
            $aInputs['object']['values'][$aForm['object']] = _t($aForm['title']);
        asort($aInputs['object']['values']);

        $aInputs['object']['type'] = 'select';
        $aInputs['object']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_object')), $aInputs['object']['values']);
        $aInputs['object']['value'] = $aParams['object'];

        $sTrlTypePostfix = '_translatable';
        $sTrlCheckFuncPostfix = 'Translatable';
        foreach($aInputs as $sName => $aInput)
            if(isset($aInput['type']) && stripos($aInput['type'], $sTrlTypePostfix) !== false) {
                $aInputs[$sName]['type'] = str_ireplace($sTrlTypePostfix, '', $aInput['type']);

                if(isset($aInput['checker']['func']) && stripos($aInput['checker']['func'], $sTrlCheckFuncPostfix) !== false)
                    $aInputs[$sName]['checker']['func'] = str_ireplace($sTrlCheckFuncPostfix, '', $aInput['checker']['func']);
            }
    }

    public static function serializeString($sValue)
    {
        if(empty($sValue))
            return '';

        $aValue = array();
        @eval("\$aValue = " . $sValue . ";");
        if(empty($aValue) || !is_array($aValue))
            return '';

        return serialize($aValue);
    }

    public static function unserializeString($sValue)
    {
        if(empty($sValue))
            return '';

        $aValue = @unserialize($sValue);
        if(empty($aValue) || !is_array($aValue))
            return '';

        return var_export($aValue, true);
    }

    /**
     * Add slashes before "'" and "\" characters that the value containing them can be used in export feature.
     *
     * @param  string  $sString       - the input string to add slashes
     * @param  boolean $bDoubleEscape - whether the string needs to be two more escaped or not
     * @return string  the slashed string
     */
    public static function dbAddSlashes($sString = '', $bDoubleEscape = false)
    {
        if ($bDoubleEscape)
            $sString = str_replace('\\', '\\\\\\\\', $sString);
        else
            $sString = str_replace('\\', '\\\\', $sString);

        return str_replace('\'', '\\\'', $sString);
    }
}

/** @} */
