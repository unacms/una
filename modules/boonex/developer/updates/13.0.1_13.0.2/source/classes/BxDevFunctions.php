<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Developer Developer
 * @ingroup     UnaModules
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

        $aInputs['object']['type'] = 'select';
        $aInputs['object']['value'] = $aParams['object'];
        $aInputs['object']['values'] = array();

    	$aForms = array();
        $oDb->getForms(array('type' => 'by_module', 'value' => $aParams['module']), $aForms, false);
        if(!empty($aForms) && is_array($aForms)) {
	        foreach($aForms as $aForm)
	            $aInputs['object']['values'][$aForm['object']] = _t($aForm['title']);

	        asort($aInputs['object']['values']);
        }
        $aInputs['object']['values'] = array_merge(array('' => _t('_bx_dev_frm_txt_select_object')), $aInputs['object']['values']);

        $aInputs['name']['type'] = 'text';

        $sTrlTypePostfix = '_translatable';
        $sTrlCheckFuncPostfix = 'Translatable';
        foreach($aInputs as $sName => $aInput)
            if(isset($aInput['type']) && stripos($aInput['type'], $sTrlTypePostfix) !== false) {
                $aInputs[$sName]['type'] = 'text';
                $aInputs[$sName]['html'] = 0;

                if(isset($aInput['checker']['func']) && stripos($aInput['checker']['func'], $sTrlCheckFuncPostfix) !== false)
                    $aInputs[$sName]['checker']['func'] = str_ireplace($sTrlCheckFuncPostfix, '', $aInput['checker']['func']);
            }

        unset($aInputs['required']['attrs']['onchange']);
        unset($aInputs['checker_func']['attrs']['disabled'], $aInputs['checker_func']['tr_attrs']['style']);
        unset($aInputs['checker_error']['attrs']['disabled'], $aInputs['checker_error']['tr_attrs']['style']);

        $aFields = array(
            'editable' => array(
                'type' => 'switcher',
                'name' => 'editable',
                'caption' => _t('_bx_dev_frm_txt_field_title_editable'),
                'info' => _t('_bx_dev_frm_txt_field_info_editable'),
                'value' => '1',
                'required' => '0',
                'db' => array (
                    'pass' => 'Int',
                )
            ),
            'deletable' => array(
                'type' => 'switcher',
                'name' => 'deletable',
                'caption' => _t('_bx_dev_frm_txt_field_title_deletable'),
                'info' => _t('_bx_dev_frm_txt_field_info_deletable'),
                'value' => '1',
                'required' => '0',
                'db' => array (
                    'pass' => 'Int',
                )
            ),
        );
        $aInputs = BxDolStudioUtils::addInArray($aInputs, 'controls', $aFields, false);
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
}

/** @} */
