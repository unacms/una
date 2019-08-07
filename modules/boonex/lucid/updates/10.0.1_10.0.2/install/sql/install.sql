SET @sName = 'bx_lucid';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_system') LIMIT 1);

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_default_mix');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_default_mix'), '_bx_lucid_stg_cpt_option_default_mix', '', 'select', 'a:2:{s:6:"module";s:8:"bx_lucid";s:6:"method";s:23:"get_options_default_mix";}', '', '', '', 10);


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Light-Mix' LIMIT 1);
UPDATE `sys_options` SET `value`=@iMixId WHERE `name`=CONCAT(@sName, '_default_mix');
