<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaView UNA Studio Representation classes
 * @ingroup     UnaStudio
 * @{
 */

class BxBaseStudioAuditGrid extends BxDolStudioAuditGrid
{
    function __construct($aOptions, $oTemplate = false)
    {
        parent::__construct($aOptions, $oTemplate);
    }
    
	protected function _getCellAdded($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(bx_time_js($mixedValue), $sKey, $aField, $aRow);
    }
    
    protected function _getCellAction ($mixedValue, $sKey, $aField, $aRow)
    {
        return parent::_getCellDefault(_t($aRow['action_lang_key'], $aRow['action_lang_key_params']), $sKey, $aField, $aRow);
    }
    
    protected function _getCellContent ($mixedValue, $sKey, $aField, $aRow)
    {
        $mixedValue = '';
        if ($aRow['content_id'] > 0 && $aRow['content_info_object'] != ''){
            $oContentInfo = BxDolContentInfo::getObjectInstance($aRow['content_info_object']);
            if ($oContentInfo){
    	        $sTitle = $oContentInfo->getContentTitle($aRow['content_id']);
                if ($sTitle != ''){
                    $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                        'href' => $oContentInfo->getContentLink($aRow['content_id']),
                        'title' =>  $sTitle,
                        'content' =>  $sTitle,
                        'class' => 'bx-def-font-grayed'
                    ));
                }
            }
        }
        if ($mixedValue == ''){
            $mixedValue = $aRow['content_title'];
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellContext ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['context_profile_id'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['context_profile_id']);
            if ($oProfile){
    	        $sProfile = $oProfile->getDisplayName();
                $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                    'href' => $oProfile->getUrl(),
                    'title' => $sProfile,
                    'content' => $sProfile,
                    'class' => 'bx-def-font-grayed'
                ));
            }
            else{
                $mixedValue = $aRow['context_profile_title'];
            }
        }
        else{
            $mixedValue = '';
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    protected function _getCellModule ($mixedValue, $sKey, $aField, $aRow)
    {
        $oModule = bxDolModule::getInstance($aRow['content_module']);
        if($oModule instanceof iBxDolContentInfoService){
            $mixedValue = $oModule->_aModule['title'];
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
    
    
    protected function _getCellProfile ($mixedValue, $sKey, $aField, $aRow)
    {
        if ($aRow['profile_id'] > 0){
    	    $oProfile = BxDolProfile::getInstance($aRow['profile_id']);
            if ($oProfile){
    	        $sProfile = $oProfile->getDisplayName();
                $mixedValue =  $this->_oTemplate->parseHtmlByName('account_link.html', array(
                    'href' => $oProfile->getUrl(),
                    'title' => $sProfile,
                    'content' => $sProfile,
                    'class' => 'bx-def-font-grayed'
                ));
            }
            else{
                $mixedValue = 'deleted';
            }
        }
        else{
            $mixedValue = '';
        }
        return parent::_getCellDefault($mixedValue, $sKey, $aField, $aRow);
    }
}

/** @} */
