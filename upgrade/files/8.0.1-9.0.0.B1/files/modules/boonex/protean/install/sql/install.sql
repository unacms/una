SET @sName = 'bx_protean';


-- SETTINGS
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('templates', @sName, '_bx_protean_stg_cpt_type', 'bx_protean@modules/boonex/protean/|std-mi.png', 2);
SET @iTypeId = LAST_INSERT_ID();

-- SETTINGS: Protean template System
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_system'), '_bx_protean_stg_cpt_category_system', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_switcher_title'), '_bx_protean_stg_cpt_option_switcher_name', 'Protean', 'digit', '', '', '', 1);


-- SETTINGS: Protean template Styles General
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_general'), '_bx_protean_stg_cpt_category_styles_general', 2);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_hover'), '_bx_protean_stg_cpt_option_general_item_bg_color_hover', 'rgba(196, 248, 156, 0.2)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_active'), '_bx_protean_stg_cpt_option_general_item_bg_color_active', 'rgba(196, 248, 156, 0.4)', 'rgba', '', '', '', 2);


-- SETTINGS: Protean template Styles Header
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_header'), '_bx_protean_stg_cpt_category_styles_header', 3);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_header_height'), '_bx_protean_stg_cpt_option_header_height', '3rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_header_content_padding'), '_bx_protean_stg_cpt_option_header_content_padding', '0px', 'digit', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_header_bg_color'), '_bx_protean_stg_cpt_option_header_bg_color', 'rgba(59, 134, 134, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_header_bg_image'), '_bx_protean_stg_cpt_option_header_bg_image', '', 'image', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_header_bg_image_repeat'), '_bx_protean_stg_cpt_option_header_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_header_bg_image_size'), '_bx_protean_stg_cpt_option_header_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_site_logo'), '_bx_protean_stg_cpt_option_site_logo', '', 'image', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_site_logo_alt'), '_bx_protean_stg_cpt_option_site_logo_alt', '', 'text', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_site_logo_width'), '_bx_protean_stg_cpt_option_site_logo_width', '240', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_site_logo_height'), '_bx_protean_stg_cpt_option_site_logo_height', '48', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_header_border_color'), '_bx_protean_stg_cpt_option_header_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_header_border_size'), '_bx_protean_stg_cpt_option_header_border_size', '0px', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_header_shadow'), '_bx_protean_stg_cpt_option_header_shadow', 'none', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_header_icon_color'), '_bx_protean_stg_cpt_option_header_icon_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_header_icon_color_hover'), '_bx_protean_stg_cpt_option_header_icon_color_hover', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_header_link_color'), '_bx_protean_stg_cpt_option_header_link_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_header_link_color_hover'), '_bx_protean_stg_cpt_option_header_link_color_hover', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 17);

-- SETTINGS: Protean template Styles Footer
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_footer'), '_bx_protean_stg_cpt_category_styles_footer', 4);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_footer_bg_color'), '_bx_protean_stg_cpt_option_footer_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image'), '_bx_protean_stg_cpt_option_footer_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image_repeat'), '_bx_protean_stg_cpt_option_footer_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image_size'), '_bx_protean_stg_cpt_option_footer_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_footer_content_padding'), '_bx_protean_stg_cpt_option_footer_content_padding', '0px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_footer_border_color'), '_bx_protean_stg_cpt_option_footer_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_footer_border_size'), '_bx_protean_stg_cpt_option_footer_border_size', '1px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_footer_shadow'), '_bx_protean_stg_cpt_option_footer_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_footer_font_color'), '_bx_protean_stg_cpt_option_footer_font_color', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_footer_icon_color'), '_bx_protean_stg_cpt_option_footer_icon_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_footer_icon_color_hover'), '_bx_protean_stg_cpt_option_footer_icon_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_footer_link_color'), '_bx_protean_stg_cpt_option_footer_link_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_footer_link_color_hover'), '_bx_protean_stg_cpt_option_footer_link_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 13);

-- SETTINGS: Protean template Styles Body
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_body'), '_bx_protean_stg_cpt_category_styles_body', 5);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_body_bg_color'), '_bx_protean_stg_cpt_option_body_bg_color', 'rgb(255, 255, 255)', 'rgb', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_body_bg_image'), '_bx_protean_stg_cpt_option_body_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_body_bg_image_repeat'), '_bx_protean_stg_cpt_option_body_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_body_bg_image_size'), '_bx_protean_stg_cpt_option_body_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_page_width'), '_bx_protean_stg_cpt_option_page_width', '1000', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_body_icon_color'), '_bx_protean_stg_cpt_option_body_icon_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_body_icon_color_hover'), '_bx_protean_stg_cpt_option_body_icon_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_body_link_color'), '_bx_protean_stg_cpt_option_body_link_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_body_link_color_hover'), '_bx_protean_stg_cpt_option_body_link_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 9);

