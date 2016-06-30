-- PAGES
UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='1' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_all_actions';
UPDATE `sys_pages_blocks` SET `cell_id`='4', `order`='5' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_location' AND `designbox_id`='3';
UPDATE `sys_pages_blocks` SET `order`='2' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_location' AND `designbox_id`='13';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_view_entry' AND `title` IN ('_bx_posts_page_block_title_entry_info');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_view_entry', 3, 'bx_posts', '', '_bx_posts_page_block_title_entry_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 1);

UPDATE `sys_pages_blocks` SET `content`='a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:15:\"categories_list\";s:6:\"params\";a:2:{i:0;s:13:\"bx_posts_cats\";i:1;a:1:{s:10:\"show_empty\";b:1;}}s:5:\"class\";s:20:\"TemplServiceCategory\";}' WHERE `object`='bx_posts_home' AND `title`='_bx_posts_page_block_title_cats';

SET @iPBCellProfile = 3;
DELETE FROM `sys_pages_blocks` WHERE (`object`='trigger_page_profile_view_entry' OR `module`='bx_posts') AND `title`='_bx_posts_page_block_title_my_entries';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_posts', '_bx_posts_page_block_title_my_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"browse_author";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 0);


-- MENU:
DELETE FROM `sys_objects_menu` WHERE `object`='bx_posts_view_popup';

UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object` IN ('bx_posts_submenu', 'bx_posts_view_submenu');