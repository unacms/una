SET @sName = 'bx_protean';


-- SETTINGS:
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_block') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_block_title_border_color'), CONCAT(@sName, '_block_title_border_size'), CONCAT(@sName, '_block_title_border_radius'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_block_title_border_color'), '_bx_protean_stg_cpt_option_block_title_border_color', 'rgba(208, 208, 208, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_block_title_border_size'), '_bx_protean_stg_cpt_option_block_title_border_size', '0px', 'digit', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_block_title_border_radius'), '_bx_protean_stg_cpt_option_block_title_border_radius', '0px', 'digit', '', '', '', 14);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_menu_slide') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_menu_slide_font_family'), CONCAT(@sName, '_menu_slide_font_size'), CONCAT(@sName, '_menu_slide_font_color'), CONCAT(@sName, '_menu_slide_font_color_hover'), CONCAT(@sName, '_menu_slide_font_color_active'), CONCAT(@sName, '_menu_slide_font_shadow'), CONCAT(@sName, '_menu_slide_font_weight'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_family'), '_bx_protean_stg_cpt_option_menu_slide_font_family', '"Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif', 'digit', '', '', '', 9),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_size'), '_bx_protean_stg_cpt_option_menu_slide_font_size', '1.0rem', 'digit', '', '', '', 10),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_color'), '_bx_protean_stg_cpt_option_menu_slide_font_color', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 11),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_color_hover'), '_bx_protean_stg_cpt_option_menu_slide_font_color_hover', 'rgba(62, 134, 133, 1)', 'rgba', '', '', '', 12),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_color_active'), '_bx_protean_stg_cpt_option_menu_slide_font_color_active', 'rgba(0, 0, 0, 1)', 'rgba', '', '', '', 13),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_shadow'), '_bx_protean_stg_cpt_option_menu_slide_font_shadow', 'none', 'digit', '', '', '', 14),
(@iCategoryId, CONCAT(@sName, '_menu_slide_font_weight'), '_bx_protean_stg_cpt_option_menu_slide_font_weight', '400', 'digit', '', '', '', 15);


SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_custom');
INSERT INTO `sys_options_categories`(`type_id`, `name`, `caption`, `order`) VALUES 
(@iTypeId, CONCAT(@sName, '_styles_custom'), '_bx_protean_stg_cpt_category_styles_custom', 17);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_styles_custom');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_styles_custom'), '_bx_protean_stg_cpt_option_styles_custom', '', 'text', '', '', '', 1);


-- MIXES
UPDATE `sys_options_mixes` SET `editable`='0' WHERE `type`=@sName AND `name`='Neat-Mix';

DELETE FROM `sys_options_mixes2options` WHERE `option` IN ('bx_protean_block_title_border_color', 'bx_protean_block_title_border_size', 'bx_protean_block_title_border_radius');
INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_block_title_border_color', @iMixId, 'rgba(208, 208, 208, 1)'),
('bx_protean_block_title_border_size', @iMixId, '0px'),
('bx_protean_block_title_border_radius', @iMixId, '0px');