-- SETTINGS: Protean template Styles Block
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_block'), '_bx_protean_stg_cpt_category_styles_block', 6);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_block_bg_color'), '_bx_protean_stg_cpt_option_block_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_block_bg_image'), '_bx_protean_stg_cpt_option_block_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_block_bg_image_repeat'), '_bx_protean_stg_cpt_option_block_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_block_bg_image_size'), '_bx_protean_stg_cpt_option_block_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_block_content_padding'), '_bx_protean_stg_cpt_option_block_content_padding', '20px 0px 0px 0px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_block_border_color'), '_bx_protean_stg_cpt_option_block_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_block_border_size'), '_bx_protean_stg_cpt_option_block_border_size', '0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_block_border_radius'), '_bx_protean_stg_cpt_option_block_border_radius', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_block_shadow'), '_bx_protean_stg_cpt_option_block_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_block_title_bg_color'), '_bx_protean_stg_cpt_option_block_title_bg_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_block_title_padding'), '_bx_protean_stg_cpt_option_block_title_padding', '0px', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_block_title_font_family'), '_bx_protean_stg_cpt_option_block_title_font_family', 'Helvetica, Arial, sans-serif', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_block_title_font_size'), '_bx_protean_stg_cpt_option_block_title_font_size', '1.5rem', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_block_title_font_color'), '_bx_protean_stg_cpt_option_block_title_font_color', 'rgba(0, 0, 20, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_block_title_font_weight'), '_bx_protean_stg_cpt_option_block_title_font_weight', '500', 'digit', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_block_title_div_height'), '_bx_protean_stg_cpt_option_block_title_div_height', '1px', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_block_title_div_bg_color'), '_bx_protean_stg_cpt_option_block_title_div_bg_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 17);


-- SETTINGS: Protean template Styles Card
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_card'), '_bx_protean_stg_cpt_category_styles_card', 7);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_card_bg_color'), '_bx_protean_stg_cpt_option_card_bg_color', 'rgba(242, 242, 242, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_card_bg_image'), '_bx_protean_stg_cpt_option_card_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_card_bg_image_repeat'), '_bx_protean_stg_cpt_option_card_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_card_bg_image_size'), '_bx_protean_stg_cpt_option_card_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_card_content_padding'), '_bx_protean_stg_cpt_option_card_content_padding', '20px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_card_border_color'), '_bx_protean_stg_cpt_option_card_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_card_border_size'), '_bx_protean_stg_cpt_option_card_border_size', '0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_card_border_radius'), '_bx_protean_stg_cpt_option_card_border_radius', '3px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_card_shadow'), '_bx_protean_stg_cpt_option_card_shadow', 'none', 'digit', '', '', '', 9);


-- SETTINGS: Protean template Styles Popups
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_popup'), '_bx_protean_stg_cpt_category_styles_popup', 8);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_popup_bg_color'), '_bx_protean_stg_cpt_option_popup_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image'), '_bx_protean_stg_cpt_option_popup_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image_repeat'), '_bx_protean_stg_cpt_option_popup_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image_size'), '_bx_protean_stg_cpt_option_popup_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_popup_content_padding'), '_bx_protean_stg_cpt_option_popup_content_padding', '1.25rem', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_popup_border_color'), '_bx_protean_stg_cpt_option_popup_border_color', 'rgba(56, 61, 102, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_popup_border_size'), '_bx_protean_stg_cpt_option_popup_border_size', '1px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_popup_border_radius'), '_bx_protean_stg_cpt_option_popup_border_radius', '3px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_popup_shadow'), '_bx_protean_stg_cpt_option_popup_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_popup_title_bg_color'), '_bx_protean_stg_cpt_option_popup_title_bg_color', 'rgba(40, 60, 80, 0.9)', 'rgba', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_popup_title_padding'), '_bx_protean_stg_cpt_option_popup_title_padding', '1.25rem', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_family'), '_bx_protean_stg_cpt_option_popup_title_font_family', 'Helvetica, Arial, sans-serif', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_size'), '_bx_protean_stg_cpt_option_popup_title_font_size', '1.5rem', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_color'), '_bx_protean_stg_cpt_option_popup_title_font_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 14);


