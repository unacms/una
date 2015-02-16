<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

class BxBaseStudioNavigationImport extends BxDolStudioNavigationImport
{
    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);

        $this->_aOptions['actions_single']['import']['attr']['title'] = _t('_adm_nav_btn_items_import');
    }

    public function performActionImport()
    {
        $iAffected = 0;
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $this->_echoResultJson(array());
            exit;
        }

        $aIdsAffected = array ();
        foreach($aIds as $iId) {
            $aItem = array();
            $iItem = $this->oDb->getItems(array('type' => 'by_id', 'value' => (int)$iId), $aItem);
            if($iItem != 1 || empty($aItem))
                continue;

            $mixedIcon = 0;
            if(is_numeric($aItem['icon']) && (int)$aItem['icon'] != 0) {
                $oStorage = BxDolStorage::getObjectInstance(BX_DOL_STORAGE_OBJ_IMAGES);

                if(($mixedIcon = $oStorage->storeFileFromStorage(array('id' => (int)$aItem['icon']), false, 0)) === false) {
                    $this->_echoResultJson(array('msg' => _t('_adm_nav_err_items_icon_copy') . $oStorage->getErrorString()), true);
                    return;
                }

                $oStorage->afterUploadCleanup($mixedIcon, 0);
            }

            $iIdImported = (int)$aItem['id'];
            $sTitleKey = $aItem['title'];

            unset($aItem['id']);
            $aItem['set_name'] = $this->sSet;
            $aItem['module'] = BX_DOL_STUDIO_MODULE_CUSTOM;
            $aItem['title'] .= '_' . time();
            $aItem['icon'] = $mixedIcon != 0 ? $mixedIcon : '';
            $aItem['active'] = 1;
            $aItem['order'] = $this->oDb->getItemOrderMax($this->sSet) + 1;

            if(($iIdAdded = (int)$this->oDb->addItem($aItem)) == 0)
                continue;

            BxDolStudioLanguagesUtils::getInstance()->addLanguageString($aItem['title'], _t($sTitleKey));

            $aIdsImported[] = $iIdImported;
            $aIdsAdded[] = $iIdAdded;
            $iAffected++;
        }

        $aResult = array('msg' => _t('_adm_nav_err_items_import'));
        if($iAffected) {
            $oGrid = BxDolGrid::getObjectInstance('sys_studio_nav_items');
            if($oGrid !== false)
                $aResult = array(
                    'parent_grid' => $oGrid->getCode(false),
                    'parent_blink' => $aIdsAdded,
                    'disable' => $aIdsImported,
                    'eval' => $this->getJsObject() . '.onImport(oData)'
                );
        }
        $this->_echoResultJson($aResult);
    }

    function getJsObject()
    {
        return 'oBxDolStudioNavigationImport';
    }

    function getCode($isDisplayHeader = true)
    {
        return $this->_oTemplate->parseHtmlByName('nav_import.html', array(
            'content' => parent::getCode($isDisplayHeader),
            'js_object' => $this->getJsObject(),
            'grid_object' => $this->_sObject,
            'params_divider' => $this->sParamsDivider
        ));
    }

    protected function _getCellIcon ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_oTemplate->getIcon($mixedValue, array('class' => 'bx-nav-item-icon bx-def-border'));
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getCellModule($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = $this->_limitMaxLength($this->getModuleTitle($aRow['module']), $sKey, $aField, $aRow, $this->_isDisplayPopupOnTextOverflow);
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }

    protected function _getActionDone ($sType, $sKey, $a, $isSmall = false, $isDisabled = false, $aRow = array())
    {
        $a['attr']['onclick'] = "$('.bx-popup-applied:visible').dolPopupHide()";
        return  parent::_getActionDefault($sType, $sKey, $a, false, $isDisabled, $aRow);
    }

    protected function _getFilterControls ()
    {
        parent::_getFilterControls();

        $oForm = new BxTemplStudioFormView(array());

        $aInputSets = array(
            'type' => 'select',
            'name' => 'set',
            'attrs' => array(
                'id' => 'bx-grid-set-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeFilter()'
            ),
            'value' => '',
            'values' => array()
        );

        $aSets = $aCounter = array();
        $this->oDb->getSets(array('type' => 'all', 'except' => $this->sSet), $aSets, false);
        $this->oDb->getItems(array('type' => 'counter_by_sets'), $aCounter, false);
        foreach($aSets as $aSet)
            $aInputSets['values'][$aSet['name']] = _t($aSet['title']) . " (" . (isset($aCounter[$aSet['name']]) ? $aCounter[$aSet['name']] : "0") . ")";

        asort($aInputSets['values']);
        $aInputSets['values'] = array_merge(array('' => _t('_adm_nav_txt_select_set')), $aInputSets['values']);

        return $oForm->genRow($aInputSets) . $this->getModulesSelectAll('getItems') . $this->getSearchInput();
    }
}

/** @} */
