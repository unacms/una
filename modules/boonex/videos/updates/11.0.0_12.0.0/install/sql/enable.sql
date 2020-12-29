-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_videos' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_videos_per_page_for_favorites_lists', 'bx_videos_auto_activation_for_categories');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_videos_per_page_for_favorites_lists', '5', @iCategId, '_bx_videos_option_per_page_for_favorites_lists', 'digit', '', '', '', '', 17),
('bx_videos_auto_activation_for_categories', 'on', @iCategId, '_bx_videos_option_auto_activation_for_categories', 'checkbox', '', '', '', '', 35);


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_videos";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_videos_author' AND `title`='_bx_videos_page_block_title_favorites_of_author';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_videos_author' AND `title`='_bx_videos_page_block_title_entries_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_videos_author', 1, 'bx_videos', '_bx_videos_page_block_title_sys_entries_in_context', '_bx_videos_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_videos";s:6:"method";s:14:"browse_context";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 1, 4);


DELETE FROM `sys_objects_page` WHERE `object`='bx_videos_favorites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_videos_favorites', '_bx_videos_page_title_sys_entries_favorites', '_bx_videos_page_title_entries_favorites', 'bx_videos', 12, 2147483647, 1, 'videos-favorites', 'page.php?i=videos-favorites', '', '', '', 0, 1, 0, 'BxVideosPageListEntry', 'modules/boonex/videos/classes/BxVideosPageListEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_videos_favorites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_videos_favorites', 2, 'bx_videos', '', '_bx_videos_page_block_title_favorites_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_videos";s:6:"method";s:15:"browse_favorite";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', 0, 1, 1),
('bx_videos_favorites', 3, 'bx_videos', '', '_bx_videos_page_block_title_favorites_entries_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_videos";s:6:"method";s:19:"favorites_list_info";}', 0, 0, 0),
('bx_videos_favorites', 3, 'bx_videos', '', '_bx_videos_page_block_title_favorites_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_videos";s:6:"method";s:22:"favorites_list_actions";}', 0, 0, 1);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_videos_home' AND `title`='_bx_videos_page_block_title_multicats';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_videos_home', 2, 'bx_videos', '_bx_videos_page_block_title_sys_multicats', '_bx_videos_page_block_title_multicats', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_videos";s:6:"method";s:21:"categories_multi_list";}', 0, 0, 0, 2);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_videos_page_block_title_sys_my_entries' WHERE `module`='bx_videos' AND `title`='_bx_videos_page_block_title_my_entries';

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_videos' AND `title`='_bx_videos_page_block_title_by_categoriers_entries_view_showcase';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('', 0, 'bx_videos', '_bx_videos_page_block_title_sys_by_categoriers_entries_view_showcase', '_bx_videos_page_block_title_by_categoriers_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_videos\";s:6:\"method\";s:20:\"browse_by_categories\";s:6:\"params\";a:5:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;s:6:\"browse\";s:7:\"popular\";s:12:\"per_category\";i:3;}}', 0, 1, IFNULL(@iBlockOrder, 0) + 1);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_videos_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_videos_allow_view_favorite_list', 'bx_videos', 'view_favorite_list', '_bx_videos_form_entry_input_allow_view_favorite_list', '3', 'bx_videos_favorites_lists', 'id', 'author_id', '', '');


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_videos_administration' AND `type` IN ('bulk', 'single') AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_videos_administration', 'bulk', 'clear_reports', '_bx_videos_grid_action_title_adm_clear_reports', '', 0, 1, 2),
('bx_videos_administration', 'single', 'clear_reports', '_bx_videos_grid_action_title_adm_clear_reports', 'eraser', 1, 0, 5);


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object`='bx_videos_record_video';
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_videos_record_video', 1, 'BxVideosUploaderRecordVideo', 'modules/boonex/videos/classes/BxVideosUploaderRecordVideo.php');
