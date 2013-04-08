<? defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 * 
 * @defgroup    Developer Developer
 * @ingroup     DolphinModules
 *
 * @{
 */

bx_import('BxDolStudioUtils');

class BxDevFunctions {
    function __construct() {}

    /*
     * Is needed to change fields in "Form's Field" creation form.
     *
     * @see BxDevFormsField
     */
    public static function changeFormField($aParams, &$aInputs, &$oDb) {
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
    }

    public static function serializeString($sValue) {
        if(empty($sValue))
            return '';

        $aValue = array();
        @eval("\$aValue = " . $sValue . ";");
        if(empty($aValue) || !is_array($aValue))
            return '';

        return serialize($aValue);
    }

    public static function unserializeString($sValue) {
        if(empty($sValue))
            return '';

        $aValue = @unserialize($sValue);
        if(empty($aValue) || !is_array($aValue))
            return '';

        return var_export($aValue, true);        
    }
}

/** @} */