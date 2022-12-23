-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_ads' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_ads_per_page_for_favorites_lists';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_ads_per_page_for_favorites_lists', '5', @iCategId, '_bx_ads_option_per_page_for_favorites_lists', 'digit', '', '', '', 26);


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:6:"bx_ads";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_ads_author' AND `title`='_bx_ads_page_block_title_favorites_of_author';

DELETE FROM `sys_objects_page` WHERE `object`='bx_ads_favorites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_favorites', '_bx_ads_page_title_sys_entries_favorites', '_bx_ads_page_title_entries_favorites', 'bx_ads', 12, 2147483647, 1, 'ads-favorites', 'page.php?i=ads-favorites', '', '', '', 0, 1, 0, 'BxAdsPageListEntry', 'modules/boonex/ads/classes/BxAdsPageListEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_ads_favorites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_ads_favorites', 2, 'bx_ads', '_bx_ads_page_block_title_favorites_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"bx_ads";s:6:"method";s:15:"browse_favorite";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', 0, 1, 1),
('bx_ads_favorites', 3, 'bx_ads', '_bx_ads_page_block_title_favorites_entries_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:19:"favorites_list_info";}', 0, 0, 0),
('bx_ads_favorites', 3, 'bx_ads', '_bx_ads_page_block_title_favorites_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:6:"bx_ads";s:6:"method";s:22:"favorites_list_actions";}', 0, 0, 1);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_ads_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_ads_allow_view_favorite_list', 'bx_ads', 'view_favorite_list', '_bx_ads_form_entry_input_allow_view_favorite_list', '3', 'bx_ads_favorites_lists', 'id', 'author_id', '', '');


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `tl`.`id` AS `id`, `tl`.`profile_id` AS `profile_id`, `tl`.`entry_id` AS `entry_id`, `te`.`title` AS `entry`, `tl`.`count` AS `count`, `tl`.`order` AS `transaction`, `tl`.`license` AS `license`, `tl`.`added` AS `added` FROM `bx_ads_licenses` AS `tl` LEFT JOIN `bx_ads_entries` AS `te` ON `tl`.`entry_id`=`te`.`id` LEFT JOIN `sys_profiles` AS `tup` ON `tl`.`profile_id`=`tup`.`id` LEFT JOIN `sys_accounts` AS `tua` ON `tup`.`account_id`=`tua`.`id` WHERE 1 ', `filter_fields`='te`.`title,tl`.`order,tl`.`license,tua`.`name,tua`.`email' WHERE `object`='bx_ads_licenses_administration';


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN ('bx_ads_simple', 'bx_ads_photos_simple', 'bx_ads_videos_simple', 'bx_ads_files_simple');
