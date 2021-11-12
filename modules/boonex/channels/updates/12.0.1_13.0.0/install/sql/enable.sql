SET @sName = 'bx_channels';

-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module`=@sName AND `title`='_bx_channels_page_block_title_cover_block';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `text`, `text_updated`, `help`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_channels', '_bx_channels_page_block_title_sys_cover_block', '_bx_channels_page_block_title_cover_block', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_channels\";s:6:\"method\";s:12:\"entity_cover\";}', '', 0, '', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_profile_followings' AND `name`='channels';
SET @iFollowingsMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_profile_followings' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('sys_profile_followings', 'bx_channels', 'channels', '_bx_channels_menu_item_title_system_followings', '_bx_channels_menu_item_title_followings', 'javascript:void(0)', '', '_self', 'hashtag col-red2', '', '', '', 2147483647, '', 1, 0, @iFollowingsMenuOrder + 1);


-- ACL
SET @iIdActionProfileDeleteAny = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='delete any entry' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionProfileDeleteAny;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionProfileDeleteAny;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
(@sName, 'delete any entry', NULL, '_bx_channels_acl_action_delete_any_profile', '', 1, 3);
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