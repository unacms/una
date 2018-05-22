-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_polls_view_entry', 2, 'bx_polls', '_bx_polls_page_block_title_sys_entry_context', '_bx_polls_page_block_title_entry_context', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_polls\";s:6:\"method\";s:14:\"entity_context\";}', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_polls_context';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_polls_context', 'polls-context', '_bx_polls_page_title_sys_entries_in_context', '_bx_polls_page_title_entries_in_context', 'bx_polls', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxPollsPageAuthor', 'modules/boonex/polls/classes/BxPollsPageAuthor.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_polls_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_polls_context', 1, 'bx_polls', '_bx_polls_page_block_title_sys_entries_in_context', '_bx_polls_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_polls\";s:6:\"method\";s:14:\"browse_context\";}', 0, 0, 1, 1);



-- MENUS:
DELETE FROM `sys_menu_items` WHERE `module`='bx_polls' AND `name`='polls-context' AND `title`='_bx_polls_menu_item_title_view_entries_in_context';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_group_view_submenu', 'bx_polls', 'polls-context', '_bx_polls_menu_item_title_system_view_entries_in_context', '_bx_polls_menu_item_title_view_entries_in_context', 'page.php?i=polls-context&profile_id={profile_id}', '', '', 'tasks col-green1', '', 2147483647, 1, 0, 0);
