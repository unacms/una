SET @sName = 'bx_uni';


-- SETTINGS
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('templates', @sName, '_bx_uni_stg_cpt_type', 'bx_uni@modules/boonex/uni/|std-mi.png', 2);
SET @iTypeId = LAST_INSERT_ID();

-- SETTINGS: UNI System
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_system'), '_bx_uni_stg_cpt_category_system', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_switcher_title'), '_bx_uni_stg_cpt_option_switcher_name', 'UNI', 'digit', '', '', '', 1);


-- SETTINGS: UNI Styles General
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_general'), '_bx_uni_stg_cpt_category_styles_general', 2);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_hover'), '_bx_uni_stg_cpt_option_general_item_bg_color_hover', 'rgba(196, 248, 156, 0.2)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_active'), '_bx_uni_stg_cpt_option_general_item_bg_color_active', 'rgba(196, 248, 156, 0.4)', 'rgba', '', '', '', 2);


-- SETTINGS: UNI Styles Header
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_header'), '_bx_uni_stg_cpt_category_styles_header', 3);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_header_height'), '_bx_uni_stg_cpt_option_header_height', '3rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_header_content_padding'), '_bx_uni_stg_cpt_option_header_content_padding', '0px', 'digit', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_header_bg_color'), '_bx_uni_stg_cpt_option_header_bg_color', 'rgba(59, 134, 134, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_header_bg_image'), '_bx_uni_stg_cpt_option_header_bg_image', '', 'image', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_header_bg_image_repeat'), '_bx_uni_stg_cpt_option_header_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_header_bg_image_size'), '_bx_uni_stg_cpt_option_header_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_site_logo'), '_bx_uni_stg_cpt_option_site_logo', '', 'image', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_site_logo_alt'), '_bx_uni_stg_cpt_option_site_logo_alt', '', 'text', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_site_logo_width'), '_bx_uni_stg_cpt_option_site_logo_width', '240', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_site_logo_height'), '_bx_uni_stg_cpt_option_site_logo_height', '48', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_header_border_color'), '_bx_uni_stg_cpt_option_header_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_header_border_size'), '_bx_uni_stg_cpt_option_header_border_size', '0px', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_header_shadow'), '_bx_uni_stg_cpt_option_header_shadow', 'none', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_header_icon_color'), '_bx_uni_stg_cpt_option_header_icon_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_header_icon_color_hover'), '_bx_uni_stg_cpt_option_header_icon_color_hover', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_header_link_color'), '_bx_uni_stg_cpt_option_header_link_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_header_link_color_hover'), '_bx_uni_stg_cpt_option_header_link_color_hover', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 17);

-- SETTINGS: UNI Styles Footer
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_footer'), '_bx_uni_stg_cpt_category_styles_footer', 4);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_footer_bg_color'), '_bx_uni_stg_cpt_option_footer_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image'), '_bx_uni_stg_cpt_option_footer_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image_repeat'), '_bx_uni_stg_cpt_option_footer_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image_size'), '_bx_uni_stg_cpt_option_footer_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_footer_content_padding'), '_bx_uni_stg_cpt_option_footer_content_padding', '0px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_footer_border_color'), '_bx_uni_stg_cpt_option_footer_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_footer_border_size'), '_bx_uni_stg_cpt_option_footer_border_size', '1px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_footer_shadow'), '_bx_uni_stg_cpt_option_footer_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_footer_font_color'), '_bx_uni_stg_cpt_option_footer_font_color', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_footer_icon_color'), '_bx_uni_stg_cpt_option_footer_icon_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_footer_icon_color_hover'), '_bx_uni_stg_cpt_option_footer_icon_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_footer_link_color'), '_bx_uni_stg_cpt_option_footer_link_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_footer_link_color_hover'), '_bx_uni_stg_cpt_option_footer_link_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 13);

