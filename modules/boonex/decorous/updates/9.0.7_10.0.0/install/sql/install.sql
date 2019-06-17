SET @sName = 'bx_decorous';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_general') LIMIT 1);
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_general_item_bg_color_disabled');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_disabled'), '_bx_decorous_stg_cpt_option_general_item_bg_color_disabled', 'rgba(221, 221, 221, 1.0)', 'rgba', '', '', '', '', 3);

UPDATE `sys_options` SET `value`='1px 0px 0px 0px' WHERE `name`=CONCAT(@sName, '_menu_page_border_size');

UPDATE `sys_options` SET `value`='93.75%' WHERE `name`=CONCAT(@sName, '_vpt_font_size_scale');
UPDATE `sys_options` SET `value`='85%' WHERE `name`=CONCAT(@sName, '_vpm_font_size_scale');
