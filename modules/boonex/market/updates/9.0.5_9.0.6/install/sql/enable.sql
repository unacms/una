-- ACL
UPDATE `sys_acl_actions` SET `DisabledForLevels`='0' WHERE `Module`='bx_market' AND `Name`='download entry';
SET @iIdActionEntryDownload = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_market' AND `Name`='download entry' LIMIT 1);

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionEntryDownload;
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iUnauthenticated, @iIdActionEntryDownload),
(@iAccount, @iIdActionEntryDownload),
(@iStandard, @iIdActionEntryDownload),
(@iUnconfirmed, @iIdActionEntryDownload),
(@iPending, @iIdActionEntryDownload),
(@iModerator, @iIdActionEntryDownload),
(@iAdministrator, @iIdActionEntryDownload),
(@iPremium, @iIdActionEntryDownload);