-- SETTINGS: UNI Styles Body
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_body'), '_bx_uni_stg_cpt_category_styles_body', 5);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_body_bg_color'), '_bx_uni_stg_cpt_option_body_bg_color', 'rgb(255, 255, 255)', 'rgb', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_body_bg_image'), '_bx_uni_stg_cpt_option_body_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_body_bg_image_repeat'), '_bx_uni_stg_cpt_option_body_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_body_bg_image_size'), '_bx_uni_stg_cpt_option_body_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_page_width'), '_bx_uni_stg_cpt_option_page_width', '1000', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_body_icon_color'), '_bx_uni_stg_cpt_option_body_icon_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_body_icon_color_hover'), '_bx_uni_stg_cpt_option_body_icon_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_body_link_color'), '_bx_uni_stg_cpt_option_body_link_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_body_link_color_hover'), '_bx_uni_stg_cpt_option_body_link_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 9);

-- SETTINGS: UNI Styles Block
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_block'), '_bx_uni_stg_cpt_category_styles_block', 6);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_block_bg_color'), '_bx_uni_stg_cpt_option_block_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_block_bg_image'), '_bx_uni_stg_cpt_option_block_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_block_bg_image_repeat'), '_bx_uni_stg_cpt_option_block_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_block_bg_image_size'), '_bx_uni_stg_cpt_option_block_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_block_content_padding'), '_bx_uni_stg_cpt_option_block_content_padding', '1rem', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_block_border_color'), '_bx_uni_stg_cpt_option_block_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_block_border_size'), '_bx_uni_stg_cpt_option_block_border_size', '0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_block_border_radius'), '_bx_uni_stg_cpt_option_block_border_radius', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_block_shadow'), '_bx_uni_stg_cpt_option_block_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_block_title_padding'), '_bx_uni_stg_cpt_option_block_title_padding', '0px', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_block_title_font_family'), '_bx_uni_stg_cpt_option_block_title_font_family', 'Helvetica, Arial, sans-serif', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_block_title_font_size'), '_bx_uni_stg_cpt_option_block_title_font_size', '1.5rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_block_title_font_color'), '_bx_uni_stg_cpt_option_block_title_font_color', 'rgba(0, 0, 20, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_block_title_div_height'), '_bx_uni_stg_cpt_option_block_title_div_height', '1px', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_block_title_div_bg_color'), '_bx_uni_stg_cpt_option_block_title_div_bg_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 15);


-- SETTINGS: UNI Styles Card
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_card'), '_bx_uni_stg_cpt_category_styles_card', 7);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_card_bg_color'), '_bx_uni_stg_cpt_option_card_bg_color', 'rgba(242, 242, 242, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_card_bg_image'), '_bx_uni_stg_cpt_option_card_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_card_bg_image_repeat'), '_bx_uni_stg_cpt_option_card_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_card_bg_image_size'), '_bx_uni_stg_cpt_option_card_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_card_content_padding'), '_bx_uni_stg_cpt_option_card_content_padding', '20px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_card_border_color'), '_bx_uni_stg_cpt_option_card_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_card_border_size'), '_bx_uni_stg_cpt_option_card_border_size', '0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_card_border_radius'), '_bx_uni_stg_cpt_option_card_border_radius', '3px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_card_shadow'), '_bx_uni_stg_cpt_option_card_shadow', 'none', 'digit', '', '', '', 9);


-- SETTINGS: UNI Styles Popups
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_popup'), '_bx_uni_stg_cpt_category_styles_popup', 8);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_popup_bg_color'), '_bx_uni_stg_cpt_option_popup_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image'), '_bx_uni_stg_cpt_option_popup_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image_repeat'), '_bx_uni_stg_cpt_option_popup_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image_size'), '_bx_uni_stg_cpt_option_popup_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_popup_content_padding'), '_bx_uni_stg_cpt_option_popup_content_padding', '1.25rem', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_popup_border_color'), '_bx_uni_stg_cpt_option_popup_border_color', 'rgba(56, 61, 102, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_popup_border_size'), '_bx_uni_stg_cpt_option_popup_border_size', '1px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_popup_border_radius'), '_bx_uni_stg_cpt_option_popup_border_radius', '3px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_popup_shadow'), '_bx_uni_stg_cpt_option_popup_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_popup_title_padding'), '_bx_uni_stg_cpt_option_popup_title_padding', '1.25rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_family'), '_bx_uni_stg_cpt_option_popup_title_font_family', 'Helvetica, Arial, sans-serif', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_size'), '_bx_uni_stg_cpt_option_popup_title_font_size', '1.5rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_color'), '_bx_uni_stg_cpt_option_popup_title_font_color', 'rgba(0, 0, 20, 1)', 'rgba', '', '', '', 13);


