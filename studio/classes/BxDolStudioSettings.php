<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentStudio Trident Studio
 * @{
 */

define('BX_DOL_STUDIO_STG_TYPE_SYSTEM', 'system');
define('BX_DOL_STUDIO_STG_TYPE_DEFAULT', BX_DOL_STUDIO_STG_TYPE_SYSTEM);

define('BX_DOL_STUDIO_STG_GROUP_SYSTEM', 'system');
define('BX_DOL_STUDIO_STG_GROUP_MODULES', 'modules');
define('BX_DOL_STUDIO_STG_GROUP_LANGUAGES', 'languages');
define('BX_DOL_STUDIO_STG_GROUP_TEMPLATES', 'templates');

define('BX_DOL_STUDIO_STG_CATEGORY_SYSTEM', 'system');
define('BX_DOL_STUDIO_STG_CATEGORY_LANGUAGES', 'languages');
define('BX_DOL_STUDIO_STG_CATEGORY_TEMPLATES', 'templates');

class BxDolStudioSettings extends BxTemplStudioPage
{
    protected $sType;
    protected $sCategory;
    protected $aCategories;
    protected $aCustomCategories;

    function __construct($sType = '', $sCategory = '')
    {
        parent::__construct('settings');

        $this->oDb = new BxDolStudioSettingsQuery();

        $this->sType = BX_DOL_STUDIO_STG_TYPE_DEFAULT;
        if(is_string($sType) && !empty($sType))
            $this->sType = $sType;

        $this->sCategory = '';
        if(is_string($sCategory) && !empty($sCategory))
            $this->sCategory = $sCategory;

        //--- Check actions ---//
        if(($sAction = bx_get('stg_action')) !== false && ($sValue = bx_get('stg_value')) !== false) {
            $sAction = bx_process_input($sAction);
            $sValue = bx_process_input($sValue);

            $aResult = array('code' => 0, 'message' => '');
            if(!empty($sAction) && !empty($sValue)) {
                switch($sAction) {
                    case 'get-page-by-type':
                        $this->sType = $sValue;
                        $aResult['content'] = $this->getPageCode();
                        break;
                }

                echo json_encode($aResult);
            }
            exit;
        }
    }

    function saveChanges(&$oForm)
    {
        $aCategories = explode(',', $oForm->getCleanValue('categories'));

        foreach ($aCategories as $sCategory) {
            $aOptions = array();
            $iOptions = $this->oDb->getOptions(array('type' => 'by_category_name_full', 'value' => $sCategory), $aOptions);

            $aData = array();
            foreach($aOptions as $aOption) {
                $aData[$aOption['name']] = $oForm->getCleanValue($aOption['name']);

                if(!empty($aOption['check'])) {
                    $sCheckerHelper = '';
                    if(!empty($aOption['type_name']) && BxDolRequest::serviceExists($aOption['type_name'], 'get_settings_checker_helper'))
                        $sCheckerHelper = BxDolService::call($aOption['type_name'], 'get_settings_checker_helper');

                    if($sCheckerHelper == '') {
                        bx_import('BxDolStudioForm');
                        $sCheckerHelper = 'BxDolStudioFormCheckerHelper';
                    }

                    $oChecker = new $sCheckerHelper();
                    $aCheckFunction = array($oChecker, 'check' . bx_gen_method_name($aOption['check']));
                    $aCheckFunctionParams = array($aData[$aOption['name']]);
                    if(!empty($aOption['check_params']))
                        $aCheckFunctionParams = array_merge($aCheckFunctionParams, unserialize($aOption['check_params']));

                    if(is_callable($aCheckFunction) && !call_user_func_array($aCheckFunction, $aCheckFunctionParams)) {
                        $this->sCategory = $sCategory;
                        return $this->getJsResult(_t('_adm_stg_err_save_error_message', _t($aOption['caption']), _t($aOption['check_error'])), false);
                    }
                }

                if(isset($aData[$aOption['name']]))
                    $aData[$aOption['name']] = $this->getProcessedValue($aOption, $aData[$aOption['name']]);
                else
                    $aData[$aOption['name']] = $this->getEmptyValue($aOption);

                if($this->oDb->setParam($aOption['name'], $aData[$aOption['name']])) {
                	$aCategoryInfo = array();
		            $this->oDb->getCategories(array('type' => 'by_name', 'value' => $sCategory), $aCategoryInfo, false);

	        		bx_alert('system', 'save_setting', 0, 0, array('category' => $aCategoryInfo, 'option' => $aOption['name'], 'value' => $aData[$aOption['name']]));
                }
            }
        }

        return $this->getJsResult('_adm_stg_scs_save');
    }

    protected function getProcessedValue($aOption, $mixedValue)
    {
        if(is_array($mixedValue))
            $mixedValue = implode(',', $mixedValue);

        return $mixedValue;
    }

    protected function getEmptyValue($aOption)
    {
        $mixedValue = '';
        switch($aOption['type']) {
            case 'digit':
                $mixedValue = 0;
                break;
            case 'select':
                if (BxDolService::isSerializedService($aOption['extra']))
                    $aValues = BxDolService::callSerialized($aOption['extra']);
                else
                    $aValues = explode(',', $aOption['extra']);
                $mixedValue = $aValues[0];
                break;
            case 'text':
            case 'checkbox':
            case 'file':
                $mixedValue = "";
                break;
        }
        return $mixedValue;
    }
}

/** @} */
