<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    Attendant Attendant
 * @ingroup     UnaModules
 *
 * @{
 */

bx_import ('BxDolModuleTemplate');

class BxAttendantTemplate extends BxBaseModGeneralTemplate
{
    var $sContainerId = 'oBxAttendantPopupWithRecommendedOnProfileAdd';
    
    function __construct(&$oConfig, &$oDb)
    {
        parent::__construct($oConfig, $oDb);
    }
    
    public function popupWithRecommendedOnProfileAdd($aModuleData)
    {
        $this->addJs('main.js');
        $this->addCss('main.css');
        $aVars = array();
        foreach ($aModuleData as $sModuleName => $sModuleData){            
            array_push($aVars, array ('html' => $sModuleData, 'title' => $this->getStringValueByModuleOrDefault('_bx_attendant_popup_with_recommended_title_', $sModuleName) , 'description' => $this->getStringValueByModuleOrDefault('_bx_attendant_popup_with_recommended_description_', $sModuleName)));
        }
        $oBxBaseFunctions = BxBaseFunctions::getInstance();
        return  $this->getJsCode('main') . $oBxBaseFunctions->transBox($this->sContainerId, $this->parseHtmlByName('popup_recommended.html', array ('bx_repeat:items' => $aVars)), true, true);
    }
    
    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'sContainerId' => $this->sContainerId
        ), $aParams);
        
        return parent::getJsCode($sType, $aParams, $bWrap);
    }

    private function getStringValueByModuleOrDefault($sKey, $sModuleName)
    {
        $sFullKey = $sKey . $sModuleName;
        $sValue = _t($sFullKey);
        if ($sValue == $sFullKey)
            $sValue =_t($sKey . 'default');
        return $sValue;
    }
    
}

/** @} */
