SET @sName = 'bx_protean';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=CONCAT(@sName, '_system') LIMIT 1);

DELETE FROM `sys_options` WHERE `name`=CONCAT(@sName, '_default_mix');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, CONCAT(@sName, '_default_mix'), '_bx_protean_stg_cpt_option_default_mix', '', 'select', 'a:2:{s:6:"module";s:10:"bx_protean";s:6:"method";s:23:"get_options_default_mix";}', '', '', '', 10);

UPDATE `sys_options` SET `value`='rgba(30, 150, 250, 0.8)' WHERE `name`=CONCAT(@sName, '_button_lg_border_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_button_lg_border_size');

UPDATE `sys_options` SET `value`='rgba(40, 180, 140, 0.8)' WHERE `name`=CONCAT(@sName, '_button_sm_border_color');
UPDATE `sys_options` SET `value`='1px' WHERE `name`=CONCAT(@sName, '_button_sm_border_size');


-- MIXES
SET @iMixId = (SELECT `id` FROM `sys_options_mixes` WHERE `type`=@sName AND `name`='Protean-Light-Mix' LIMIT 1);
UPDATE `sys_options` SET `value`=@iMixId WHERE `name`=CONCAT(@sName, '_default_mix');
