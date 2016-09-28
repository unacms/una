-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_albums' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_albums_searchable_fields');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_albums_searchable_fields', 'title,text', @iCategoryId, '_bx_albums_option_searchable_fields', 'list', '', '', 'a:2:{s:6:"module";s:9:"bx_albums";s:6:"method";s:21:"get_searchable_fields";}', 30);


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='192', `field_active`='status_admin' WHERE `object`='bx_albums_administration';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_albums_common';


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_albums' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action`='save_setting' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);