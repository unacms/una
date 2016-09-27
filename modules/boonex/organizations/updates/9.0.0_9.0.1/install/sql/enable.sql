-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_organizations' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_organizations_per_page_browse', 'bx_organizations_searchable_fields');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_organizations_per_page_browse', '20', @iCategId, '_bx_orgs_option_per_page_browse', 'digit', '', '', '', 11),
('bx_organizations_searchable_fields', 'org_name,org_desc', @iCategId, '_bx_orgs_option_searchable_fields', 'list', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:21:"get_searchable_fields";}', '', '', 20);


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='192' WHERE `object`='bx_organizations_administration';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_organizations_common';


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_organizations' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action`='save_setting' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);