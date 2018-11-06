-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_channels' AND `title`='_bx_channels_page_block_title_categories';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_view_actions_all' AND `name`='repost';


-- ACL
SET @iIdActionCreateChannelAuto = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_channels' AND `Name`='create channel auto' LIMIT 1);
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionCreateChannelAuto;
DELETE FROM `sys_acl_actions` WHERE `Module`='bx_channels' AND `Name`='create channel auto';

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_channels', 'create channel auto', NULL, '_bx_channels_acl_action_create_channel_auto', '', 1, 1);
SET @iIdActionCreateChannelAuto = LAST_INSERT_ID();

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
(@iAccount, @iIdActionCreateChannelAuto),
(@iStandard, @iIdActionCreateChannelAuto),
(@iUnconfirmed, @iIdActionCreateChannelAuto),
(@iPending, @iIdActionCreateChannelAuto),
(@iModerator, @iIdActionCreateChannelAuto),
(@iAdministrator, @iIdActionCreateChannelAuto),
(@iPremium, @iIdActionCreateChannelAuto);
