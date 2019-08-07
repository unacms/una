SET @sName = 'bx_decorous';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_system') LIMIT 1);

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_default_mix');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_default_mix'), '_bx_decorous_stg_cpt_option_default_mix', '', 'select', 'a:2:{s:6:"module";s:11:"bx_decorous";s:6:"method";s:23:"get_options_default_mix";}', '', '', '', 10);
