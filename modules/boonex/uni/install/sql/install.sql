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
(@iCategoryId, CONCAT(@sName, '_switcher_title'), '_bx_uni_stg_cpt_option_switcher_name', 'UNI', 'digit', '', '', '', 1),
(@iCategoryId, CONCAT(@sName, '_page_width'), '_bx_uni_stg_cpt_option_page_width', '1000', 'digit', '', '', '', 2);

-- SETTINGS: UNI Styles
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles'), '_bx_uni_stg_cpt_category_styles', 1);
SET @iCategoryId = LAST_INSERT_ID();

INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_header_bg_color'), '_bx_uni_stg_cpt_option_header_bg_color', 'rgba(59, 134, 134, 1)', 'rgba', '', '', '', 1),
	(@iCategoryId, CONCAT(@sName, '_header_bg_image'), '_bx_uni_stg_cpt_option_header_bg_image', '', 'image', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_header_border_color'), '_bx_uni_stg_cpt_option_header_border_color', 'rgb(208, 208, 208)', 'rgb', '', '', '', 3),
(@iCategoryId, CONCAT(@sName, '_header_border_size'), '_bx_uni_stg_cpt_option_header_border_size', '0px', 'digit', '', '', '', 4),
(@iCategoryId, CONCAT(@sName, '_site_bg_color'), '_bx_uni_stg_cpt_option_site_bg_color', 'rgb(255, 255, 255)', 'rgb', '', '', '', 11),
	(@iCategoryId, CONCAT(@sName, '_site_bg_image'), '_bx_uni_stg_cpt_option_site_bg_image', '', 'image', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_footer_bg_color'), '_bx_uni_stg_cpt_option_footer_bg_color', 'rgb(255, 255, 255, 1)', 'rgba', '', '', '', 21),
	(@iCategoryId, CONCAT(@sName, '_footer_bg_image'), '_bx_uni_stg_cpt_option_footer_bg_image', '', 'image', '', '', '', 22),
(@iCategoryId, CONCAT(@sName, '_footer_border_color'), '_bx_uni_stg_cpt_option_footer_border_color', 'rgb(208, 208, 208)', 'rgb', '', '', '', 23),
(@iCategoryId, CONCAT(@sName, '_footer_border_size'), '_bx_uni_stg_cpt_option_footer_border_size', '1px', 'digit', '', '', '', 24),
(@iCategoryId, CONCAT(@sName, '_block_bg_color'), '_bx_uni_stg_cpt_option_block_bg_color', 'rgb(255, 255, 255, 1)', 'rgba', '', '', '', 31),
	(@iCategoryId, CONCAT(@sName, '_block_bg_image'), '_bx_uni_stg_cpt_option_block_bg_image', '', 'image', '', '', '', 32),
(@iCategoryId, CONCAT(@sName, '_block_border_color'), '_bx_uni_stg_cpt_option_block_border_color', 'rgb(208, 208, 208)', 'rgb', '', '', '', 33),
(@iCategoryId, CONCAT(@sName, '_block_border_size'), '_bx_uni_stg_cpt_option_block_border_size', '0px', 'digit', '', '', '', 34),
(@iCategoryId, CONCAT(@sName, '_block_border_radius'), '_bx_uni_stg_cpt_option_block_border_radius', '0px', 'digit', '', '', '', 35),
(@iCategoryId, CONCAT(@sName, '_font_family'), '_bx_uni_stg_cpt_option_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 41),
(@iCategoryId, CONCAT(@sName, '_font_size_default'), '_bx_uni_stg_cpt_option_size_default', '18px', 'digit', '', '', '', 42),
(@iCategoryId, CONCAT(@sName, '_font_size_small'), '_bx_uni_stg_cpt_option_size_small', '14px', 'digit', '', '', '', 43),
(@iCategoryId, CONCAT(@sName, '_font_size_middle'), '_bx_uni_stg_cpt_option_size_middle', '16px', 'digit', '', '', '', 44),
(@iCategoryId, CONCAT(@sName, '_font_size_large'), '_bx_uni_stg_cpt_option_size_large', '22px', 'digit', '', '', '', 45),
(@iCategoryId, CONCAT(@sName, '_font_size_h1'), '_bx_uni_stg_cpt_option_size_h1', '38px', 'digit', '', '', '', 46),
(@iCategoryId, CONCAT(@sName, '_font_size_h2'), '_bx_uni_stg_cpt_option_size_h2', '24px', 'digit', '', '', '', 47),
(@iCategoryId, CONCAT(@sName, '_font_size_h3'), '_bx_uni_stg_cpt_option_size_h3', '18px', 'digit', '', '', '', 48);

-- SETTINGS: System
SET @iSystemCategoryId = (SELECT IFNULL(`id`, @iCategoryId) FROM `sys_options_categories` WHERE `name`='system' LIMIT 1);
SET @iSystemCategoryOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_options` WHERE `category_id`=@iSystemCategoryId LIMIT 1);
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iSystemCategoryId, CONCAT(@sName, '_site_logo'), '', '0', 'digit', '', '', '', @iSystemCategoryOrder + 1),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_alt'), '', '', 'text', '', '', '', @iSystemCategoryOrder + 2),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_width'), '', '240', 'digit', '', '', '', @iSystemCategoryOrder + 3),
(@iSystemCategoryId, CONCAT(@sName, '_site_logo_height'), '', '48', 'digit', '', '', '', @iSystemCategoryOrder + 4);


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