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
    
    public function init()
    {
        $this->addJs([
                'main.js', 
                'flickity/flickity.pkgd.min.js', 
                'modules/base/general/js/|showcase.js'
            ]
        );
        $this->addCss([
                'main.css', 
                BX_DIRECTORY_PATH_PLUGINS_PUBLIC . 'flickity/|flickity.css'
            ]
        );
        
        return $this->getJsCode('main') . BxBaseFunctions::getInstance()->transBox($this->sContainerId, '', true, true);
    }
    
    public function popupWithRecommendedOnProfileAdd($aModuleData)
    {
        $aVars = [];
        
        foreach ($aModuleData as $sModuleName => $sModuleData){            
           $aVars[] = [
               'html' => $sModuleData, 
               'title' => $this->getStringValueByModuleOrDefault('_bx_attendant_popup_with_recommended_title_', $sModuleName) , 
               'description' => $this->getStringValueByModuleOrDefault('_bx_attendant_popup_with_recommended_description_', $sModuleName)
           ];
        }
        
        return $this->parseHtmlByName('popup_recommended.html', [
                'bx_repeat:items' => $aVars, 
                'button_text' => _t('_bx_attendant_popup_with_recommended_button_text')
            ]
        );
    }
    
    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $aParams = array_merge(array(
            'sContainerId' => $this->sContainerId,
            'sUrlAfterShow' => getParam('bx_attendant_on_profile_after_action_url')
        ), $aParams);
        
        return parent::getJsCode($sType, $aParams, $bWrap);
    }

    private function getStringValueByModuleOrDefault($sKey, $sModuleName)
    {
        $sFullKey = $sKey . $sModuleName;
        $sValue = _t($sFullKey);
        if ($sValue == $sFullKey){
            $oModule = BxDolModule::getInstance($sModuleName);
            $sValue =_t($sKey . 'default', $oModule->_aModule['title']);
        }
        return $sValue;
    }
    
}

/** @} */
