-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_convos_view_entry' AND `title`='_bx_cnv_page_block_title_entry_breadcrumb';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_convos_view_entry', 1, 'bx_convos', '_bx_cnv_page_block_title_entry_breadcrumb', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_convos";s:6:"method";s:17:"entity_breadcrumb";}', 0, 0, 1, 0);