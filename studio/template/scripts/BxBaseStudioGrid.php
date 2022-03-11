<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioGrid extends BxDolStudioGrid
{
    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);
    }

    function getJsObject()
    {
        return '';
    }
    
    public function getModulesSelectOneArray($sGetItemsMethod, $bShowCustom = true, $bShowSystem = true)
    {
        if(empty($sGetItemsMethod))
            return '';

        $aInputModules = array(
            'type' => 'select',
            'name' => 'module',
            'attrs' => array(
                'id' => 'bx-grid-module-' . $this->_sObject,
                'onChange' => 'javascript:' . $this->getJsObject() . '.onChangeModule()'
            ),
            'value' => $this->sModule,
            'values' => $this->getModules($bShowCustom, $bShowSystem)
        );

        $aCounter = array();
        $this->oDb->$sGetItemsMethod(array('type' => 'counter_by_modules'), $aCounter, false);
        foreach($aInputModules['values'] as $sKey => $sValue)
            $aInputModules['values'][$sKey] = $aInputModules['values'][$sKey] . " (" . (isset($aCounter[$sKey]) ? $aCounter[$sKey] : "0") . ")";
        
        return $aInputModules;
    }

    public function getModulesSelectOne($sGetItemsMethod, $bShowCustom = true, $bShowSystem = true)
    {
        $aInputModules = $this->getModulesSelectOneArray($sGetItemsMethod, $bShowCustom, $bShowSystem);
        $aInputModules['values'] = array_merge(array('' => _t('_adm_txt_select_module')), $aInputModules['values']);
        $oForm = new BxTemplStudioFormView(array());
        return $oForm->genRow($aInputModules);
    }

    protected function _getItem($sDbMethod = '')
    {
        $aIds = bx_get('ids');
        if(!$aIds || !is_array($aIds)) {
            $iId = (int)bx_get('id');
            if(!$iId)
                return false;

            $aIds = array($iId);
        }

        $iId = $aIds[0];

        $aItem = array();
        $this->oDb->$sDbMethod(array('type' => 'by_id', 'value' => $iId), $aItem, false);
        if(!is_array($aItem) || empty($aItem))
            return false;

        return $aItem;
    }
}

/** @} */
