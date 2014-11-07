UPDATE `sys_objects_page` SET `layout_id`='11' WHERE `object`='bx_posts_view_entry' LIMIT 1;


DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_view_entry' AND `title` IN ('_bx_posts_page_block_title_entry_author', '_bx_posts_page_block_title_entry_actions', '_bx_posts_page_block_title_entry_text', '_bx_posts_page_block_title_entry_social_sharing', '_bx_posts_page_block_title_entry_comments');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_view_entry', 3, 'bx_posts', '_bx_posts_page_block_title_entry_location', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:15:\"entity_location\";}', 0, 0, 1, 0),
('bx_posts_view_entry', 2, 'bx_posts', '_bx_posts_page_block_title_entry_author', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:13:\"entity_author\";}', 0, 0, 1, 0),
('bx_posts_view_entry', 1, 'bx_posts', '_bx_posts_page_block_title_entry_actions', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 1, 0),
('bx_posts_view_entry', 4, 'bx_posts', '_bx_posts_page_block_title_entry_text', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 0),
('bx_posts_view_entry', 4, 'bx_posts', '_bx_posts_page_block_title_entry_social_sharing', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:21:\"entity_social_sharing\";}', 0, 0, 1, 1),
('bx_posts_view_entry', 4, 'bx_posts', '_bx_posts_page_block_title_entry_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1, 2);


-- PAGE: featured entries
DELETE FROM `sys_objects_page` WHERE `module`='bx_posts' AND `object`='bx_posts_featured' LIMIT 1;
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_posts' AND `object`='bx_posts_featured';


-- PAGE: popular entries
DELETE FROM `sys_objects_page` WHERE `module`='bx_posts' AND `object`='bx_posts_popular' LIMIT 1;
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_popular', '_bx_posts_page_title_sys_entries_popular', '_bx_posts_page_title_entries_popular', 'bx_posts', 5, 2147483647, 1, 'posts-popular', 'page.php?i=posts-popular', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_posts' AND `object`='bx_posts_popular';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_popular', 1, 'bx_posts', '_bx_posts_page_block_title_popular_entries', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:14:\"browse_popular\";}', 0, 1, 1);


-- PAGE: module manage
DELETE FROM `sys_objects_page` WHERE `module`='bx_posts' AND `object`='bx_posts_manage' LIMIT 1;
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_manage', '_bx_posts_page_title_sys_manage', '_bx_posts_page_title_manage', 'bx_posts', 5, 2147483647, 1, 'posts-manage', 'page.php?i=posts-manage', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_posts' AND `object`='bx_posts_manage';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_manage', 1, 'bx_posts', '_bx_posts_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:12:\"manage_tools\";}}', 0, 1, 0);


-- PAGE: module moderation
DELETE FROM `sys_objects_page` WHERE `module`='bx_posts' AND `object`='bx_posts_moderation' LIMIT 1;
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_moderation', '_bx_posts_page_title_sys_manage', '_bx_posts_page_title_manage', 'bx_posts', 5, 64, 1, 'posts-moderation', 'page.php?i=posts-moderation', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_posts' AND `object`='bx_posts_moderation';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_moderation', 1, 'bx_posts', '_bx_posts_page_block_title_manage', 11, 64, 'service', 'a:3:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:10:\"moderation\";}}', 0, 1, 0);


-- PAGE: module administration
DELETE FROM `sys_objects_page` WHERE `module`='bx_posts' AND `object`='bx_posts_administration' LIMIT 1;
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_administration', '_bx_posts_page_title_sys_manage', '_bx_posts_page_title_manage', 'bx_posts', 5, 128, 1, 'posts-administration', 'page.php?i=posts-administration', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_posts' AND `object`='bx_posts_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_posts_administration', 1, 'bx_posts', '_bx_posts_page_block_title_manage', 11, 128, 'service', 'a:3:{s:6:\"module\";s:8:\"bx_posts\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);


DELETE FROM `sys_menu_items` WHERE `module`='bx_posts' AND `set_name`='bx_posts_submenu' AND `name` IN ('posts-featured', 'posts-popular', 'posts-manage');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_posts_submenu', 'bx_posts', 'posts-popular', '_bx_posts_menu_item_title_system_entries_popular', '_bx_posts_menu_item_title_entries_popular', 'page.php?i=posts-popular', '', '', '', '', 2147483647, 1, 1, 2),
('bx_posts_submenu', 'bx_posts', 'posts-manage', '_bx_posts_menu_item_title_system_entries_manage', '_bx_posts_menu_item_title_entries_manage', 'page.php?i=posts-manage', '', '', '', '', 2147483647, 1, 1, 3);


-- MENU: profile stats
DELETE FROM `sys_menu_items` WHERE `module`='bx_posts' AND `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-posts' LIMIT 1;
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_posts', 'profile-stats-manage-posts', '_bx_posts_menu_item_title_system_manage_my_posts', '_bx_posts_menu_item_title_manage_my_posts', 'page.php?i=posts-manage', '', '_self', 'file-text col-red3', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:41:"get_menu_addon_manage_tools_profile_stats";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 1);


-- MENU: manage tools submenu
DELETE FROM `sys_objects_menu` WHERE `module`='bx_posts' AND `object`='bx_posts_menu_manage_tools' LIMIT 1;
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_menu_manage_tools', '_bx_posts_menu_title_manage_tools', 'bx_posts_menu_manage_tools', 'bx_posts', 6, 0, 1, 'BxPostsMenuManageTools', 'modules/boonex/posts/classes/BxPostsMenuManageTools.php');

DELETE FROM `sys_menu_sets` WHERE `module`='bx_posts' AND `set_name`='bx_posts_menu_manage_tools' LIMIT 1;
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_posts_menu_manage_tools', 'bx_posts', '_bx_posts_menu_set_title_manage_tools', 0);


-- MENU: dashboard manage tools
DELETE FROM `sys_menu_items` WHERE `module`='bx_posts' AND `set_name`='sys_account_dashboard_manage_tools' AND `name` IN ('posts-moderation', 'posts-administration');
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_posts', 'posts-moderation', '_bx_posts_menu_item_title_system_admt_posts', '_bx_posts_menu_item_title_admt_posts', 'page.php?i=posts-moderation', '', '_self', '', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 64, 1, 0, @iManageMenuOrder + 1),
('sys_account_dashboard_manage_tools', 'bx_posts', 'posts-administration', '_bx_posts_menu_item_title_system_admt_posts', '_bx_posts_menu_item_title_admt_posts', 'page.php?i=posts-administration', '', '_self', '', 'a:2:{s:6:"module";s:8:"bx_posts";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 128, 1, 0, @iManageMenuOrder + 2);


-- GRIDS: manage tools
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_posts_administration', 'bx_posts_moderation', 'bx_posts_common');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_posts_administration', 'bx_posts_moderation', 'bx_posts_common');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_posts_administration', 'bx_posts_moderation', 'bx_posts_common');

INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_posts_administration', 'Sql', 'SELECT * FROM `bx_posts_posts` WHERE 1 ', 'bx_posts_posts', 'id', '', 'status', '', 20, NULL, 'start', '', 'title,text', '', 'like', '', '', 'BxPostsGridAdministration', 'modules/boonex/posts/classes/BxPostsGridAdministration.php'),
('bx_posts_moderation', 'Sql', 'SELECT * FROM `bx_posts_posts` WHERE 1 ', 'bx_posts_posts', 'id', '', 'status', '', 20, NULL, 'start', '', 'title,text', '', 'like', '', '', 'BxPostsGridModeration', 'modules/boonex/posts/classes/BxPostsGridModeration.php'),
('bx_posts_common', 'Sql', 'SELECT * FROM `bx_posts_posts` WHERE 1 ', 'bx_posts_posts', 'id', '', 'status', '', 20, NULL, 'start', '', 'title,text', '', 'like', '', '', 'BxPostsGridCommon', 'modules/boonex/posts/classes/BxPostsGridCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_posts_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_posts_administration', 'switcher', '_bx_posts_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_posts_administration', 'title', '_bx_posts_grid_column_title_adm_title', '25%', 0, '', '', 3),
('bx_posts_administration', 'added', '_bx_posts_grid_column_title_adm_added', '20%', 1, '25', '', 4),
('bx_posts_administration', 'author', '_bx_posts_grid_column_title_adm_author', '25%', 0, '25', '', 5),
('bx_posts_administration', 'actions', '', '20%', 0, '', '', 6),
('bx_posts_moderation', 'switcher', '', '10%', 0, '', '', 1),
('bx_posts_moderation', 'title', '_bx_posts_grid_column_title_adm_title', '25%', 0, '', '', 2),
('bx_posts_moderation', 'added', '_bx_posts_grid_column_title_adm_added', '25%', 1, '25', '', 3),
('bx_posts_moderation', 'author', '_bx_posts_grid_column_title_adm_author', '25%', 0, '25', '', 4),
('bx_posts_moderation', 'actions', '', '15%', 0, '', '', 5),
('bx_posts_common', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_posts_common', 'switcher', '', '8%', 0, '', '', 2),
('bx_posts_common', 'title', '_bx_posts_grid_column_title_adm_title', '40%', 0, '', '', 3),
('bx_posts_common', 'added', '_bx_posts_grid_column_title_adm_added', '30%', 1, '25', '', 4),
('bx_posts_common', 'actions', '', '20%', 0, '', '', 5);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_posts_administration', 'bulk', 'delete', '_bx_posts_grid_action_title_adm_delete', '', 1, 2),
('bx_posts_administration', 'single', 'edit', '', 'pencil', 0, 1),
('bx_posts_administration', 'single', 'delete', '', 'remove', 1, 2),
('bx_posts_administration', 'single', 'settings', '', 'cog', 0, 3),
('bx_posts_moderation', 'single', 'edit', '', 'pencil', 0, 1),
('bx_posts_moderation', 'single', 'settings', '', 'cog', 0, 2),
('bx_posts_common', 'bulk', 'delete', '_bx_posts_grid_action_title_adm_delete', '', 1, 2),
('bx_posts_common', 'single', 'edit', '', 'pencil', 0, 1),
('bx_posts_common', 'single', 'delete', '', 'remove', 1, 2),
('bx_posts_common', 'single', 'settings', '', 'cog', 0, 3);