-- PAGES
UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `object`='bx_timeline_view' AND `title`='_bx_timeline_page_block_title_view_profile';
UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `object`='bx_timeline_view' AND `title`='_bx_timeline_page_block_title_view_profile_outline';
UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `object`='bx_timeline_view_home' AND `title`='_bx_timeline_page_block_title_view_home';
UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `object`='bx_timeline_view_home' AND `title`='_bx_timeline_page_block_title_view_home_outline';
UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `object`='bx_timeline_view_hot' AND `title`='_bx_timeline_page_block_title_view_hot';
UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `object`='bx_timeline_view_hot' AND `title`='_bx_timeline_page_block_title_view_hot_outline';

SET @iPBCellDashboard = 4;
SET @iPBOrderDashboard = 1;
UPDATE `sys_pages_blocks` SET `cell_id`=@iPBCellDashboard, `order`=@iPBOrderDashboard WHERE `object`='sys_dashboard' AND `title`='_bx_timeline_page_block_title_post_account';
UPDATE `sys_pages_blocks` SET `cell_id`=@iPBCellDashboard, `designbox_id`='0', `order`=@iPBOrderDashboard+1 WHERE `object`='sys_dashboard' AND `title`='_bx_timeline_page_block_title_view_account';
UPDATE `sys_pages_blocks` SET `cell_id`=@iPBCellDashboard, `designbox_id`='0', `order`=@iPBOrderDashboard+1 WHERE `object`='sys_dashboard' AND `title`='_bx_timeline_page_block_title_view_account_outline';

UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `object`='sys_home' AND `title`='_bx_timeline_page_block_title_view_home';
UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `object`='sys_home' AND `title`='_bx_timeline_page_block_title_view_home_outline';

UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `title`='_bx_timeline_page_block_title_view_profile';
UPDATE `sys_pages_blocks` SET `designbox_id`='0' WHERE `title`='_bx_timeline_page_block_title_view_profile_outline';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-reaction';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-reaction', '_bx_timeline_menu_item_title_system_item_reaction', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 30);

UPDATE `sys_menu_items` SET `addon`='a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_menu_item_addon_comment";s:6:"params";a:3:{i:0;s:16:"{comment_system}";i:1;s:16:"{comment_object}";i:2;a:3:{s:4:"name";s:6:"{name}";s:4:"view";s:6:"{view}";s:4:"type";s:6:"{type}";}}}' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-comment';
UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-vote';
UPDATE `sys_menu_items` SET `onclick`='bx_menu_popup(''bx_timeline_menu_item_share'', this, {''id'':''bx_timeline_menu_item_share_{content_id}''}, {content_id:{content_id}, name:''{name}'', view:''{view}'', type:''{type}''});' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-share';
UPDATE `sys_menu_items` SET `onclick`='bx_menu_popup(''bx_timeline_menu_item_manage'', this, {''id'':''bx_timeline_menu_item_manage_{content_id}''}, {content_id:{content_id}, name:''{name}'', view:''{view}'', type:''{type}''});' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-more';

UPDATE `sys_objects_menu` SET `template_id`='23' WHERE `object`='bx_timeline_menu_post_attachments';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name` IN ('add-photo', 'add-video', 'add-photo-simple', 'add-photo-html5', 'add-video-simple', 'add-video-html5');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-photo-simple', '_bx_timeline_menu_item_title_system_add_photo_simple', '_bx_timeline_menu_item_title_add_photo', 'javascript:void(0)', 'javascript:{js_object_uploader_simple_photo}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, '', 0, 0, 1, 2),
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-photo-html5', '_bx_timeline_menu_item_title_system_add_photo_html5', '_bx_timeline_menu_item_title_add_photo', 'javascript:void(0)', 'javascript:{js_object_uploader_html5_photo}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, '', 1, 0, 1, 3),
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-video-simple', '_bx_timeline_menu_item_title_system_add_video_simple', '_bx_timeline_menu_item_title_add_video', 'javascript:void(0)', 'javascript:{js_object_uploader_simple_video}.showUploaderForm();', '_self', 'video', '', '', 2147483647, '', 0, 0, 1, 4),
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-video-html5', '_bx_timeline_menu_item_title_system_add_video_html5', '_bx_timeline_menu_item_title_add_video', 'javascript:void(0)', 'javascript:{js_object_uploader_html5_video}.showUploaderForm();', '_self', 'video', '', '', 2147483647, '', 1, 0, 1, 5);

UPDATE `sys_menu_items` SET `icon`='clock' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='timeline-administration' AND `icon`='';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_enable_count_all_views', 'bx_timeline_enable_cache_item', 'bx_timeline_cache_item_engine', 'bx_timeline_cache_item_lifetime', 'bx_timeline_enable_cache_list');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_count_all_views', '', @iCategId, '_bx_timeline_option_enable_count_all_views', 'checkbox', '', '', '', '', 6),
('bx_timeline_enable_cache_item', 'on', @iCategId, '_bx_timeline_option_enable_cache_item', 'checkbox', '', '', '', '', 70),
('bx_timeline_cache_item_engine', 'File', @iCategId, '_bx_timeline_option_cache_item_engine', 'select', '', '', '', 'File,Memcache,APC,XCache', 71),
('bx_timeline_cache_item_lifetime', '604800', @iCategId, '_bx_timeline_option_cache_item_lifetime', 'digit', '', '', '', '', 72),
('bx_timeline_enable_cache_list', 'on', @iCategId, '_bx_timeline_option_enable_cache_list', 'checkbox', '', '', '', '', 73);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_timeline' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action`='clear_cache' AND `handler_id`=@iHandler;
DELETE FROM `sys_alerts` WHERE `unit`='bx_timeline_videos_mp4' AND `action`='transcoded' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'clear_cache', @iHandler),
('bx_timeline_videos_mp4', 'transcoded', @iHandler);


-- GRIDS:
UPDATE `sys_objects_grid` SET `source`='SELECT * FROM `bx_timeline_events` WHERE 1 AND `active`=''1'' ', `field_active`='status' WHERE `object`='bx_timeline_administration';

UPDATE `sys_grid_fields` SET `order`='3' WHERE `object`='bx_timeline_administration' AND `name`='reports';
UPDATE `sys_grid_fields` SET `order`='4' WHERE `object`='bx_timeline_administration' AND `name`='description';


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_timeline_publishing';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_timeline_publishing', '* * * * *', 'BxTimelineCronPublishing', 'modules/boonex/timeline/classes/BxTimelineCronPublishing.php', '');