-- SETTINGS: UNI Styles Slide Menus
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_slide'), '_bx_uni_stg_cpt_category_styles_menu_slide', 9);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_color'), '_bx_uni_stg_cpt_option_menu_slide_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image'), '_bx_uni_stg_cpt_option_menu_slide_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image_repeat'), '_bx_uni_stg_cpt_option_menu_slide_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image_size'), '_bx_uni_stg_cpt_option_menu_slide_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_slide_content_padding'), '_bx_uni_stg_cpt_option_menu_slide_content_padding', '1.25rem', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_slide_border_color'), '_bx_uni_stg_cpt_option_menu_slide_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_slide_border_size'), '_bx_uni_stg_cpt_option_menu_slide_border_size', '0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_slide_shadow'), '_bx_uni_stg_cpt_option_menu_slide_shadow', 'none', 'digit', '', '', '', 8);


-- SETTINGS: UNI Styles Page Menu
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_page'), '_bx_uni_stg_cpt_category_styles_menu_page', 10);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_page_height'), '_bx_uni_stg_cpt_option_menu_page_height', '2.5rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_color'), '_bx_uni_stg_cpt_option_menu_page_bg_color', 'rgba(242, 242, 242, 1)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image'), '_bx_uni_stg_cpt_option_menu_page_bg_image', '', 'image', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image_repeat'), '_bx_uni_stg_cpt_option_menu_page_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image_size'), '_bx_uni_stg_cpt_option_menu_page_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_page_content_padding'), '_bx_uni_stg_cpt_option_menu_page_content_padding', '0px', 'digit', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_page_border_color'), '_bx_uni_stg_cpt_option_menu_page_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_page_border_size'), '_bx_uni_stg_cpt_option_menu_page_border_size', '0px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_page_shadow'), '_bx_uni_stg_cpt_option_menu_page_shadow', 'none', 'digit', '', '', '', 7);


-- SETTINGS: UNI Styles Forms
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_form'), '_bx_uni_stg_cpt_category_styles_form', 11);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_form_input_height'), '_bx_uni_stg_cpt_option_form_input_height', '2.2rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_form_input_bg_color'), '_bx_uni_stg_cpt_option_form_input_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_form_input_bg_color_active'), '_bx_uni_stg_cpt_option_form_input_bg_color_active', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_form_input_border_color'), '_bx_uni_stg_cpt_option_form_input_border_color', 'rgba(121, 189, 154, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_form_input_border_color_active'), '_bx_uni_stg_cpt_option_form_input_border_color_active', 'rgba(108, 170, 138, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_form_input_border_size'), '_bx_uni_stg_cpt_option_form_input_border_size', '2px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_form_input_shadow'), '_bx_uni_stg_cpt_option_form_input_shadow', 'none', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_form_input_font_family'), '_bx_uni_stg_cpt_option_form_input_font_family', 'Helvetica, Arial, sans-serif', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_form_input_font_size'), '_bx_uni_stg_cpt_option_form_input_font_size', '1.125rem', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_form_input_font_color'), '_bx_uni_stg_cpt_option_form_input_font_color', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 10);


-- SETTINGS: UNI Styles Large Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_large_button'), '_bx_uni_stg_cpt_category_styles_large_button', 12);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_lg_height'), '_bx_uni_stg_cpt_option_button_lg_height', '2.25rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color'), '_bx_uni_stg_cpt_option_button_lg_bg_color', 'rgba(108, 170, 138, 1)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color_hover'), '_bx_uni_stg_cpt_option_button_lg_bg_color_hover', 'rgba(58, 134, 134, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color'), '_bx_uni_stg_cpt_option_button_lg_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color_hover'), '_bx_uni_stg_cpt_option_button_lg_border_color_hover', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_size'), '_bx_uni_stg_cpt_option_button_lg_border_size', '0px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_radius'), '_bx_uni_stg_cpt_option_button_lg_border_radius', '3px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_lg_shadow'), '_bx_uni_stg_cpt_option_button_lg_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_family'), '_bx_uni_stg_cpt_option_button_lg_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_size'), '_bx_uni_stg_cpt_option_button_lg_font_size', '1rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color'), '_bx_uni_stg_cpt_option_button_lg_font_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color_hover'), '_bx_uni_stg_cpt_option_button_lg_font_color_hover', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_shadow'), '_bx_uni_stg_cpt_option_button_lg_font_shadow', 'none', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_weight'), '_bx_uni_stg_cpt_option_button_lg_font_weight', '400', 'digit', '', '', '', 14);

