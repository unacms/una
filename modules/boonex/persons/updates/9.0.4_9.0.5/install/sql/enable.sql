-- PAGE: profile friend requests
DELETE FROM `sys_objects_page` WHERE `object`='bx_persons_friend_requests';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_friend_requests', 'persons-friend-requests', '_bx_persons_page_title_sys_friend_requests', '_bx_persons_page_title_friend_requests', 'bx_persons', 5, 2147483647, 1, 'page.php?i=persons-friend-requests', '', '', '', 0, 1, 0, 'BxPersonsPageEntry', 'modules/boonex/persons/classes/BxPersonsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_friend_requests';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_persons_friend_requests', 1, 'bx_persons', '_bx_persons_page_block_title_system_friend_requests', '_bx_persons_page_block_title_friend_requests_link', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:25:\"connections_request_table\";s:5:\"class\";s:23:\"TemplServiceConnections\";}', 0, 0, 1, 1);
