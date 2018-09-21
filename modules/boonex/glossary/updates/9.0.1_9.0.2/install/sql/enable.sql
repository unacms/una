-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:\"module\";s:11:\"bx_glossary\";s:6:\"method\";s:14:\"browse_popular\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}' WHERE `module`='bx_glossary' AND `title`='_bx_glossary_page_block_title_popular_entries_view_showcase';


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_glossary_view_actions';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_glossary_view_actions', '_sys_menu_title_view_actions', 'bx_glossary_view_actions', 'bx_glossary', 15, 0, 1, 'BxGlsrMenuViewActions', 'modules/boonex/glossary/classes/BxGlsrMenuViewActions.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_glossary_view_actions';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_glossary_view_actions', 'bx_glossary', '_sys_menu_set_title_view_actions', 0);

DELETE FROM `sys_menu_items`  WHERE `set_name`='bx_glossary_view_actions';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_glossary_view_actions', 'bx_glossary', 'edit-glossary', '_bx_glossary_menu_item_title_system_edit_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 10),
('bx_glossary_view_actions', 'bx_glossary', 'delete-glossary', '_bx_glossary_menu_item_title_system_delete_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 20),
('bx_glossary_view_actions', 'bx_glossary', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, 0, 0, 200),
('bx_glossary_view_actions', 'bx_glossary', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 210),
('bx_glossary_view_actions', 'bx_glossary', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 220),
('bx_glossary_view_actions', 'bx_glossary', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 230),
('bx_glossary_view_actions', 'bx_glossary', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 240),
('bx_glossary_view_actions', 'bx_glossary', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 250),
('bx_glossary_view_actions', 'bx_glossary', 'repost', '_sys_menu_item_title_system_va_repost', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 260),
('bx_glossary_view_actions', 'bx_glossary', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 270),
('bx_glossary_view_actions', 'bx_glossary', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 300),
('bx_glossary_view_actions', 'bx_glossary', 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 310),
('bx_glossary_view_actions', 'bx_glossary', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 320),
('bx_glossary_view_actions', 'bx_glossary', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 330),
('bx_glossary_view_actions', 'bx_glossary', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, 1, 0, 9999);
