SET @sName = 'bx_timeline';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_timeline' AND `title` IN ('_bx_timeline_page_block_title_view_feed_and_hot', '_bx_timeline_page_block_title_view_contexts_groups', '_bx_timeline_page_block_title_view_media_files', '_bx_timeline_page_block_title_view_media_images', '_bx_timeline_page_block_title_view_media_videos', '_bx_timeline_page_block_title_view_media_any');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_feed_and_hot', '_bx_timeline_page_block_title_view_feed_and_hot', 0, 0, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_block_view_custom";s:6:"params";a:1:{i:0;a:2:{s:4:"view";s:8:"timeline";s:4:"type";s:12:"feed_and_hot";}}}', 0, 1, 1, @iBlockOrder + 1),

('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_contexts_groups', '_bx_timeline_page_block_title_view_contexts_groups', 0, 0, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_block_view_custom";s:6:"params";a:1:{i:0;a:3:{s:4:"view";s:8:"timeline";s:4:"type";s:18:"connected_contexts";s:7:"context";s:9:"bx_groups";}}}', 0, 1, 1, @iBlockOrder + 2),

('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_media_files', '_bx_timeline_page_block_title_view_media_files', 0, 0, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_block_view_custom";s:6:"params";a:1:{i:0;a:3:{s:4:"view";s:8:"timeline";s:4:"type";s:4:"feed";s:5:"media";s:5:"files";}}}', 0, 1, 1, @iBlockOrder + 3),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_media_images', '_bx_timeline_page_block_title_view_media_images', 0, 0, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_block_view_custom";s:6:"params";a:1:{i:0;a:3:{s:4:"view";s:8:"timeline";s:4:"type";s:4:"feed";s:5:"media";s:6:"images";}}}', 0, 1, 1, @iBlockOrder + 4),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_media_videos', '_bx_timeline_page_block_title_view_media_videos', 0, 0, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_block_view_custom";s:6:"params";a:1:{i:0;a:3:{s:4:"view";s:8:"timeline";s:4:"type";s:4:"feed";s:5:"media";s:6:"videos";}}}', 0, 1, 1, @iBlockOrder + 5),
('', 0, 'bx_timeline', '_bx_timeline_page_block_title_system_view_media_any', '_bx_timeline_page_block_title_view_media_any', 0, 0, 2147483644, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_block_view_custom";s:6:"params";a:1:{i:0;a:3:{s:4:"view";s:8:"timeline";s:4:"type";s:4:"feed";s:5:"media";a:3:{i:0;s:5:"files";i:1;s:6:"images";i:2;s:6:"videos";}}}}', 0, 1, 1, @iBlockOrder + 6);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_view' AND `name`='feed_and_hot';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_timeline_menu_view', 'bx_timeline', 'feed_and_hot', '_bx_timeline_menu_item_title_system_feed_and_hot', '_bx_timeline_menu_item_title_feed_and_hot', 'javascript:void(0)', 'javascript:{js_object_view}.changeView(this, ''feed_and_hot'')', '_self', '', '', 2147483647, 0, 0, 5);


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_browse' LIMIT 1);
DELETE FROM `sys_options`WHERE `name`='bx_timeline_live_updates_length';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_live_updates_length', '5', @iCategId, '_bx_timeline_option_live_updates_length', 'digit', '', '', '', '', 15);

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_cache' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_enable_cache_table', 'bx_timeline_cache_table_interval');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_cache_table', '', @iCategId, '_bx_timeline_option_enable_cache_table', 'checkbox', '', '', '', '', 10),
('bx_timeline_cache_table_interval', '90', @iCategId, '_bx_timeline_option_cache_table_interval', 'digit', '', '', '', '', 11);

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_post' LIMIT 1);
DELETE FROM `sys_options`WHERE `name`='bx_timeline_enable_media_priority';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_media_priority', '', @iCategId, '_bx_timeline_option_enable_media_priority', 'checkbox', '', '', '', '', 3);
