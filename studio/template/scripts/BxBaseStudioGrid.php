<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentView Trident Studio Representation classes
 * @ingroup     TridentStudio
 * @{
 */

bx_import('BxDolStudioGrid');

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

    public function getModulesSelectOne($sGetItemsMethod, $bShowCustom = true, $bShowSystem = true)
    {
        if(empty($sGetItemsMethod))
            return '';

        bx_import('BxTemplStudioFormView');
        $oForm = new BxTemplStudioFormView(array());

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

        $aInputModules['values'] = array_merge(array('' => _t('_adm_txt_select_module')), $aInputModules['values']);

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
