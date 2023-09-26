SET @sName = 'bx_events';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_followed_events';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_followed_events', '_bx_events_page_title_sys_followed', '_bx_events_page_title_followed', 'bx_events', 5, 2147483647, 1, 'events-followed', 'page.php?i=events-followed', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_followed_events';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_followed_events', 1, 'bx_events', '_bx_events_page_block_title_followed_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:23:"browse_followed_entries";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;b:1;}}', 0, 1, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_submenu' AND `name`='events-followed';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_events_submenu', 'bx_events', 'events-followed', '_bx_events_menu_item_title_system_entries_followed', '_bx_events_menu_item_title_entries_followed', 'page.php?i=events-followed', '', '', '', '', 2147483647, 1, 1, 8);
