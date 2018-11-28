-- PAGES
UPDATE `sys_pages_blocks` SET `active`='0', `order`='0' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_text';
UPDATE `sys_pages_blocks` SET `designbox_id`='11' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_subentries';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_text_and_subentries';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_polls_view_entry', 2, 'bx_polls', '', '_bx_polls_page_block_title_entry_text_and_subentries', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_polls\";s:6:\"method\";s:29:\"get_block_text_and_subentries\";}', 0, 0, 1, 2);
