-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_channels_author';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_channels_author', 'channels-author', '_bx_channels_page_title_sys_entries_of_author', '_bx_channels_page_title_entries_of_author', 'bx_channels', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxCnlPageAuthor', 'modules/boonex/channels/classes/BxCnlPageAuthor.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_channels_author';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_channels_author', 1, 'bx_channels', '_bx_channels_page_block_title_sys_favorites_of_author', '_bx_channels_page_block_title_favorites_of_author', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_channels";s:6:"method";s:15:"browse_favorite";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 1, 1),
('bx_channels_author', 1, 'bx_channels', '_bx_channels_page_block_title_sys_entries_of_author', '_bx_channels_page_block_title_entries_of_author', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_channels";s:6:"method";s:13:"browse_author";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:1:{s:13:"empty_message";b:1;}}}}', 0, 0, 1, 2);

UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:"module";s:11:"bx_channels";s:6:"method";s:13:"browse_author";}', `deletable`='0', `copyable`='1', `active`='0', `order`='0' WHERE `object`='bx_channels_home' AND `title`='_bx_channels_page_block_title_entries_my';

DELETE FROM `sys_objects_page` WHERE `object`='bx_channels_my';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_channels_my';

SET @iPBCellProfile = 3;
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_channels' AND `title`='_bx_channels_page_trigger_block_title_entries_my';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_channels', '_bx_channels_page_trigger_block_title_entries_my', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_channels";s:6:"method";s:13:"browse_author";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_submenu' AND `name`='channels-my';

DELETE FROM `sys_menu_items` WHERE `module`='bx_channels' AND `name`='channels-author' AND `title`='_bx_channels_menu_item_title_view_entries_author';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_submenu', 'bx_channels', 'channels-author', '_bx_channels_menu_item_title_system_view_entries_author', '_bx_channels_menu_item_title_view_entries_author', 'page.php?i=channels-author&profile_id={profile_id}', '', '', 'hashtag col-red2', '', 2147483647, 1, 0, 0);
