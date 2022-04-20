<?php defined('BX_DOL') or die('hack attempt');
/**
 * Copyright (c) UNA, Inc - https://una.io
 * MIT License - https://opensource.org/licenses/MIT
 *
 * @defgroup    UnaTemplate UNA Template Classes
 * @{
 */

class BxTemplConfig extends BxBaseConfig
{
    protected $_isModule;
	
    function __construct()
    {
        parent::__construct();

        $sName = 'bx_protean';
        $this->_isModule = BxDolModuleQuery::getInstance()->isModuleByName($sName);

        //--- Images
        $oStorage = BxDolStorage::getObjectInstance('sys_images_custom');

        $this->_aConfig['aLessConfig']['bx-image-bg-header'] = $this->_setBgUrl($sName . '_header_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-header-repeat'] = $this->_setBgRepeat($sName . '_header_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-header-attachment'] = $this->_setBgAttachment($sName . '_header_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-header-size'] = $this->_setBgSize($sName . '_header_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-footer'] = $this->_setBgUrl($sName . '_footer_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-footer-repeat'] = $this->_setBgRepeat($sName . '_footer_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-footer-attachment'] = $this->_setBgAttachment($sName . '_footer_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-footer-size'] = $this->_setBgSize($sName . '_footer_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-page'] = $this->_setBgUrl($sName . '_body_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-page-repeat'] = $this->_setBgRepeat($sName . '_body_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-page-attachment'] = $this->_setBgAttachment($sName . '_body_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-page-size'] = $this->_setBgSize($sName . '_body_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-block'] = $this->_setBgUrl($sName . '_block_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-block-repeat'] = $this->_setBgRepeat($sName . '_block_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-block-attachment'] = $this->_setBgAttachment($sName . '_block_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-block-size'] = $this->_setBgSize($sName . '_block_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-box'] = $this->_setBgUrl($sName . '_card_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-box-repeat'] = $this->_setBgRepeat($sName . '_card_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-box-attachment'] = $this->_setBgAttachment($sName . '_card_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-box-size'] = $this->_setBgSize($sName . '_card_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-popup'] = $this->_setBgUrl($sName . '_popup_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-popup-repeat'] = $this->_setBgRepeat($sName . '_popup_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-popup-attachment'] = $this->_setBgAttachment($sName . '_popup_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-popup-size'] = $this->_setBgSize($sName . '_popup_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-main'] = $this->_setBgUrl($sName . '_menu_main_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-main-repeat'] = $this->_setBgRepeat($sName . '_menu_main_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-main-attachment'] = $this->_setBgAttachment($sName . '_menu_main_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-main-size'] = $this->_setBgSize($sName . '_menu_main_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-account'] = $this->_setBgUrl($sName . '_menu_account_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-account-repeat'] = $this->_setBgRepeat($sName . '_menu_account_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-account-attachment'] = $this->_setBgAttachment($sName . '_menu_account_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-account-size'] = $this->_setBgSize($sName . '_menu_account_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-page'] = $this->_setBgUrl($sName . '_menu_page_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-page-repeat'] = $this->_setBgRepeat($sName . '_menu_page_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-page-attachment'] = $this->_setBgAttachment($sName . '_menu_page_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-page-size'] = $this->_setBgSize($sName . '_menu_page_bg_image_size', 'cover');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-slide'] = $this->_setBgUrl($sName . '_menu_slide_bg_image', $oStorage);
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-slide-repeat'] = $this->_setBgRepeat($sName . '_menu_slide_bg_image_repeat', 'no-repeat');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-slide-attachment'] = $this->_setBgAttachment($sName . '_menu_slide_bg_image_attachment', 'scroll');
        $this->_aConfig['aLessConfig']['bx-image-bg-menu-slide-size'] = $this->_setBgSize($sName . '_menu_slide_bg_image_size', 'cover');


        //--- Shadow
        $this->_aConfig['aLessConfig']['bx-shadow-header'] = $this->_setShadow($sName . '_header_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-footer'] = $this->_setShadow($sName . '_footer_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-cover'] = $this->_setShadow($sName . '_cover_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-cover-icon'] = $this->_setShadow($sName . '_cover_icon_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-block'] = $this->_setShadow($sName . '_block_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-card'] = $this->_setShadow($sName . '_card_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-popup'] = $this->_setShadow($sName . '_popup_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-menu-main'] = $this->_setShadow($sName . '_menu_main_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-menu-account'] = $this->_setShadow($sName . '_menu_account_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-menu-page'] = $this->_setShadow($sName . '_menu_page_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-menu-slide'] = $this->_setShadow($sName . '_menu_slide_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-form-input'] = $this->_setShadow($sName . '_form_input_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-button-large'] = $this->_setShadow($sName . '_button_lg_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-button-large-primary'] = $this->_setShadow($sName . '_button_lgp_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-button'] = $this->_setShadow($sName . '_button_nl_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-button-primary'] = $this->_setShadow($sName . '_button_nlp_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-button-small'] = $this->_setShadow($sName . '_button_sm_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-button-small-primary'] = $this->_setShadow($sName . '_button_smp_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-color-button-click'] = $this->_setShadowCustom(-2, -2, 0, 0, $sName . '_button_nl_font_color_click', 0.08) . ', ' . $this->_setShadowCustom(2, 2, 0, 0, $sName . '_button_nl_font_color_click', 0.08);
        $this->_aConfig['aLessConfig']['bx-shadow-color-button-primary-click'] = $this->_setShadowCustom(-2, -2, 0, 0, $sName . '_button_nlp_font_color_click', 0.08) . ', ' . $this->_setShadowCustom(2, 2, 0, 0, $sName . '_button_nlp_font_color_click', 0.08);

