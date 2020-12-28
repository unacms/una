SET @sName = 'bx_forum';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_forum_per_page_for_favorites_lists';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_forum_per_page_for_favorites_lists', '5', @iCategId, '_bx_forum_option_per_page_for_favorites_lists', 'digit', '', '', '', '', 17);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_view_entry' AND `title`='_bx_forum_page_block_title_entry_polls';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_view_entry', 2, @sName, '', '_bx_forum_page_block_title_entry_polls', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:12:"entity_polls";}', 0, 0, 1, 3);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_forum_author' AND `title`='_bx_forum_page_block_title_favorites_of_author';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_author' AND `title`='_bx_forum_page_block_title_entries_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_author', 1, @sName, '_bx_forum_page_block_title_sys_entries_in_context', '_bx_forum_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:14:"browse_context";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 1, 4);

DELETE FROM `sys_objects_page` WHERE `object`='bx_forum_favorites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_favorites', '_bx_forum_page_title_sys_entries_favorites', '_bx_forum_page_title_entries_favorites', @sName, 12, 2147483647, 1, 'discussions-favorites', 'page.php?i=discussions-favorites', '', '', '', 0, 1, 0, 'BxForumPageListEntry', 'modules/boonex/forum/classes/BxForumPageListEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_favorites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_forum_favorites', 2, @sName, '', '_bx_forum_page_block_title_favorites_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:15:"browse_favorite";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', 0, 1, 1),
('bx_forum_favorites', 3, @sName, '', '_bx_forum_page_block_title_favorites_entries_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:19:"favorites_list_info";}', 0, 0, 0),
('bx_forum_favorites', 3, @sName, '', '_bx_forum_page_block_title_favorites_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_forum";s:6:"method";s:22:"favorites_list_actions";}', 0, 0, 1);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_forum";s:6:"method";s:13:"browse_latest";s:6:"params";a:3:{i:0;s:5:"table";i:1;b:1;i:2;b:0;}}' WHERE `object`='bx_forum_home' AND `title`='_bx_forum_page_block_title_latest_entries';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_home' AND `title`='_bx_forum_page_block_title_browse_labels';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_forum_home', 2, @sName, '', '_bx_forum_page_block_title_browse_labels', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:13:"browse_labels";s:5:"class";s:20:"TemplServiceMetatags";}', 0, 1, 0, 0);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_forum_page_block_title_sys_my_entries' WHERE `module`=@sName AND `title`='_bx_forum_page_block_title_my_entries';


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_forum_entry_attachments';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_entry_attachments', '_bx_forum_menu_title_entry_attachments', 'bx_forum_entry_attachments', @sName, 23, 0, 1, 'BxForumMenuAttachments', 'modules/boonex/forum/classes/BxForumMenuAttachments.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_forum_entry_attachments';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_forum_entry_attachments', @sName, '_bx_forum_menu_set_title_entry_attachments', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_forum_entry_attachments';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_forum_entry_attachments', @sName, 'photo_simple', '_bx_forum_menu_item_title_system_cpa_photo_simple', '_bx_forum_menu_item_title_cpa_photo_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_photos_simple}.showUploaderForm();', '_self', 'camera', '', '', '', 2147483647, '', 0, 0, 1, 1),
('bx_forum_entry_attachments', @sName, 'photo_html5', '_bx_forum_menu_item_title_system_cpa_photo_html5', '_bx_forum_menu_item_title_cpa_photo_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_photos_html5}.showUploaderForm();', '_self', 'camera', '', '', '', 2147483647, '', 1, 0, 1, 2),
('bx_forum_entry_attachments', @sName, 'video_simple', '_bx_forum_menu_item_title_system_cpa_video_simple', '_bx_forum_menu_item_title_cpa_video_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_simple}.showUploaderForm();', '_self', 'video', '', '', '', 2147483647, '', 0, 0, 1, 3),
('bx_forum_entry_attachments', @sName, 'video_html5', '_bx_forum_menu_item_title_system_cpa_video_html5', '_bx_forum_menu_item_title_cpa_video_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_html5}.showUploaderForm();', '_self', 'video', '', '', '', 2147483647, '', 1, 0, 1, 4),
('bx_forum_entry_attachments', @sName, 'video_record_video', '_bx_forum_menu_item_title_system_cpa_video_record', '_bx_forum_menu_item_title_cpa_video_record', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_record_video}.showUploaderForm();', '_self', 'fas circle', '', '', '', 2147483647, '', 1, 0, 1, 5),
('bx_forum_entry_attachments', @sName, 'file_simple', '_bx_forum_menu_item_title_system_cpa_file_simple', '_bx_forum_menu_item_title_cpa_file_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_files_simple}.showUploaderForm();', '_self', 'file', '', '', '', 2147483647, '', 0, 0, 1, 6),
('bx_forum_entry_attachments', @sName, 'file_html5', '_bx_forum_menu_item_title_system_cpa_file_html5', '_bx_forum_menu_item_title_cpa_file_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_files_html5}.showUploaderForm();', '_self', 'file', '', '', '', 2147483647, '', 1, 0, 1, 7),
('bx_forum_entry_attachments', @sName, 'poll', '_bx_forum_menu_item_title_system_cpa_poll', '_bx_forum_menu_item_title_cpa_poll', 'javascript:void(0)', 'javascript:{js_object}.showPollForm(this);', '_self', 'tasks', '', '', '', 2147483647, '', 1, 0, 1, 7);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_forum_administration' AND `type` IN ('bulk', 'single') AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_forum_administration', 'bulk', 'clear_reports', '_bx_forum_grid_action_title_adm_clear_reports', '', 0, 1, 2),
('bx_forum_administration', 'single', 'clear_reports', '_bx_forum_grid_action_title_adm_clear_reports', 'eraser', 1, 0, 5);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_forum_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_allow_view_favorite_list', @sName, 'view_favorite_list', '_bx_forum_form_entry_input_allow_view_favorite_list', '3', 'bx_forum_favorites_lists', 'id', 'author_id', '', '');


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_forum_record_video', 'bx_forum_photos_simple', 'bx_forum_photos_html5', 'bx_forum_videos_simple', 'bx_forum_videos_html5', 'bx_forum_videos_record_video', 'bx_forum_files_simple', 'bx_forum_files_html5');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_record_video', 1, 'BxForumUploaderRecordVideo', 'modules/boonex/forum/classes/BxForumUploaderRecordVideo.php'),
('bx_forum_photos_simple', 1, 'BxForumUploaderSimpleAttach', 'modules/boonex/forum/classes/BxForumUploaderSimpleAttach.php'),
('bx_forum_photos_html5', 1, 'BxForumUploaderHTML5Attach', 'modules/boonex/forum/classes/BxForumUploaderHTML5Attach.php'),
('bx_forum_videos_simple', 1, 'BxForumUploaderSimpleAttach', 'modules/boonex/forum/classes/BxForumUploaderSimpleAttach.php'),
('bx_forum_videos_html5', 1, 'BxForumUploaderHTML5Attach', 'modules/boonex/forum/classes/BxForumUploaderHTML5Attach.php'),
('bx_forum_videos_record_video', 1, 'BxForumUploaderRecordVideoAttach', 'modules/boonex/forum/classes/BxForumUploaderRecordVideoAttach.php'),
('bx_forum_files_simple', 1, 'BxForumUploaderSimpleAttach', 'modules/boonex/forum/classes/BxForumUploaderSimpleAttach.php'),
('bx_forum_files_html5', 1, 'BxForumUploaderHTML5Attach', 'modules/boonex/forum/classes/BxForumUploaderHTML5Attach.php');
