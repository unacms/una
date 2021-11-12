SET @sName = 'bx_accounts';


-- ACL
SET @iIdActionProfileDeleteAny = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='delete any entry' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionProfileDeleteAny;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionProfileDeleteAny;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'delete any entry', NULL, '_bx_accnt_acl_action_delete_any_account', '', 1, 3);
SET @iIdActionProfileDeleteAny = LAST_INSERT_ID();

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
(@iAdministrator, @iIdActionProfileDeleteAny);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_accounts_administration' AND `name`='send_message';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_accounts_administration', 'bulk', 'send_message', '_bx_accnt_grid_action_title_adm_send_message', '', 0, 0, 9);