-- MENUS
UPDATE `sys_menu_items` SET `title_system`='_bx_groups_menu_item_title_system_pay_and_join' WHERE `set_name`='bx_groups_view_actions_all' AND `name`='join-group-profile';
UPDATE `sys_menu_items` SET `title_system`='_bx_groups_menu_item_title_system_edit_pricing' WHERE `set_name`='bx_groups_view_actions_all' AND `name`='edit-group-pricing';


-- ACL
SET @iIdActionUsePaidJoin = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_groups' AND `Name`='use paid join' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionUsePaidJoin;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionUsePaidJoin;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_groups', 'use paid join', NULL, '_bx_groups_acl_action_use_paid_join', '', 1, 1);
SET @iIdActionUsePaidJoin = LAST_INSERT_ID();

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
(@iStandard, @iIdActionUsePaidJoin),
(@iModerator, @iIdActionUsePaidJoin),
(@iAdministrator, @iIdActionUsePaidJoin),
(@iPremium, @iIdActionUsePaidJoin);


-- GRIDS
UPDATE `sys_objects_grid` SET `responsive`='1' WHERE `object`='bx_groups_fans';


-- PRIVACY 
UPDATE `sys_objects_privacy` SET `title`='_bx_groups_form_profile_input_allow_view_favorite_list' WHERE `object`='bx_groups_allow_view_favorite_list';
