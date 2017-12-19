SET @sName = 'bx_protean';


UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = @sName LIMIT 1;


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_header') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_header_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_header_bg_image_attachment'), '_bx_protean_stg_cpt_option_header_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 6);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_footer') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_footer_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_footer_bg_image_attachment'), '_bx_protean_stg_cpt_option_footer_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_body') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_body_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_body_bg_image_attachment'), '_bx_protean_stg_cpt_option_body_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_block') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_block_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_block_bg_image_attachment'), '_bx_protean_stg_cpt_option_block_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_card') LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN (CONCAT(@sName, '_card_bg_color_hover'), CONCAT(@sName, '_card_bg_image_attachment'));
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_card_bg_color_hover'), '_bx_protean_stg_cpt_option_card_bg_color_hover', 'rgba(255, 255, 255, 0.5)', 'rgba', '', '', '', 2),
(@iCategoryId, CONCAT(@sName, '_card_bg_image_attachment'), '_bx_protean_stg_cpt_option_card_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 5);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_popup') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_popup_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_popup_bg_image_attachment'), '_bx_protean_stg_cpt_option_popup_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_menu_main') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_menu_main_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_main_bg_image_attachment'), '_bx_protean_stg_cpt_option_menu_main_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_menu_account') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_menu_account_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_account_bg_image_attachment'), '_bx_protean_stg_cpt_option_menu_account_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_menu_page') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_menu_page_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_page_bg_image_attachment'), '_bx_protean_stg_cpt_option_menu_page_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4);

SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_menu_slide') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_menu_slide_bg_image_attachment');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_menu_slide_bg_image_attachment'), '_bx_protean_stg_cpt_option_menu_slide_bg_image_attachment', 'scroll', 'select', 'fixed,scroll,local', '', '', 4);


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `name`='Neat-Mix' LIMIT 1);

DELETE FROM `sys_options_mixes2options` WHERE `option` IN ('bx_protean_card_bg_color_hover');
INSERT INTO `sys_options_mixes2options` (`option`, `mix_id`, `value`) VALUES
('bx_protean_card_bg_color_hover', @iMixId, 'rgba(245, 250, 255, 0.5)');
