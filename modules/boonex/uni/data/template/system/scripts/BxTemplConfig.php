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
    function __construct()
    {
        parent::__construct();

        $sName = 'bx_uni';
        $bAvailable = BxDolModuleQuery::getInstance()->isModuleByName($sName);

        //--- Images
        $oStorage = BxDolStorage::getObjectInstance('sys_images_custom');

        $aImageKeys = array(
        	'bx-image-bg-header' => $sName . '_header_bg_image',
        	'bx-image-bg-page' => $sName . '_site_bg_image',
        	'bx-image-bg-footer' => $sName . '_footer_bg_image',
        	'bx-image-bg-block' => $sName . '_block_bg_image'
        );
        foreach($aImageKeys as $sKey => $sParam) {
        	$this->_aConfig['aLessConfig'][$sKey] = ""; 

			$iImageId = (int)getParam($sParam);
        	if(empty($iImageId))
        		continue;

        	$sImageUrl = $oStorage->getFileUrlById($iImageId);
        	if(empty($sImageUrl))
        		continue;

        	$this->_aConfig['aLessConfig'][$sKey] = "'" . $sImageUrl . "'";
        }

        //--- Colors
        $this->_aConfig['aLessConfig']['bx-color-header'] = $bAvailable ? getParam($sName . '_header_bg_color') : 'rgba(59, 134, 134, 1)';
        $this->_aConfig['aLessConfig']['bx-color-page'] = $bAvailable ? getParam($sName . '_site_bg_color') : 'rgb(255, 255, 255)';
        $this->_aConfig['aLessConfig']['bx-color-footer'] = $bAvailable ? getParam($sName . '_footer_bg_color') : 'rgb(255, 255, 255, 1)';
        $this->_aConfig['aLessConfig']['bx-color-block'] = $bAvailable ? getParam($sName . '_block_bg_color') : 'rgb(255, 255, 255, 1)';

        //--- Borders
        $this->_aConfig['aLessConfig']['bx-border-color-header'] = $bAvailable ? getParam($sName . '_header_border_color') : 'rgb(208, 208, 208, 1)';
        $this->_aConfig['aLessConfig']['bx-border-color-footer'] = $bAvailable ? getParam($sName . '_footer_border_color') : 'rgb(208, 208, 208, 1)';
        $this->_aConfig['aLessConfig']['bx-border-color-block'] = $bAvailable ? getParam($sName . '_block_border_color') : 'rgba(208, 208, 208, 1)';

        $this->_aConfig['aLessConfig']['bx-border-width-header'] = $bAvailable ? getParam($sName . '_header_border_size') : '0px';
        $this->_aConfig['aLessConfig']['bx-border-width-footer'] = $bAvailable ? getParam($sName . '_footer_border_size') : '1px';
        $this->_aConfig['aLessConfig']['bx-border-width-block'] = $bAvailable ? getParam($sName . '_block_border_size') : '0px';

        $this->_aConfig['aLessConfig']['bx-border-radius-block'] = $bAvailable ? getParam($sName . '_block_border_radius') : '0px';

		//--- Fonts
		if($bAvailable) {
	        $this->_aConfig['aLessConfig']['bx-font-family'] = getParam($sName . '_font_family');
	        $this->_aConfig['aLessConfig']['bx-font-size-default'] = getParam($sName . '_font_size_default');
	        $this->_aConfig['aLessConfig']['bx-font-size-small'] = getParam($sName . '_font_size_small');
	        $this->_aConfig['aLessConfig']['bx-font-size-middle'] = getParam($sName . '_font_size_middle');
	        $this->_aConfig['aLessConfig']['bx-font-size-large'] = getParam($sName . '_font_size_large');
	        $this->_aConfig['aLessConfig']['bx-font-size-h1'] = getParam($sName . '_font_size_h1');
	        $this->_aConfig['aLessConfig']['bx-font-size-h2'] = getParam($sName . '_font_size_h2');
	        $this->_aConfig['aLessConfig']['bx-font-size-h3'] = getParam($sName . '_font_size_h3');
		}

		if($bAvailable)
        	$this->setPageWidth('bx_uni_page_width');
    }
}

/** @} */
