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

        $sPrefix = 'bx_uni';

        //--- Images
        $oStorage = BxDolStorage::getObjectInstance('sys_images_custom');
        
        $aImageKeys = array(
        	'bx-image-bg-header' => $sPrefix . '_header_bg_image',
        	'bx-image-bg-page' => $sPrefix . '_site_bg_image',
        	'bx-image-bg-footer' => $sPrefix . '_footer_bg_image',
        	'bx-image-bg-block' => $sPrefix . '_block_bg_image'
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
        $this->_aConfig['aLessConfig']['bx-color-header'] = getParam($sPrefix . '_header_bg_color');
        $this->_aConfig['aLessConfig']['bx-color-page'] = getParam($sPrefix . '_site_bg_color');
        $this->_aConfig['aLessConfig']['bx-color-footer'] = getParam($sPrefix . '_footer_bg_color');
        $this->_aConfig['aLessConfig']['bx-color-block'] = getParam($sPrefix . '_block_bg_color');


        //--- Borders
        $this->_aConfig['aLessConfig']['bx-border-color-header'] = getParam($sPrefix . '_header_border_color');
        $this->_aConfig['aLessConfig']['bx-border-color-footer'] = getParam($sPrefix . '_footer_border_color');
        $this->_aConfig['aLessConfig']['bx-border-color-block'] = getParam($sPrefix . '_block_border_color');

        $this->_aConfig['aLessConfig']['bx-border-width-header'] = getParam($sPrefix . '_header_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-footer'] = getParam($sPrefix . '_footer_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-block'] = getParam($sPrefix . '_block_border_size');

        $this->_aConfig['aLessConfig']['bx-border-radius-block'] = getParam($sPrefix . '_block_border_radius');

		//--- Fonts
        $this->_aConfig['aLessConfig']['bx-font-family'] = getParam($sPrefix . '_font_family');
        $this->_aConfig['aLessConfig']['bx-font-size-default'] = getParam($sPrefix . '_font_size_default');
        $this->_aConfig['aLessConfig']['bx-font-size-small'] = getParam($sPrefix . '_font_size_small');
        $this->_aConfig['aLessConfig']['bx-font-size-middle'] = getParam($sPrefix . '_font_size_middle');
        $this->_aConfig['aLessConfig']['bx-font-size-large'] = getParam($sPrefix . '_font_size_large');
        $this->_aConfig['aLessConfig']['bx-font-size-h1'] = getParam($sPrefix . '_font_size_h1');
        $this->_aConfig['aLessConfig']['bx-font-size-h2'] = getParam($sPrefix . '_font_size_h2');
        $this->_aConfig['aLessConfig']['bx-font-size-h3'] = getParam($sPrefix . '_font_size_h3');

        $this->setPageWidth('bx_uni_page_width');
    }
}

/** @} */
