SET @sName = 'bx_invites';


-- SETTINGS
DELETE FROM `sys_options` WHERE `name`='bx_invites_count_per_user';


-- ACL
SET @iIdActionInvite = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='invite' LIMIT 1);

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

UPDATE `sys_acl_matrix` SET `AllowedCount`='5' WHERE `IDLevel` IN (@iAccount, @iStandard, @iUnconfirmed, @iPending, @iSuspended, @iModerator, @iAdministrator, @iPremium) AND `IDAction`=@iIdActionInvite;
