-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_albums_top';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_top', '_bx_albums_page_title_sys_entries_top', '_bx_albums_page_title_entries_top', 'bx_albums', 5, 2147483647, 1, 'albums-top', 'page.php?i=albums-top', '', '', '', 0, 1, 0, 'BxAlbumsPageBrowse', 'modules/boonex/albums/classes/BxAlbumsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_top';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_albums_top', 1, 'bx_albums', '', '_bx_albums_page_block_title_top_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:10:"browse_top";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_albums_top_media';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_top_media', '_bx_albums_page_title_sys_entries_top_media', '_bx_albums_page_title_entries_top_media', 'bx_albums', 5, 2147483647, 1, 'albums-top-media', 'page.php?i=albums-top-media', '', '', '', 0, 1, 0, 'BxAlbumsPageBrowseMedia', 'modules/boonex/albums/classes/BxAlbumsPageBrowseMedia.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_top_media';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_albums_top_media', 1, 'bx_albums', '', '_bx_albums_page_block_title_top_media', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:16:"browse_top_media";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_submenu' AND `name` IN ('albums-top', 'albums-top-media');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_albums_submenu', 'bx_albums', 'albums-top', '_bx_albums_menu_item_title_system_entries_top', '_bx_albums_menu_item_title_entries_top', 'page.php?i=albums-top', '', '', '', '', '', 2147483647, '', 1, 1, 3),
('bx_albums_submenu', 'bx_albums', 'albums-top-media', '_bx_albums_menu_item_title_system_entries_top_media', '_bx_albums_menu_item_title_entries_top_media', 'page.php?i=albums-top-media', '', '', '', '', '', 2147483647, '', 1, 1, 5);


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_albums_administration' AND `name`='audit_content';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_albums_administration', 'single', 'audit_content', '_bx_albums_grid_action_title_adm_audit_content', 'search', 1, 0, 4);