        $this->_aConfig['aLessConfig']['bx-shadow-text-cover'] = $this->_setShadowFont($sName . '_cover_text_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-menu-main'] = $this->_setShadowFont($sName . '_menu_main_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-menu-account'] = $this->_setShadowFont($sName . '_menu_account_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-menu-page'] = $this->_setShadowFont($sName . '_menu_page_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-menu-slide'] = $this->_setShadowFont($sName . '_menu_slide_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-button-large'] = $this->_setShadowFont($sName . '_button_lg_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-button-large-primary'] = $this->_setShadowFont($sName . '_button_lgp_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-button'] = $this->_setShadowFont($sName . '_button_nl_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-button-primary'] = $this->_setShadowFont($sName . '_button_nlp_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-button-small'] = $this->_setShadowFont($sName . '_button_sm_font_shadow');
        $this->_aConfig['aLessConfig']['bx-shadow-font-button-small-primary'] = $this->_setShadowFont($sName . '_button_smp_font_shadow');

        //--- Height
        $this->_aConfig['aLessConfig']['bx-height-header'] = $this->_setSize($sName . '_header_height', '3rem');
        $this->_aConfig['aLessConfig']['bx-height-cover'] = $this->_setSize($sName . '_cover_height', '40vh');
        $this->_aConfig['aLessConfig']['bx-height-block-title-div'] = $this->_setSize($sName . '_block_title_div_height', '1px');
        $this->_aConfig['aLessConfig']['bx-height-form-input'] = $this->_setSize($sName . '_form_input_height', '2.2rem');
        $this->_aConfig['aLessConfig']['bx-height-button-large'] = $this->_setSize($sName . '_button_lg_height', '2.25rem');
        $this->_aConfig['aLessConfig']['bx-height-button-large-primary'] = $this->_setSize($sName . '_button_lgp_height', '2.25rem');
        $this->_aConfig['aLessConfig']['bx-height-button'] = $this->_setSize($sName . '_button_nl_height', '2.25rem');
        $this->_aConfig['aLessConfig']['bx-height-button-primary'] = $this->_setSize($sName . '_button_nlp_height', '2.25rem');
        $this->_aConfig['aLessConfig']['bx-height-button-small'] = $this->_setSize($sName . '_button_sm_height', '1.5rem');
        $this->_aConfig['aLessConfig']['bx-height-button-small-primary'] = $this->_setSize($sName . '_button_smp_height', '1.5rem');


        //--- Title/Content Margins
        $this->_aConfig['aLessConfig']['bx-content-padding-header'] = $this->_setMargin($sName . '_header_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-footer'] = $this->_setMargin($sName . '_footer_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-cover'] = $this->_setMargin($sName . '_cover_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-block'] = $this->_setMargin($sName . '_block_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-card'] = $this->_setMargin($sName . '_card_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-popup'] = $this->_setMargin($sName . '_popup_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-menu-main'] = $this->_setMargin($sName . '_menu_main_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-menu-account'] = $this->_setMargin($sName . '_menu_account_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-menu-page'] = $this->_setMargin($sName . '_menu_page_content_padding');
        $this->_aConfig['aLessConfig']['bx-content-padding-menu-slide'] = $this->_setMargin($sName . '_menu_slide_content_padding');

        $this->_aConfig['aLessConfig']['bx-title-padding-block'] = $this->_setMargin($sName . '_block_title_padding');
        $this->_aConfig['aLessConfig']['bx-title-padding-popup'] = $this->_setMargin($sName . '_popup_title_padding');


        //--- Colors
        if($this->_isModule) {
            $this->_aConfig['aLessConfig']['bx-color-hl'] = $this->_setColorRgba($sName . '_general_item_bg_color_hover', 'rgba(196, 248, 156, 0.2)');
            $this->_aConfig['aLessConfig']['bx-color-active'] = $this->_setColorRgba($sName . '_general_item_bg_color_active', 'rgba(196, 248, 156, 0.4)');
            $this->_aConfig['aLessConfig']['bx-color-disabled'] = $this->_setColorRgba($sName . '_general_item_bg_color_disabled', 'rgba(221, 221, 221, 1.0)');
        }

