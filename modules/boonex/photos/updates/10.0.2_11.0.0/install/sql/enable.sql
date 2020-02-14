-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_photos_view_entry' AND `title`='_bx_photos_page_block_title_entry_media_exif';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_photos_view_entry', 3, 'bx_photos', '', '_bx_photos_page_block_title_entry_media_exif', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_photos\";s:6:\"method\";s:10:\"media_exif\";}', 0, 0, 1, 5);

DELETE FROM `sys_objects_page` WHERE `object`='bx_photos_top';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_photos_top', '_bx_photos_page_title_sys_entries_top', '_bx_photos_page_title_entries_top', 'bx_photos', 5, 2147483647, 1, 'photos-top', 'page.php?i=photos-top', '', '', '', 0, 1, 0, 'BxPhotosPageBrowse', 'modules/boonex/photos/classes/BxPhotosPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_photos_top';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_photos_top', 1, 'bx_photos', '', '_bx_photos_page_block_title_top_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_photos";s:6:"method";s:10:"browse_top";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_photos_submenu' AND `name`='photos-top';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_photos_submenu', 'bx_photos', 'photos-top', '_bx_photos_menu_item_title_system_entries_top', '_bx_photos_menu_item_title_entries_top', 'page.php?i=photos-top', '', '', '', '', '', 2147483647, '', 1, 1, 3);


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object`='bx_photos_camera';
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_photos_camera', 'bx_photos_meta_keywords_camera', '', '', '', '');


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_photos_administration' AND `name`='audit_content';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_photos_administration', 'single', 'audit_content', '_bx_photos_grid_action_title_adm_audit_content', 'search', 1, 0, 4);
