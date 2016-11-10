-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_persons@modules/boonex/persons/|std-icon.svg' WHERE `name`='bx_persons';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `title` IN ('_bx_persons_page_block_title_profile_all_actions');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_persons_view_profile', 3, 'bx_persons', '', '_bx_persons_page_block_title_profile_all_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_persons";s:6:"method";s:18:"entity_all_actions";}', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_persons_profile_favorites';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_profile_favorites', 'persons-profile-favorites', '_bx_persons_page_title_sys_profile_favorites', '_bx_persons_page_title_profile_favorites', 'bx_persons', 5, 2147483647, 1, 'page.php?i=persons-profile-favorites', '', '', '', 0, 1, 0, 'BxPersonsPageBrowse', 'modules/boonex/persons/classes/BxPersonsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_profile_favorites' AND `title` IN ('_bx_persons_page_block_title_profile_favorites');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_persons_profile_favorites', 1, 'bx_persons', '_bx_persons_page_block_title_system_profile_favorites', '_bx_persons_page_block_title_profile_favorites', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_persons";s:6:"method";s:15:"browse_favorite";}', 0, 1, 1, 1);


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-favorite-persons';
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_persons', 'profile-stats-favorite-persons', '_bx_persons_menu_item_title_system_favorites', '_bx_persons_menu_item_title_favorites', 'page.php?i=persons-profile-favorites&profile_id={member_id}', '', '', 'star col-blue3', 'a:2:{s:6:"module";s:10:"bx_persons";s:6:"method";s:38:"get_menu_addon_favorites_profile_stats";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 1);


-- FAFORITES
DELETE FROM `sys_objects_favorite` WHERE `name`='bx_persons';
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_persons', 'bx_persons_favorites_track', '1', '1', '0', 'page.php?i=view-persons-profile&id={object_id}', 'bx_persons_data', 'id', 'author', 'favorites', '', '');


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_persons';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_persons', 'bx_persons_reports', 'bx_persons_reports_track', '1', 'page.php?i=view-persons-profile&id={object_id}', 'bx_persons_data', 'id', 'author', 'reports', '', '');