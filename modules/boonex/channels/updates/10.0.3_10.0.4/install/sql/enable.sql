-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_channels_toplevel';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_channels_toplevel', '_bx_channels_page_title_sys_entries_toplevel', '_bx_channels_page_title_entries_toplevel', 'bx_channels', 5, 2147483647, 1, 'channels-toplevel', 'page.php?i=toplevel', '', '', '', 0, 1, 0, 'BxCnlPageBrowse', 'modules/boonex/channels/classes/BxCnlPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_channels_toplevel';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_channels_toplevel', 1, 'bx_channels', '', '_bx_channels_page_block_title_toplevel', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:11:\"bx_channels\";s:6:\"method\";s:15:\"browse_by_level\";s:6:"params";a:2:{i:0;i:0;i:1;b:1;}}', 0, 1, 1, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_submenu' AND `name`='channels-toplevel';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_channels_submenu', 'bx_channels', 'channels-toplevel', '_bx_channels_menu_item_title_system_entries_toplevel', '_bx_channels_menu_item_title_entries_toplevel', 'page.php?i=channels-toplevel', '', '', '', '', '', 2147483647, '', 1, 1, 3);
