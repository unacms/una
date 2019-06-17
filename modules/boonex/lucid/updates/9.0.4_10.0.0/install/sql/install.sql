SET @sName = 'bx_lucid';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_styles_general'));
DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_general_item_bg_color_disabled');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_general_item_bg_color_disabled'), '_bx_lucid_stg_cpt_option_general_item_bg_color_disabled', 'rgba(221, 221, 221, 1.0)', 'rgba', '', '', '', '', 3);

UPDATE `sys_options` SET `value`='93.75%' WHERE `name`=CONCAT(@sName, '_vpt_font_size_scale');
UPDATE `sys_options` SET `value`='85%' WHERE `name`=CONCAT(@sName, '_vpm_font_size_scale');


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Light-Mix' LIMIT 1);
UPDATE sys_options_mixes2options SET `value`='rgba(220, 230, 240, 1)' WHERE `option`='bx_lucid_menu_page_bg_color' AND `mix_id`=@iMixId;
UPDATE sys_options_mixes2options SET `value`='93.75%' WHERE `option`='bx_lucid_vpt_font_size_scale' AND `mix_id`=@iMixId;
UPDATE sys_options_mixes2options SET `value`='85%' WHERE `option`='bx_lucid_vpm_font_size_scale' AND `mix_id`=@iMixId;
UPDATE sys_options_mixes2options SET `value`='rgba(255, 255, 255, 1)' WHERE `option`='bx_lucid_cover_bg_color' AND `mix_id`=@iMixId;
