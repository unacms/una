-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_attachments';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_view_entry', 4, 'bx_posts', '_bx_posts_page_block_title_entry_attachments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:18:\"entity_attachments\";}', 0, 0, 1, 1);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:14:"browse_popular";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}' WHERE `object`='bx_posts_popular' AND `title`='_bx_posts_page_block_title_popular_entries';

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"browse_public";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}' WHERE `object`='bx_posts_home' AND `title`='_bx_posts_page_block_title_recent_entries';

UPDATE `sys_pages_blocks` SET `title_system`='_bx_posts_page_block_title_system_manage' WHERE `object`='bx_posts_manage' AND `title`='_bx_posts_page_block_title_manage';

DELETE FROM `sys_objects_page` WHERE `object`='bx_posts_moderation';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_moderation';

UPDATE `sys_objects_page` SET `title_system`='_bx_posts_page_title_sys_manage_administration', `visible_for_levels`='192' WHERE `object`='bx_posts_administration';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_posts_page_block_title_system_manage_administration', `visible_for_levels`='192' WHERE `object`='bx_posts_administration' AND `title`='_bx_posts_page_block_title_manage';

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_posts' AND `title` IN ('_bx_posts_page_block_title_recent_entries_view_extended', '_bx_posts_page_block_title_recent_entries_view_full', '_bx_posts_page_block_title_popular_entries_view_extended', '_bx_posts_page_block_title_popular_entries_view_full');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('', 0, 'bx_posts', '_bx_posts_page_block_title_recent_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"browse_public";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 1),
('', 0, 'bx_posts', '_bx_posts_page_block_title_recent_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"browse_public";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 2),
('', 0, 'bx_posts', '_bx_posts_page_block_title_popular_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:14:"browse_popular";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 3),
('', 0, 'bx_posts', '_bx_posts_page_block_title_popular_entries_view_full', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:14:"browse_popular";s:6:"params";a:1:{i:0;s:4:"full";}}', 0, 1, IFNULL(@iBlockOrder, 0) + 4);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='posts-moderation';
UPDATE `sys_menu_items` SET `visible_for_levels`='192' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='posts-administration';


-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
UPDATE `sys_objects_search` SET `Order`=@iSearchOrder + 1 WHERE `ObjectName`='bx_posts';
UPDATE `sys_objects_search` SET `Order`=@iSearchOrder + 2 WHERE `ObjectName`='bx_posts_cmts';


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_posts_moderation';
DELETE FROM `sys_grid_fields` WHERE `object`='bx_posts_moderation';
DELETE FROM `sys_grid_actions` WHERE `object`='bx_posts_moderation';

UPDATE `sys_objects_grid` SET `field_order`='added' WHERE `object` IN ('bx_posts_administration', 'bx_posts_common');

UPDATE `sys_grid_actions` SET `order`='1' WHERE `object`='bx_posts_administration' AND `type`='bulk' AND `name`='delete';
UPDATE `sys_grid_actions` SET `order`='1' WHERE `object`='bx_posts_common' AND `type`='bulk' AND `name`='delete';