        $this->_aConfig['aLessConfig']['bx-color-header'] = $this->_setColorRgba($sName . '_header_bg_color', 'rgba(59, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-footer'] = $this->_setColorRgba($sName . '_footer_bg_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-page'] = $this->_setColorRgb($sName . '_body_bg_color', 'rgb(255, 255, 255)');
        $this->_aConfig['aLessConfig']['bx-color-page-gradient-left'] = $this->_setGradientLeft($sName . '_body_bg_color', '242, 242, 242');
        $this->_aConfig['aLessConfig']['bx-color-page-gradient-right'] = $this->_setGradientRight($sName . '_body_bg_color', '242, 242, 242');
        $this->_aConfig['aLessConfig']['bx-color-cover'] = $this->_setColorRgba($sName . '_cover_bg_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-block'] = $this->_setColorRgba($sName . '_block_bg_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-block-transparent'] = $this->_setColorRgbaCustom($sName . '_block_bg_color', '0', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-block-gradient-left'] = $this->_setGradientLeft($sName . '_block_bg_color', '242, 242, 242');
        $this->_aConfig['aLessConfig']['bx-color-block-gradient-right'] = $this->_setGradientRight($sName . '_block_bg_color', '242, 242, 242');
        $this->_aConfig['aLessConfig']['bx-color-block-title'] = $this->_setColorRgba($sName . '_block_title_bg_color', 'rgba(255, 255, 255, 1.0)');
        $this->_aConfig['aLessConfig']['bx-color-block-title-div'] = $this->_setColorRgba($sName . '_block_title_div_bg_color', 'rgba(208, 208, 208, 1)');
        $this->_aConfig['aLessConfig']['bx-color-box'] = $this->_setColorRgba($sName . '_card_bg_color', 'rgba(242, 242, 242, 1)');
        $this->_aConfig['aLessConfig']['bx-color-box-transparent'] = $this->_setColorRgbaCustom($sName . '_card_bg_color', '0', 'rgba(242, 242, 242, 1)');
        $this->_aConfig['aLessConfig']['bx-color-box-gradient-left'] = $this->_setGradientLeft($sName . '_card_bg_color', '242, 242, 242');
        $this->_aConfig['aLessConfig']['bx-color-box-gradient-right'] = $this->_setGradientRight($sName . '_card_bg_color', '242, 242, 242');
        $this->_aConfig['aLessConfig']['bx-color-box-hover'] = $this->_setColorRgba($sName . '_card_bg_color_hover', 'rgba(242, 242, 242, 0.5)');
        $this->_aConfig['aLessConfig']['bx-color-popup'] = $this->_setColorRgba($sName . '_popup_bg_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-popup-title'] = $this->_setColorRgba($sName . '_popup_title_bg_color', 'rgba(40, 60, 80, 0.9)');
        $this->_aConfig['aLessConfig']['bx-color-menu-main'] = $this->_setColorRgba($sName . '_menu_main_bg_color', 'rgba(255, 255, 255, 0.9)');
        $this->_aConfig['aLessConfig']['bx-color-menu-account'] = $this->_setColorRgba($sName . '_menu_account_bg_color', 'rgba(255, 255, 255, 0.9)');
        $this->_aConfig['aLessConfig']['bx-color-menu-page'] = $this->_setColorRgba($sName . '_menu_page_bg_color', 'rgba(242, 242, 242, 1)');
        $this->_aConfig['aLessConfig']['bx-color-menu-slide'] = $this->_setColorRgba($sName . '_menu_slide_bg_color', 'rgba(255, 255, 255, 0.9)');
        $this->_aConfig['aLessConfig']['bx-color-form-input'] = $this->_setColorRgba($sName . '_form_input_bg_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-form-input-active'] = $this->_setColorRgba($sName . '_form_input_bg_color_active', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-large'] = $this->_setColorRgba($sName . '_button_lg_bg_color', 'rgba(108, 170, 138, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-large-hover'] = $this->_setColorRgba($sName . '_button_lg_bg_color_hover', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-large-click'] = $this->_setColorRgba($sName . '_button_lg_bg_color_click', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-large-primary'] = $this->_setColorRgba($sName . '_button_lgp_bg_color', 'rgba(108, 170, 138, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-large-primary-hover'] = $this->_setColorRgba($sName . '_button_lgp_bg_color_hover', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-large-primary-click'] = $this->_setColorRgba($sName . '_button_lgp_bg_color_click', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button'] = $this->_setColorRgba($sName . '_button_nl_bg_color', 'rgba(108, 170, 138, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-hover'] = $this->_setColorRgba($sName . '_button_nl_bg_color_hover', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-click'] = $this->_setColorRgba($sName . '_button_nl_bg_color_click', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-primary'] = $this->_setColorRgba($sName . '_button_nlp_bg_color', 'rgba(108, 170, 138, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-primary-hover'] = $this->_setColorRgba($sName . '_button_nlp_bg_color_hover', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-primary-click'] = $this->_setColorRgba($sName . '_button_nlp_bg_color_click', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-small'] = $this->_setColorRgba($sName . '_button_sm_bg_color', 'rgba(108, 170, 138, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-small-hover'] = $this->_setColorRgba($sName . '_button_sm_bg_color_hover', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-small-click'] = $this->_setColorRgba($sName . '_button_sm_bg_color_click', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-small-primary'] = $this->_setColorRgba($sName . '_button_smp_bg_color', 'rgba(108, 170, 138, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-small-primary-hover'] = $this->_setColorRgba($sName . '_button_smp_bg_color_hover', 'rgba(58, 134, 134, 1)');
        $this->_aConfig['aLessConfig']['bx-color-button-small-primary-click'] = $this->_setColorRgba($sName . '_button_smp_bg_color_click', 'rgba(58, 134, 134, 1)');

        $this->_aConfig['aLessConfig']['bx-color-font-cover'] = $this->_setColorRgba($sName . '_cover_font_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-font-block-title'] = $this->_setColorRgba($sName . '_block_title_font_color', 'rgba(0, 0, 20, 1)');
        $this->_aConfig['aLessConfig']['bx-color-font-popup-title'] = $this->_setColorRgba($sName . '_popup_title_font_color', 'rgba(255, 255, 255, 1.0)');

        $sDefColBorder = 'rgba(208, 208, 208, 1)';
        $this->_aConfig['aLessConfig']['bx-color-border-header'] = $this->_setColorRgba($sName . '_header_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-footer'] = $this->_setColorRgba($sName . '_footer_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-cover'] = $this->_setColorRgba($sName . '_cover_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-cover-icon'] = $this->_setColorRgba($sName . '_cover_icon_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-block'] = $this->_setColorRgba($sName . '_block_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-block-title'] = $this->_setColorRgba($sName . '_block_title_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-box'] = $this->_setColorRgba($sName . '_card_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-popup'] = $this->_setColorRgba($sName . '_popup_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-menu-main'] = $this->_setColorRgba($sName . '_menu_main_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-menu-account'] = $this->_setColorRgba($sName . '_menu_account_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-menu-page'] = $this->_setColorRgba($sName . '_menu_page_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-menu-slide'] = $this->_setColorRgba($sName . '_menu_slide_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-form-input'] = $this->_setColorRgba($sName . '_form_input_border_color', 'rgba(121, 189, 154, 1)');
        $this->_aConfig['aLessConfig']['bx-color-border-form-input-active'] = $this->_setColorRgba($sName . '_form_input_border_color_active', 'rgba(108, 170, 138, 1)');
        $this->_aConfig['aLessConfig']['bx-color-border-button-large'] = $this->_setColorRgba($sName . '_button_lg_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-large-hover'] = $this->_setColorRgba($sName . '_button_lg_border_color_hover', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-large-click'] = $this->_setColorRgba($sName . '_button_lg_border_color_click', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-large-primary'] = $this->_setColorRgba($sName . '_button_lgp_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-large-primary-hover'] = $this->_setColorRgba($sName . '_button_lgp_border_color_hover', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-large-primary-click'] = $this->_setColorRgba($sName . '_button_lgp_border_color_click', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button'] = $this->_setColorRgba($sName . '_button_nl_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-hover'] = $this->_setColorRgba($sName . '_button_nl_border_color_hover', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-click'] = $this->_setColorRgba($sName . '_button_nl_border_color_click', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-primary'] = $this->_setColorRgba($sName . '_button_nlp_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-primary-hover'] = $this->_setColorRgba($sName . '_button_nlp_border_color_hover', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-primary-click'] = $this->_setColorRgba($sName . '_button_nlp_border_color_click', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-small'] = $this->_setColorRgba($sName . '_button_sm_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-small-hover'] = $this->_setColorRgba($sName . '_button_sm_border_color_hover', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-small-click'] = $this->_setColorRgba($sName . '_button_sm_border_color_click', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-small-primary'] = $this->_setColorRgba($sName . '_button_smp_border_color', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-small-primary-hover'] = $this->_setColorRgba($sName . '_button_smp_border_color_hover', $sDefColBorder);
        $this->_aConfig['aLessConfig']['bx-color-border-button-small-primary-click'] = $this->_setColorRgba($sName . '_button_smp_border_color_click', $sDefColBorder);

        $this->_aConfig['aLessConfig']['bx-color-icon-header'] = $this->_setColorRgba($sName . '_header_icon_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-icon-header-hover'] = $this->_setColorRgba($sName . '_header_icon_color_hover', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-icon-footer'] = $this->_setColorRgba($sName . '_footer_icon_color', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-color-icon-footer-hover'] = $this->_setColorRgba($sName . '_footer_icon_color_hover', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-color-icon-body'] = $this->_setColorRgba($sName . '_body_icon_color', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-color-icon-body-hover'] = $this->_setColorRgba($sName . '_body_icon_color_hover', 'rgba(62, 134, 133, 1)');

        $this->_aConfig['aLessConfig']['bx-color-link-header'] = $this->_setColorRgba($sName . '_header_link_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-link-header-hover'] = $this->_setColorRgba($sName . '_header_link_color_hover', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-color-link-footer'] = $this->_setColorRgba($sName . '_footer_link_color', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-color-link-footer-hover'] = $this->_setColorRgba($sName . '_footer_link_color_hover', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-color-link-body'] = $this->_setColorRgba($sName . '_body_link_color', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-color-link-body-hover'] = $this->_setColorRgba($sName . '_body_link_color_hover', 'rgba(62, 134, 133, 1)');

        //--- Borders
        $this->_aConfig['aLessConfig']['bx-border-width-header'] = $this->_setSize($sName . '_header_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-footer'] = $this->_setSize($sName . '_footer_border_size', '1px');
        $this->_aConfig['aLessConfig']['bx-border-width-cover'] = $this->_setSize($sName . '_cover_border_size', '0px');
        $this->_aConfig['aLessConfig']['bx-border-width-cover-icon'] = $this->_setSize($sName . '_cover_icon_border_size', '1px');
        $this->_aConfig['aLessConfig']['bx-border-width-block'] = $this->_setSize($sName . '_block_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-block-title'] = $this->_setSize($sName . '_block_title_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-box'] = $this->_setSize($sName . '_card_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-popup'] = $this->_setSize($sName . '_popup_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-menu-main'] = $this->_setSize($sName . '_menu_main_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-menu-account'] = $this->_setSize($sName . '_menu_account_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-menu-page'] = $this->_setSize($sName . '_menu_page_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-menu-slide'] = $this->_setSize($sName . '_menu_slide_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-form-input'] = $this->_setSize($sName . '_form_input_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-button-large'] = $this->_setSizePx($sName . '_button_lg_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-button-large-primary'] = $this->_setSizePx($sName . '_button_lgp_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-button'] = $this->_setSizePx($sName . '_button_nl_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-button-primary'] = $this->_setSizePx($sName . '_button_nlp_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-button-small'] = $this->_setSizePx($sName . '_button_sm_border_size');
        $this->_aConfig['aLessConfig']['bx-border-width-button-small-primary'] = $this->_setSizePx($sName . '_button_smp_border_size');

        $this->_aConfig['aLessConfig']['bx-border-radius-block'] = $this->_setSize($sName . '_block_border_radius');
        list(
            $this->_aConfig['aLessConfig']['bx-border-radius-block-tl'],
            $this->_aConfig['aLessConfig']['bx-border-radius-block-tr'],
            $this->_aConfig['aLessConfig']['bx-border-radius-block-br'],
            $this->_aConfig['aLessConfig']['bx-border-radius-block-bl']
        ) = $this->_setSizeDivided($this->_aConfig['aLessConfig']['bx-border-radius-block']);
        list(
            $this->_aConfig['aLessConfig']['bx-border-radius-block-itl'],
            $this->_aConfig['aLessConfig']['bx-border-radius-block-itr'],
            $this->_aConfig['aLessConfig']['bx-border-radius-block-ibr'],
            $this->_aConfig['aLessConfig']['bx-border-radius-block-ibl']
        ) = $this->_setInnerSizeDivided($this->_aConfig['aLessConfig']['bx-border-radius-block']);
        $this->_aConfig['aLessConfig']['bx-border-radius-cover'] = $this->_setSize($sName . '_cover_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-cover-icon'] = $this->_setSize($sName . '_cover_icon_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-block-title'] = $this->_setSize($sName . '_block_title_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-box'] = $this->_setSize($sName . '_card_border_radius', '3px');
        list(
            $this->_aConfig['aLessConfig']['bx-border-radius-box-tl'],
            $this->_aConfig['aLessConfig']['bx-border-radius-box-tr'],
            $this->_aConfig['aLessConfig']['bx-border-radius-box-br'],
            $this->_aConfig['aLessConfig']['bx-border-radius-box-bl']
        ) = $this->_setSizeDivided($this->_aConfig['aLessConfig']['bx-border-radius-box']);
        list(
            $this->_aConfig['aLessConfig']['bx-border-radius-box-itl'],
            $this->_aConfig['aLessConfig']['bx-border-radius-box-itr'],
            $this->_aConfig['aLessConfig']['bx-border-radius-box-ibr'],
            $this->_aConfig['aLessConfig']['bx-border-radius-box-ibl']
        ) = $this->_setInnerSizeDivided($this->_aConfig['aLessConfig']['bx-border-radius-box']);
        $this->_aConfig['aLessConfig']['bx-border-radius-popup'] = $this->_setSize($sName . '_popup_border_radius', '3px');
        list(
            $this->_aConfig['aLessConfig']['bx-border-radius-popup-tl'],
            $this->_aConfig['aLessConfig']['bx-border-radius-popup-tr'],
            $this->_aConfig['aLessConfig']['bx-border-radius-popup-br'],
            $this->_aConfig['aLessConfig']['bx-border-radius-popup-bl']
        ) = $this->_setSizeDivided($this->_aConfig['aLessConfig']['bx-border-radius-popup']);
        $this->_aConfig['aLessConfig']['bx-border-radius-button-large'] = $this->_setSize($sName . '_button_lg_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-button-large-primary'] = $this->_setSize($sName . '_button_lgp_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-button'] = $this->_setSize($sName . '_button_nl_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-button-primary'] = $this->_setSize($sName . '_button_nlp_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-button-small'] = $this->_setSize($sName . '_button_sm_border_radius');
        $this->_aConfig['aLessConfig']['bx-border-radius-button-small-primary'] = $this->_setSize($sName . '_button_smp_border_radius');

        //--- Text
        $this->_aConfig['aLessConfig']['bx-text-align-cover'] = $this->_setAlign($sName . '_cover_text_align');

        //--- Default Fonts
        if($this->_isModule) {
            $this->_aConfig['aLessConfig']['bx-font-family'] = $this->_setValue($sName . '_font_family', 'Helvetica, Arial, sans-serif');
            $this->_aConfig['aLessConfig']['bx-font-size-default'] = $this->_setSize($sName . '_font_size_default', '18px');
            $this->_aConfig['aLessConfig']['bx-font-size-small'] = $this->_setSize($sName . '_font_size_small', '14px');
            $this->_aConfig['aLessConfig']['bx-font-size-middle'] = $this->_setSize($sName . '_font_size_middle', '16px');
            $this->_aConfig['aLessConfig']['bx-font-size-large'] = $this->_setSize($sName . '_font_size_large', '22px');
            $this->_aConfig['aLessConfig']['bx-font-size-h1'] = $this->_setSize($sName . '_font_size_h1', '38px');
            $this->_aConfig['aLessConfig']['bx-font-size-h2'] = $this->_setSize($sName . '_font_size_h2', '24px');
            $this->_aConfig['aLessConfig']['bx-font-size-h3'] = $this->_setSize($sName . '_font_size_h3', '18px');

            $this->_aConfig['aLessConfig']['bx-font-color-default'] = $this->_setColorRgba($sName . '_font_color_default', 'rgba(51, 51, 51, 1)');
            $this->_aConfig['aLessConfig']['bx-font-color-grayed'] = $this->_setColorRgba($sName . '_font_color_grayed', 'rgba(153, 153, 153, 1)');
            $this->_aConfig['aLessConfig']['bx-font-color-contrasted'] = $this->_setColorRgba($sName . '_font_color_contrasted', 'rgba(255, 255, 255, 1)');
        }

        //--- Font Family
        $this->_aConfig['aLessConfig']['bx-font-family-cover'] = $this->_setValue($sName . '_cover_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-block-title'] = $this->_setValue($sName . '_block_title_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-popup-title'] = $this->_setValue($sName . '_popup_title_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-form-input'] = $this->_setValue($sName . '_form_input_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-menu-main'] = $this->_setValue($sName . '_menu_main_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-menu-account'] = $this->_setValue($sName . '_menu_account_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-menu-page'] = $this->_setValue($sName . '_menu_page_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-menu-slide'] = $this->_setValue($sName . '_menu_slide_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-button-large'] = $this->_setValue($sName . '_button_lg_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-button-large-primary'] = $this->_setValue($sName . '_button_lgp_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-button'] = $this->_setValue($sName . '_button_nl_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-button-primary'] = $this->_setValue($sName . '_button_nlp_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-button-small'] = $this->_setValue($sName . '_button_sm_font_family');
        $this->_aConfig['aLessConfig']['bx-font-family-button-small-primary'] = $this->_setValue($sName . '_button_smp_font_family');

        //--- Font Size
        $this->_aConfig['aLessConfig']['bx-font-size-cover'] = $this->_setSize($sName . '_cover_font_size', '2.0rem');
        $this->_aConfig['aLessConfig']['bx-font-size-block-title'] = $this->_setSize($sName . '_block_title_font_size', '1.5rem');
        $this->_aConfig['aLessConfig']['bx-font-size-popup-title'] = $this->_setSize($sName . '_popup_title_font_size', '1.5rem');
        $this->_aConfig['aLessConfig']['bx-font-size-form-input'] = $this->_setSize($sName . '_form_input_font_size', '1.125rem');
        $this->_aConfig['aLessConfig']['bx-font-size-menu-main'] = $this->_setSize($sName . '_menu_main_font_size', '1.125rem');
        $this->_aConfig['aLessConfig']['bx-font-size-menu-account'] = $this->_setSize($sName . '_menu_account_font_size', '1.125rem');
        $this->_aConfig['aLessConfig']['bx-font-size-menu-page'] = $this->_setSize($sName . '_menu_page_font_size', '1.2rem');
        $this->_aConfig['aLessConfig']['bx-font-size-menu-slide'] = $this->_setSize($sName . '_menu_slide_font_size', '1.0rem');
        $this->_aConfig['aLessConfig']['bx-font-size-button-large'] = $this->_setSize($sName . '_button_lg_font_size', '1rem');
        $this->_aConfig['aLessConfig']['bx-font-size-button-large-primary'] = $this->_setSize($sName . '_button_lgp_font_size', '1rem');
        $this->_aConfig['aLessConfig']['bx-font-size-button'] = $this->_setSize($sName . '_button_nl_font_size', '1rem');
        $this->_aConfig['aLessConfig']['bx-font-size-button-primary'] = $this->_setSize($sName . '_button_nlp_font_size', '1rem');
        $this->_aConfig['aLessConfig']['bx-font-size-button-small'] = $this->_setSize($sName . '_button_sm_font_size', '0.9rem');
        $this->_aConfig['aLessConfig']['bx-font-size-button-small-primary'] = $this->_setSize($sName . '_button_smp_font_size', '0.9rem');

        //--- Font Color
        $sColFontDef = 'rgba(51, 51, 51, 1.0)';		//--- Default
        $sColFontGrd = 'rgba(153, 153, 153, 1)';	//--- Grayed
        $sColFontCnt = 'rgba(255, 255, 255, 1)';	//--- Contrasted
        $sColFontLnk = 'rgba(62, 134, 133, 1)';		//--- Link
        $sColFontLnkHov = 'rgba(62, 134, 133, 1)';	//--- Link on-hiver
        $this->_aConfig['aLessConfig']['bx-font-color-footer'] = $this->_setColorRgba($sName . '_footer_font_color', $sColFontDef);
        $this->_aConfig['aLessConfig']['bx-font-color-menu-main'] = $this->_setColorRgba($sName . '_menu_main_font_color', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-main-hover'] = $this->_setColorRgba($sName . '_menu_main_font_color_hover', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-main-active'] = $this->_setColorRgba($sName . '_menu_main_font_color_active', 'rgba(0, 0, 0, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-account'] = $this->_setColorRgba($sName . '_menu_account_font_color', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-account-hover'] = $this->_setColorRgba($sName . '_menu_account_font_color_hover', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-account-active'] = $this->_setColorRgba($sName . '_menu_account_font_color_active', 'rgba(0, 0, 0, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-page'] = $this->_setColorRgba($sName . '_menu_page_font_color', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-page-hover'] = $this->_setColorRgba($sName . '_menu_page_font_color_hover', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-page-active'] = $this->_setColorRgba($sName . '_menu_page_font_color_active', 'rgba(0, 0, 0, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-slide'] = $this->_setColorRgba($sName . '_menu_slide_font_color', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-slide-hover'] = $this->_setColorRgba($sName . '_menu_slide_font_color_hover', 'rgba(62, 134, 133, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-menu-slide-active'] = $this->_setColorRgba($sName . '_menu_slide_font_color_active', 'rgba(0, 0, 0, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-large'] = $this->_setColorRgba($sName . '_button_lg_font_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-large-hover'] = $this->_setColorRgba($sName . '_button_lg_font_color_hover', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-large-click'] = $this->_setColorRgba($sName . '_button_lg_font_color_click', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-large-primary'] = $this->_setColorRgba($sName . '_button_lgp_font_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-large-primary-hover'] = $this->_setColorRgba($sName . '_button_lgp_font_color_hover', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-large-primary-click'] = $this->_setColorRgba($sName . '_button_lgp_font_color_click', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button'] = $this->_setColorRgba($sName . '_button_nl_font_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-hover'] = $this->_setColorRgba($sName . '_button_nl_font_color_hover', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-click'] = $this->_setColorRgba($sName . '_button_nl_font_color_click', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-primary'] = $this->_setColorRgba($sName . '_button_nlp_font_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-primary-hover'] = $this->_setColorRgba($sName . '_button_nlp_font_color_hover', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-primary-click'] = $this->_setColorRgba($sName . '_button_nlp_font_color_click', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-small'] = $this->_setColorRgba($sName . '_button_sm_font_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-small-hover'] = $this->_setColorRgba($sName . '_button_sm_font_color_hover', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-small-click'] = $this->_setColorRgba($sName . '_button_sm_font_color_click', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-small-primary'] = $this->_setColorRgba($sName . '_button_smp_font_color', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-small-primary-hover'] = $this->_setColorRgba($sName . '_button_smp_font_color_hover', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-button-small-primary-click'] = $this->_setColorRgba($sName . '_button_smp_font_color_click', 'rgba(255, 255, 255, 1)');
        $this->_aConfig['aLessConfig']['bx-font-color-form-input'] = $this->_setColorRgba($sName . '_form_input_font_color', $sColFontDef);
        $this->_aConfig['aLessConfig']['bx-font-color-default-h1'] = $this->_setColorRgba($sName . '_font_color_default_h1', $sColFontDef);
        $this->_aConfig['aLessConfig']['bx-font-color-grayed-h1'] = $this->_setColorRgba($sName . '_font_color_grayed_h1', $sColFontGrd);
        $this->_aConfig['aLessConfig']['bx-font-color-contrasted-h1'] = $this->_setColorRgba($sName . '_font_color_contrasted_h1', $sColFontCnt);
        $this->_aConfig['aLessConfig']['bx-font-color-link-h1'] = $this->_setColorRgba($sName . '_font_color_link_h1', $sColFontLnk);
        $this->_aConfig['aLessConfig']['bx-font-color-link-h1-hover'] = $this->_setColorRgba($sName . '_font_color_link_h1_hover', $sColFontLnkHov);
        $this->_aConfig['aLessConfig']['bx-font-color-default-h2'] = $this->_setColorRgba($sName . '_font_color_default_h2', $sColFontDef);
        $this->_aConfig['aLessConfig']['bx-font-color-grayed-h2'] = $this->_setColorRgba($sName . '_font_color_grayed_h2', $sColFontGrd);
        $this->_aConfig['aLessConfig']['bx-font-color-contrasted-h2'] = $this->_setColorRgba($sName . '_font_color_contrasted_h2', $sColFontCnt);
        $this->_aConfig['aLessConfig']['bx-font-color-link-h2'] = $this->_setColorRgba($sName . '_font_color_link_h2', $sColFontLnk);
        $this->_aConfig['aLessConfig']['bx-font-color-link-h2-hover'] = $this->_setColorRgba($sName . '_font_color_link_h2_hover', $sColFontLnkHov);
        $this->_aConfig['aLessConfig']['bx-font-color-default-h3'] = $this->_setColorRgba($sName . '_font_color_default_h3', $sColFontDef);
        $this->_aConfig['aLessConfig']['bx-font-color-grayed-h3'] = $this->_setColorRgba($sName . '_font_color_grayed_h3', $sColFontGrd);
        $this->_aConfig['aLessConfig']['bx-font-color-contrasted-h3'] = $this->_setColorRgba($sName . '_font_color_contrasted_h3', $sColFontCnt);
        $this->_aConfig['aLessConfig']['bx-font-color-link-h3'] = $this->_setColorRgba($sName . '_font_color_link_h3', $sColFontLnk);
        $this->_aConfig['aLessConfig']['bx-font-color-link-h3-hover'] = $this->_setColorRgba($sName . '_font_color_link_h3_hover', $sColFontLnkHov);

        //--- Font Weight
        $this->_aConfig['aLessConfig']['bx-font-weight-cover'] = $this->_setValue($sName . '_cover_font_weight', '700');
        $this->_aConfig['aLessConfig']['bx-font-weight-block-title'] = $this->_setValue($sName . '_block_title_font_weight', '500');
        $this->_aConfig['aLessConfig']['bx-font-weight-menu-main'] = $this->_setValue($sName . '_menu_main_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-menu-account'] = $this->_setValue($sName . '_menu_account_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-menu-page'] = $this->_setValue($sName . '_menu_page_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-menu-slide'] = $this->_setValue($sName . '_menu_slide_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-button-large'] = $this->_setValue($sName . '_button_lg_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-button-large-primary'] = $this->_setValue($sName . '_button_lgp_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-button'] = $this->_setValue($sName . '_button_nl_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-button-primary'] = $this->_setValue($sName . '_button_nlp_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-button-small'] = $this->_setValue($sName . '_button_sm_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-button-small-primary'] = $this->_setValue($sName . '_button_smp_font_weight', '400');
        $this->_aConfig['aLessConfig']['bx-font-weight-h1'] = $this->_setValue($sName . '_font_weight_h1', '700');
        $this->_aConfig['aLessConfig']['bx-font-weight-h2'] = $this->_setValue($sName . '_font_weight_h2', '700');
        $this->_aConfig['aLessConfig']['bx-font-weight-h3'] = $this->_setValue($sName . '_font_weight_h3', '500');

        //--- Viewport 
        $this->_aConfig['aLessConfig']['bx-viewport-font-tablet'] = $this->_setValue($sName . '_vpt_font_size_scale', '100%');
        $this->_aConfig['aLessConfig']['bx-viewport-font-mobile'] = $this->_setValue($sName . '_vpm_font_size_scale', '85%');

        if($this->_isModule) {
            $this->setPageWidth('bx_protean_page_width');
        }
    }
}

/** @} */
