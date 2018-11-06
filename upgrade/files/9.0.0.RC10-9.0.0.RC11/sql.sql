
-- Settings

DELETE FROM `sys_options` WHERE `name` IN('sys_eq_time', 'sys_push_queue_time');

SET @iCategoryIdHidden = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdHidden, 'sys_eq_time', '_adm_stg_cpt_option_sys_eq_time', '0', 'digit', '', '', '', 6),
(@iCategoryIdHidden, 'sys_push_queue_time', '_adm_stg_cpt_option_sys_push_queue_time', '0', 'digit', '', '', '', 7);


-- ACL

UPDATE `sys_acl_actions` SET `Name` = 'view_view_viewers_own' WHERE `Name` = 'view_view_viewers' AND `Module` = 'system';

-- Set default values 

UPDATE `sys_cron_jobs` SET `time` = '*' WHERE `time` IS NULL;
ALTER TABLE  `sys_cron_jobs` CHANGE  `time`  `time` VARCHAR( 128 ) NOT NULL DEFAULT  '*';


UPDATE `sys_modules` SET `type` = 'module' WHERE `type` IS NULL;
ALTER TABLE  `sys_modules` CHANGE  `type`  `type` VARCHAR( 16 ) NOT NULL DEFAULT  'module';


UPDATE `sys_objects_cmts` SET `RootStylePrefix` = 'cmt' WHERE `RootStylePrefix` IS NULL;
ALTER TABLE  `sys_objects_cmts` CHANGE  `RootStylePrefix`  `RootStylePrefix` VARCHAR( 16 ) NOT NULL DEFAULT  'cmt';


UPDATE `sys_objects_grid` SET `order_get_field` = 'order_field' WHERE `order_get_field` IS NULL;
ALTER TABLE  `sys_objects_grid` CHANGE  `order_get_field`  `order_get_field` VARCHAR( 255 ) NOT NULL DEFAULT  'order_field';

UPDATE `sys_objects_grid` SET `order_get_dir` = 'order_dir' WHERE `order_get_dir` IS NULL;
ALTER TABLE  `sys_objects_grid` CHANGE  `order_get_dir`  `order_get_dir` VARCHAR( 255 ) NOT NULL DEFAULT  'order_dir';

UPDATE `sys_objects_grid` SET `filter_get` = 'filter' WHERE `filter_get` IS NULL;
ALTER TABLE  `sys_objects_grid` CHANGE  `filter_get`  `filter_get` VARCHAR( 255 ) NOT NULL DEFAULT  'filter';



-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC11' WHERE (`version` = '9.0.0.RC10' OR `version` = '9.0.0-RC10') AND `name` = 'system';