-- SETTINGS: Protean template Styles Main Menu
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_main'), '_bx_protean_stg_cpt_category_styles_menu_main', 9);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_color'), '_bx_protean_stg_cpt_option_menu_main_bg_color', 'rgba(255, 255, 255, 0.9)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_image'), '_bx_protean_stg_cpt_option_menu_main_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_image_repeat'), '_bx_protean_stg_cpt_option_menu_main_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_image_size'), '_bx_protean_stg_cpt_option_menu_main_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_main_content_padding'), '_bx_protean_stg_cpt_option_menu_main_content_padding', '0px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_main_border_color'), '_bx_protean_stg_cpt_option_menu_main_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_main_border_size'), '_bx_protean_stg_cpt_option_menu_main_border_size', '0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_main_shadow'), '_bx_protean_stg_cpt_option_menu_main_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_family'), '_bx_protean_stg_cpt_option_menu_main_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_size'), '_bx_protean_stg_cpt_option_menu_main_font_size', '1.125rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_color'), '_bx_protean_stg_cpt_option_menu_main_font_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_color_hover'), '_bx_protean_stg_cpt_option_menu_main_font_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_color_active'), '_bx_protean_stg_cpt_option_menu_main_font_color_active', 'rgba(0, 0, 0, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_shadow'), '_bx_protean_stg_cpt_option_menu_main_font_shadow', 'none', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_weight'), '_bx_protean_stg_cpt_option_menu_main_font_weight', '400', 'digit', '', '', '', 15);


-- SETTINGS: Protean template Styles Page Menu
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_page'), '_bx_protean_stg_cpt_category_styles_menu_page', 10);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_color'), '_bx_protean_stg_cpt_option_menu_page_bg_color', 'rgba(242, 242, 242, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image'), '_bx_protean_stg_cpt_option_menu_page_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image_repeat'), '_bx_protean_stg_cpt_option_menu_page_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image_size'), '_bx_protean_stg_cpt_option_menu_page_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_page_content_padding'), '_bx_protean_stg_cpt_option_menu_page_content_padding', '0px', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_page_border_color'), '_bx_protean_stg_cpt_option_menu_page_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_page_border_size'), '_bx_protean_stg_cpt_option_menu_page_border_size', '0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_page_shadow'), '_bx_protean_stg_cpt_option_menu_page_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_family'), '_bx_protean_stg_cpt_option_menu_page_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_size'), '_bx_protean_stg_cpt_option_menu_page_font_size', '1.2rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_color'), '_bx_protean_stg_cpt_option_menu_page_font_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_color_hover'), '_bx_protean_stg_cpt_option_menu_page_font_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_color_active'), '_bx_protean_stg_cpt_option_menu_page_font_color_active', 'rgba(0, 0, 0, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_shadow'), '_bx_protean_stg_cpt_option_menu_page_font_shadow', 'none', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_weight'), '_bx_protean_stg_cpt_option_menu_page_font_weight', '400', 'digit', '', '', '', 15);


-- SETTINGS: Protean template Styles Slide Menus
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_slide'), '_bx_protean_stg_cpt_category_styles_menu_slide', 11);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_color'), '_bx_protean_stg_cpt_option_menu_slide_bg_color', 'rgba(255, 255, 255, 0.9)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image'), '_bx_protean_stg_cpt_option_menu_slide_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image_repeat'), '_bx_protean_stg_cpt_option_menu_slide_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image_size'), '_bx_protean_stg_cpt_option_menu_slide_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_slide_content_padding'), '_bx_protean_stg_cpt_option_menu_slide_content_padding', '1.25rem', 'digit', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_slide_border_color'), '_bx_protean_stg_cpt_option_menu_slide_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_slide_border_size'), '_bx_protean_stg_cpt_option_menu_slide_border_size', '1px 0px 1px 0px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_slide_shadow'), '_bx_protean_stg_cpt_option_menu_slide_shadow', 'none', 'digit', '', '', '', 8);


