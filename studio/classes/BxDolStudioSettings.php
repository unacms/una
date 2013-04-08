<?php
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    DolphinStudio Dolphin Studio
 * @{
 */
defined('BX_DOL') or die('hack attempt');

bx_import('BxTemplStudioPage');
bx_import('BxDolStudioSettingsQuery');

define('BX_DOL_STUDIO_STG_TYPE_SYSTEM', 'system');
define('BX_DOL_STUDIO_STG_TYPE_DEFAULT', BX_DOL_STUDIO_STG_TYPE_SYSTEM);

define('BX_DOL_STUDIO_STG_GROUP_SYSTEM', 'system');
define('BX_DOL_STUDIO_STG_GROUP_MODULES', 'modules');
define('BX_DOL_STUDIO_STG_GROUP_LANGUAGES', 'languages');

define('BX_DOL_STUDIO_STG_CATEGORY_SYSTEM', 'system');
define('BX_DOL_STUDIO_STG_CATEGORY_LANGUAGES', 'languages');
define('BX_DOL_STUDIO_STG_CATEGORY_TEMPLATES', 'templates');

class BxDolStudioSettings extends BxTemplStudioPage {
    protected $sType;
    protected $sCategory;
    protected $aCategories;
    protected $aCustomCategories;

    function BxDolStudioSettings($sType = '', $sCategory = '') {
        parent::BxTemplStudioPage('settings');

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

                $oJson = new Services_JSON();
                echo $oJson->encode($aResult);
            }
            exit;
        }
    }

    function saveChanges(&$oForm) {
        $aCategories = explode(',', $oForm->getCleanValue('categories'));

        foreach ($aCategories as $sCategory) {
            $aOptions = array();
            $iOptions = $this->oDb->getOptions(array('type' => 'by_category_name', 'value' => $sCategory), $aOptions);

            $aData = array();
            foreach($aOptions as $aOption) {
                $aData[$aOption['name']] = $oForm->getCleanValue($aOption['name']);

                if(!empty($aOption['check'])) {
                    $oFunction = create_function('$arg0', $aOption['check']);
                    if(!$oFunction($aData[$aOption['name']])) {
                        $this->sCategory = $sCategory;
                        return $this->getJsResult("'" . $aOption['title'] .  "' " . $aOption['check_error'], false);
                    }
                }

                if(isset($aData[$aOption['name']]))
                    $aData[$aOption['name']] = $this->getProcessedValue($aOption, $aData[$aOption['name']]);
                else
                    $aData[$aOption['name']] = $this->getEmptyValue($aOption);

                $this->oDb->setParam($aOption['name'], $aData[$aOption['name']]);
            }
        }

        return $this->getJsResult('_adm_stg_scs_save');
    }

    protected function getProcessedValue($aOption, $mixedValue) {
        if(is_array($mixedValue))
            $mixedValue = implode(',', $mixedValue);

        switch($aOption['type']) {
            case 'checkbox':
                $mixedValue = $mixedValue === true ? 'on' : '';
                break;
        }

        return $mixedValue;
    }

    protected function getEmptyValue($aOption) {
        $mixedValue = '';
        switch($aOption['type']) {
            case 'digit':
                $mixedValue = 0;
                break;
            case 'select':
                $aValues = explode(",", $aOption['extra']);
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