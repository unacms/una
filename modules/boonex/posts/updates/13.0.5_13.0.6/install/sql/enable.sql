-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_breadcrumb';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_view_entry', 1,'bx_posts','', '_bx_posts_page_block_title_entry_breadcrumb', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:17:"entity_breadcrumb";}', 0, 0, 1, 1);
