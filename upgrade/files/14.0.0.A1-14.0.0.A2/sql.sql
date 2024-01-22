
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- ACL

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

SET @iIdActionVoteView = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module` = 'system' AND `Name` = 'vote_view');

DELETE FROM `sys_acl_matrix` WHERE `IDAction` = @iIdActionVoteView AND `IDLevel` IN(@iUnauthenticated, @iAccount, @iUnconfirmed, @iPending);

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iUnauthenticated, @iIdActionVoteView),
(@iAccount, @iIdActionVoteView),
(@iUnconfirmed, @iIdActionVoteView),
(@iPending, @iIdActionVoteView);

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_translation' AND `content` IN('_sys_form_input_password_show', '_sys_form_input_password_hide');

INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_translation', '_sys_form_input_password_show', 1, 6),
('system', 'js_translation', '_sys_form_input_password_hide', 1, 7);
 

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-A2' WHERE (`version` = '14.0.0.A1' OR `version` = '14.0.0-A1') AND `name` = 'system';