-- SETTINGS: Protean template Styles Forms
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_form'), '_bx_protean_stg_cpt_category_styles_form', 12);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_form_input_height'), '_bx_protean_stg_cpt_option_form_input_height', '2.2rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_form_input_bg_color'), '_bx_protean_stg_cpt_option_form_input_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_form_input_bg_color_active'), '_bx_protean_stg_cpt_option_form_input_bg_color_active', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_form_input_border_color'), '_bx_protean_stg_cpt_option_form_input_border_color', 'rgba(121, 189, 154, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_form_input_border_color_active'), '_bx_protean_stg_cpt_option_form_input_border_color_active', 'rgba(108, 170, 138, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_form_input_border_size'), '_bx_protean_stg_cpt_option_form_input_border_size', '2px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_form_input_shadow'), '_bx_protean_stg_cpt_option_form_input_shadow', 'none', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_form_input_font_family'), '_bx_protean_stg_cpt_option_form_input_font_family', 'Helvetica, Arial, sans-serif', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_form_input_font_size'), '_bx_protean_stg_cpt_option_form_input_font_size', '1.125rem', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_form_input_font_color'), '_bx_protean_stg_cpt_option_form_input_font_color', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 10);


-- SETTINGS: Protean template Styles Large Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_large_button'), '_bx_protean_stg_cpt_category_styles_large_button', 13);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_lg_height'), '_bx_protean_stg_cpt_option_button_lg_height', '2.25rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color'), '_bx_protean_stg_cpt_option_button_lg_bg_color', 'rgba(108, 170, 138, 1)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color_hover'), '_bx_protean_stg_cpt_option_button_lg_bg_color_hover', 'rgba(58, 134, 134, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color'), '_bx_protean_stg_cpt_option_button_lg_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color_hover'), '_bx_protean_stg_cpt_option_button_lg_border_color_hover', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_size'), '_bx_protean_stg_cpt_option_button_lg_border_size', '0px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_radius'), '_bx_protean_stg_cpt_option_button_lg_border_radius', '3px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_lg_shadow'), '_bx_protean_stg_cpt_option_button_lg_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_family'), '_bx_protean_stg_cpt_option_button_lg_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_size'), '_bx_protean_stg_cpt_option_button_lg_font_size', '1rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color'), '_bx_protean_stg_cpt_option_button_lg_font_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color_hover'), '_bx_protean_stg_cpt_option_button_lg_font_color_hover', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_shadow'), '_bx_protean_stg_cpt_option_button_lg_font_shadow', 'none', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_weight'), '_bx_protean_stg_cpt_option_button_lg_font_weight', '400', 'digit', '', '', '', 14);

-- SETTINGS: Protean template Styles Small Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_small_button'), '_bx_protean_stg_cpt_category_styles_small_button', 14);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_sm_height'), '_bx_protean_stg_cpt_option_button_sm_height', '1.5rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color'), '_bx_protean_stg_cpt_option_button_sm_bg_color', 'rgba(108, 170, 138, 1)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color_hover'), '_bx_protean_stg_cpt_option_button_sm_bg_color_hover', 'rgba(58, 134, 134, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color'), '_bx_protean_stg_cpt_option_button_sm_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color_hover'), '_bx_protean_stg_cpt_option_button_sm_border_color_hover', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_size'), '_bx_protean_stg_cpt_option_button_sm_border_size', '0px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_radius'), '_bx_protean_stg_cpt_option_button_sm_border_radius', '3px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_sm_shadow'), '_bx_protean_stg_cpt_option_button_sm_shadow', 'none', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_family'), '_bx_protean_stg_cpt_option_button_sm_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_size'), '_bx_protean_stg_cpt_option_button_sm_font_size', '0.9rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color'), '_bx_protean_stg_cpt_option_button_sm_font_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color_hover'), '_bx_protean_stg_cpt_option_button_sm_font_color_hover', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_shadow'), '_bx_protean_stg_cpt_option_button_sm_font_shadow', 'none', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_weight'), '_bx_protean_stg_cpt_option_button_sm_font_weight', '400', 'digit', '', '', '', 14);

