-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='12' WHERE `object`='bx_polls_view_entry' AND `layout_id`='10';

UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='2' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_text' AND `cell_id`='1' AND `order`='1';
UPDATE `sys_pages_blocks` SET `designbox_id`='13', `active`='0', `order`='0' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_subentries';
UPDATE `sys_pages_blocks` SET `order`='1' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_author' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_context' AND `cell_id`='2';
UPDATE `sys_pages_blocks` SET `order`='2' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_info' AND `order`='1';
UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='3' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_all_actions' AND `cell_id`='4' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='2' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_comments' AND `cell_id`='4';
UPDATE `sys_pages_blocks` SET `cell_id`='3', `order`='3' WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_entry_location' AND `cell_id`='4' AND `order`='5';
UPDATE `sys_pages_blocks` SET `active`='0', `order`='0' WHERE `object`='bx_polls_view_entry' AND `title` IN ('_bx_polls_page_block_title_entry_actions', '_bx_polls_page_block_title_entry_social_sharing', '_bx_polls_page_block_title_entry_attachments');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_polls_view_entry' AND `title`='_bx_polls_page_block_title_featured_entries';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_polls_view_entry', 3, 'bx_polls', '', '_bx_polls_page_block_title_featured_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_polls";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 0, 1, 4);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:8:\"bx_polls\";s:6:\"method\";s:14:\"browse_popular\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}' WHERE `module`='bx_polls' AND `title`='_bx_polls_page_block_title_popular_entries_view_showcase';


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_polls_view_actions';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_polls_view_actions', '_sys_menu_title_view_actions', 'bx_polls_view_actions', 'bx_polls', 15, 0, 1, 'BxPollsMenuViewActions', 'modules/boonex/polls/classes/BxPollsMenuViewActions.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_polls_view_actions';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_polls_view_actions', 'bx_polls', '_sys_menu_set_title_view_actions', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_polls_view_actions';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_polls_view_actions', 'bx_polls', 'edit-poll', '_bx_polls_menu_item_title_system_edit_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 10),
('bx_polls_view_actions', 'bx_polls', 'delete-poll', '_bx_polls_menu_item_title_system_delete_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 20),
('bx_polls_view_actions', 'bx_polls', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, 0, 0, 200),
('bx_polls_view_actions', 'bx_polls', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 210),
('bx_polls_view_actions', 'bx_polls', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 220),
('bx_polls_view_actions', 'bx_polls', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 230),
('bx_polls_view_actions', 'bx_polls', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 240),
('bx_polls_view_actions', 'bx_polls', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 250),
('bx_polls_view_actions', 'bx_polls', 'repost', '_sys_menu_item_title_system_va_repost', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 260),
('bx_polls_view_actions', 'bx_polls', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 270),
('bx_polls_view_actions', 'bx_polls', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 300),
('bx_polls_view_actions', 'bx_polls', 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 310),
('bx_polls_view_actions', 'bx_polls', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 320),
('bx_polls_view_actions', 'bx_polls', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 330),
('bx_polls_view_actions', 'bx_polls', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, 1, 0, 9999);
