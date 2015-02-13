SET @sName = 'bx_accounts';

-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_accounts_moderation';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_accounts_moderation';

UPDATE `sys_objects_page` SET `title_system`='_bx_accnt_page_title_sys_manage_administration', `visible_for_levels`='192' WHERE `object`='bx_accounts_administration';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_accnt_page_block_title_system_manage_administration', `visible_for_levels`='192' WHERE `object`='bx_accounts_administration' AND `title`='_bx_accnt_page_block_title_manage';


-- MENUS
UPDATE `sys_menu_items` SET `visible_for_levels`='192' WHERE `set_name`='bx_accounts_menu_manage_tools' AND `name` IN ('delete', 'delete-with-content');

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='accounts-moderation';
UPDATE `sys_menu_items` SET `visible_for_levels`='192' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='accounts-administration';


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = @sName;
DELETE FROM `sys_acl_actions` WHERE `Module` = @sName;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'edit any entry', NULL, '_bx_accnt_acl_action_edit_any_account', '', 1, 3);
SET @iIdActionProfileEditAny = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- edit any entry 
(@iModerator, @iIdActionProfileEditAny),
(@iAdministrator, @iIdActionProfileEditAny);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_accounts_moderation';
DELETE FROM `sys_grid_fields` WHERE `object`='bx_accounts_moderation';
DELETE FROM `sys_grid_actions` WHERE `object`='bx_accounts_moderation';

UPDATE `sys_objects_grid` SET `field_order`='logged' WHERE `object`='bx_accounts_administration';

UPDATE `sys_grid_actions` SET `order`='1' WHERE `object`='bx_accounts_administration' AND `type`='single' AND `name`='settings';