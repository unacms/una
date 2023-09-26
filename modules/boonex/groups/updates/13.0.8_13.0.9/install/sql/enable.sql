-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_followed_groups';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_followed_groups', '_bx_groups_page_title_sys_followed', '_bx_groups_page_title_followed', 'bx_groups', 5, 2147483647, 1, 'groups-followed', 'page.php?i=groups-followed', '', '', '', 0, 1, 0, 'BxGroupsPageBrowse', 'modules/boonex/groups/classes/BxGroupsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_followed_groups';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_followed_groups', 1, 'bx_groups', '_bx_groups_page_block_title_followed_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:23:"browse_followed_entries";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;b:1;}}', 0, 1, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_submenu' AND `name`='groups-followed';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_submenu', 'bx_groups', 'groups-followed', '_bx_groups_menu_item_title_system_entries_followed', '_bx_groups_menu_item_title_entries_followed', 'page.php?i=groups-followed', '', '', '', '', 2147483647, 1, 1, 5);
