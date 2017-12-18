-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_polls";s:6:"method";s:13:"browse_public";s:6:"params";a:2:{i:0;b:0;i:1;b:0;}}' WHERE `object`='sys_home' AND `title`='_bx_polls_page_block_title_recent_entries';

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_polls";s:6:"method";s:13:"browse_author";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:1:{s:13:"empty_message";b:0;}}}}' WHERE `module`='bx_polls' AND `title`='_bx_polls_page_block_title_my_entries';


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_polls_snippet_meta';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_polls_snippet_meta', '_sys_menu_title_snippet_meta', 'bx_polls_snippet_meta', 'bx_polls', 15, 0, 1, 'BxPollsMenuSnippetMeta', 'modules/boonex/polls/classes/BxPollsMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_polls_snippet_meta';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_polls_snippet_meta', 'bx_polls', '_sys_menu_set_title_snippet_meta', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_polls_snippet_meta';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_polls_snippet_meta', 'bx_polls', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_polls_snippet_meta', 'bx_polls', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', '', '', 2147483647, 1, 0, 1, 2),
('bx_polls_snippet_meta', 'bx_polls', 'author', '_sys_menu_item_title_system_sm_author', '_sys_menu_item_title_sm_author', '', '', '', '', '', 2147483647, 1, 0, 1, 3),
('bx_polls_snippet_meta', 'bx_polls', 'category', '_sys_menu_item_title_system_sm_category', '_sys_menu_item_title_sm_category', '', '', '', '', '', 2147483647, 0, 0, 1, 4),
('bx_polls_snippet_meta', 'bx_polls', 'tags', '_sys_menu_item_title_system_sm_tags', '_sys_menu_item_title_sm_tags', '', '', '', '', '', 2147483647, 0, 0, 1, 5),
('bx_polls_snippet_meta', 'bx_polls', 'views', '_sys_menu_item_title_system_sm_views', '_sys_menu_item_title_sm_views', '', '', '', '', '', 2147483647, 0, 0, 1, 6),
('bx_polls_snippet_meta', 'bx_polls', 'actions', '_sys_menu_item_title_system_sm_actions', '_sys_menu_item_title_sm_actions', '', '', '', '', '', 2147483647, 1, 0, 1, 7);
