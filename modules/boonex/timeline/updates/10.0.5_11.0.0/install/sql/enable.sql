SET @sName = 'bx_timeline';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='sys_dashboard' AND `title_system` IN ('_bx_timeline_page_block_title_system_views_timeline', '_bx_timeline_page_block_title_system_views_outline');
SET @iPBCellDashboard = 2;
SET @iPBOrderDashboard = 1;
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_dashboard', @iPBCellDashboard, 'bx_timeline', '_bx_timeline_page_block_title_system_views_timeline', '_bx_timeline_page_block_title_views_timeline', 3, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:24:"get_block_views_timeline";s:6:"params";a:1:{i:0;s:4:"feed";}}', 0, 0, 0, @iPBOrderDashboard + 1),
('sys_dashboard', @iPBCellDashboard, 'bx_timeline', '_bx_timeline_page_block_title_system_views_outline', '_bx_timeline_page_block_title_views_outline', 3, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:23:"get_block_views_outline";s:6:"params";a:1:{i:0;s:4:"feed";}}', 0, 0, 0, @iPBOrderDashboard + 2);

UPDATE `sys_pages_blocks` SET `visible_for_levels`='2147483644', `active`='0' WHERE `object`='sys_home' AND `module`=@sName AND `title_system`='_bx_timeline_page_block_title_system_post_home';
UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='sys_home' AND `module`=@sName AND `title_system`='_bx_timeline_page_block_title_system_view_home';
UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='sys_home' AND `module`=@sName AND `title_system`='_bx_timeline_page_block_title_system_view_home_outline';


DELETE FROM `sys_pages_blocks` WHERE `object`='sys_home' AND `title_system` IN ('_bx_timeline_page_block_title_system_views_timeline');
SET @iPBCellHome = 3;
SET @iPBOrderHome = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = @iPBCellHome ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('sys_home', @iPBCellHome, 'bx_timeline', '_bx_timeline_page_block_title_system_views_timeline', '_bx_timeline_page_block_title_views_timeline', 3, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:24:"get_block_views_timeline";s:6:"params";a:1:{i:0;s:4:"feed";}}', 0, 0, 1, @iPBOrderHome + 2);


DELETE FROM `sys_pages_blocks` WHERE `object`='sys_home' AND `title_system` IN ('_bx_timeline_page_block_title_system_view_home_outline');
SET @iPBCellHome = 4;
SET @iPBOrderHome = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = @iPBCellHome ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('sys_home', @iPBCellHome, 'bx_timeline', '_bx_timeline_page_block_title_system_view_home_outline', '_bx_timeline_page_block_title_view_home_outline', 0, 1, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_block_view_home_outline";}', 0, 0, 1, @iPBOrderHome + 1);

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_timeline' AND `title_system` IN ('_bx_timeline_page_block_title_system_views_timeline', '_bx_timeline_page_block_title_system_views_outline', '_bx_timeline_page_block_title_system_view_home', '_bx_timeline_page_block_title_system_view_home_outline', '_bx_timeline_page_block_title_system_view_account', '_bx_timeline_page_block_title_system_view_account_outline');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_views_timeline', '_bx_timeline_page_block_title_views_timeline', 3, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:24:"get_block_views_timeline";}', 0, 1, 1, @iPBOrderHome + 1),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_views_outline', '_bx_timeline_page_block_title_views_outline', 3, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:23:"get_block_views_outline";}', 0, 1, 1, @iPBOrderHome + 2),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_home', '_bx_timeline_page_block_title_view_home', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:19:"get_block_view_home";}', 0, 1, 1, @iBlockOrder + 3),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_home_outline', '_bx_timeline_page_block_title_view_home_outline', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_block_view_home_outline";}', 0, 1, 1, @iBlockOrder + 4),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_account', '_bx_timeline_page_block_title_view_account', 0, 2147483644, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_account";}', 0, 1, 1, @iBlockOrder + 5),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_account_outline', '_bx_timeline_page_block_title_view_account_outline', 0, 2147483644, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:30:"get_block_view_account_outline";}', 0, 1, 1, @iBlockOrder + 6);


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_timeline_menu_view';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_view', '_bx_timeline_menu_title_view', 'bx_timeline_menu_view', 'bx_timeline', 22, 0, 1, 'BxTimelineMenuView', 'modules/boonex/timeline/classes/BxTimelineMenuView.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_timeline_menu_view';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_view', 'bx_timeline', '_bx_timeline_menu_set_title_view', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_view';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_timeline_menu_view', 'bx_timeline', 'feed', '_bx_timeline_menu_item_title_system_feed', '_bx_timeline_menu_item_title_feed', 'javascript:void(0)', 'javascript:{js_object_view}.changeView(this, ''feed'')', '_self', '', '', '', 2147483647, '', 1, 0, 1),
('bx_timeline_menu_view', 'bx_timeline', 'public', '_bx_timeline_menu_item_title_system_public', '_bx_timeline_menu_item_title_public', 'javascript:void(0)', 'javascript:{js_object_view}.changeView(this, ''public'')', '_self', '', '', '', 2147483647, '', 1, 0, 2),
('bx_timeline_menu_view', 'bx_timeline', 'channels', '_bx_timeline_menu_item_title_system_channels', '_bx_timeline_menu_item_title_channels', 'javascript:void(0)', 'javascript:{js_object_view}.changeView(this, ''channels'')', '_self', '', '', '', 2147483647, '', 0, 0, 3),
('bx_timeline_menu_view', 'bx_timeline', 'hot', '_bx_timeline_menu_item_title_system_hot', '_bx_timeline_menu_item_title_hot', 'javascript:void(0)', 'javascript:{js_object_view}.changeView(this, ''hot'')', '_self', '', '', '', 2147483647, '', 1, 0, 4);

