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
    
    public function popup($aModuleData, $bManual)
    {
        $aVars = [];
        
        foreach ($aModuleData as $sModuleName => $sModuleData){            
           $aVars[] = [
               'html' => $sModuleData, 
               'title' => $this->getStringValueByModuleOrDefault('_bx_attendant_popup_with_recommended_title_', $sModuleName) , 
               'description' => $this->getStringValueByModuleOrDefault('_bx_attendant_popup_with_recommended_description_', $sModuleName)
           ];
        }
        
        if ($bManual || count($aVars) >0){
            return $this->parseHtmlByName('popup_recommended.html', [
                    'bx_if:data' => [
                        'condition' => count($aVars) > 0,
                        'content' => [
                            'bx_repeat:items' => $aVars, 
                            'button_text' => _t('_bx_attendant_popup_with_recommended_button_text')
                        ]
                    ],
                    'bx_if:nodata' => [
                        'condition' => count($aVars) == 0,
                        'content' => [
                        ]
                    ]
                ]
            );
        }
        else{
            return '';
        }
    }
    
    public function getJsCode($sType, $aParams = array(), $bWrap = true)
    {
        $sRedirectUrl = '';
        switch (getParam('bx_attendant_on_profile_after_action_url')) {
            case 'profile':
                $sRedirectUrl = BxDolProfile::getInstance()->getUrl();
                break;

            case 'custom':
                $sRedirectCustom = getParam('bx_attendant_on_profile_after_action_url_custom');
                if($sRedirectCustom) {
                    $sRedirectUrl = BxDolPermalinks::getInstance()->permalink($sRedirectCustom);

                    if (false === strpos($sRedirectUrl, 'http://') && false === strpos($sRedirectUrl, 'https://'))
                        $sRedirectUrl = BX_DOL_URL_ROOT . $sRedirectCustom;
                }
                break;
                
            case 'homepage':
                $sRedirectUrl =  BX_DOL_URL_ROOT;  
                break;
        }
        
        $aParams = array_merge(array(
            'sContainerId' => $this->sContainerId,
            'sUrlAfterShow' => $sRedirectUrl
        ), $aParams);
        
        return parent::getJsCode($sType, $aParams, $bWrap);
    }

    private function getStringValueByModuleOrDefault($sKey, $sModuleName)
    {
        $sFullKey = $sKey . $sModuleName;
        $sValue = _t($sFullKey);
        if ($sValue == $sFullKey){
            $oModule = BxDolModule::getInstance($sModuleName);
            if ($oModule)
                $sValue =_t($sKey . 'default', $oModule->_aModule['title']);
        }
        return $sValue;
    }
    
}

/** @} */
