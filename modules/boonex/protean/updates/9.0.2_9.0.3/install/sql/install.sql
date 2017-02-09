SET @sName = 'bx_protean';


-- SETTINGS
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`=@sName LIMIT 1);

UPDATE `sys_options_categories` SET `order`=10 WHERE `name`=CONCAT(@sName, '_system');

UPDATE `sys_options_categories` SET `order`=20 WHERE `name`=CONCAT(@sName, '_styles_general');
UPDATE `sys_options` SET `value`='rgba(210, 230, 250, 0.3)' WHERE `name`=CONCAT(@sName, '_general_item_bg_color_hover');

UPDATE `sys_options_categories` SET `order`=30 WHERE `name`=CONCAT(@sName, '_styles_header');
UPDATE `sys_options` SET `value`='4rem' WHERE `name`=CONCAT(@sName, '_header_height');
UPDATE `sys_options` SET `value`='0.5rem' WHERE `name`=CONCAT(@sName, '_header_content_padding');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_header_bg_color');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.2)' WHERE `name`=CONCAT(@sName, '_header_border_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_header_border_size');
UPDATE `sys_options` SET `value`='0px 1px 3px 0px rgba(0, 0, 0, 0.05)' WHERE `name`=CONCAT(@sName, '_header_shadow');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.8)' WHERE `name`=CONCAT(@sName, '_header_icon_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_header_icon_color_hover');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.8)' WHERE `name`=CONCAT(@sName, '_header_link_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_header_link_color_hover');

UPDATE `sys_options_categories` SET `order`=40 WHERE `name`=CONCAT(@sName, '_styles_footer');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.5)' WHERE `name`=CONCAT(@sName, '_footer_bg_color');
UPDATE `sys_options` SET `value`='1rem' WHERE `name`=CONCAT(@sName, '_footer_content_padding');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 1)' WHERE `name`=CONCAT(@sName, '_footer_border_color');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.9)' WHERE `name`=CONCAT(@sName, '_footer_font_color');
UPDATE `sys_options` SET `value`='rgba(10, 120, 220, 1)' WHERE `name`=CONCAT(@sName, '_footer_icon_color');
UPDATE `sys_options` SET `value`='rgba(10, 120, 220, 1)' WHERE `name`=CONCAT(@sName, '_footer_icon_color_hover');
UPDATE `sys_options` SET `value`='rgba(10, 120, 220, 1)' WHERE `name`=CONCAT(@sName, '_footer_link_color');
UPDATE `sys_options` SET `value`='rgba(10, 120, 220, 1)' WHERE `name`=CONCAT(@sName, '_footer_link_color_hover');

UPDATE `sys_options_categories` SET `order`=50 WHERE `name`=CONCAT(@sName, '_styles_body');
UPDATE `sys_options` SET `value`='rgb(230, 240, 250)' WHERE `name`=CONCAT(@sName, '_body_bg_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.9)' WHERE `name`=CONCAT(@sName, '_body_icon_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_body_icon_color_hover');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.9)' WHERE `name`=CONCAT(@sName, '_body_link_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_body_link_color_hover');

DELETE FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_cover');
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_cover'), '_bx_protean_stg_cpt_category_styles_cover', 55);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_cover_height'), CONCAT(@sName, '_cover_bg_color'), CONCAT(@sName, '_cover_content_padding'), CONCAT(@sName, '_cover_border_color'), CONCAT(@sName, '_cover_border_size'), CONCAT(@sName, '_cover_border_radius'), CONCAT(@sName, '_cover_shadow'), CONCAT(@sName, '_cover_icon_border_color'), CONCAT(@sName, '_cover_icon_border_size'), CONCAT(@sName, '_cover_icon_border_radius'), CONCAT(@sName, '_cover_icon_shadow'), CONCAT(@sName, '_cover_text_align'), CONCAT(@sName, '_cover_text_shadow'), CONCAT(@sName, '_cover_font_family'), CONCAT(@sName, '_cover_font_size'), CONCAT(@sName, '_cover_font_color'), CONCAT(@sName, '_cover_font_weight'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_cover_height'), '_bx_protean_stg_cpt_option_cover_height', '30vh', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_cover_bg_color'), '_bx_protean_stg_cpt_option_cover_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_cover_content_padding'), '_bx_protean_stg_cpt_option_cover_content_padding', '2rem 3rem 2rem 3rem', 'digit', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_cover_border_color'), '_bx_protean_stg_cpt_option_cover_border_color', 'rgba(208, 208, 208, 0)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_cover_border_size'), '_bx_protean_stg_cpt_option_cover_border_size', '0px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_cover_border_radius'), '_bx_protean_stg_cpt_option_cover_border_radius', '0px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_cover_shadow'), '_bx_protean_stg_cpt_option_cover_shadow', 'none', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_cover_icon_border_color'), '_bx_protean_stg_cpt_option_cover_icon_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_cover_icon_border_size'), '_bx_protean_stg_cpt_option_cover_icon_border_size', '1px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_cover_icon_border_radius'), '_bx_protean_stg_cpt_option_cover_icon_border_radius', '3px', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_cover_icon_shadow'), '_bx_protean_stg_cpt_option_cover_icon_shadow', 'none', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_cover_text_align'), '_bx_protean_stg_cpt_option_cover_text_align', 'center', 'select', 'left,center,right', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_cover_text_shadow'), '_bx_protean_stg_cpt_option_cover_text_shadow', '0px 1px 3px rgba(0, 0, 0, .3)', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_cover_font_family'), '_bx_protean_stg_cpt_option_cover_font_family', 'Arial, sans-serif', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_cover_font_size'), '_bx_protean_stg_cpt_option_cover_font_size', '2.0rem', 'digit', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_cover_font_color'), '_bx_protean_stg_cpt_option_cover_font_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_cover_font_weight'), '_bx_protean_stg_cpt_option_cover_font_weight', '700', 'digit', '', '', '', 17);

UPDATE `sys_options_categories` SET `order`=60 WHERE `name`=CONCAT(@sName, '_styles_block');
UPDATE `sys_options` SET `value`='rgba(245, 250, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_block_bg_color');
UPDATE `sys_options` SET `value`='1rem' WHERE `name`=CONCAT(@sName, '_block_content_padding');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0)' WHERE `name`=CONCAT(@sName, '_block_border_color');
UPDATE `sys_options` SET `value`='3px' WHERE `name`=CONCAT(@sName, '_block_border_radius');
UPDATE `sys_options` SET `value`='0px 1px 2px 0px rgba(0, 0, 0, 0.05)' WHERE `name`=CONCAT(@sName, '_block_shadow');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 0)' WHERE `name`=CONCAT(@sName, '_block_title_bg_color');
UPDATE `sys_options` SET `value`='0.3rem 1rem 0rem 1rem' WHERE `name`=CONCAT(@sName, '_block_title_padding');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0)' WHERE `name`=CONCAT(@sName, '_block_title_border_color');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_block_title_font_family');
UPDATE `sys_options` SET `value`='1rem' WHERE `name`=CONCAT(@sName, '_block_title_font_size');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.8)' WHERE `name`=CONCAT(@sName, '_block_title_font_color');
UPDATE `sys_options` SET `value`='700' WHERE `name`=CONCAT(@sName, '_block_title_font_weight');
UPDATE `sys_options` SET `value`='0px' WHERE `name`=CONCAT(@sName, '_block_title_div_height');
UPDATE `sys_options` SET `value`='rgba(40, 60, 80, 0)' WHERE `name`=CONCAT(@sName, '_block_title_div_bg_color');

UPDATE `sys_options_categories` SET `order`=70 WHERE `name`=CONCAT(@sName, '_styles_card');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 1)' WHERE `name`=CONCAT(@sName, '_card_bg_color');
UPDATE `sys_options` SET `value`='1rem' WHERE `name`=CONCAT(@sName, '_card_content_padding');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_card_border_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_card_border_size');
UPDATE `sys_options` SET `value`='0px 1px 3px 0px rgba(0, 0, 0, 0.05)' WHERE `name`=CONCAT(@sName, '_card_shadow');

UPDATE `sys_options_categories` SET `order`=80 WHERE `name`=CONCAT(@sName, '_styles_popup');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.2)' WHERE `name`=CONCAT(@sName, '_popup_border_color');
UPDATE `sys_options` SET `value`='0px 1px 5px 0px rgba(0, 0, 0, 0.05)' WHERE `name`=CONCAT(@sName, '_popup_shadow');
UPDATE `sys_options` SET `value`='rgba(230, 240, 250, 0.9)' WHERE `name`=CONCAT(@sName, '_popup_title_bg_color');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_popup_title_font_family');
UPDATE `sys_options` SET `value`='1rem' WHERE `name`=CONCAT(@sName, '_popup_title_font_size');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.9)' WHERE `name`=CONCAT(@sName, '_popup_title_font_color');

UPDATE `sys_options_categories` SET `order`=90 WHERE `name`=CONCAT(@sName, '_styles_menu_main');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_menu_main_border_color');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_menu_main_font_family');
UPDATE `sys_options` SET `value`='1rem' WHERE `name`=CONCAT(@sName, '_menu_main_font_size');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.8)' WHERE `name`=CONCAT(@sName, '_menu_main_font_color');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.9)' WHERE `name`=CONCAT(@sName, '_menu_main_font_color_hover');
UPDATE `sys_options` SET `value`='rgba(20, 30, 40, 1)' WHERE `name`=CONCAT(@sName, '_menu_main_font_color_active');
UPDATE `sys_options` SET `value`='700' WHERE `name`=CONCAT(@sName, '_menu_main_font_weight');

DELETE FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_menu_account');
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_account'), '_bx_protean_stg_cpt_category_styles_menu_account', 95);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_menu_account_bg_color'), CONCAT(@sName, '_menu_account_bg_image'), CONCAT(@sName, '_menu_account_bg_image_repeat'), CONCAT(@sName, '_menu_account_bg_image_size'), CONCAT(@sName, '_menu_account_content_padding'), CONCAT(@sName, '_menu_account_border_color'), CONCAT(@sName, '_menu_account_border_size'), CONCAT(@sName, '_menu_account_shadow'), CONCAT(@sName, '_menu_account_font_family'), CONCAT(@sName, '_menu_account_font_size'), CONCAT(@sName, '_menu_account_font_color'), CONCAT(@sName, '_menu_account_font_color_hover'), CONCAT(@sName, '_menu_account_font_color_active'), CONCAT(@sName, '_menu_account_font_shadow'), CONCAT(@sName, '_menu_account_font_weight'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_color'), '_bx_protean_stg_cpt_option_menu_account_bg_color', 'rgba(255, 255, 255, 0.9)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_image'), '_bx_protean_stg_cpt_option_menu_account_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_image_repeat'), '_bx_protean_stg_cpt_option_menu_account_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_image_size'), '_bx_protean_stg_cpt_option_menu_account_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_account_content_padding'), '_bx_protean_stg_cpt_option_menu_account_content_padding', '0px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_account_border_color'), '_bx_protean_stg_cpt_option_menu_account_border_color', 'rgba(0, 0, 0, 0.1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_account_border_size'), '_bx_protean_stg_cpt_option_menu_account_border_size', '0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_account_shadow'), '_bx_protean_stg_cpt_option_menu_account_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_family'), '_bx_protean_stg_cpt_option_menu_account_font_family', 'Arial, sans-serif', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_size'), '_bx_protean_stg_cpt_option_menu_account_font_size', '1rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_color'), '_bx_protean_stg_cpt_option_menu_account_font_color', 'rgba(40, 50, 60, 0.8)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_color_hover'), '_bx_protean_stg_cpt_option_menu_account_font_color_hover', 'rgba(40, 50, 60, 0.9)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_color_active'), '_bx_protean_stg_cpt_option_menu_account_font_color_active', 'rgba(20, 30, 40, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_shadow'), '_bx_protean_stg_cpt_option_menu_account_font_shadow', 'none', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_weight'), '_bx_protean_stg_cpt_option_menu_account_font_weight', '400', 'digit', '', '', '', 15);

UPDATE `sys_options_categories` SET `order`=100 WHERE `name`=CONCAT(@sName, '_styles_menu_page');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_menu_page_bg_color');
UPDATE `sys_options` SET `value`='0.3rem' WHERE `name`=CONCAT(@sName, '_menu_page_content_padding');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_menu_page_border_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_menu_page_border_size');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_menu_page_font_family');
UPDATE `sys_options` SET `value`='1rem' WHERE `name`=CONCAT(@sName, '_menu_page_font_size');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.8)' WHERE `name`=CONCAT(@sName, '_menu_page_font_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_menu_page_font_color_hover');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_menu_page_font_color_active');

UPDATE `sys_options_categories` SET `order`=110 WHERE `name`=CONCAT(@sName, '_styles_menu_slide');
UPDATE `sys_options` SET `value`='rgba(0, 0, 0, 0.1)' WHERE `name`=CONCAT(@sName, '_menu_slide_border_color');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_menu_slide_font_family');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.8)' WHERE `name`=CONCAT(@sName, '_menu_slide_font_color');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.9)' WHERE `name`=CONCAT(@sName, '_menu_slide_font_color_hover');
UPDATE `sys_options` SET `value`='rgba(20, 30, 40, 1)' WHERE `name`=CONCAT(@sName, '_menu_slide_font_color_active');

UPDATE `sys_options_categories` SET `order`=120 WHERE `name`=CONCAT(@sName, '_styles_form');
UPDATE `sys_options` SET `value`='2rem' WHERE `name`=CONCAT(@sName, '_form_input_height');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 0.8)' WHERE `name`=CONCAT(@sName, '_form_input_bg_color');
UPDATE `sys_options` SET `value`='rgba(40, 60, 80, 0.5)' WHERE `name`=CONCAT(@sName, '_form_input_border_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_form_input_border_color_active');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_form_input_border_size');
UPDATE `sys_options` SET `value`='inset 0px 0px 2px 1px rgba(0,0,0,0.15)' WHERE `name`=CONCAT(@sName, '_form_input_shadow');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_form_input_font_family');
UPDATE `sys_options` SET `value`='1rem' WHERE `name`=CONCAT(@sName, '_form_input_font_size');
UPDATE `sys_options` SET `value`='rgba(20, 30, 40, 1)' WHERE `name`=CONCAT(@sName, '_form_input_font_color');

UPDATE `sys_options_categories` SET `order`=130 WHERE `name`=CONCAT(@sName, '_styles_large_button');
UPDATE `sys_options` SET `value`='2rem' WHERE `name`=CONCAT(@sName, '_button_lg_height');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.8)' WHERE `name`=CONCAT(@sName, '_button_lg_bg_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color_hover');
UPDATE `sys_options` SET `value`='0px 0px 0px 1px rgba(0,0,0,0.0)' WHERE `name`=CONCAT(@sName, '_button_lg_shadow');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_button_lg_font_family');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_button_lg_font_color');
UPDATE `sys_options` SET `value`='700' WHERE `name`=CONCAT(@sName, '_button_lg_font_weight');

UPDATE `sys_options_categories` SET `order`=140 WHERE `name`=CONCAT(@sName, '_styles_small_button');
UPDATE `sys_options` SET `value`='rgba(40, 180, 140, 0.8)' WHERE `name`=CONCAT(@sName, '_button_sm_bg_color');
UPDATE `sys_options` SET `value`='rgba(40, 180, 140, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_bg_color_hover');
UPDATE `sys_options` SET `value`='rgba(40, 180, 140, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_border_color');
UPDATE `sys_options` SET `value`='rgba(40, 180, 140, 1)' WHERE `name`=CONCAT(@sName, '_button_sm_border_color_hover');
UPDATE `sys_options` SET `value`='2px' WHERE `name`=CONCAT(@sName, '_button_sm_border_radius');
UPDATE `sys_options` SET `value`='0px 0px 0px 1px rgba(0,0,0,0)' WHERE `name`=CONCAT(@sName, '_button_sm_shadow');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_button_sm_font_family');
UPDATE `sys_options` SET `value`='0.75rem' WHERE `name`=CONCAT(@sName, '_button_sm_font_size');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_button_sm_font_color');

UPDATE `sys_options_categories` SET `order`=150 WHERE `name`=CONCAT(@sName, '_styles_font');
UPDATE `sys_options` SET `value`='Arial, sans-serif' WHERE `name`=CONCAT(@sName, '_font_family');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_default');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.5)' WHERE `name`=CONCAT(@sName, '_font_color_grayed');
UPDATE `sys_options` SET `value`='12px' WHERE `name`=CONCAT(@sName, '_font_size_small');
UPDATE `sys_options` SET `value`='15px' WHERE `name`=CONCAT(@sName, '_font_size_middle');
UPDATE `sys_options` SET `value`='18px' WHERE `name`=CONCAT(@sName, '_font_size_large');
UPDATE `sys_options` SET `value`='32px' WHERE `name`=CONCAT(@sName, '_font_size_h1');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 1)' WHERE `name`=CONCAT(@sName, '_font_color_default_h1');
UPDATE `sys_options` SET `value`='rgba(40, 50, 60, 0.5)' WHERE `name`=CONCAT(@sName, '_font_color_grayed_h1');
UPDATE `sys_options` SET `value`='rgba(255, 255, 255, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_contrasted_h1');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_link_h1');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_font_color_link_h1_hover');
UPDATE `sys_options` SET `value`='rgba(40, 60, 80, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_default_h2');
UPDATE `sys_options` SET `value`='rgba(40, 60, 80, 0.5)' WHERE `name`=CONCAT(@sName, '_font_color_grayed_h2');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_link_h2');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_font_color_link_h2_hover');
UPDATE `sys_options` SET `value`='20px' WHERE `name`=CONCAT(@sName, '_font_size_h3');
UPDATE `sys_options` SET `value`='rgba(40, 60, 80, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_default_h3');
UPDATE `sys_options` SET `value`='rgba(40, 60, 80, 0.5)' WHERE `name`=CONCAT(@sName, '_font_color_grayed_h3');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.9)' WHERE `name`=CONCAT(@sName, '_font_color_link_h3');
UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 1)' WHERE `name`=CONCAT(@sName, '_font_color_link_h3_hover');

UPDATE `sys_options_categories` SET `order`=160 WHERE `name`=CONCAT(@sName, '_styles_custom');
UPDATE `sys_options` SET `value`='div.bx-market-unit-cover div.bx-base-text-unit-no-thumb {\r\n border-width: 0px;\r\n}' WHERE `name`=CONCAT(@sName, '_styles_custom');

UPDATE `sys_options_categories` SET `order`=170 WHERE `name`=CONCAT(@sName, '_viewport_tablet');

UPDATE `sys_options_categories` SET `order`=180 WHERE `name`=CONCAT(@sName, '_viewport_mobile');
UPDATE `sys_options` SET `value`='100%' WHERE `name`=CONCAT(@sName, '_vpm_font_size_scale');


-- MIXES
DELETE FROM `sys_options_mixes2options` WHERE `option` IN ('bx_protean_popup_menu_border_size', 'bx_protean_popup_menu_border_color', 'bx_protean_popup_menu_content_padding', 'bx_protean_popup_menu_bg_image', 'bx_protean_popup_menu_bg_color', 'bx_protean_font_color_small', 'bx_protean_font_color_h1', 'bx_protean_font_color_h2', 'bx_protean_font_color_h3', 'bx_protean_menu_page_height');

UPDATE `sys_options_mixes2options` SET `value`='rgba(255, 255, 255, 1)' WHERE `option`='bx_protean_popup_title_font_color';
UPDATE `sys_options_mixes2options` SET `value`='15px' WHERE `option`='bx_protean_font_size_default';

DELETE FROM `sys_options_mixes2options` WHERE `option` IN ('bx_protean_menu_account_bg_color', 'bx_protean_menu_account_bg_image', 'bx_protean_menu_account_bg_image_repeat', 'bx_protean_menu_account_bg_image_size', 'bx_protean_menu_account_content_padding', 'bx_protean_menu_account_border_color', 'bx_protean_menu_account_border_size', 'bx_protean_menu_account_shadow', 'bx_protean_menu_account_font_family', 'bx_protean_menu_account_font_size', 'bx_protean_menu_account_font_color', 'bx_protean_menu_account_font_color_hover', 'bx_protean_menu_account_font_color_active', 'bx_protean_menu_account_font_shadow', 'bx_protean_menu_account_font_weight', 'bx_protean_styles_custom');
INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_menu_account_bg_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_menu_account_bg_image', @iMixId, '0'),
('bx_protean_menu_account_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_account_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_account_content_padding', @iMixId, '0px'),
('bx_protean_menu_account_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_menu_account_border_size', @iMixId, '0px'),
('bx_protean_menu_account_shadow', @iMixId, 'none'),
('bx_protean_menu_account_font_family', @iMixId, '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif'),
('bx_protean_menu_account_font_size', @iMixId, '1.0rem'),
('bx_protean_menu_account_font_color', @iMixId, 'rgba(62, 134, 133, 1)'),
('bx_protean_menu_account_font_color_hover', @iMixId, 'rgba(62, 134, 133, 1)'),
('bx_protean_menu_account_font_color_active', @iMixId, 'rgba(0, 0, 0, 1)'),
('bx_protean_menu_account_font_shadow', @iMixId, 'none'),
('bx_protean_menu_account_font_weight', @iMixId, '400'),

('bx_protean_styles_custom', @iMixId, 'div.bx-market-unit-cover div.bx-base-text-unit-no-thumb {\r\n border-width: 0px;\r\n}');