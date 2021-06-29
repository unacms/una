SET @sName = 'bx_accounts';


-- SETTINGS
DELETE FROM `sys_options_types` WHERE `name`=@sName;
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_accounts', 'bx_accounts@modules/boonex/accounts/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

DELETE FROM `sys_options_categories` WHERE `name`=@sName;
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_accounts', 1);
SET @iCategId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN ('bx_accounts_export_to', 'bx_accounts_export_fields');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_accounts_export_to', 'csv', @iCategId, '_bx_accounts_option_export_to', 'select', '', '', '', 'a:2:{s:6:"module";s:11:"bx_accounts";s:6:"method";s:21:"get_options_export_to";}', 10),
('bx_accounts_export_fields', 'name,email', @iCategId, '_bx_accounts_option_export_fields', 'list', '', '', '', 'a:2:{s:6:"module";s:11:"bx_accounts";s:6:"method";s:25:"get_options_export_fields";}', 20);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_accounts_administration' AND `name`='export' AND `type` IN ('bulk', 'independent');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'bulk', 'export', '_bx_accnt_grid_action_title_adm_export', '', 0, 0, 8),
('bx_accounts_administration', 'independent', 'export', '_bx_accnt_grid_action_title_adm_export', 'download', 0, 0, 1);