UPDATE `sys_menu_items` SET `addon`='' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-comment';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_timeline_menu_item_actions_all';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_item_actions_all', '_bx_timeline_menu_title_item_actions_all', 'bx_timeline_menu_item_actions_all', 'bx_timeline', 15, 0, 1, 'BxTimelineMenuItemActionsAll', 'modules/boonex/timeline/classes/BxTimelineMenuItemActionsAll.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_timeline_menu_item_actions_all';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_item_actions_all', 'bx_timeline', '_bx_timeline_menu_set_title_item_actions_all', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions_all';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-view', '_bx_timeline_menu_item_title_system_item_view', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 1, 0),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-comment', '_bx_timeline_menu_item_title_system_item_comment', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 10),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-vote', '_bx_timeline_menu_item_title_system_item_vote', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 1, 20),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-reaction', '_bx_timeline_menu_item_title_system_item_reaction', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 30),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-score', '_bx_timeline_menu_item_title_system_item_score', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 1, 40),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-share', '_bx_timeline_menu_item_title_system_item_share', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 50),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-pin', '_bx_timeline_menu_item_title_system_item_pin', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 100),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-unpin', '_bx_timeline_menu_item_title_system_item_unpin', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 110),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-stick', '_bx_timeline_menu_item_title_system_item_stick', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 120),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-unstick', '_bx_timeline_menu_item_title_system_item_unstick', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 130),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-promote', '_bx_timeline_menu_item_title_system_item_promote', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 140),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-unpromote', '_bx_timeline_menu_item_title_system_item_unpromote', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 150),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-report', '_bx_timeline_menu_item_title_system_item_report', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 160),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-edit', '_bx_timeline_menu_item_title_system_item_edit', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 170),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-delete', '_bx_timeline_menu_item_title_system_item_delete', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 180),
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, '', 1, 0, 1, 9999);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_timeline_menu_item_counters';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_item_counters', '_bx_timeline_menu_title_item_counters', 'bx_timeline_menu_item_counters', 'bx_timeline', 15, 0, 1, 'BxTimelineMenuItemCounters', 'modules/boonex/timeline/classes/BxTimelineMenuItemCounters.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_timeline_menu_item_counters';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_item_counters', 'bx_timeline', '_bx_timeline_menu_set_title_item_counters', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_counters';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_timeline_menu_item_counters', 'bx_timeline', 'item-vote', '_bx_timeline_menu_item_title_system_item_vote', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, '', 0, 0, 1, 0),
('bx_timeline_menu_item_counters', 'bx_timeline', 'item-reaction', '_bx_timeline_menu_item_title_system_item_reaction', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 10),
('bx_timeline_menu_item_counters', 'bx_timeline', 'item-score', '_bx_timeline_menu_item_title_system_item_score', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, '', 0, 0, 1, 20),
('bx_timeline_menu_item_counters', 'bx_timeline', 'item-view', '_bx_timeline_menu_item_title_system_item_view', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, '', 0, 0, 1, 30),
('bx_timeline_menu_item_counters', 'bx_timeline', 'item-comment', '_bx_timeline_menu_item_title_system_item_comment', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 40);


UPDATE `sys_objects_menu` SET `override_class_name`='BxTimelineMenuPostAttachments', `override_class_file`='modules/boonex/timeline/classes/BxTimelineMenuPostAttachments.php' WHERE `object`='bx_timeline_menu_post_attachments';

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name`='add-emoji';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_enable_infinite_scroll', 'bx_timeline_events_per_preload', 'bx_timeline_auto_preloads', 'bx_timeline_attachments_layout');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_infinite_scroll', 'on', @iCategId, '_bx_timeline_option_enable_infinite_scroll', 'checkbox', '', '', '', '', 15),
('bx_timeline_events_per_preload', '10', @iCategId, '_bx_timeline_option_events_per_preload', 'digit', '', '', '', '', 16),
('bx_timeline_auto_preloads', '10', @iCategId, '_bx_timeline_option_auto_preloads', 'digit', '', '', '', '', 17),
('bx_timeline_attachments_layout', 'gallery', @iCategId, '_bx_timeline_option_attachments_layout', 'select', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:30:"get_options_attachments_layout";}', 53);

UPDATE `sys_options` SET `value`='on' WHERE `name`='bx_timeline_enable_hot';


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_timeline_administration' AND `name`='audit_content';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_timeline_administration', 'single', 'audit_content', '_bx_timeline_grid_action_title_adm_audit_content', 'search', 1, 0, 4);
