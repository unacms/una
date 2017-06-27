SET @sName = 'bx_messenger';

-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', @sName, '_bx_messenger', 'bx_messenger@modules/boonex/messenger/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, @sName, '_bx_messenger', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_messenger_max_symbols_number', '64000', @iCategId, '_bx_messenger_symbols_num_option', 'digit', '', '', '', 1),
('bx_messenger_max_symbols_brief_jot', '145', @iCategId, '_bx_messenger_symbols_num_brief_jot', 'digit', '', '', '', 2),
('bx_messenger_max_jot_number_default', '20', @iCategId, '_bx_messenger_jot_number_default', 'digit', '', '', '', 3),
('bx_messenger_max_jot_number_in_history', '10', @iCategId, '_bx_messenger_max_jot_number_in_history', 'digit', '', '', '', 4),
('bx_messenger_is_push_enabled', '', @iCategId, '_bx_messenger_is_push_enabled', 'checkbox', '', '', '', 5),
('bx_messenger_push_app_id', '', @iCategId, '_bx_messenger_push_app_id', 'digit', '', '', '', 6),
('bx_messenger_push_rest_api', '', @iCategId, '_bx_messenger_push_rest_api', 'digit', '', '', '', 7),
('bx_messenger_push_short_name', '', @iCategId, '_bx_messenger_push_short_name', 'digit', '', '', '', 8),
('bx_messenger_push_safari_id', '', @iCategId, '_bx_messenger_push_safari_id', 'digit', '', '', '', 9),
('bx_messenger_typing_smiles', '', @iCategId, '_bx_messenger_typing_smiles', 'checkbox', '', '', '', 10),
('bx_messenger_server_url', '', @iCategId, '_bx_messenger_server_url', 'digit', '', '', '', 11);

-- MENU: notifications
SET @iMIOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', @sName, 'notifications-messenger', '_bx_messenger_menu_notifications_item_sys_title', '_bx_messenger_menu_notifications_item_title', 'page.php?i=messenger', '', '', 'comments-o col-green1', 'a:2:{s:6:"module";s:12:"bx_messenger";s:6:"method";s:20:"get_new_messages_num";}', '', 2147483646, 1, 0, @iMIOrder + 1),
('trigger_profile_view_actions', @sName, 'messenger', '_bx_messenger_menu_new_chat_sys_title', '_bx_messenger_menu_new_chat_action_title', 'page.php?i=messenger&profile={profile_id}', '', '', 'comments-o', '', '', 2147483646, 1, 0, 0);

-- PAGE: module home
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`, `cover`) VALUES 
('bx_messenger_main', '_bx_messenger_page_title_sys_main', '_bx_messenger_page_title_main', @sName, 1, 2147483647, 1, 'messenger', 'page.php?i=messenger', '', '', '', 0, 1, 0, 'BxMessengerPageMain', 'modules/boonex/messenger/classes/BxMessengerPageMain.php', 0);

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_messenger_main', 1, @sName, '_bx_messenger_page_inbox_block_title', 0, 2147483647, 'service', 'a:3:{s:6:"module";s:12:"bx_messenger";s:6:"method";s:15:"get_block_inbox";s:6:"params";a:1:{i:0;s:2:"{}";}}', 0, 1, 0),
('bx_messenger_main', 2, @sName, '_bx_messenger_page_conversation_block_title', 0, 2147483647, 'service', 'a:3:{s:6:"module";s:12:"bx_messenger";s:6:"method";s:13:"get_block_lot";s:6:"params";a:1:{i:0;s:2:"{}";}}', 0, 1, 0);


-- PAGES: add page block to profiles modules (trigger* page objects are processed separately upon modules enable/disable)
SET @iPBCellProfile = 2;
SET @iPBCellGroup = 4;
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`, `active`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_messenger', '_bx_messenger_page_block_title_messenger', 0, 2147483647, 'service', 'a:3:{s:6:"module";s:12:"bx_messenger";s:6:"method";s:19:"get_block_messenger";s:6:"params";a:1:{i:0;s:6:"{type}";}}', 0, 0, 0, 0),
('trigger_page_group_view_entry', @iPBCellGroup, 'bx_messenger', '_bx_messenger_page_block_title_messenger', 0, 2147483647, 'service', 'a:3:{s:6:"module";s:12:"bx_messenger";s:6:"method";s:19:"get_block_messenger";s:6:"params";a:1:{i:0;s:6:"{type}";}}', 0, 0, 0, 0);

-- PAGES: add page block on home
SET @iPBCellHome = 1;
SET @iPBOrderHome = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = @iPBCellHome ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`, `active`) VALUES 
('sys_home', @iPBCellHome, @sName, '_bx_messenger_home_page_all_members_block', 0, 2147483647, 'service', 'a:3:{s:6:"module";s:12:"bx_messenger";s:6:"method";s:19:"get_block_messenger";s:6:"params";a:1:{i:0;s:7:"members";}}', 0, 1, @iPBOrderHome + 1, 0);


-- ALERTS

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_messenger', '', '', 'a:2:{s:6:"module";s:12:"bx_messenger";s:6:"method";s:24:"delete_history_by_author";}');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'delete', @iHandler);