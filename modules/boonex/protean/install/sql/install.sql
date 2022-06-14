SET @sName = 'bx_protean';


-- SETTINGS
SET @iSystemCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='system' LIMIT 1);
SET @iSystemCategoryOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options` WHERE `category_id`=@iSystemCategoryId LIMIT 1);
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_aspect_ratio'), '', '', 'digit', '', '', '', @iSystemCategoryOrder + 1);

INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('templates', @sName, '_bx_protean_stg_cpt_type', 'bx_protean@modules/boonex/protean/|std-icon.svg', 2);
SET @iTypeId = LAST_INSERT_ID();

-- SETTINGS: Protean template System
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_system'), '_bx_protean_stg_cpt_category_system', 10);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_switcher_title'), '_bx_protean_stg_cpt_option_switcher_name', 'Protean', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_default_mix'), '_bx_protean_stg_cpt_option_default_mix', '', 'select', 'a:2:{s:6:"module";s:10:"bx_protean";s:6:"method";s:23:"get_options_default_mix";}', '', '', 10);


-- SETTINGS: Protean template Styles General
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_general'), '_bx_protean_stg_cpt_category_styles_general', 20);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_hover'), '_bx_protean_stg_cpt_option_general_item_bg_color_hover', 'rgba(210, 230, 250, 0.3)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_active'), '_bx_protean_stg_cpt_option_general_item_bg_color_active', 'rgba(196, 248, 156, 0.4)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_disabled'), '_bx_protean_stg_cpt_option_general_item_bg_color_disabled', 'rgba(221, 221, 221, 1.0)', 'rgba', '', '', '', 3);


-- SETTINGS: Protean template Styles Header
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_header'), '_bx_protean_stg_cpt_category_styles_header', 30);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_header_height'), '_bx_protean_stg_cpt_option_header_height', '4rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_header_content_padding'), '_bx_protean_stg_cpt_option_header_content_padding', '0.5rem', 'digit', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_header_bg_color'), '_bx_protean_stg_cpt_option_header_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_header_bg_image'), '_bx_protean_stg_cpt_option_header_bg_image', '', 'image', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_header_bg_image_repeat'), '_bx_protean_stg_cpt_option_header_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_header_bg_image_attachment'), '_bx_protean_stg_cpt_option_header_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_header_bg_image_size'), '_bx_protean_stg_cpt_option_header_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_site_logo'), '_bx_protean_stg_cpt_option_site_logo', '', 'image', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_site_logo_alt'), '_bx_protean_stg_cpt_option_site_logo_alt', '', 'text', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_header_border_color'), '_bx_protean_stg_cpt_option_header_border_color', 'rgba(0, 0, 0, 0.2)', 'rgba', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_header_border_size'), '_bx_protean_stg_cpt_option_header_border_size', '1px', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_header_shadow'), '_bx_protean_stg_cpt_option_header_shadow', '0px 1px 3px 0px rgba(0, 0, 0, 0.05)', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_header_icon_color'), '_bx_protean_stg_cpt_option_header_icon_color', 'rgba(30, 150, 250, 0.8)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_header_icon_color_hover'), '_bx_protean_stg_cpt_option_header_icon_color_hover', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_header_link_color'), '_bx_protean_stg_cpt_option_header_link_color', 'rgba(30, 150, 250, 0.8)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_header_link_color_hover'), '_bx_protean_stg_cpt_option_header_link_color_hover', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 16);

-- SETTINGS: Protean template Styles Footer
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_footer'), '_bx_protean_stg_cpt_category_styles_footer', 40);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_footer_bg_color'), '_bx_protean_stg_cpt_option_footer_bg_color', 'rgba(30, 150, 250, 0.5)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image'), '_bx_protean_stg_cpt_option_footer_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image_repeat'), '_bx_protean_stg_cpt_option_footer_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image_attachment'), '_bx_protean_stg_cpt_option_footer_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_footer_bg_image_size'), '_bx_protean_stg_cpt_option_footer_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_footer_content_padding'), '_bx_protean_stg_cpt_option_footer_content_padding', '1rem', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_footer_border_color'), '_bx_protean_stg_cpt_option_footer_border_color', 'rgba(0, 0, 0, 1)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_footer_border_size'), '_bx_protean_stg_cpt_option_footer_border_size', '1px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_footer_shadow'), '_bx_protean_stg_cpt_option_footer_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_footer_font_color'), '_bx_protean_stg_cpt_option_footer_font_color', 'rgba(40, 50, 60, 0.9)', 'rgba', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_footer_icon_color'), '_bx_protean_stg_cpt_option_footer_icon_color', 'rgba(10, 120, 220, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_footer_icon_color_hover'), '_bx_protean_stg_cpt_option_footer_icon_color_hover', 'rgba(10, 120, 220, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_footer_link_color'), '_bx_protean_stg_cpt_option_footer_link_color', 'rgba(10, 120, 220, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_footer_link_color_hover'), '_bx_protean_stg_cpt_option_footer_link_color_hover', 'rgba(10, 120, 220, 1)', 'rgba', '', '', '', 14);

-- SETTINGS: Protean template Styles Body
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_body'), '_bx_protean_stg_cpt_category_styles_body', 50);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_body_bg_color'), '_bx_protean_stg_cpt_option_body_bg_color', 'rgb(230, 240, 250)', 'rgb', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_body_bg_image'), '_bx_protean_stg_cpt_option_body_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_body_bg_image_repeat'), '_bx_protean_stg_cpt_option_body_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_body_bg_image_attachment'), '_bx_protean_stg_cpt_option_body_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_body_bg_image_size'), '_bx_protean_stg_cpt_option_body_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_page_width'), '_bx_protean_stg_cpt_option_page_width', '1280', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_body_icon_color'), '_bx_protean_stg_cpt_option_body_icon_color', 'rgba(30, 150, 250, 0.9)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_body_icon_color_hover'), '_bx_protean_stg_cpt_option_body_icon_color_hover', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_body_link_color'), '_bx_protean_stg_cpt_option_body_link_color', 'rgba(30, 150, 250, 0.9)', 'rgba', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_body_link_color_hover'), '_bx_protean_stg_cpt_option_body_link_color_hover', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 10);


-- SETTINGS: Protean template Styles Cover
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_cover'), '_bx_protean_stg_cpt_category_styles_cover', 55);
SET @iCategoryId = LAST_INSERT_ID();

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


-- SETTINGS: Protean template Styles Block
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_block'), '_bx_protean_stg_cpt_category_styles_block', 60);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_block_bg_color'), '_bx_protean_stg_cpt_option_block_bg_color', 'rgba(245, 250, 255, 0.9)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_block_bg_image'), '_bx_protean_stg_cpt_option_block_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_block_bg_image_repeat'), '_bx_protean_stg_cpt_option_block_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_block_bg_image_attachment'), '_bx_protean_stg_cpt_option_block_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_block_bg_image_size'), '_bx_protean_stg_cpt_option_block_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_block_content_padding'), '_bx_protean_stg_cpt_option_block_content_padding', '1rem', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_block_border_color'), '_bx_protean_stg_cpt_option_block_border_color', 'rgba(0, 0, 0, 0)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_block_border_size'), '_bx_protean_stg_cpt_option_block_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_block_border_radius'), '_bx_protean_stg_cpt_option_block_border_radius', '3px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_block_shadow'), '_bx_protean_stg_cpt_option_block_shadow', '0px 1px 2px 0px rgba(0, 0, 0, 0.05)', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_block_title_bg_color'), '_bx_protean_stg_cpt_option_block_title_bg_color', 'rgba(255, 255, 255, 0)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_block_title_padding'), '_bx_protean_stg_cpt_option_block_title_padding', '0.75rem 1.0rem 0.75rem 1.0rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_block_title_border_color'), '_bx_protean_stg_cpt_option_block_title_border_color', 'rgba(0, 0, 0, 0)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_block_title_border_size'), '_bx_protean_stg_cpt_option_block_title_border_size', '0px', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_block_title_border_radius'), '_bx_protean_stg_cpt_option_block_title_border_radius', '0px', 'digit', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_block_title_font_family'), '_bx_protean_stg_cpt_option_block_title_font_family', 'Arial, sans-serif', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_block_title_font_size'), '_bx_protean_stg_cpt_option_block_title_font_size', '1rem', 'digit', '', '', '', 17),
(@iCategoryId, CONCAT(@sName, '_block_title_font_color'), '_bx_protean_stg_cpt_option_block_title_font_color', 'rgba(40, 50, 60, 0.8)', 'rgba', '', '', '', 18),
(@iCategoryId, CONCAT(@sName, '_block_title_font_weight'), '_bx_protean_stg_cpt_option_block_title_font_weight', '700', 'digit', '', '', '', 19),
(@iCategoryId, CONCAT(@sName, '_block_title_div_height'), '_bx_protean_stg_cpt_option_block_title_div_height', '0px', 'digit', '', '', '', 20),
(@iCategoryId, CONCAT(@sName, '_block_title_div_bg_color'), '_bx_protean_stg_cpt_option_block_title_div_bg_color', 'rgba(40, 60, 80, 0)', 'rgba', '', '', '', 21);


-- SETTINGS: Protean template Styles Card
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_card'), '_bx_protean_stg_cpt_category_styles_card', 70);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_card_bg_color'), '_bx_protean_stg_cpt_option_card_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_card_bg_color_hover'), '_bx_protean_stg_cpt_option_card_bg_color_hover', 'rgba(255, 255, 255, 0.5)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_card_bg_image'), '_bx_protean_stg_cpt_option_card_bg_image', '', 'image', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_card_bg_image_repeat'), '_bx_protean_stg_cpt_option_card_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_card_bg_image_attachment'), '_bx_protean_stg_cpt_option_card_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_card_bg_image_size'), '_bx_protean_stg_cpt_option_card_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_card_content_padding'), '_bx_protean_stg_cpt_option_card_content_padding', '1rem', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_card_border_color'), '_bx_protean_stg_cpt_option_card_border_color', 'rgba(0, 0, 0, 0.1)', 'rgba', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_card_border_size'), '_bx_protean_stg_cpt_option_card_border_size', '1px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_card_border_radius'), '_bx_protean_stg_cpt_option_card_border_radius', '3px', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_card_shadow'), '_bx_protean_stg_cpt_option_card_shadow', '0px 1px 3px 0px rgba(0, 0, 0, 0.05)', 'digit', '', '', '', 11);


-- SETTINGS: Protean template Styles Popups
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_popup'), '_bx_protean_stg_cpt_category_styles_popup', 80);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_popup_bg_color'), '_bx_protean_stg_cpt_option_popup_bg_color', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image'), '_bx_protean_stg_cpt_option_popup_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image_repeat'), '_bx_protean_stg_cpt_option_popup_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image_attachment'), '_bx_protean_stg_cpt_option_popup_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_popup_bg_image_size'), '_bx_protean_stg_cpt_option_popup_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_popup_content_padding'), '_bx_protean_stg_cpt_option_popup_content_padding', '1.25rem', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_popup_border_color'), '_bx_protean_stg_cpt_option_popup_border_color', 'rgba(0, 0, 0, 0.2)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_popup_border_size'), '_bx_protean_stg_cpt_option_popup_border_size', '1px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_popup_border_radius'), '_bx_protean_stg_cpt_option_popup_border_radius', '3px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_popup_shadow'), '_bx_protean_stg_cpt_option_popup_shadow', '0px 1px 5px 0px rgba(0, 0, 0, 0.05)', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_popup_title_bg_color'), '_bx_protean_stg_cpt_option_popup_title_bg_color', 'rgba(230, 240, 250, 0.9)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_popup_title_padding'), '_bx_protean_stg_cpt_option_popup_title_padding', '1.25rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_family'), '_bx_protean_stg_cpt_option_popup_title_font_family', 'Arial, sans-serif', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_size'), '_bx_protean_stg_cpt_option_popup_title_font_size', '1rem', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_popup_title_font_color'), '_bx_protean_stg_cpt_option_popup_title_font_color', 'rgba(40, 50, 60, 0.9)', 'rgba', '', '', '', 15);


-- SETTINGS: Protean template Styles Main Menu
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_main'), '_bx_protean_stg_cpt_category_styles_menu_main', 90);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_color'), '_bx_protean_stg_cpt_option_menu_main_bg_color', 'rgba(255, 255, 255, 0.9)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_image'), '_bx_protean_stg_cpt_option_menu_main_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_image_repeat'), '_bx_protean_stg_cpt_option_menu_main_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_image_attachment'), '_bx_protean_stg_cpt_option_menu_main_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_image_size'), '_bx_protean_stg_cpt_option_menu_main_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_main_content_padding'), '_bx_protean_stg_cpt_option_menu_main_content_padding', '0px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_main_border_color'), '_bx_protean_stg_cpt_option_menu_main_border_color', 'rgba(0, 0, 0, 0.1)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_main_border_size'), '_bx_protean_stg_cpt_option_menu_main_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_menu_main_shadow'), '_bx_protean_stg_cpt_option_menu_main_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_family'), '_bx_protean_stg_cpt_option_menu_main_font_family', 'Arial, sans-serif', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_size'), '_bx_protean_stg_cpt_option_menu_main_font_size', '1rem', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_color'), '_bx_protean_stg_cpt_option_menu_main_font_color', 'rgba(40, 50, 60, 0.8)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_color_hover'), '_bx_protean_stg_cpt_option_menu_main_font_color_hover', 'rgba(40, 50, 60, 0.9)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_color_active'), '_bx_protean_stg_cpt_option_menu_main_font_color_active', 'rgba(20, 30, 40, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_shadow'), '_bx_protean_stg_cpt_option_menu_main_font_shadow', 'none', 'digit', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_menu_main_font_weight'), '_bx_protean_stg_cpt_option_menu_main_font_weight', '700', 'digit', '', '', '', 16);


-- SETTINGS: Protean template Styles Account Menu
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_account'), '_bx_protean_stg_cpt_category_styles_menu_account', 95);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_color'), '_bx_protean_stg_cpt_option_menu_account_bg_color', 'rgba(255, 255, 255, 0.9)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_image'), '_bx_protean_stg_cpt_option_menu_account_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_image_repeat'), '_bx_protean_stg_cpt_option_menu_account_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_image_attachment'), '_bx_protean_stg_cpt_option_menu_account_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_image_size'), '_bx_protean_stg_cpt_option_menu_account_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_account_content_padding'), '_bx_protean_stg_cpt_option_menu_account_content_padding', '0px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_account_border_color'), '_bx_protean_stg_cpt_option_menu_account_border_color', 'rgba(0, 0, 0, 0.1)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_account_border_size'), '_bx_protean_stg_cpt_option_menu_account_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_menu_account_shadow'), '_bx_protean_stg_cpt_option_menu_account_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_family'), '_bx_protean_stg_cpt_option_menu_account_font_family', 'Arial, sans-serif', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_size'), '_bx_protean_stg_cpt_option_menu_account_font_size', '1rem', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_color'), '_bx_protean_stg_cpt_option_menu_account_font_color', 'rgba(40, 50, 60, 0.8)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_color_hover'), '_bx_protean_stg_cpt_option_menu_account_font_color_hover', 'rgba(40, 50, 60, 0.9)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_color_active'), '_bx_protean_stg_cpt_option_menu_account_font_color_active', 'rgba(20, 30, 40, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_shadow'), '_bx_protean_stg_cpt_option_menu_account_font_shadow', 'none', 'digit', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_menu_account_font_weight'), '_bx_protean_stg_cpt_option_menu_account_font_weight', '400', 'digit', '', '', '', 16);


-- SETTINGS: Protean template Styles Page Menu
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_page'), '_bx_protean_stg_cpt_category_styles_menu_page', 100);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_color'), '_bx_protean_stg_cpt_option_menu_page_bg_color', 'rgba(255, 255, 255, 0.9)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image'), '_bx_protean_stg_cpt_option_menu_page_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image_repeat'), '_bx_protean_stg_cpt_option_menu_page_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image_attachment'), '_bx_protean_stg_cpt_option_menu_page_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image_size'), '_bx_protean_stg_cpt_option_menu_page_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_page_content_padding'), '_bx_protean_stg_cpt_option_menu_page_content_padding', '0.3rem', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_page_border_color'), '_bx_protean_stg_cpt_option_menu_page_border_color', 'rgba(0, 0, 0, 0.1)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_page_border_size'), '_bx_protean_stg_cpt_option_menu_page_border_size', '1px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_menu_page_shadow'), '_bx_protean_stg_cpt_option_menu_page_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_family'), '_bx_protean_stg_cpt_option_menu_page_font_family', 'Arial, sans-serif', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_size'), '_bx_protean_stg_cpt_option_menu_page_font_size', '1rem', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_color'), '_bx_protean_stg_cpt_option_menu_page_font_color', 'rgba(40, 50, 60, 0.8)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_color_hover'), '_bx_protean_stg_cpt_option_menu_page_font_color_hover', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_color_active'), '_bx_protean_stg_cpt_option_menu_page_font_color_active', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_shadow'), '_bx_protean_stg_cpt_option_menu_page_font_shadow', 'none', 'digit', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_menu_page_font_weight'), '_bx_protean_stg_cpt_option_menu_page_font_weight', '400', 'digit', '', '', '', 16);


-- SETTINGS: Protean template Styles Slide Menus
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_menu_slide'), '_bx_protean_stg_cpt_category_styles_menu_slide', 110);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_color'), '_bx_protean_stg_cpt_option_menu_slide_bg_color', 'rgba(255, 255, 255, 0.9)', 'rgba', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image'), '_bx_protean_stg_cpt_option_menu_slide_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image_repeat'), '_bx_protean_stg_cpt_option_menu_slide_bg_image_repeat', 'no-repeat', 'select', 'no-repeat,repeat,repeat-x,repeat-y', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image_attachment'), '_bx_protean_stg_cpt_option_menu_slide_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image_size'), '_bx_protean_stg_cpt_option_menu_slide_bg_image_size', 'cover', 'select', 'auto,cover,contain', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_menu_slide_content_padding'), '_bx_protean_stg_cpt_option_menu_slide_content_padding', '1.25rem', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_menu_slide_border_color'), '_bx_protean_stg_cpt_option_menu_slide_border_color', 'rgba(0, 0, 0, 0.1)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_menu_slide_border_size'), '_bx_protean_stg_cpt_option_menu_slide_border_size', '1px 0px 1px 0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_menu_slide_shadow'), '_bx_protean_stg_cpt_option_menu_slide_shadow', 'none', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_family'), '_bx_protean_stg_cpt_option_menu_slide_font_family', 'Arial, sans-serif', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_size'), '_bx_protean_stg_cpt_option_menu_slide_font_size', '1.0rem', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_color'), '_bx_protean_stg_cpt_option_menu_slide_font_color', 'rgba(40, 50, 60, 0.8)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_color_hover'), '_bx_protean_stg_cpt_option_menu_slide_font_color_hover', 'rgba(40, 50, 60, 0.9)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_color_active'), '_bx_protean_stg_cpt_option_menu_slide_font_color_active', 'rgba(20, 30, 40, 1)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_shadow'), '_bx_protean_stg_cpt_option_menu_slide_font_shadow', 'none', 'digit', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_weight'), '_bx_protean_stg_cpt_option_menu_slide_font_weight', '400', 'digit', '', '', '', 16);


-- SETTINGS: Protean template Styles Forms
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_form'), '_bx_protean_stg_cpt_category_styles_form', 120);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_form_input_height'), '_bx_protean_stg_cpt_option_form_input_height', '2rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_form_input_bg_color'), '_bx_protean_stg_cpt_option_form_input_bg_color', 'rgba(255, 255, 255, 0.8)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_form_input_bg_color_active'), '_bx_protean_stg_cpt_option_form_input_bg_color_active', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_form_input_border_color'), '_bx_protean_stg_cpt_option_form_input_border_color', 'rgba(40, 60, 80, 0.5)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_form_input_border_color_active'), '_bx_protean_stg_cpt_option_form_input_border_color_active', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_form_input_border_size'), '_bx_protean_stg_cpt_option_form_input_border_size', '1px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_form_input_shadow'), '_bx_protean_stg_cpt_option_form_input_shadow', 'inset 0px 0px 2px 1px rgba(0,0,0,0.15)', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_form_input_font_family'), '_bx_protean_stg_cpt_option_form_input_font_family', 'Arial, sans-serif', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_form_input_font_size'), '_bx_protean_stg_cpt_option_form_input_font_size', '1rem', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_form_input_font_color'), '_bx_protean_stg_cpt_option_form_input_font_color', 'rgba(20, 30, 40, 1)', 'rgba', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_form_input_editor_skin'), '_bx_protean_stg_cpt_option_form_input_editor_skin', '', 'select', 'a:2:{s:6:"module";s:10:"bx_protean";s:6:"method";s:34:"get_options_form_input_editor_skin";}', '', '', 11);


-- SETTINGS: Protean template Styles Large Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_large_button'), '_bx_protean_stg_cpt_category_styles_large_button', 130);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_lg_height'), '_bx_protean_stg_cpt_option_button_lg_height', '2.5rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color'), '_bx_protean_stg_cpt_option_button_lg_bg_color', 'rgba(228, 233, 242, 1.0)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color_hover'), '_bx_protean_stg_cpt_option_button_lg_bg_color_hover', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_lg_bg_color_click'), '_bx_protean_stg_cpt_option_button_lg_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color'), '_bx_protean_stg_cpt_option_button_lg_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color_hover'), '_bx_protean_stg_cpt_option_button_lg_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_color_click'), '_bx_protean_stg_cpt_option_button_lg_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_size'), '_bx_protean_stg_cpt_option_button_lg_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_lg_border_radius'), '_bx_protean_stg_cpt_option_button_lg_border_radius', '4px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_lg_shadow'), '_bx_protean_stg_cpt_option_button_lg_shadow', '0px 0px 0px 1px rgba(0,0,0,0.0)', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_family'), '_bx_protean_stg_cpt_option_button_lg_font_family', 'Arial, sans-serif', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_size'), '_bx_protean_stg_cpt_option_button_lg_font_size', '1rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color'), '_bx_protean_stg_cpt_option_button_lg_font_color', 'rgba(46, 56, 86, 1.0)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color_hover'), '_bx_protean_stg_cpt_option_button_lg_font_color_hover', 'rgba(46, 56, 86, 1.0)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_color_click'), '_bx_protean_stg_cpt_option_button_lg_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_shadow'), '_bx_protean_stg_cpt_option_button_lg_font_shadow', 'none', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_lg_font_weight'), '_bx_protean_stg_cpt_option_button_lg_font_weight', '700', 'digit', '', '', '', 17);

-- SETTINGS: Lucid template Styles Large Primary Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_large_button_primary'), '_bx_protean_stg_cpt_category_styles_large_button_primary', 131);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_lgp_height'), '_bx_protean_stg_cpt_option_button_height', '2rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_lgp_bg_color'), '_bx_protean_stg_cpt_option_button_bg_color', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_lgp_bg_color_hover'), '_bx_protean_stg_cpt_option_button_bg_color_hover', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_lgp_bg_color_click'), '_bx_protean_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_color'), '_bx_protean_stg_cpt_option_button_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_color_hover'), '_bx_protean_stg_cpt_option_button_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_color_click'), '_bx_protean_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_size'), '_bx_protean_stg_cpt_option_button_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_lgp_border_radius'), '_bx_protean_stg_cpt_option_button_border_radius', '4px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_lgp_shadow'), '_bx_protean_stg_cpt_option_button_shadow', '0px 0px 0px 1px rgba(0,0,0,0.0)', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_family'), '_bx_protean_stg_cpt_option_button_font_family', 'Arial, sans-serif', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_size'), '_bx_protean_stg_cpt_option_button_font_size', '1rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_color'), '_bx_protean_stg_cpt_option_button_font_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_color_hover'), '_bx_protean_stg_cpt_option_button_font_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_color_click'), '_bx_protean_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_shadow'), '_bx_protean_stg_cpt_option_button_font_shadow', 'none', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_lgp_font_weight'), '_bx_protean_stg_cpt_option_button_font_weight', '700', 'digit', '', '', '', 17);

-- SETTINGS: Protean template Styles Normal Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_button'), '_bx_protean_stg_cpt_category_styles_button', 135);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_nl_height'), '_bx_protean_stg_cpt_option_button_height', '2rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_nl_bg_color'), '_bx_protean_stg_cpt_option_button_bg_color', 'rgba(228, 233, 242, 1.0)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_nl_bg_color_hover'), '_bx_protean_stg_cpt_option_button_bg_color_hover', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_nl_bg_color_click'), '_bx_protean_stg_cpt_option_button_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_color'), '_bx_protean_stg_cpt_option_button_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_color_hover'), '_bx_protean_stg_cpt_option_button_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_color_click'), '_bx_protean_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_size'), '_bx_protean_stg_cpt_option_button_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_nl_border_radius'), '_bx_protean_stg_cpt_option_button_border_radius', '4px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_nl_shadow'), '_bx_protean_stg_cpt_option_button_shadow', '0px 0px 0px 1px rgba(0,0,0,0.0)', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_family'), '_bx_protean_stg_cpt_option_button_font_family', 'Arial, sans-serif', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_size'), '_bx_protean_stg_cpt_option_button_font_size', '0.75rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_color'), '_bx_protean_stg_cpt_option_button_font_color', 'rgba(46, 56, 86, 1.0)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_color_hover'), '_bx_protean_stg_cpt_option_button_font_color_hover', 'rgba(46, 56, 86, 1.0)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_color_click'), '_bx_protean_stg_cpt_option_button_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_shadow'), '_bx_protean_stg_cpt_option_button_font_shadow', 'none', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_nl_font_weight'), '_bx_protean_stg_cpt_option_button_font_weight', '600', 'digit', '', '', '', 17);

-- SETTINGS: Lucid template Styles Large Primary Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_button_primary'), '_bx_protean_stg_cpt_category_styles_button_primary', 136);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_nlp_height'), '_bx_protean_stg_cpt_option_button_height', '2rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_nlp_bg_color'), '_bx_protean_stg_cpt_option_button_bg_color', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_nlp_bg_color_hover'), '_bx_protean_stg_cpt_option_button_bg_color_hover', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_nlp_bg_color_click'), '_bx_protean_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_color'), '_bx_protean_stg_cpt_option_button_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_color_hover'), '_bx_protean_stg_cpt_option_button_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_color_click'), '_bx_protean_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_size'), '_bx_protean_stg_cpt_option_button_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_nlp_border_radius'), '_bx_protean_stg_cpt_option_button_border_radius', '4px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_nlp_shadow'), '_bx_protean_stg_cpt_option_button_shadow', '0px 0px 0px 1px rgba(0,0,0,0.0)', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_family'), '_bx_protean_stg_cpt_option_button_font_family', 'Arial, sans-serif', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_size'), '_bx_protean_stg_cpt_option_button_font_size', '1rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_color'), '_bx_protean_stg_cpt_option_button_font_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_color_hover'), '_bx_protean_stg_cpt_option_button_font_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_color_click'), '_bx_protean_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_shadow'), '_bx_protean_stg_cpt_option_button_font_shadow', 'none', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_nlp_font_weight'), '_bx_protean_stg_cpt_option_button_font_weight', '700', 'digit', '', '', '', 17);

-- SETTINGS: Protean template Styles Small Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_small_button'), '_bx_protean_stg_cpt_category_styles_small_button', 140);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_sm_height'), '_bx_protean_stg_cpt_option_button_sm_height', '1.75rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color'), '_bx_protean_stg_cpt_option_button_sm_bg_color', 'rgba(228, 233, 242, 1.0)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color_hover'), '_bx_protean_stg_cpt_option_button_sm_bg_color_hover', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_sm_bg_color_click'), '_bx_protean_stg_cpt_option_button_sm_bg_color_click', 'rgba(197, 206, 224, 1)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color'), '_bx_protean_stg_cpt_option_button_sm_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color_hover'), '_bx_protean_stg_cpt_option_button_sm_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_color_click'), '_bx_protean_stg_cpt_option_button_sm_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_size'), '_bx_protean_stg_cpt_option_button_sm_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_sm_border_radius'), '_bx_protean_stg_cpt_option_button_sm_border_radius', '4px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_sm_shadow'), '_bx_protean_stg_cpt_option_button_sm_shadow', '0px 0px 0px 1px rgba(0,0,0,0)', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_family'), '_bx_protean_stg_cpt_option_button_sm_font_family', 'Arial, sans-serif', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_size'), '_bx_protean_stg_cpt_option_button_sm_font_size', '0.75rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color'), '_bx_protean_stg_cpt_option_button_sm_font_color', 'rgba(46, 56, 86, 1.0)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color_hover'), '_bx_protean_stg_cpt_option_button_sm_font_color_hover', 'rgba(46, 56, 86, 1.0)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_color_click'), '_bx_protean_stg_cpt_option_button_sm_font_color_click', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_shadow'), '_bx_protean_stg_cpt_option_button_sm_font_shadow', 'none', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_sm_font_weight'), '_bx_protean_stg_cpt_option_button_sm_font_weight', '400', 'digit', '', '', '', 17);

-- SETTINGS: Lucid template Styles Small Primary Buttons
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_small_button_primary'), '_bx_protean_stg_cpt_category_styles_small_button_primary', 141);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_button_smp_height'), '_bx_protean_stg_cpt_option_button_height', '1.75rem', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_button_smp_bg_color'), '_bx_protean_stg_cpt_option_button_bg_color', 'rgba(51, 102, 255, 1.0)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_button_smp_bg_color_hover'), '_bx_protean_stg_cpt_option_button_bg_color_hover', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_button_smp_bg_color_click'), '_bx_protean_stg_cpt_option_button_bg_color_click', 'rgba(0, 87, 194, 1.0)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_color'), '_bx_protean_stg_cpt_option_button_border_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_color_hover'), '_bx_protean_stg_cpt_option_button_border_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_color_click'), '_bx_protean_stg_cpt_option_button_border_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_size'), '_bx_protean_stg_cpt_option_button_border_size', '0px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_button_smp_border_radius'), '_bx_protean_stg_cpt_option_button_border_radius', '4px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_button_smp_shadow'), '_bx_protean_stg_cpt_option_button_shadow', '0px 0px 0px 1px rgba(0,0,0,0)', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_family'), '_bx_protean_stg_cpt_option_button_font_family', 'Arial, sans-serif', 'digit', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_size'), '_bx_protean_stg_cpt_option_button_font_size', '0.75rem', 'digit', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_color'), '_bx_protean_stg_cpt_option_button_font_color', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_color_hover'), '_bx_protean_stg_cpt_option_button_font_color_hover', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_color_click'), '_bx_protean_stg_cpt_option_button_font_color_click', 'rgba(255, 255, 255, 1.0)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_shadow'), '_bx_protean_stg_cpt_option_button_font_shadow', 'none', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_button_smp_font_weight'), '_bx_protean_stg_cpt_option_button_font_weight', '400', 'digit', '', '', '', 17);

-- SETTINGS: Protean template Styles Font
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_font'), '_bx_protean_stg_cpt_category_styles_font', 150);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_font_family'), '_bx_protean_stg_cpt_option_font_family', 'Arial, sans-serif', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_font_size_default'), '_bx_protean_stg_cpt_option_font_size_default', '16px', 'digit', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_font_color_default'), '_bx_protean_stg_cpt_option_font_color_default', 'rgba(40, 50, 60, 0.9)', 'rgba', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed'), '_bx_protean_stg_cpt_option_font_color_grayed', 'rgba(40, 50, 60, 0.5)', 'rgba', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted'), '_bx_protean_stg_cpt_option_font_color_contrasted', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 5),
(@iCategoryId, CONCAT(@sName, '_font_size_small'), '_bx_protean_stg_cpt_option_font_size_small', '12px', 'digit', '', '', '', 6),
(@iCategoryId, CONCAT(@sName, '_font_size_middle'), '_bx_protean_stg_cpt_option_font_size_middle', '14px', 'digit', '', '', '', 7),
(@iCategoryId, CONCAT(@sName, '_font_size_large'), '_bx_protean_stg_cpt_option_font_size_large', '20px', 'digit', '', '', '', 8),
(@iCategoryId, CONCAT(@sName, '_font_size_h1'), '_bx_protean_stg_cpt_option_font_size_h1', '32px', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_font_weight_h1'), '_bx_protean_stg_cpt_option_font_weight_h1', '700', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_font_color_default_h1'), '_bx_protean_stg_cpt_option_font_color_default_h1', 'rgba(40, 50, 60, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed_h1'), '_bx_protean_stg_cpt_option_font_color_grayed_h1', 'rgba(40, 50, 60, 0.5)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted_h1'), '_bx_protean_stg_cpt_option_font_color_contrasted_h1', 'rgba(255, 255, 255, 0.9)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h1'), '_bx_protean_stg_cpt_option_font_color_link_h1', 'rgba(30, 150, 250, 0.9)', 'rgba', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h1_hover'), '_bx_protean_stg_cpt_option_font_color_link_h1_hover', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 15),
(@iCategoryId, CONCAT(@sName, '_font_size_h2'), '_bx_protean_stg_cpt_option_font_size_h2', '28px', 'digit', '', '', '', 16),
(@iCategoryId, CONCAT(@sName, '_font_weight_h2'), '_bx_protean_stg_cpt_option_font_weight_h2', '700', 'digit', '', '', '', 17),
(@iCategoryId, CONCAT(@sName, '_font_color_default_h2'), '_bx_protean_stg_cpt_option_font_color_default_h2', 'rgba(40, 60, 80, 0.9)', 'rgba', '', '', '', 18),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed_h2'), '_bx_protean_stg_cpt_option_font_color_grayed_h2', 'rgba(40, 60, 80, 0.5)', 'rgba', '', '', '', 19),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted_h2'), '_bx_protean_stg_cpt_option_font_color_contrasted_h2', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 20),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h2'), '_bx_protean_stg_cpt_option_font_color_link_h2', 'rgba(30, 150, 250, 0.9)', 'rgba', '', '', '', 21),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h2_hover'), '_bx_protean_stg_cpt_option_font_color_link_h2_hover', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 22),
(@iCategoryId, CONCAT(@sName, '_font_size_h3'), '_bx_protean_stg_cpt_option_font_size_h3', '24px', 'digit', '', '', '', 23),
(@iCategoryId, CONCAT(@sName, '_font_weight_h3'), '_bx_protean_stg_cpt_option_font_weight_h3', '500', 'digit', '', '', '', 24),
(@iCategoryId, CONCAT(@sName, '_font_color_default_h3'), '_bx_protean_stg_cpt_option_font_color_default_h3', 'rgba(40, 60, 80, 0.9)', 'rgba', '', '', '', 25),
(@iCategoryId, CONCAT(@sName, '_font_color_grayed_h3'), '_bx_protean_stg_cpt_option_font_color_grayed_h3', 'rgba(40, 60, 80, 0.5)', 'rgba', '', '', '', 26),
(@iCategoryId, CONCAT(@sName, '_font_color_contrasted_h3'), '_bx_protean_stg_cpt_option_font_color_contrasted_h3', 'rgba(255, 255, 255, 1)', 'rgba', '', '', '', 27),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h3'), '_bx_protean_stg_cpt_option_font_color_link_h3', 'rgba(30, 150, 250, 0.9)', 'rgba', '', '', '', 28),
(@iCategoryId, CONCAT(@sName, '_font_color_link_h3_hover'), '_bx_protean_stg_cpt_option_font_color_link_h3_hover', 'rgba(30, 150, 250, 1)', 'rgba', '', '', '', 29);

-- SETTINGS: Protean template Custom Styles
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_custom'), '_bx_protean_stg_cpt_category_styles_custom', 160);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_styles_custom'), '_bx_protean_stg_cpt_option_styles_custom', 'div.bx-market-unit-cover div.bx-base-text-unit-no-thumb {\r\n border-width: 0px;\r\n}', 'text', '', '', '', 1);

-- SETTINGS: Protean template Viewport Tablet
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_viewport_tablet'), '_bx_protean_stg_cpt_category_viewport_tablet', 170);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_vpt_font_size_scale'), '_bx_protean_stg_cpt_option_vpt_font_size_scale', '100%', 'digit', '', '', '', 1);

-- SETTINGS: Protean template Viewport Mobile
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_viewport_mobile'), '_bx_protean_stg_cpt_category_viewport_mobile', 180);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_vpm_font_size_scale'), '_bx_protean_stg_cpt_option_vpm_font_size_scale', '87.5%', 'digit', '', '', '', 1);


-- MIXES
-- Neat Mix
INSERT INTO `sys_options_mixes` (`type`, `category`, `name`, `title`, `active`, `published`, `editable`) VALUES
(@sName, '', 'Neat-Mix', 'Neat Mix', 0, 0, 0);
SET @iMixId = LAST_INSERT_ID();

INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_menu_slide_shadow', @iMixId, 'none'),
('bx_protean_menu_slide_border_size', @iMixId, '1px 0px 1px 0px'),
('bx_protean_menu_slide_border_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_menu_slide_content_padding', @iMixId, '1.25rem'),
('bx_protean_menu_slide_bg_image', @iMixId, '0'),
('bx_protean_menu_slide_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_popup_menu_shadow', @iMixId, 'none'),
('bx_protean_popup_title_font_color', @iMixId, 'rgba(255, 255, 255, 1)'),
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
('bx_protean_page_width', @iMixId, '1280'),
('bx_protean_block_bg_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_block_bg_image', @iMixId, '0'),
('bx_protean_block_content_padding', @iMixId, '1rem'),
('bx_protean_block_border_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_block_border_size', @iMixId, '1px'),
('bx_protean_block_border_radius', @iMixId, '3px'),
('bx_protean_block_shadow', @iMixId, 'none'),
('bx_protean_block_title_padding', @iMixId, '0.75rem 1.0rem 0.75rem 1.0rem'),
('bx_protean_block_title_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_block_title_border_size', @iMixId, '0px'),
('bx_protean_block_title_border_radius', @iMixId, '0px'),
('bx_protean_block_title_font_family', @iMixId, 'Arial, sans-serif'),
('bx_protean_block_title_font_size', @iMixId, '1.25rem'),
('bx_protean_block_title_font_color', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_block_title_div_height', @iMixId, '1px'),
('bx_protean_block_title_div_bg_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_card_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_card_bg_color_hover', @iMixId, 'rgba(255, 255, 255, 0.5)'),
('bx_protean_card_bg_image', @iMixId, '0'),
('bx_protean_card_content_padding', @iMixId, '1rem'),
('bx_protean_card_border_color', @iMixId, 'rgba(40, 60, 80, 0.1)'),
('bx_protean_card_border_size', @iMixId, '1px'),
('bx_protean_card_border_radius', @iMixId, '3px'),
('bx_protean_card_shadow', @iMixId, 'none'),
('bx_protean_font_family', @iMixId, 'Arial, sans-serif'),
('bx_protean_font_size_default', @iMixId, '16px'),
('bx_protean_font_color_default', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_size_small', @iMixId, '12px'),
('bx_protean_font_size_middle', @iMixId, '14px'),
('bx_protean_font_color_middle', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_size_large', @iMixId, '20px'),
('bx_protean_font_color_large', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_size_h1', @iMixId, '32px'),
('bx_protean_font_size_h2', @iMixId, '28px'),
('bx_protean_font_size_h3', @iMixId, '24px'),
('bx_protean_font_color_grayed', @iMixId, 'rgba(40, 60, 80, 0.5)'),
('bx_protean_font_color_contrasted', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_vpt_font_size_scale', @iMixId, '100%'),
('bx_protean_vpm_font_size_scale', @iMixId, '87.5%'),
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
('bx_protean_menu_main_font_size', @iMixId, '0.875rem'),
('bx_protean_menu_main_font_color', @iMixId, 'rgba(62, 134, 133, 1)'),
('bx_protean_menu_main_font_color_hover', @iMixId, 'rgba(62, 134, 133, 1)'),
('bx_protean_menu_main_font_color_active', @iMixId, 'rgba(0, 0, 0, 1)'),
('bx_protean_menu_main_font_shadow', @iMixId, 'none'),
('bx_protean_menu_main_font_weight', @iMixId, '400'),
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
('bx_protean_menu_page_font_family', @iMixId, '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif'),
('bx_protean_menu_page_font_size', @iMixId, '1.0rem'),
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
('bx_protean_block_title_font_weight', @iMixId, '700'),
('bx_protean_popup_title_bg_color', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_weight_h1', @iMixId, '700'),
('bx_protean_font_weight_h2', @iMixId, '700'),
('bx_protean_font_weight_h3', @iMixId, '500'),
('bx_protean_styles_custom', @iMixId, 'div.bx-market-unit-cover div.bx-base-text-unit-no-thumb {\r\n border-width: 0px;\r\n}');


-- Light Mix
INSERT INTO `sys_options_mixes` (`type`, `category`, `name`, `title`, `active`, `published`, `editable`) VALUES
(@sName, '', 'Protean-Light-Mix', 'Light Mix', 1, 1, 0);
SET @iMixId = LAST_INSERT_ID();

UPDATE `sys_options` SET `value`=@iMixId WHERE `name`=CONCAT(@sName, '_default_mix');

INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_block_bg_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_block_bg_image', @iMixId, '0'),
('bx_protean_block_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_block_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_block_bg_image_size', @iMixId, 'cover'),
('bx_protean_block_border_color', @iMixId, 'rgba(0, 0, 0, 0.1)'),
('bx_protean_block_border_radius', @iMixId, '5px'),
('bx_protean_block_border_size', @iMixId, '1px'),
('bx_protean_block_content_padding', @iMixId, '1rem'),
('bx_protean_block_shadow', @iMixId, '0px 2px 12px 0px rgba(0, 0, 0, 0.05)'),
('bx_protean_block_title_bg_color', @iMixId, 'rgba(252, 253, 254, 0)'),
('bx_protean_block_title_border_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_block_title_border_radius', @iMixId, '4px 4px 0px 0px'),
('bx_protean_block_title_border_size', @iMixId, '0px'),
('bx_protean_block_title_div_bg_color', @iMixId, 'rgba(255, 255, 255, 0)'),
('bx_protean_block_title_div_height', @iMixId, '0px'),
('bx_protean_block_title_font_color', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_block_title_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_block_title_font_size', @iMixId, '1.25rem'),
('bx_protean_block_title_font_weight', @iMixId, '700'),
('bx_protean_block_title_padding', @iMixId, '0.75rem 1.0rem 0.75rem 1.0rem'),
('bx_protean_body_bg_color', @iMixId, 'rgb(248, 252, 255)'),
('bx_protean_body_bg_image', @iMixId, '0'),
('bx_protean_body_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_body_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_body_bg_image_size', @iMixId, 'cover'),
('bx_protean_body_icon_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_body_icon_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_body_link_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_body_link_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_card_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_card_bg_color_hover', @iMixId, 'rgba(255, 255, 255, 0.8)'),
('bx_protean_card_bg_image', @iMixId, '0'),
('bx_protean_card_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_card_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_card_bg_image_size', @iMixId, 'cover'),
('bx_protean_card_border_color', @iMixId, 'rgba(0, 0, 0, 0.1)'),
('bx_protean_card_border_radius', @iMixId, '5px'),
('bx_protean_card_border_size', @iMixId, '1px'),
('bx_protean_card_content_padding', @iMixId, '1rem'),
('bx_protean_card_shadow', @iMixId, '0px 1px 4px 0px rgba(0, 0, 0, 0.05)'),
('bx_protean_cover_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_cover_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_cover_border_radius', @iMixId, '0px'),
('bx_protean_cover_border_size', @iMixId, '0px 1px 0px 1px'),
('bx_protean_cover_content_padding', @iMixId, '2rem 3rem 2rem 3rem'),
('bx_protean_cover_font_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_cover_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_cover_font_size', @iMixId, '2.0rem'),
('bx_protean_cover_font_weight', @iMixId, '700'),
('bx_protean_cover_height', @iMixId, '30vh'),
('bx_protean_cover_icon_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_cover_icon_border_radius', @iMixId, '3px'),
('bx_protean_cover_icon_border_size', @iMixId, '1px'),
('bx_protean_cover_icon_shadow', @iMixId, 'none'),
('bx_protean_cover_shadow', @iMixId, '0px 0px 3px 0px rgba(51, 68, 85, 0.1)'),
('bx_protean_cover_text_align', @iMixId, 'center'),
('bx_protean_cover_text_shadow', @iMixId, '0px 1px 3px rgba(0, 0, 0, .3)'),
('bx_protean_font_color_contrasted', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_font_color_contrasted_h1', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_font_color_contrasted_h2', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_font_color_contrasted_h3', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_font_color_default', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_font_color_default_h1', @iMixId, 'rgba(17, 34, 51, 1)'),
('bx_protean_font_color_default_h2', @iMixId, 'rgba(17, 34, 51, 1)'),
('bx_protean_font_color_default_h3', @iMixId, 'rgba(17, 34, 51, 1)'),
('bx_protean_font_color_grayed', @iMixId, 'rgba(51, 68, 85, 0.5)'),
('bx_protean_font_color_grayed_h1', @iMixId, 'rgba(17, 34, 51, 0.5)'),
('bx_protean_font_color_grayed_h2', @iMixId, 'rgba(17, 34, 51, 0.5)'),
('bx_protean_font_color_grayed_h3', @iMixId, 'rgba(17, 34, 51, 0.5)'),
('bx_protean_font_color_large', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_color_link_h1', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_font_color_link_h1_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_font_color_link_h2', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_font_color_link_h2_hover', @iMixId, 'rgba(68, 136, 255, 0.5)'),
('bx_protean_font_color_link_h3', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_font_color_link_h3_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_font_color_middle', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_font_size_default', @iMixId, '16px'),
('bx_protean_font_size_h1', @iMixId, '32px'),
('bx_protean_font_size_h2', @iMixId, '28px'),
('bx_protean_font_size_h3', @iMixId, '24px'),
('bx_protean_font_size_large', @iMixId, '20px'),
('bx_protean_font_size_middle', @iMixId, '14px'),
('bx_protean_font_size_small', @iMixId, '12px'),
('bx_protean_font_weight_h1', @iMixId, '700'),
('bx_protean_font_weight_h2', @iMixId, '700'),
('bx_protean_font_weight_h3', @iMixId, '700'),
('bx_protean_footer_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_footer_bg_image', @iMixId, '0'),
('bx_protean_footer_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_footer_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_footer_bg_image_size', @iMixId, 'cover'),
('bx_protean_footer_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_footer_border_size', @iMixId, '1px'),
('bx_protean_footer_content_padding', @iMixId, '1rem'),
('bx_protean_footer_font_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_footer_icon_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_footer_icon_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_footer_link_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_footer_link_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_footer_shadow', @iMixId, 'none'),
('bx_protean_form_input_bg_color', @iMixId, 'rgba(245, 250, 255, 0.5)'),
('bx_protean_form_input_bg_color_active', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_form_input_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_form_input_border_color_active', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_form_input_border_size', @iMixId, '1px'),
('bx_protean_form_input_font_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_form_input_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_form_input_font_size', @iMixId, '1rem'),
('bx_protean_form_input_height', @iMixId, '2.5rem'),
('bx_protean_form_input_shadow', @iMixId, 'inset 0px 0px 2px 1px rgba(0, 0, 0, 0.01)'),
('bx_protean_form_input_editor_skin', @iMixId, 'gray'),
('bx_protean_general_item_bg_color_active', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_general_item_bg_color_disabled', @iMixId, 'rgba(0, 0, 0, 0.05)'),
('bx_protean_general_item_bg_color_hover', @iMixId, 'rgba(0, 0, 0, 0.03)'),
('bx_protean_header_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_header_bg_image', @iMixId, '0'),
('bx_protean_header_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_header_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_header_bg_image_size', @iMixId, 'cover'),
('bx_protean_header_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_header_border_size', @iMixId, '1px'),
('bx_protean_header_content_padding', @iMixId, '0.5rem 0px 0.5rem 0px'),
('bx_protean_header_height', @iMixId, '4rem'),
('bx_protean_header_icon_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_header_icon_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_header_link_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_header_link_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_header_shadow', @iMixId, 'none'),
('bx_protean_menu_account_bg_color', @iMixId, 'rgba(255, 255, 255, 0.95)'),
('bx_protean_menu_account_bg_image', @iMixId, '0'),
('bx_protean_menu_account_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_menu_account_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_account_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_account_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_menu_account_border_size', @iMixId, '0px'),
('bx_protean_menu_account_content_padding', @iMixId, '0px'),
('bx_protean_menu_account_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_menu_account_font_color_active', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_account_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_menu_account_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_menu_account_font_shadow', @iMixId, 'none'),
('bx_protean_menu_account_font_size', @iMixId, '1.0rem'),
('bx_protean_menu_account_font_weight', @iMixId, '400'),
('bx_protean_menu_account_shadow', @iMixId, 'none'),
('bx_protean_menu_main_bg_color', @iMixId, 'rgba(255, 255, 255, 0.9)'),
('bx_protean_menu_main_bg_image', @iMixId, '0'),
('bx_protean_menu_main_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_menu_main_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_main_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_main_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_menu_main_border_size', @iMixId, '0px'),
('bx_protean_menu_main_content_padding', @iMixId, '0px'),
('bx_protean_menu_main_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_menu_main_font_color_active', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_main_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_menu_main_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_menu_main_font_shadow', @iMixId, 'none'),
('bx_protean_menu_main_font_size', @iMixId, '0.875rem'),
('bx_protean_menu_main_font_weight', @iMixId, '400'),
('bx_protean_menu_main_shadow', @iMixId, 'none'),
('bx_protean_menu_page_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_menu_page_bg_image', @iMixId, '0'),
('bx_protean_menu_page_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_menu_page_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_page_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_page_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_menu_page_border_size', @iMixId, '1px'),
('bx_protean_menu_page_content_padding', @iMixId, '0px'),
('bx_protean_menu_page_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_menu_page_font_color_active', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_page_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_menu_page_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_menu_page_font_shadow', @iMixId, 'none'),
('bx_protean_menu_page_font_size', @iMixId, '1.0rem'),
('bx_protean_menu_page_font_weight', @iMixId, '400'),
('bx_protean_menu_page_shadow', @iMixId, '0px 0px 3px 0px rgba(51, 68, 85, 0.1)'),
('bx_protean_menu_slide_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_menu_slide_bg_image', @iMixId, '0'),
('bx_protean_menu_slide_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_menu_slide_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_slide_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_slide_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_menu_slide_border_size', @iMixId, '1px 0px 1px 0px'),
('bx_protean_menu_slide_content_padding', @iMixId, '1.25rem'),
('bx_protean_menu_slide_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_menu_slide_font_color_active', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_slide_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_menu_slide_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_menu_slide_font_shadow', @iMixId, 'none'),
('bx_protean_menu_slide_font_size', @iMixId, '1.0rem'),
('bx_protean_menu_slide_font_weight', @iMixId, '400'),
('bx_protean_menu_slide_shadow', @iMixId, 'none'),
('bx_protean_page_width', @iMixId, '1280'),
('bx_protean_popup_bg_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_popup_bg_image', @iMixId, '0'),
('bx_protean_popup_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_popup_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_popup_bg_image_size', @iMixId, 'cover'),
('bx_protean_popup_border_color', @iMixId, 'rgba(119, 136, 153, 0.3)'),
('bx_protean_popup_border_radius', @iMixId, '5px'),
('bx_protean_popup_border_size', @iMixId, '1px'),
('bx_protean_popup_content_padding', @iMixId, '1.25rem'),
('bx_protean_popup_menu_shadow', @iMixId, 'none'),
('bx_protean_popup_shadow', @iMixId, '0px 0px 3px 0px rgba(51, 68, 85, 0.1)'),
('bx_protean_popup_title_bg_color', @iMixId, 'rgba(40, 60, 80, 0.9)'),
('bx_protean_popup_title_font_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_popup_title_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_popup_title_font_size', @iMixId, '1.5rem'),
('bx_protean_popup_title_padding', @iMixId, '1.25rem'),
('bx_protean_site_logo', @iMixId, '0'),
('bx_protean_site_logo_alt', @iMixId, ''),
('bx_protean_styles_custom', @iMixId, 'div.bx-base-pofile-cover {\r\nborder: 1px solid rgba(136, 153, 170, 0.3);\r\nborder-bottom-width: 0;\r\n}\r\n\r\n\r\ndiv.bx-market-unit-cover div.bx-base-text-unit-no-thumb {\r\nborder-width: 0px;\r\n}\r\n\r\n\r\n.bx-def-font {\r\nline-height:18px;\r\nline-height:1.125rem;\r\n}\r\nh1,\r\n.bx-def-font-h1 {\r\nline-height:36px;\r\nline-height:2.25rem;\r\n}\r\nh2,\r\n.bx-def-font-h2 {\r\nline-height:30px;\r\nline-height:1.875rem;\r\n}\r\nh3,\r\n.bx-def-font-h3 {\r\nline-height:26px;\r\nline-height:1.625rem;\r\n}\r\n'),
('bx_protean_vpm_font_size_scale', @iMixId, '87.5%'),
('bx_protean_vpt_font_size_scale', @iMixId, '100%');


-- Dark Mix
INSERT INTO `sys_options_mixes` (`type`, `category`, `name`, `title`, `dark`, `active`, `published`, `editable`) VALUES
(@sName, '', 'Protean-Dark-Mix', 'Dark Mix', 1, 0, 1, 0);
SET @iMixId = LAST_INSERT_ID();

INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_block_bg_color', @iMixId, 'rgba(34, 51, 68, 1)'),
('bx_protean_block_bg_image', @iMixId, '0'),
('bx_protean_block_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_block_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_block_bg_image_size', @iMixId, 'cover'),
('bx_protean_block_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_block_border_radius', @iMixId, '5px'),
('bx_protean_block_border_size', @iMixId, '1px'),
('bx_protean_block_content_padding', @iMixId, '1rem'),
('bx_protean_block_shadow', @iMixId, '0px 0px 3px 0px rgba(51, 68, 85, 0.1)'),
('bx_protean_block_title_bg_color', @iMixId, 'rgba(255, 255, 255, 0)'),
('bx_protean_block_title_border_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_block_title_border_radius', @iMixId, '4px 4px 0px 0px'),
('bx_protean_block_title_border_size', @iMixId, '0px'),
('bx_protean_block_title_div_bg_color', @iMixId, 'rgba(255, 255, 255, 0)'),
('bx_protean_block_title_div_height', @iMixId, '0px'),
('bx_protean_block_title_font_color', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_block_title_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_block_title_font_size', @iMixId, '1.25rem'),
('bx_protean_block_title_font_weight', @iMixId, '700'),
('bx_protean_block_title_padding', @iMixId, '0.75rem 1.0rem 0.75rem 1.0rem'),
('bx_protean_body_bg_color', @iMixId, 'rgb(17, 34, 51)'),
('bx_protean_body_bg_image', @iMixId, '0'),
('bx_protean_body_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_body_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_body_bg_image_size', @iMixId, 'cover'),
('bx_protean_body_icon_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_body_icon_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_body_link_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_body_link_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_lg_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_lg_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_lg_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_lg_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_lg_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_lg_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_lg_border_radius', @iMixId, '4px'),
('bx_protean_button_lg_border_size', @iMixId, '0px'),
('bx_protean_button_lg_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_lg_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_lg_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_lg_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_lg_font_shadow', @iMixId, 'none'),
('bx_protean_button_lg_font_size', @iMixId, '1rem'),
('bx_protean_button_lg_font_weight', @iMixId, '400'),
('bx_protean_button_lg_height', @iMixId, '2.5rem'),
('bx_protean_button_lg_shadow', @iMixId, 'none'),
('bx_protean_button_lgp_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_lgp_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_lgp_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_lgp_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_lgp_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_lgp_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_lgp_border_radius', @iMixId, '4px'),
('bx_protean_button_lgp_border_size', @iMixId, '0px'),
('bx_protean_button_lgp_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_lgp_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_lgp_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_lgp_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_lgp_font_shadow', @iMixId, 'none'),
('bx_protean_button_lgp_font_size', @iMixId, '1rem'),
('bx_protean_button_lgp_font_weight', @iMixId, '400'),
('bx_protean_button_lgp_height', @iMixId, '2.5rem'),
('bx_protean_button_lgp_shadow', @iMixId, 'none'),
('bx_protean_button_nl_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_nl_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_nl_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_nl_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_nl_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_nl_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_nl_border_radius', @iMixId, '4px'),
('bx_protean_button_nl_border_size', @iMixId, '0px'),
('bx_protean_button_nl_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_nl_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_nl_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_nl_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_nl_font_shadow', @iMixId, 'none'),
('bx_protean_button_nl_font_size', @iMixId, '0.75rem'),
('bx_protean_button_nl_font_weight', @iMixId, '600'),
('bx_protean_button_nl_height', @iMixId, '2.0rem'),
('bx_protean_button_nl_shadow', @iMixId, 'none'),
('bx_protean_button_nlp_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_nlp_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_nlp_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_nlp_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_nlp_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_nlp_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_nlp_border_radius', @iMixId, '4px'),
('bx_protean_button_nlp_border_size', @iMixId, '0px'),
('bx_protean_button_nlp_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_nlp_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_nlp_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_nlp_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_nlp_font_shadow', @iMixId, 'none'),
('bx_protean_button_nlp_font_size', @iMixId, '0.75rem'),
('bx_protean_button_nlp_font_weight', @iMixId, '600'),
('bx_protean_button_nlp_height', @iMixId, '2.0rem'),
('bx_protean_button_nlp_shadow', @iMixId, 'none'),
('bx_protean_button_sm_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_sm_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_sm_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_sm_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_sm_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_sm_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_sm_border_radius', @iMixId, '4px'),
('bx_protean_button_sm_border_size', @iMixId, '0px'),
('bx_protean_button_sm_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_sm_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_sm_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_sm_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_sm_font_shadow', @iMixId, 'none'),
('bx_protean_button_sm_font_size', @iMixId, '0.75rem'),
('bx_protean_button_sm_font_weight', @iMixId, '400'),
('bx_protean_button_sm_height', @iMixId, '1.75rem'),
('bx_protean_button_sm_shadow', @iMixId, 'none'),
('bx_protean_button_smp_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_button_smp_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_smp_bg_color_click', @iMixId, 'rgba(51, 68, 85, 0.8)'),
('bx_protean_button_smp_border_color', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_button_smp_border_color_hover', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_smp_border_color_click', @iMixId, 'rgba(255, 255, 255, 0.2)'),
('bx_protean_button_smp_border_radius', @iMixId, '4px'),
('bx_protean_button_smp_border_size', @iMixId, '0px'),
('bx_protean_button_smp_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_button_smp_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_smp_font_color_click', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_button_smp_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_button_smp_font_shadow', @iMixId, 'none'),
('bx_protean_button_smp_font_size', @iMixId, '0.75rem'),
('bx_protean_button_smp_font_weight', @iMixId, '400'),
('bx_protean_button_smp_height', @iMixId, '1.75rem'),
('bx_protean_button_smp_shadow', @iMixId, 'none'),
('bx_protean_card_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_card_bg_color_hover', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_card_bg_image', @iMixId, '0'),
('bx_protean_card_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_card_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_card_bg_image_size', @iMixId, 'cover'),
('bx_protean_card_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_card_border_radius', @iMixId, '5px'),
('bx_protean_card_border_size', @iMixId, '1px'),
('bx_protean_card_content_padding', @iMixId, '1rem'),
('bx_protean_card_shadow', @iMixId, '0px 0px 3px 0px rgba(51, 68, 85, 0.1)'),
('bx_protean_cover_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_cover_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_cover_border_radius', @iMixId, '0px'),
('bx_protean_cover_border_size', @iMixId, '0px 1px 0px 1px'),
('bx_protean_cover_content_padding', @iMixId, '2rem 3rem 2rem 3rem'),
('bx_protean_cover_font_color', @iMixId, 'rgba(255, 255, 255, 1)'),
('bx_protean_cover_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_cover_font_size', @iMixId, '2.0rem'),
('bx_protean_cover_font_weight', @iMixId, '700'),
('bx_protean_cover_height', @iMixId, '30vh'),
('bx_protean_cover_icon_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_cover_icon_border_radius', @iMixId, '3px'),
('bx_protean_cover_icon_border_size', @iMixId, '1px'),
('bx_protean_cover_icon_shadow', @iMixId, 'none'),
('bx_protean_cover_shadow', @iMixId, '0px 0px 3px 0px rgba(51, 68, 85, 0.1)'),
('bx_protean_cover_text_align', @iMixId, 'center'),
('bx_protean_cover_text_shadow', @iMixId, '0px 1px 3px rgba(0, 0, 0, .3)'),
('bx_protean_font_color_contrasted', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_font_color_contrasted_h1', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_font_color_contrasted_h2', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_font_color_contrasted_h3', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_font_color_default', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_font_color_default_h1', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_font_color_default_h2', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_font_color_default_h3', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_font_color_grayed', @iMixId, 'rgba(243, 244, 245, 0.5)'),
('bx_protean_font_color_grayed_h1', @iMixId, 'rgba(243, 244, 245, 0.5)'),
('bx_protean_font_color_grayed_h2', @iMixId, 'rgba(243, 244, 245, 0.5)'),
('bx_protean_font_color_grayed_h3', @iMixId, 'rgba(243, 244, 245, 0.5)'),
('bx_protean_font_color_link_h1', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_font_color_link_h1_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_font_color_link_h2', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_font_color_link_h2_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_font_color_link_h3', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_font_color_link_h3_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_font_size_default', @iMixId, '16px'),
('bx_protean_font_size_h1', @iMixId, '32px'),
('bx_protean_font_size_h2', @iMixId, '28px'),
('bx_protean_font_size_h3', @iMixId, '24px'),
('bx_protean_font_size_large', @iMixId, '20px'),
('bx_protean_font_size_middle', @iMixId, '14px'),
('bx_protean_font_size_small', @iMixId, '12px'),
('bx_protean_font_weight_h1', @iMixId, '700'),
('bx_protean_font_weight_h2', @iMixId, '700'),
('bx_protean_font_weight_h3', @iMixId, '500'),
('bx_protean_footer_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_footer_bg_image', @iMixId, '0'),
('bx_protean_footer_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_footer_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_footer_bg_image_size', @iMixId, 'cover'),
('bx_protean_footer_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_footer_border_size', @iMixId, '1px'),
('bx_protean_footer_content_padding', @iMixId, '1rem'),
('bx_protean_footer_font_color', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_footer_icon_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_footer_icon_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_footer_link_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_footer_link_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_footer_shadow', @iMixId, 'none'),
('bx_protean_form_input_bg_color', @iMixId, 'rgba(17, 34, 51, 1)'),
('bx_protean_form_input_bg_color_active', @iMixId, 'rgba(34, 51, 68, 1)'),
('bx_protean_form_input_border_color', @iMixId, 'rgba(17, 34, 51, 1)'),
('bx_protean_form_input_border_color_active', @iMixId, 'rgba(17, 34, 51, 1)'),
('bx_protean_form_input_border_size', @iMixId, '2px'),
('bx_protean_form_input_font_color', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_form_input_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_form_input_font_size', @iMixId, '1rem'),
('bx_protean_form_input_height', @iMixId, '2.5rem'),
('bx_protean_form_input_shadow', @iMixId, 'none'),
('bx_protean_form_input_editor_skin', @iMixId, 'dark'),
('bx_protean_general_item_bg_color_active', @iMixId, 'rgba(34, 51, 68, 1)'),
('bx_protean_general_item_bg_color_disabled', @iMixId, 'rgba(255, 255, 255, 0.1)'),
('bx_protean_general_item_bg_color_hover', @iMixId, 'rgba(0, 0, 0, 0.05)'),
('bx_protean_header_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_header_bg_image', @iMixId, '0'),
('bx_protean_header_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_header_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_header_bg_image_size', @iMixId, 'cover'),
('bx_protean_header_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_header_border_size', @iMixId, '1px'),
('bx_protean_header_content_padding', @iMixId, '0.5rem 0px 0.5rem 0px'),
('bx_protean_header_height', @iMixId, '4rem'),
('bx_protean_header_icon_color', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_header_icon_color_hover', @iMixId, 'rgba(243, 244, 245, 0.8)'),
('bx_protean_header_link_color', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_header_link_color_hover', @iMixId, 'rgba(243, 244, 245, 0.8)'),
('bx_protean_header_shadow', @iMixId, 'none'),
('bx_protean_menu_account_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_account_bg_image', @iMixId, '0'),
('bx_protean_menu_account_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_menu_account_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_account_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_account_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_menu_account_border_size', @iMixId, '0px'),
('bx_protean_menu_account_content_padding', @iMixId, '0px'),
('bx_protean_menu_account_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_menu_account_font_color_active', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_account_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_menu_account_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_menu_account_font_shadow', @iMixId, 'none'),
('bx_protean_menu_account_font_size', @iMixId, '1rem'),
('bx_protean_menu_account_font_weight', @iMixId, '400'),
('bx_protean_menu_account_shadow', @iMixId, 'none'),
('bx_protean_menu_main_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_main_bg_image', @iMixId, '0'),
('bx_protean_menu_main_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_menu_main_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_main_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_main_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_menu_main_border_size', @iMixId, '0px'),
('bx_protean_menu_main_content_padding', @iMixId, '0px'),
('bx_protean_menu_main_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_menu_main_font_color_active', @iMixId, 'rgba(252, 253, 254, 1)'),
('bx_protean_menu_main_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_menu_main_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_menu_main_font_shadow', @iMixId, 'none'),
('bx_protean_menu_main_font_size', @iMixId, '0.875rem'),
('bx_protean_menu_main_font_weight', @iMixId, '400'),
('bx_protean_menu_main_shadow', @iMixId, 'none'),
('bx_protean_menu_page_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_page_bg_image', @iMixId, '0'),
('bx_protean_menu_page_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_menu_page_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_page_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_page_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_menu_page_border_size', @iMixId, '1px'),
('bx_protean_menu_page_content_padding', @iMixId, '0px'),
('bx_protean_menu_page_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_menu_page_font_color_active', @iMixId, 'rgba(252, 253, 254, 1)'),
('bx_protean_menu_page_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_menu_page_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_menu_page_font_shadow', @iMixId, 'none'),
('bx_protean_menu_page_font_size', @iMixId, '1.0rem'),
('bx_protean_menu_page_font_weight', @iMixId, '400'),
('bx_protean_menu_page_shadow', @iMixId, '0px 0px 3px 0px rgba(51, 68, 85, 0.1)'),
('bx_protean_menu_slide_bg_color', @iMixId, 'rgba(34, 51, 68, 0.9)'),
('bx_protean_menu_slide_bg_image', @iMixId, '0'),
('bx_protean_menu_slide_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_menu_slide_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_menu_slide_bg_image_size', @iMixId, 'cover'),
('bx_protean_menu_slide_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_menu_slide_border_size', @iMixId, '1px 0px 1px 0px'),
('bx_protean_menu_slide_content_padding', @iMixId, '1.25rem'),
('bx_protean_menu_slide_font_color', @iMixId, 'rgba(68, 136, 255, 1)'),
('bx_protean_menu_slide_font_color_active', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_menu_slide_font_color_hover', @iMixId, 'rgba(68, 136, 255, 0.8)'),
('bx_protean_menu_slide_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_menu_slide_font_shadow', @iMixId, 'none'),
('bx_protean_menu_slide_font_size', @iMixId, '1.0rem'),
('bx_protean_menu_slide_font_weight', @iMixId, '400'),
('bx_protean_menu_slide_shadow', @iMixId, 'none'),
('bx_protean_page_width', @iMixId, '1280'),
('bx_protean_popup_bg_color', @iMixId, 'rgba(51, 68, 85, 1)'),
('bx_protean_popup_bg_image', @iMixId, '0'),
('bx_protean_popup_bg_image_attachment', @iMixId, 'scroll'),
('bx_protean_popup_bg_image_repeat', @iMixId, 'no-repeat'),
('bx_protean_popup_bg_image_size', @iMixId, 'cover'),
('bx_protean_popup_border_color', @iMixId, 'rgba(136, 153, 170, 0.3)'),
('bx_protean_popup_border_radius', @iMixId, '5px'),
('bx_protean_popup_border_size', @iMixId, '1px'),
('bx_protean_popup_content_padding', @iMixId, '1.25rem'),
('bx_protean_popup_shadow', @iMixId, '0px 0px 3px 0px rgba(51, 68, 85, 0.1)'),
('bx_protean_popup_title_bg_color', @iMixId, 'rgba(255, 255, 255, 0)'),
('bx_protean_popup_title_font_color', @iMixId, 'rgba(243, 244, 245, 1)'),
('bx_protean_popup_title_font_family', @iMixId, '\"Helvetica Neue\", sans-serif'),
('bx_protean_popup_title_font_size', @iMixId, '1.5rem'),
('bx_protean_popup_title_padding', @iMixId, '1.25rem'),
('bx_protean_site_logo', @iMixId, '0'),
('bx_protean_site_logo_alt', @iMixId, ''),
('bx_protean_styles_custom', @iMixId, 'div.bx-base-pofile-cover {\r\nborder: 1px solid rgba(136, 153, 170, 0.3);\r\nborder-bottom-width: 0;\r\n}\r\n\r\n\r\ndiv.bx-market-unit-cover div.bx-base-text-unit-no-thumb {\r\nborder-width: 0px;\r\n}\r\n\r\n.sys-cpf-header,\r\n.sys-cpf-title,\r\n.sys-cpf-submenu-cnt ul li {\r\nborder-bottom-color: rgba(136, 153, 170, 0.3);\r\n}\r\n\r\n.sys-cpf-submenu-cnt ul li.bx-menu-inter-act {\r\nborder-bottom-color: rgb(17, 34, 51);\r\n}\r\n\r\n.bx-def-hr, \r\n.bx-def-hr-auto {\r\nborder-color: rgba(255, 255, 255, 0.1);\r\n}\r\n\r\n.bx-def-font {\r\nline-height:18px;\r\nline-height:1.125rem;\r\n}\r\nh1,\r\n.bx-def-font-h1 {\r\nline-height:36px;\r\nline-height:2.25rem;\r\n}\r\nh2,\r\n.bx-def-font-h2 {\r\nline-height:30px;\r\nline-height:1.875rem;\r\n}\r\nh3,\r\n.bx-def-font-h3 {\r\nline-height:26px;\r\nline-height:1.625rem;\r\n}\r\n\r\n.bx-def-label {\r\nbackground-color: rgba(245, 250, 255, 0.5);\r\n}\r\n.bx-def-label,\r\n.bx-def-label a,\r\n.bx-def-label .sys-icon {\r\ncolor: rgba(51, 68, 85, 1);\r\n}\r\n\r\n#bx-popup-fog {\r\nbackground-color: rgba(0, 0, 0) !important;\r\n}\r\n\r\n/*--- Informers ---*/\r\n.bx-informer-msg-info {\r\nbackground-color: rgba(60, 164, 85, 1.0);\r\n}\r\n.bx-informer-msg-alert {\r\nbackground-color: rgba(230, 115, 55, 1.0);\r\n}\r\n.bx-informer-msg-error {\r\nbackground-color: rgba(200, 64, 64, 1.0);\r\n}\r\n\r\n\r\n/*--- Menus ---*/\r\n.bx-menu-notif li.bx-menu-tab-active a, \r\n.bx-menu-verlit li.bx-menu-tab-active a, \r\n.bx-menu-ver li.bx-menu-tab-active a,\r\n.bx-menu-hor li.bx-menu-tab-active a,\r\n.bx-menu-custom-hor li.bx-menu-tab-active a {\r\ncolor: rgba(252, 253, 254, 1);\r\n}\r\n\r\n/*--- Forms ---*/\r\n.bx-form-value .bx-form-input-autotoken b {\r\ncolor: rgba(243, 244, 245, 1);\r\n}\r\n.bx-form-value .select2-container--default .select2-selection--multiple {\r\nbackground-color: rgba(17, 34, 51, 1);\r\nborder-color: rgba(17, 34, 51, 1);\r\n}\r\n.bx-form-value .select2-container--default .select2-selection--multiple .select2-selection__choice {\r\nbackground-color: rgba(51, 68, 85, 1);\r\nborder-color: rgba(68, 136, 255, 1);\r\n}\r\n.bx-form-value .select2-container--default .select2-selection--multiple .select2-selection__choice__remove,\r\n.bx-form-value .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {\r\ncolor:  rgba(243, 244, 245, 1);\r\n}\r\n.select2-container .select2-dropdown  {\r\nbackground-color: rgba(17, 34, 51, 1);\r\nborder-color: rgba(17, 34, 51, 1);\r\n}\r\n.s2-to-tree.select2-container li.select2-results__option[aria-selected="true"] > span.item-label {\r\nbackground-color: rgba(34, 51, 68, 1);\r\n}\r\n.bx-popup .dropzone {\r\nbackground-color: rgba(17, 34, 51, 1);\r\n}\r\n\r\n/*--- Timeline ---*/\r\ndiv.bx-tl-view-timeline div.bx-tl-items:before,\r\ndiv.bx-tl-view-timeline div.bx-tl-item-circle {\r\nbackground-color: rgba(51, 68, 85, 1);\r\n}\r\ndiv.bx-tl-il-embed {\r\npadding: 0.5rem;\r\nbackground-color: rgba(255, 255, 255, 0.8);\r\nborder-radius:  5px;\r\n}\r\ndiv.bx-tl-item div.bx-tl-item-activity .bx-menu-custom li .sys-action-counter-icon,\r\ndiv.bx-tl-item div.bx-tl-item-activity .bx-menu-custom li .sys-action-do-icon, \r\ndiv.bx-tl-item div.bx-tl-item-activity .bx-menu-custom li .bx-menu-item-icon, \r\ndiv.bx-tl-item div.bx-tl-item-manage a .bx-menu-item-icon {\r\nbackground-color: rgba(51, 68, 85, 1) !important;\r\nborder: 1px solid rgba(255, 255, 255, 0.1)  !important;\r\n}\r\ndiv.bx-tl-item div.bx-tl-item-activity .bx-menu-custom li a:hover .sys-action-do-icon, \r\ndiv.bx-tl-item div.bx-tl-item-activity .bx-menu-custom li a:hover .bx-menu-item-icon {\r\nbackground-color: rgba(51, 68, 85, 0.8);\r\nborder: 1px solid rgba(255, 255, 255, 0.2);\r\n}\r\n .fr-embedly {\r\nbackground: #ccc;\r\n}\r\n.ql-stroke{\r\n    stroke: rgb(209, 213, 219) !important;\r\n}\r\n .ql-fill, .ql-embed{\r\n    fill: rgb(209, 213, 219) !important;\r\n}\r\n .ql-picker{\r\n    color: rgb(209, 213, 219) !important;\r\n}\r\n.ql-toolbar{\r\n    border-color: rgba(55,65,81,1) !important;\r\n    border-radius: 0.3rem 0.3rem 0 0;\r\n}\r\n.ql-container.ql-snow{\r\n    border-color: rgba(55,65,81,1) !important;\r\n}\r\n.ql-editor{\r\n    background: #112233;\r\n    color: rgb(209, 213, 219);\r\n}\r\n .ql-editor.ql-blank::before{\r\n    color: rgba(255,255,255,0.6);\r\n}\r\n.ql-toolbar.ql-snow .ql-picker.ql-expanded .ql-picker-options{\r\n    background: rgb(17, 24, 39);\r\n    color: rgb(209, 213, 219);\r\n}\r\n.ql-container{\r\n    border-radius: 0 0 0.3rem 0.3rem;border:none !importnat\r\n}\r\n'),
('bx_protean_vpm_font_size_scale', @iMixId, '87.5%'),
('bx_protean_vpt_font_size_scale', @iMixId, '100%');


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '', '', 'bx_protean@modules/boonex/protean/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`='home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id`=@iParentPageId);
INSERT INTO `sys_std_widgets`(`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, 'appearance', CONCAT('{url_studio}design.php?name=', @sName), '', 'bx_protean@modules/boonex/protean/|std-icon.svg', '_bx_protean_wgt_cpt', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioDesigns";}');
INSERT INTO `sys_std_pages_widgets`(`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), @iParentPageOrder + 1);