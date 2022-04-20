SET @sName = 'bx_timeline';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_item' AND `title`='_bx_timeline_page_block_title_entry_reports';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_timeline_item', 1, 'bx_timeline', '', '_bx_timeline_page_block_title_entry_reports', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_timeline\";s:6:\"method\";s:14:\"entity_reports\";}', 0, 0, 1, 6);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_manage' AND `title`='_bx_timeline_page_block_title_manage_own';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_timeline_manage', 1, 'bx_timeline', '', '_bx_timeline_page_block_title_manage_own', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:12:"manage_tools";}', 0, 0, 1, 1);

UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='trigger_page_group_view_entry' AND `title`='_bx_timeline_page_block_title_post_profile';

DELETE FROM `sys_pages_blocks` WHERE `object`='trigger_page_group_view_entry' AND `title`='_sys_page_block_title_create_post_context';
SET @iPBCellGroup = 4;
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('trigger_page_group_view_entry', @iPBCellGroup, 'system', '_sys_page_block_title_sys_create_post_context', '_sys_page_block_title_create_post_context', 11, 1, 4, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:20:"get_create_post_form";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}s:5:"class";s:13:"TemplServices";}', 0, 0, 1, 0);

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_timeline' AND `title` IN ('_bx_timeline_page_block_title_menu_db', '_bx_timeline_page_block_title_views_db');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_menu_db', '_bx_timeline_page_block_title_menu_db', 11, 0, 2147483644, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:17:"get_block_menu_db";}', 0, 1, 1, @iBlockOrder + 1),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_views_db', '_bx_timeline_page_block_title_views_db', 11, 0, 2147483644, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:18:"get_block_views_db";}', 0, 1, 1, @iBlockOrder + 2);


-- MENUS
DELETE FROM `sys_menu_templates` WHERE `template`='menu_feeds.html' AND `title`='_bx_timeline_menu_template_title_feeds';
INSERT INTO `sys_menu_templates` (`id`, `template`, `title`, `visible`) VALUES
(ROUND(RAND()*(9999 - 1000) + 1000), 'menu_feeds.html', '_bx_timeline_menu_template_title_feeds', 1);
SET @iTemplId = (SELECT `id` FROM `sys_menu_templates` WHERE `template`='menu_feeds.html' AND `title`='_bx_timeline_menu_template_title_feeds' LIMIT 1);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_timeline_menu_feeds';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_feeds', '_bx_timeline_menu_title_feeds', 'bx_timeline_menu_feeds', 'bx_timeline', @iTemplId, 0, 1, 'BxTimelineMenuFeeds', 'modules/boonex/timeline/classes/BxTimelineMenuFeeds.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_timeline_menu_feeds';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_feeds', 'bx_timeline', '_bx_timeline_menu_set_title_feeds', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_feeds';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_timeline_menu_feeds', 'bx_timeline', 'feed', '_bx_timeline_menu_item_title_system_feed', '_bx_timeline_menu_item_title_feed', 'javascript:void(0)', 'javascript:{js_object_view}.changeFeed(this, ''feed'')', '_self', '', '', 2147483647, 1, 0, 1),
('bx_timeline_menu_feeds', 'bx_timeline', 'public', '_bx_timeline_menu_item_title_system_public', '_bx_timeline_menu_item_title_public', 'javascript:void(0)', 'javascript:{js_object_view}.changeFeed(this, ''public'')', '_self', '', '', 2147483647, 1, 0, 2),
('bx_timeline_menu_feeds', 'bx_timeline', 'hot', '_bx_timeline_menu_item_title_system_hot', '_bx_timeline_menu_item_title_hot', 'javascript:void(0)', 'javascript:{js_object_view}.changeFeed(this, ''hot'')', '_self', '', '', 2147483647, 1, 0, 3),
('bx_timeline_menu_feeds', 'bx_timeline', 'divider', '_bx_timeline_menu_item_title_system_divider', '', '', '', '', '', '', 2147483647, 1, 1, 4);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_share' AND `name`='item-repost-with';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_timeline_menu_item_share', 'bx_timeline', 'item-repost-with', '_bx_timeline_menu_item_title_system_item_repost_with', '_bx_timeline_menu_item_title_item_repost_with', 'javascript:void(0)', 'javascript:{repost_with_onclick}', '_self', 'redo', '', 2147483647, 1, 0, 2);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_manage' AND `name`='item-notes';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '_self', 'exclamation-triangle', '', 2147483647, 1, 0, 7);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_counters' AND `name`='item-repost';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_timeline_menu_item_counters', 'bx_timeline', 'item-repost', '_bx_timeline_menu_item_title_system_item_repost', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 1, 0, 1, 50);

UPDATE `sys_menu_items` SET `name`='profile-stats-my-timeline' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-timeline';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_enable_auto_approve';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_auto_approve', 'on', @iCategId, '_bx_timeline_option_enable_auto_approve', 'checkbox', '', '', '', '', 0);


SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_browse' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_hot_sources', 'bx_timeline_hot_threshold_age', 'bx_timeline_hot_threshold_comment', 'bx_timeline_hot_threshold_vote');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_hot_sources', 'content,comment,vote', @iCategId, '_bx_timeline_option_hot_sources', 'list', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:25:"get_hot_sources_checklist";}', 31),
('bx_timeline_hot_threshold_age', '0', @iCategId, '_bx_timeline_option_hot_threshold_age', 'digit', '', '', '', '', 32),
('bx_timeline_hot_threshold_comment', '1', @iCategId, '_bx_timeline_option_hot_threshold_comment', 'digit', '', '', '', '', 33),
('bx_timeline_hot_threshold_vote', '2', @iCategId, '_bx_timeline_option_hot_threshold_vote', 'digit', '', '', '', '', 34);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_enable_cache_list';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_post' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_editor_auto_attach_insertion';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_editor_auto_attach_insertion', '', @iCategId, '_bx_timeline_option_editor_auto_attach_insertion', 'checkbox', '', '', '', '', 2);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_timeline' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action` IN ('enable', 'disable') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'enable', @iHandler),
('system', 'disable', @iHandler);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_timeline_common';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_common', 'Sql', 'SELECT * FROM `bx_timeline_events` WHERE 1 AND `active`=''1'' ', 'bx_timeline_events', 'id', 'date', 'status', '', 20, NULL, 'start', '', 'title,description', '', 'like', '', '', 2147483647, 'BxTimelineGridCommon', 'modules/boonex/timeline/classes/BxTimelineGridCommon.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_timeline_common';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_timeline_common', 'checkbox', '_sys_select', '2%', 0, 0, '', 1),
('bx_timeline_common', 'switcher', '_bx_timeline_grid_column_title_adm_active', '8%', 0, 0, '', 2),
('bx_timeline_common', 'description', '_bx_timeline_grid_column_title_adm_description', '30%', 0, 25, '', 3),
('bx_timeline_common', 'date', '_bx_timeline_grid_column_title_adm_added', '20%', 1, 25, '', 4),
('bx_timeline_common', 'status_admin', '_bx_posts_grid_column_title_adm_status_admin', '20%', 0, 16, '', 5),
('bx_timeline_common', 'actions', '', '20%', 0, 0, '', 6);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_timeline_common';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_timeline_common', 'bulk', 'delete', '_bx_timeline_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_timeline_common', 'single', 'delete', '_bx_timeline_grid_action_title_adm_delete', 'remove', 1, 1, 2);


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_timeline' WHERE `object`='bx_timeline';


-- CONNECTIONS
UPDATE `sys_objects_connection` SET `profile_initiator`='1', `profile_content`='1' WHERE `object`='bx_timeline_mute';
