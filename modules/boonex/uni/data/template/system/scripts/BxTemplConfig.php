<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) BoonEx Pty Limited - http://www.boonex.com/
 * CC-BY License - http://creativecommons.org/licenses/by/3.0/
 *
 * @defgroup    TridentCore Trident Core
 * @{
 */

class BxTemplConfig extends BxBaseConfig
{
	protected $_isModule;
	
    function __construct()
    {
        parent::__construct();

        $sName = 'bx_uni';
        $this->_isModule = BxDolModuleQuery::getInstance()->isModuleByName($sName);


        //--- Images
        $oStorage = BxDolStorage::getObjectInstance('sys_images_custom');

        $this->_aConfig['aLessConfig']['bx-image-bg-header'] = $this->_setUrl($sName . '_header_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-footer'] = $this->_setUrl($sName . '_footer_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-page'] = $this->_setUrl($sName . '_body_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-block'] = $this->_setUrl($sName . '_block_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-box'] = $this->_setUrl($sName . '_card_bg_image', $oStorage);

        //--- Shadow
        $this->_aConfig['aLessConfig']['bx-shadow-header'] = $this->_setShadow($sName . '_header_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-footer'] = $this->_setShadow($sName . '_footer_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-block'] = $this->_setShadow($sName . '_block_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-card'] = $this->_setShadow($sName . '_card_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-button'] = $this->_setShadow($sName . '_button_lg_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-button-small'] = $this->_setShadow($sName . '_button_sm_shadow');
        
        $this->_aConfig['aLessConfig']['bx-shadow-font-button'] = $this->_setShadowFont($sName . '_button_lg_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-button-small'] = $this->_setShadowFont($sName . '_button_sm_font_shadow');

        //--- Height
		$this->_aConfig['aLessConfig']['bx-height-button'] = $this->_setSize($sName . '_button_lg_height', '36px');
		$this->_aConfig['aLessConfig']['bx-height-button-small'] = $this->_setSize($sName . '_button_sm_height', '24px');

        //--- Title/Content Margins
        $this->_aConfig['aLessConfig']['bx-content-padding-header'] = $this->_setMargin($sName . '_header_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-footer'] = $this->_setMargin($sName . '_footer_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-block'] = $this->_setMargin($sName . '_block_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-card'] = $this->_setMargin($sName . '_card_content_padding');

		$this->_aConfig['aLessConfig']['bx-title-padding-block'] = $this->_setMargin($sName . '_block_title_padding');


        //--- Colors
        $this->_aConfig['aLessConfig']['bx-color-header'] = $this->_setColor($sName . '_header_bg_color', 'rgba(59, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-page'] = $this->_setColor($sName . '_body_bg_color', 'rgb(255, 255, 255)');
        $this->_aConfig['aLessConfig']['bx-color-footer'] = $this->_setColor($sName . '_footer_bg_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-block'] = $this->_setColor($sName . '_block_bg_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-box'] = $this->_setColor($sName . '_card_bg_color', 'rgba(242, 242, 242, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button'] = $this->_setColor($sName . '_button_lg_bg_color', 'rgba(108, 170, 138, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-small'] = $this->_setColor($sName . '_button_sm_bg_color', 'rgba(108, 170, 138, 1)');

        $this->_aConfig['aLessConfig']['bx-color-font-block-title'] = $this->_setColor($sName . '_block_title_font_color', 'rgba(0, 0, 20, 1)');

        $this->_aConfig['aLessConfig']['bx-color-border-header'] = $this->_setColor($sName . '_header_border_color', 'rgba(208, 208, 208, 1)');
        $this->_aConfig['aLessConfig']['bx-color-border-footer'] = $this->_setColor($sName . '_footer_border_color', 'rgba(208, 208, 208, 1)');
        $this->_aConfig['aLessConfig']['bx-color-border-block'] = $this->_setColor($sName . '_block_border_color', 'rgba(208, 208, 208, 1)');
        $this->_aConfig['aLessConfig']['bx-color-border-box'] = $this->_setColor($sName . '_card_border_color', 'rgba(208, 208, 208, 1)');

		$this->_aConfig['aLessConfig']['bx-color-icon-header'] = $this->_setColor($sName . '_header_icon_color', 'rgba(255, 255, 255, 1)');
		$this->_aConfig['aLessConfig']['bx-color-icon-footer'] = $this->_setColor($sName . '_footer_icon_color', 'rgba(62, 134, 133, 1)');

		$this->_aConfig['aLessConfig']['bx-color-link-header'] = $this->_setColor($sName . '_header_link_color', 'rgba(62, 134, 133, 1)');		
		$this->_aConfig['aLessConfig']['bx-color-link-footer'] = $this->_setColor($sName . '_footer_link_color', 'rgba(62, 134, 133, 1)');

		$this->_aConfig['aLessConfig']['bx-color-font-button'] = $this->_setColor($sName . '_button_lg_font_color', 'rgba(255, 255, 255, 1)');
		$this->_aConfig['aLessConfig']['bx-color-font-button-small'] = $this->_setColor($sName . '_button_sm_font_color', 'rgba(255, 255, 255, 1)');
		
		

        //--- Borders
        $this->_aConfig['aLessConfig']['bx-border-width-header'] = $this->_setSize($sName . '_header_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-footer'] = $this->_setSize($sName . '_footer_border_size', '1px');
        $this->_aConfig['aLessConfig']['bx-border-width-block'] = $this->_setSize($sName . '_block_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-box'] = $this->_setSize($sName . '_card_border_size');

        $this->_aConfig['aLessConfig']['bx-border-radius-block'] = $this->_setSize($sName . '_block_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-box'] = $this->_setSize($sName . '_card_border_radius', '3px');
        $this->_aConfig['aLessConfig']['bx-border-radius-button'] = $this->_setSize($sName . '_button_lg_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-button-small'] = $this->_setSize($sName . '_button_sm_border_radius');

		//--- Default Fonts
		if($this->_isModule) {
	        $this->_aConfig['aLessConfig']['bx-font-family'] = getParam($sName . '_font_family');
	        $this->_aConfig['aLessConfig']['bx-font-size-default'] = getParam($sName . '_font_size_default');
	        $this->_aConfig['aLessConfig']['bx-font-size-small'] = getParam($sName . '_font_size_small');
	        $this->_aConfig['aLessConfig']['bx-font-size-middle'] = getParam($sName . '_font_size_middle');
	        $this->_aConfig['aLessConfig']['bx-font-size-large'] = getParam($sName . '_font_size_large');
	        $this->_aConfig['aLessConfig']['bx-font-size-h1'] = getParam($sName . '_font_size_h1');
	        $this->_aConfig['aLessConfig']['bx-font-size-h2'] = getParam($sName . '_font_size_h2');
	        $this->_aConfig['aLessConfig']['bx-font-size-h3'] = getParam($sName . '_font_size_h3');
		}

		//--- Font Family
		$this->_aConfig['aLessConfig']['bx-font-family-block-title'] = $this->_setValue($sName . '_block_title_font_family');
		$this->_aConfig['aLessConfig']['bx-font-family-button'] = $this->_setValue($sName . '_button_lg_font_family');
		$this->_aConfig['aLessConfig']['bx-font-family-button-small'] = $this->_setValue($sName . '_button_sm_font_family');

		//--- Font Size
		$this->_aConfig['aLessConfig']['bx-font-size-block-title'] = $this->_setSize($sName . '_block_title_font_size', '24px');
		$this->_aConfig['aLessConfig']['bx-font-size-button'] = $this->_setSize($sName . '_button_lg_font_size', '16px');
		$this->_aConfig['aLessConfig']['bx-font-size-button-small'] = $this->_setSize($sName . '_button_sm_font_size', '14px');

		//--- Font Weight
		$this->_aConfig['aLessConfig']['bx-font-weight-button'] = $this->_setValue($sName . '_button_lg_font_weight', '400');
		$this->_aConfig['aLessConfig']['bx-font-weight-button-small'] = $this->_setValue($sName . '_button_sm_font_weight', '400');

		//--- Viewport 
		$this->_aConfig['aLessConfig']['bx-viewport-font-tablet'] = $this->_setValue($sName . '_vpt_font_size_scale', '100%');
		$this->_aConfig['aLessConfig']['bx-viewport-font-mobile'] = $this->_setValue($sName . '_vpm_font_size_scale', '85%');

		if($this->_isModule)
        	$this->setPageWidth('bx_uni_page_width');
    }

    protected function _setValue($sKey, $sDefault = '')
    {
    	$sValue = trim(getParam($sKey));
    	if(!$this->_isModule || empty($sValue))
			$sValue = $sDefault;

    	return $sValue;
    }

	protected function _setSize($sKey, $sDefault = '')
    {
    	if(empty($sDefault))
    		$sDefault = '0px';

    	$sPattern = "/([0-9]+\s*px){1}/";

    	$sValue = trim(getParam($sKey));
    	if(!$this->_isModule || empty($sValue) || !preg_match($sPattern, $sValue))
    		$sValue = $sDefault;

    	return $sValue;
    }

    protected function _setMargin($sKey, $sDefault = '')
    {
    	if(empty($sDefault))
    		$sDefault = '0px';

    	$sPattern = "/([0-9]+\s*px\s*){1,4}/";

    	$sValue = trim(getParam($sKey));
    	if(!$this->_isModule || empty($sValue) || !preg_match($sPattern, $sValue))
    		$sValue = $sDefault;

    	return $sValue;
    }

    protected function _setColor($sKey, $sDefault = '')
    {
    	if(empty($sDefault))
    		$sDefault = 'rgba(51, 51, 51, 1.0)';

    	$sPattern = "/rgba\s*\(\s*([0-9]{1,3}\s*,\s*){3}\s*[0-9\.]+\s*\)/";

    	$sValue = trim(getParam($sKey));
    	if(!$this->_isModule || empty($sValue) || !preg_match($sPattern, $sValue))
    		$sValue = $sDefault;

    	return $sValue;
    }

    protected function _setUrl($sKey, $oStorage = null)
    {
    	if(empty($oStorage))
    		$oStorage = BxDolStorage::getObjectInstance('sys_images_custom');

		$iImageId = (int)getParam($sKey);
		if(empty($iImageId))
			return "";

		$sImageUrl = $oStorage->getFileUrlById($iImageId);
		if(empty($sImageUrl))
			return "";

		return "'" . $sImageUrl . "'";
    }

    protected function _setShadow($sKey, $sDefault = '')
    {
    	if(empty($sDefault))
    		$sDefault = '0px 1px 3px 0px rgba(0, 0, 0, 0.25)';

    	$sPattern = "/(-?[0-9]+\s*px\s*){4}\s*rgba\s*\(\s*([0-9]{1,3}\s*,\s*){3}\s*[0-9\.]+\s*\)/";

    	$sValue = trim(getParam($sKey));
    	if(!$this->_isModule || empty($sValue) || $sValue == 'none')
    		$sValue = 'none';
    	else if(!preg_match($sPattern, $sValue))
    		$sValue = $sDefault;

		return $sValue;
    }

    protected function _setShadowFont($sKey, $sDefault = '')
    {
    	if(empty($sDefault))
    		$sDefault = '0px 1px 3px rgba(0, 0, 0, 0.25)';

    	$sPattern = "/(-?[0-9]+\s*px\s*){3}\s*rgba\s*\(\s*([0-9]{1,3}\s*,\s*){3}\s*[0-9\.]+\s*\)/";

    	$sValue = trim(getParam($sKey));
    	if(!$this->_isModule || empty($sValue) || $sValue == 'none')
    		$sValue = 'none';
    	else if(!preg_match($sPattern, $sValue))
    		$sValue = $sDefault;

		return $sValue;
    }
}

/** @} */
