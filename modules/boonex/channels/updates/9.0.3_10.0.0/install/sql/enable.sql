-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_channels_view_profile' AND `title` IN ('_bx_channels_page_block_title_entry_breadcrumb', '_bx_channels_page_block_title_entry_parent', '_bx_channels_page_block_title_entry_childs');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_channels_view_profile', 1, 'bx_channels', '', '_bx_channels_page_block_title_entry_breadcrumb', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_channels";s:6:"method";s:17:"entity_breadcrumb";}', 0, 0, 1, 1),
('bx_channels_view_profile', 2, 'bx_channels', '', '_bx_channels_page_block_title_entry_parent', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_channels";s:6:"method";s:13:"entity_parent";}', 0, 0, 1, 1),
('bx_channels_view_profile', 3, 'bx_channels', '', '_bx_channels_page_block_title_entry_childs', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_channels";s:6:"method";s:13:"entity_childs";}', 0, 0, 1, 1);


-- MENUS
UPDATE `sys_objects_menu` SET `override_class_name`='BxCnlMenuViewActions', `override_class_file`='modules/boonex/channels/classes/BxCnlMenuViewActions.php' WHERE `object`='bx_channels_view_actions';
UPDATE `sys_objects_menu` SET `override_class_name`='BxCnlMenuViewActions', `override_class_file`='modules/boonex/channels/classes/BxCnlMenuViewActions.php' WHERE `object`='bx_channels_view_actions_more';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_view_actions_all' AND `name`='social-sharing-googleplus';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_snippet_meta' AND `name`='nl';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_channels_snippet_meta', 'bx_channels', 'nl', '_sys_menu_item_title_system_sm_nl', '_sys_menu_item_title_sm_nl', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 7);

UPDATE `sys_menu_items` SET `order`='1' WHERE `set_name`='bx_channels_snippet_meta' AND `name`='date';
UPDATE `sys_menu_items` SET `order`='2' WHERE `set_name`='bx_channels_snippet_meta' AND `name`='tags';
UPDATE `sys_menu_items` SET `order`='3' WHERE `set_name`='bx_channels_snippet_meta' AND `name`='views';
UPDATE `sys_menu_items` SET `order`='4' WHERE `set_name`='bx_channels_snippet_meta' AND `name`='comments';
UPDATE `sys_menu_items` SET `order`='5' WHERE `set_name`='bx_channels_snippet_meta' AND `name`='members';
UPDATE `sys_menu_items` SET `order`='6' WHERE `set_name`='bx_channels_snippet_meta' AND `name`='subscribers';
UPDATE `sys_menu_items` SET `order`='8' WHERE `set_name`='bx_channels_snippet_meta' AND `name`='subscribe';
UPDATE `sys_menu_items` SET `order`='9' WHERE `set_name`='bx_channels_snippet_meta' AND `name`='unsubscribe';

UPDATE `sys_menu_items` SET `icon`='hashtag' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='channels-administration' AND `icon`='';


-- ACL
SET @iIdActionProfileCreate = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_channels' AND `Name`='create entry' LIMIT 1);
SET @iIdActionProfileDelete = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_channels' AND `Name`='delete entry' LIMIT 1);

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

DELETE FROM `sys_acl_matrix` WHERE `IDLevel` IN (@iAccount, @iStandard, @iUnconfirmed, @iPending, @iModerator, @iPremium) AND `IDAction`=@iIdActionProfileCreate;
DELETE FROM `sys_acl_matrix` WHERE `IDLevel` IN (@iAccount, @iStandard, @iUnconfirmed, @iPending, @iModerator, @iPremium) AND `IDAction`=@iIdActionProfileDelete;


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_channels' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_channels' AND `action` IN ('timeline_score', 'timeline_pin', 'timeline_promote') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_channels', 'timeline_score', @iHandler),
('bx_channels', 'timeline_pin', @iHandler),
('bx_channels', 'timeline_promote', @iHandler);
