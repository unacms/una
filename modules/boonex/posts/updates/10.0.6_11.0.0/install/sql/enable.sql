-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_posts' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_posts_auto_activation_for_categories';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_posts_auto_activation_for_categories', 'on', @iCategId, '_bx_posts_option_auto_activation_for_categories', 'checkbox', '', '', '', '', 35);


-- PAGES
UPDATE `sys_pages_blocks` SET `cell_id`='2' WHERE `object`='bx_posts_view_entry' AND `title`='_bx_posts_page_block_title_entry_polls' AND `cell_id`='3';

DELETE FROM `sys_objects_page` WHERE `object`='bx_posts_top';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_top', '_bx_posts_page_title_sys_entries_top', '_bx_posts_page_title_entries_top', 'bx_posts', 5, 2147483647, 1, 'posts-top', 'page.php?i=posts-top', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_top';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_top', 1, 'bx_posts', '', '_bx_posts_page_block_title_top_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:10:"browse_top";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_home' AND `title_system`='_bx_posts_page_block_title_sys_multicats';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_home', 2, 'bx_posts', '_bx_posts_page_block_title_sys_multicats', '_bx_posts_page_block_title_multicats', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:21:"categories_multi_list";}', 0, 1, 0, 2);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_posts_view_actions' AND `name`='set-badges';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_posts_view_actions', 'bx_posts', 'set-badges', '_sys_menu_item_title_system_set_badges', '_sys_menu_item_title_set_badges', 'javascript:void(0)', 'bx_menu_popup(''sys_set_badges'', window, {}, {module: ''bx_posts'', content_id: {content_id}});', '', 'check-circle', '', '', 0, 2147483647, 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 40);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_posts_submenu' AND `name`='posts-top';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_posts_submenu', 'bx_posts', 'posts-top', '_bx_posts_menu_item_title_system_entries_top', '_bx_posts_menu_item_title_entries_top', 'page.php?i=posts-top', '', '', '', '', '', 2147483647, '', 1, 1, 3);


--GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_posts_administration' AND `name`='audit_content';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_posts_administration', 'single', 'audit_content', '_bx_posts_grid_action_title_adm_audit_content', 'search', 1, 0, 4);
