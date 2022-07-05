-- ACL
SET @iIdActionMakeOffer = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_ads' AND `Name`='make offer' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionMakeOffer;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionMakeOffer;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_ads', 'make offer', NULL, '_bx_ads_acl_action_make_offer', '', 1, 3);
SET @iIdActionMakeOffer = LAST_INSERT_ID();

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

-- make offer
(@iStandard, @iIdActionMakeOffer),
(@iModerator, @iIdActionMakeOffer),
(@iAdministrator, @iIdActionMakeOffer),
(@iPremium, @iIdActionMakeOffer);