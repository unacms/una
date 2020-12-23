-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_files_author' AND `title`='_bx_files_page_block_title_entries_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_files_author', 1, 'bx_files', '_bx_files_page_block_title_sys_entries_in_context', '_bx_files_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_files";s:6:"method";s:14:"browse_context";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 1, 4);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_files";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_files_author' AND `title`='_bx_files_page_block_title_favorites_of_author';

DELETE FROM `sys_objects_page` WHERE `object`='bx_files_group';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_files_group';

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_files' AND `title`='_bx_files_page_block_title_group_entries';


-- MENU
UPDATE `sys_menu_items` SET `link`='page.php?i=create-file' WHERE `set_name`='bx_files_my' AND `name`='create-file';
