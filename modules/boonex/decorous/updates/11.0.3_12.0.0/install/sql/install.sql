SET @sName = 'bx_decorous';


-- SETTINGS
UPDATE `sys_options` SET `value`='rgba(245, 245, 245, 1)' WHERE `name`=CONCAT(@sName, '_general_item_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(230, 247, 255, 1)' WHERE `name`=CONCAT(@sName, '_general_item_bg_color_active');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_general_item_bg_color_disabled');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 0.8)' WHERE `name`=CONCAT(@sName, '_header_bg_color');
UPDATE `sys_options` SET `value`='64' WHERE `name`=CONCAT(@sName, '_site_logo_height');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_header_border_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_header_border_size');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_header_shadow');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_header_icon_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_header_icon_color_hover');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_header_link_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_header_link_color_hover');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_footer_bg_color');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_footer_border_color');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_footer_shadow');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_footer_font_color');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_footer_icon_color');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_footer_icon_color_hover');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_footer_link_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_footer_link_color_hover');

UPDATE `sys_options` SET `value`='rgb(240, 242, 245)' WHERE `name`=CONCAT(@sName, '_body_bg_color');
UPDATE `sys_options` SET `value`='100%' WHERE `name`=CONCAT(@sName, '_page_width');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_body_icon_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_body_icon_color_hover');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_body_link_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_body_link_color_hover');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_cover_bg_color');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_cover_border_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_cover_border_size');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_cover_shadow');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_cover_font_family');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_block_bg_color');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_block_border_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_block_border_size');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_block_border_radius');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_block_shadow');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_block_title_bg_color');
UPDATE `sys_options` SET `value`='0.5rem 1rem' WHERE `name`=CONCAT(@sName, '_block_title_padding');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_block_title_border_color');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_block_title_border_size');
UPDATE `sys_options` SET `value`='4px 4px 0px 0px' WHERE `name`=CONCAT(@sName, '_block_title_border_radius');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_block_title_font_family');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.85);' WHERE `name`=CONCAT(@sName, '_block_title_font_color');
UPDATE `sys_options` SET `value`='500' WHERE `name`=CONCAT(@sName, '_block_title_font_weight');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_block_title_div_height');
UPDATE `sys_options` SET `value`='rgba(240, 240, 240, 1)' WHERE `name`=CONCAT(@sName, '');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_block_title_div_bg_color');

UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_card_border_radius');
UPDATE `sys_options` SET `value`='0px 2px 4px 0px rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_bx_decorous_stg_cpt_option_card_shadow');

UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_popup_title_font_family');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1.0)' WHERE `name`=CONCAT(@sName, '_menu_main_bg_color');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_menu_main_content_padding');
UPDATE `sys_options` SET `value`='2px 0px 8px 0px rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_menu_main_shadow');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_menu_main_font_family');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_menu_main_font_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_menu_main_font_color_hover');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_menu_main_font_color_active');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_menu_account_bg_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_menu_account_border_size');
UPDATE `sys_options` SET `value`='0px 8px 16px 0px rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_menu_account_shadow');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_menu_account_font_family');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_menu_account_font_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_menu_account_font_color_hover');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_menu_account_font_color_active');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_menu_add_bg_color');
UPDATE `sys_options` SET `value`='0.5rem 1.25rem' WHERE `name`=CONCAT(@sName, '_menu_add_content_padding');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_menu_add_border_size');
UPDATE `sys_options` SET `value`='0px 8px 16px 0px rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_menu_add_shadow');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_menu_add_font_family');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_menu_add_font_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_menu_add_font_color_hover');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_menu_add_font_color_active');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_menu_page_bg_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_menu_page_border_size');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_menu_page_shadow');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_menu_page_font_family');
UPDATE `sys_options` SET `value`='rgba(89, 89, 89, 1)' WHERE `name`=CONCAT(@sName, '_menu_page_font_color');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_menu_page_font_color_hover');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_menu_page_font_color_active');

UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_menu_slide_font_family');

UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_form_input_bg_color');
UPDATE `sys_options` SET `value`='rgba(217, 217, 217, 1)' WHERE `name`=CONCAT(@sName, '_form_input_border_color');
UPDATE `sys_options` SET `value`='rgba(64, 169, 255, 1)' WHERE `name`=CONCAT(@sName, '_form_input_border_color_active');
UPDATE `sys_options` SET `value`='' WHERE `name`=CONCAT(@sName, '_form_input_shadow');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_form_input_font_family');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.65)' WHERE `name`=CONCAT(@sName, '_form_input_font_color');

UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_bg_color');
UPDATE `sys_options` SET `value`='rgba(64, 169, 255, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color');
UPDATE `sys_options` SET `value`='rgba(64, 169, 255, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color_hover');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_button_lg_border_size');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_lg_border_radius');
UPDATE `sys_options` SET `value`='px 2px 0px 0px rgba(0, 0, 0, 0.016)' WHERE `name`=CONCAT(@sName, '_button_lg_shadow');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_button_lg_font_family');
UPDATE `sys_options` SET `value`='0.875rem' WHERE `name`=CONCAT(@sName, '_button_lg_font_size');
UPDATE `sys_options` SET `value`='0px -1px 0px; rgba(0, 0, 0, 0.12)' WHERE `name`=CONCAT(@sName, '_button_lg_font_shadow');
UPDATE `sys_options` SET `value`='400' WHERE `name`=CONCAT(@sName, '_button_lg_font_weight');

UPDATE `sys_options` SET `value`='2rem' WHERE `name`=CONCAT(@sName, '_button_sm_height');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_bg_color');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(217, 217, 217, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_border_color');
UPDATE `sys_options` SET `value`='rgba(64, 169, 255, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_border_color_hover');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_button_sm_border_size');
UPDATE `sys_options` SET `value`='4px' WHERE `name`=CONCAT(@sName, '_button_sm_border_radius');
UPDATE `sys_options` SET `value`='0px 2px 0px 0px rgba(0, 0, 0, 0.016)' WHERE `name`=CONCAT(@sName, '_button_sm_shadow');
UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_button_sm_font_family');
UPDATE `sys_options` SET `value`='0.875rem' WHERE `name`=CONCAT(@sName, '_button_sm_font_size');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.65)' WHERE `name`=CONCAT(@sName, '_button_sm_font_color');
UPDATE `sys_options` SET `value`='rgba(64, 169, 255, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_font_color_hover');

UPDATE `sys_options` SET `value`='-apple-system,BlinkMacSystemFont,segoe ui,Roboto,helvetica neue,Arial,noto sans,sans-serif' WHERE `name`=CONCAT(@sName, '_font_family');
UPDATE `sys_options` SET `value`='16px' WHERE `name`=CONCAT(@sName, '_font_size_middle');
UPDATE `sys_options` SET `value`='28px' WHERE `name`=CONCAT(@sName, '_font_size_h1');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_link_h1');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_font_color_link_h1_hover');
UPDATE `sys_options` SET `value`='24px' WHERE `name`=CONCAT(@sName, '_font_size_h2');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_link_h2');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_font_color_link_h2_hover');
UPDATE `sys_options` SET `value`='20px' WHERE `name`=CONCAT(@sName, '_font_size_h3');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_link_h3');
UPDATE `sys_options` SET `value`='rgba(24, 144, 255, 1)' WHERE `name`=CONCAT(@sName, '_font_color_link_h3_hover');


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`=@sName LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='appearance' WHERE `page_id`=@iPageId;