-- SETTINGS: UNI Styles Small Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_small_button'), '_bx_uni_stg_cpt_category_styles_small_button', 13);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_sm_height'), '_bx_uni_stg_cpt_option_button_sm_height', '1.5rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color'), '_bx_uni_stg_cpt_option_button_sm_bg_color', 'rgba(108, 170, 138, 1)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color_hover'), '_bx_uni_stg_cpt_option_button_sm_bg_color_hover', 'rgba(58, 134, 134, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color'), '_bx_uni_stg_cpt_option_button_sm_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color_hover'), '_bx_uni_stg_cpt_option_button_sm_border_color_hover', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_size'), '_bx_uni_stg_cpt_option_button_sm_border_size', '0px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_radius'), '_bx_uni_stg_cpt_option_button_sm_border_radius', '3px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_sm_shadow'), '_bx_uni_stg_cpt_option_button_sm_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_family'), '_bx_uni_stg_cpt_option_button_sm_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_size'), '_bx_uni_stg_cpt_option_button_sm_font_size', '0.9rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color'), '_bx_uni_stg_cpt_option_button_sm_font_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color_hover'), '_bx_uni_stg_cpt_option_button_sm_font_color_hover', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_shadow'), '_bx_uni_stg_cpt_option_button_sm_font_shadow', 'none', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_weight'), '_bx_uni_stg_cpt_option_button_sm_font_weight', '400', 'digit', '', '', '', 14);

-- SETTINGS: UNI Styles Font
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_font'), '_bx_uni_stg_cpt_category_styles_font', 14);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_font_family'), '_bx_uni_stg_cpt_option_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_font_size_default'), '_bx_uni_stg_cpt_option_size_default', '18px', 'digit', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_font_color_default'), '_bx_uni_stg_cpt_option_color_default', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_font_size_small'), '_bx_uni_stg_cpt_option_size_small', '14px', 'digit', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_font_color_small'), '_bx_uni_stg_cpt_option_color_small', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_font_size_middle'), '_bx_uni_stg_cpt_option_size_middle', '16px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_font_color_middle'), '_bx_uni_stg_cpt_option_color_middle', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_font_size_large'), '_bx_uni_stg_cpt_option_size_large', '22px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_font_color_large'), '_bx_uni_stg_cpt_option_color_large', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_font_size_h1'), '_bx_uni_stg_cpt_option_size_h1', '38px', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_font_color_h1'), '_bx_uni_stg_cpt_option_color_h1', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_font_size_h2'), '_bx_uni_stg_cpt_option_size_h2', '24px', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_font_color_h2'), '_bx_uni_stg_cpt_option_color_h2', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_font_size_h3'), '_bx_uni_stg_cpt_option_size_h3', '18px', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_font_color_h3'), '_bx_uni_stg_cpt_option_color_h3', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed'), '_bx_uni_stg_cpt_option_color_grayed', 'rgba(153, 153, 153, 1)', 'rgba', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted'), '_bx_uni_stg_cpt_option_color_contrasted', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 17);

-- SETTINGS: UNI Viewport Tablet
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_viewport_tablet'), '_bx_uni_stg_cpt_category_viewport_tablet', 15);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_vpt_font_size_scale'), '_bx_uni_stg_cpt_option_vpt_font_size_scale', '100%', 'digit', '', '', '', 1);

-- SETTINGS: UNI Viewport Mobile
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_viewport_mobile'), '_bx_uni_stg_cpt_category_viewport_mobile', 16);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_vpm_font_size_scale'), '_bx_uni_stg_cpt_option_vpm_font_size_scale', '85%', 'digit', '', '', '', 1);


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '', '', 'bx_uni@modules/boonex/uni/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id`=@iParentPageId);
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, CONCAT('{url_studio}design.php?name=', @sName), '', 'bx_uni@modules/boonex/uni/|std-wi.png', '_bx_uni_wgt_cpt', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioDesigns";}');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder + 1);