-- SETTINGS: Protean template Styles Font
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_font'), '_bx_protean_stg_cpt_category_styles_font', 15);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_font_family'), '_bx_protean_stg_cpt_option_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_font_size_default'), '_bx_protean_stg_cpt_option_font_size_default', '18px', 'digit', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_font_color_default'), '_bx_protean_stg_cpt_option_font_color_default', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed'), '_bx_protean_stg_cpt_option_font_color_grayed', 'rgba(153, 153, 153, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted'), '_bx_protean_stg_cpt_option_font_color_contrasted', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_font_size_small'), '_bx_protean_stg_cpt_option_font_size_small', '14px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_font_size_middle'), '_bx_protean_stg_cpt_option_font_size_middle', '16px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_font_size_large'), '_bx_protean_stg_cpt_option_font_size_large', '22px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_font_size_h1'), '_bx_protean_stg_cpt_option_font_size_h1', '38px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_font_weight_h1'), '_bx_protean_stg_cpt_option_font_weight_h1', '700', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_font_color_default_h1'), '_bx_protean_stg_cpt_option_font_color_default_h1', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed_h1'), '_bx_protean_stg_cpt_option_font_color_grayed_h1', 'rgba(153, 153, 153, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted_h1'), '_bx_protean_stg_cpt_option_font_color_contrasted_h1', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h1'), '_bx_protean_stg_cpt_option_font_color_link_h1', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h1_hover'), '_bx_protean_stg_cpt_option_font_color_link_h1_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_font_size_h2'), '_bx_protean_stg_cpt_option_font_size_h2', '24px', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_font_weight_h2'), '_bx_protean_stg_cpt_option_font_weight_h2', '700', 'digit', '', '', '', 17),
(@iCategoryId, CONCAT(@sName, '_font_color_default_h2'), '_bx_protean_stg_cpt_option_font_color_default_h2', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 18),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed_h2'), '_bx_protean_stg_cpt_option_font_color_grayed_h2', 'rgba(153, 153, 153, 1)', 'rgba', '', '', '', 19),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted_h2'), '_bx_protean_stg_cpt_option_font_color_contrasted_h2', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 20),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h2'), '_bx_protean_stg_cpt_option_font_color_link_h2', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 21),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h2_hover'), '_bx_protean_stg_cpt_option_font_color_link_h2_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 22),
(@iCategoryId, CONCAT(@sName, '_font_size_h3'), '_bx_protean_stg_cpt_option_font_size_h3', '18px', 'digit', '', '', '', 23),
(@iCategoryId, CONCAT(@sName, '_font_weight_h3'), '_bx_protean_stg_cpt_option_font_weight_h3', '500', 'digit', '', '', '', 24),
(@iCategoryId, CONCAT(@sName, '_font_color_default_h3'), '_bx_protean_stg_cpt_option_font_color_default_h3', 'rgba(51, 51, 51, 1)', 'rgba', '', '', '', 25),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed_h3'), '_bx_protean_stg_cpt_option_font_color_grayed_h3', 'rgba(153, 153, 153, 1)', 'rgba', '', '', '', 26),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted_h3'), '_bx_protean_stg_cpt_option_font_color_contrasted_h3', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 27),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h3'), '_bx_protean_stg_cpt_option_font_color_link_h3', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 28),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h3_hover'), '_bx_protean_stg_cpt_option_font_color_link_h3_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 29);

-- SETTINGS: Protean template Viewport Tablet
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_viewport_tablet'), '_bx_protean_stg_cpt_category_viewport_tablet', 16);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_vpt_font_size_scale'), '_bx_protean_stg_cpt_option_vpt_font_size_scale', '100%', 'digit', '', '', '', 1);

-- SETTINGS: Protean template Viewport Mobile
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_viewport_mobile'), '_bx_protean_stg_cpt_category_viewport_mobile', 17);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_vpm_font_size_scale'), '_bx_protean_stg_cpt_option_vpm_font_size_scale', '85%', 'digit', '', '', '', 1);


-- MIXES
INSERT INTO `sys_options_mixes` (`type`, `category`, `name`, `title`, `active`) VALUES
(@sName, '', 'Neat-Mix', 'Neat Mix', 0);
SET @iMixId = LAST_INSERT_ID();

INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_menu_slide_shadow', @iMixId, 'none'),
('bx_protean_menu_slide_border_size', @iMixId, '1px 0px 1px 0px'),
('bx_protean_menu_slide_border_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_menu_slide_content_padding', @iMixId, '1.25rem'),
('bx_protean_menu_slide_bg_image', @iMixId, '0'),
('bx_protean_menu_slide_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_button_sm_border_size', @iMixId, '1px'),
('bx_protean_button_sm_border_color_hover', @iMixId, 'rgba(20, 40, 60, 0.9)'),
('bx_protean_button_sm_border_color', @iMixId, 'rgba(20, 40, 60, 0.8)'),
('bx_protean_button_lg_border_size', @iMixId, '1px'),
('bx_protean_button_lg_border_color_hover', @iMixId, 'rgba(20, 40, 60, 0.9)'),
('bx_protean_popup_menu_shadow', @iMixId, 'none'),
('bx_protean_button_lg_border_color', @iMixId, 'rgba(20, 40, 60, 0.8)'),
('bx_protean_popup_menu_border_size', @iMixId, '0px'),
('bx_protean_popup_menu_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_popup_menu_content_padding', @iMixId, 'inherit'),
('bx_protean_popup_menu_bg_image', @iMixId, '0'),
('bx_protean_popup_menu_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_popup_title_font_color', @iMixId, 'rgba(0, 0, 20, 1)'),
('bx_protean_popup_title_font_size', @iMixId, '1.5rem'),
('bx_protean_popup_title_padding', @iMixId, '1.25rem'),
('bx_protean_popup_title_font_family', @iMixId, 'Helvetica, Arial, sans-serif'),
('bx_protean_popup_shadow', @iMixId, '0px 3px 5px 1px rgba(0,0,0,0.1)'),
('bx_protean_popup_border_radius', @iMixId, '3px'),
('bx_protean_popup_border_size', @iMixId, '1px'),
('bx_protean_popup_content_padding', @iMixId, '1.25rem'),
('bx_protean_popup_border_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_popup_bg_image', @iMixId, '0'),
('bx_protean_popup_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_body_link_color_hover', @iMixId, 'rgba(10, 120, 220, 1)'),
('bx_protean_body_link_color', @iMixId, 'rgba(30, 140, 240, 1)'),
('bx_protean_body_icon_color_hover', @iMixId, 'rgba(10, 120, 220, 1)'),
('bx_protean_body_icon_color', @iMixId, 'rgba(30, 140, 240, 1)'),
('bx_protean_footer_link_color_hover', @iMixId, 'rgba(10, 120, 220, 1)'),
('bx_protean_footer_icon_color_hover', @iMixId, 'rgba(10, 120, 220, 1)'),
('bx_protean_header_link_color_hover', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_header_icon_color_hover', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_header_height', @iMixId, '3.4rem'),
('bx_protean_header_content_padding', @iMixId, '0.2rem'),
('bx_protean_header_bg_color', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_header_bg_image', @iMixId, '0'),
('bx_protean_site_logo', @iMixId, '0'),
('bx_protean_site_logo_alt', @iMixId, ''),
('bx_protean_site_logo_width', @iMixId, '240'),
('bx_protean_site_logo_height', @iMixId, '48'),
('bx_protean_header_border_color', @iMixId, 'rgba(40, 60, 80, 0.95)'),
('bx_protean_header_border_size', @iMixId, '1px'),
('bx_protean_header_shadow', @iMixId, '0px 0px 1px 1px rgba(0,0,0,0.2)'),
('bx_protean_header_icon_color', @iMixId, 'rgba(215, 235, 255, 1)'),
('bx_protean_header_link_color', @iMixId, 'rgba(215, 235, 255, 1)'),
('bx_protean_footer_bg_color', @iMixId, 'rgba(40, 60, 80, 0.05)'),
('bx_protean_footer_bg_image', @iMixId, '0'),
('bx_protean_footer_content_padding', @iMixId, '1rem'),
('bx_protean_footer_border_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_footer_border_size', @iMixId, '1px'),
('bx_protean_footer_shadow', @iMixId, 'none'),
('bx_protean_footer_font_color', @iMixId, 'rgba(40, 60, 80, 1)'),
('bx_protean_footer_icon_color', @iMixId, 'rgba(30, 140, 240, 1)'),
('bx_protean_footer_link_color', @iMixId, 'rgba(30, 140, 240, 1)'),
('bx_protean_body_bg_color', @iMixId, 'rgb(245, 250, 255)'),
('bx_protean_body_bg_image', @iMixId, '0'),
('bx_protean_page_width', @iMixId, '1000'),
('bx_protean_block_bg_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_block_bg_image', @iMixId, '0'),
('bx_protean_block_content_padding', @iMixId, '1rem'),
('bx_protean_block_border_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_block_border_size', @iMixId, '1px'),
('bx_protean_block_border_radius', @iMixId, '3px'),
('bx_protean_block_shadow', @iMixId, 'none'),
('bx_protean_block_title_padding', @iMixId, '0.2rem 1rem 0.2rem 1rem'),
('bx_protean_block_title_font_family', @iMixId, 'Arial, sans-serif'),
('bx_protean_block_title_font_size', @iMixId, '1.25rem'),
('bx_protean_block_title_font_color', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_block_title_div_height', @iMixId, '1px'),
('bx_protean_block_title_div_bg_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_card_bg_color', @iMixId, 'rgba(245, 250, 255, 1)'),
('bx_protean_card_bg_image', @iMixId, '0'),
('bx_protean_card_content_padding', @iMixId, '1rem'),
('bx_protean_card_border_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_card_border_size', @iMixId, '1px'),
('bx_protean_card_border_radius', @iMixId, '3px'),
('bx_protean_card_shadow', @iMixId, 'none'),
('bx_protean_button_lg_height', @iMixId, '2.5rem'),
('bx_protean_button_lg_bg_color', @iMixId, 'rgba(40, 60, 80, 0.8)'),
('bx_protean_button_lg_bg_color_hover', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_button_lg_border_radius', @iMixId, '3px'),
('bx_protean_button_lg_shadow', @iMixId, '0px 0px 0px 1px rgba(0,0,0,0.1);'),
('bx_protean_button_lg_font_family', @iMixId, 'Arial, sans-serif'),
('bx_protean_button_lg_font_size', @iMixId, '1rem'),
('bx_protean_button_lg_font_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_button_lg_font_shadow', @iMixId, 'none'),
('bx_protean_button_lg_font_weight', @iMixId, '400'),
('bx_protean_button_sm_height', @iMixId, '1.5rem'),
('bx_protean_button_sm_bg_color', @iMixId, 'rgba(40, 60, 80, 0.8)'),
('bx_protean_button_sm_bg_color_hover', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_button_sm_border_radius', @iMixId, '3px'),
('bx_protean_button_sm_shadow', @iMixId, '0px 0px 0px 1px rgba(0,0,0,0.1);'),
('bx_protean_button_sm_font_family', @iMixId, 'Arial, sans-serif'),
('bx_protean_button_sm_font_size', @iMixId, '0.75rem'),
('bx_protean_button_sm_font_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_button_sm_font_shadow', @iMixId, 'none'),
('bx_protean_button_sm_font_weight', @iMixId, '400'),
('bx_protean_font_family', @iMixId, 'Arial, sans-serif'),
('bx_protean_font_size_default', @iMixId, '16px'),
('bx_protean_font_color_default', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_size_small', @iMixId, '12px'),
('bx_protean_font_color_small', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_size_middle', @iMixId, '14px'),
('bx_protean_font_color_middle', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_size_large', @iMixId, '18px'),
('bx_protean_font_color_large', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_size_h1', @iMixId, '32px'),
('bx_protean_font_color_h1', @iMixId, 'rgba(40, 60, 80, 1)'),
('bx_protean_font_size_h2', @iMixId, '24px'),
('bx_protean_font_color_h2', @iMixId, 'rgba(40, 60, 80, 1)'),
('bx_protean_font_size_h3', @iMixId, '20px'),
('bx_protean_font_color_h3', @iMixId, 'rgba(40, 60, 80, 1)'),
('bx_protean_font_color_grayed', @iMixId, 'rgba(40, 60, 80, 0.5)'),
('bx_protean_font_color_contrasted', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_vpt_font_size_scale', @iMixId, '100%'),
('bx_protean_vpm_font_size_scale', @iMixId, '85%'),
('bx_protean_header_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_header_bg_image_size', @iMixId, 'cover'),
('bx_protean_footer_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_footer_bg_image_size', @iMixId, 'cover'),
('bx_protean_body_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_body_bg_image_size', @iMixId, 'cover'),
('bx_protean_block_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_block_bg_image_size', @iMixId, 'cover'),
('bx_protean_card_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_card_bg_image_size', @iMixId, 'cover'),
('bx_protean_popup_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_popup_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_slide_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_slide_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_page_height', @iMixId, '2.6rem'),
('bx_protean_menu_page_bg_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_menu_page_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_page_bg_image', @iMixId, '0'),
('bx_protean_menu_page_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_page_content_padding', @iMixId, '0.1rem'),
('bx_protean_menu_page_border_color', @iMixId, 'rgba(0, 0, 0, 0)'),
('bx_protean_menu_page_border_size', @iMixId, 'none'),
('bx_protean_menu_page_shadow', @iMixId, 'inset 0px -1px 0px 0px rgba(0,0,0,0.1);'),
('bx_protean_form_input_height', @iMixId, '2.5rem'),
('bx_protean_form_input_bg_color', @iMixId, 'rgba(245, 250, 255, 0.5)'),
('bx_protean_form_input_bg_color_active', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_form_input_border_color', @iMixId, 'rgba(40, 60, 80, 0.5)'),
('bx_protean_form_input_border_color_active', @iMixId, 'rgba(30, 140, 240, 1)'),
('bx_protean_form_input_border_size', @iMixId, '2px'),
('bx_protean_form_input_shadow', @iMixId, 'inset 0px 0px 2px 1px rgba(0,0,0,0.15);'),
('bx_protean_form_input_font_family', @iMixId, 'Arial, sans-serif'),
('bx_protean_form_input_font_size', @iMixId, '1rem'),
('bx_protean_form_input_font_color', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_button_lg_font_color_hover', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_button_sm_font_color_hover', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_general_item_bg_color_hover', @iMixId, 'rgba(210, 230, 250, 0.3)'),
('bx_protean_general_item_bg_color_active', @iMixId, 'rgba(210, 230, 250, 0.5)'),
('bx_protean_menu_main_bg_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_menu_main_bg_image', @iMixId, '0'),
('bx_protean_menu_main_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_main_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_main_content_padding', @iMixId, '0px'),
('bx_protean_menu_main_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_menu_main_border_size', @iMixId, '0px'),
('bx_protean_menu_main_shadow', @iMixId, 'none'),
('bx_protean_menu_main_font_family', @iMixId, '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif'),
('bx_protean_menu_main_font_size', @iMixId, '1.125rem'),
('bx_protean_menu_main_font_color', @iMixId, 'rgba(62, 134, 133, 1)'),
('bx_protean_menu_main_font_color_hover', @iMixId, 'rgba(62, 134, 133, 1)'),
('bx_protean_menu_main_font_color_active', @iMixId, 'rgba(0, 0, 0, 1)'),
('bx_protean_menu_main_font_shadow', @iMixId, 'none'),
('bx_protean_menu_main_font_weight', @iMixId, '400'),
('bx_protean_menu_page_font_family', @iMixId, '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif'),
('bx_protean_menu_page_font_size', @iMixId, '1.2rem'),
('bx_protean_menu_page_font_color', @iMixId, 'rgba(62, 134, 133, 1)'),
('bx_protean_menu_page_font_color_hover', @iMixId, 'rgba(62, 134, 133, 1)'),
('bx_protean_menu_page_font_color_active', @iMixId, 'rgba(0, 0, 0, 1)'),
('bx_protean_menu_page_font_shadow', @iMixId, 'none'),
('bx_protean_menu_page_font_weight', @iMixId, '400'),
('bx_protean_font_color_default_h1', @iMixId, 'rgba(40, 60, 80, 1)'),
('bx_protean_font_color_grayed_h1', @iMixId, 'rgba(40, 60, 80, 0.5)'),
('bx_protean_font_color_contrasted_h1', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_font_color_link_h1', @iMixId, 'rgba(30, 140, 240, 1)'),
('bx_protean_font_color_link_h1_hover', @iMixId, 'rgba(10, 120, 220, 1)'),
('bx_protean_font_color_default_h2', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_color_grayed_h2', @iMixId, 'rgba(40, 60, 80, 0.5)'),
('bx_protean_font_color_contrasted_h2', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_font_color_link_h2', @iMixId, 'rgba(30, 140, 240, 1)'),
('bx_protean_font_color_link_h2_hover', @iMixId, 'rgba(10, 120, 220, 1)'),
('bx_protean_font_color_default_h3', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_color_grayed_h3', @iMixId, 'rgba(40, 60, 80, 0.5)'),
('bx_protean_font_color_contrasted_h3', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_font_color_link_h3', @iMixId, 'rgba(30, 140, 240, 1)'),
('bx_protean_font_color_link_h3_hover', @iMixId, 'rgba(10, 120, 220, 1)'),
('bx_protean_block_title_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_block_title_font_weight', @iMixId, '500'),
('bx_protean_popup_title_bg_color', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_weight_h1', @iMixId, '700'),
('bx_protean_font_weight_h2', @iMixId, '700'),
('bx_protean_font_weight_h3', @iMixId, '500');


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '', '', 'bx_protean@modules/boonex/protean/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id`=@iParentPageId);
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, CONCAT('{url_studio}design.php?name=', @sName), '', 'bx_protean@modules/boonex/protean/|std-wi.png', '_bx_protean_wgt_cpt', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioDesigns";}');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder + 1);