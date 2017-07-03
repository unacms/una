-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_posts_search';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_posts_search', '_bx_posts_page_title_sys_entries_search', '_bx_posts_page_title_entries_search', 'bx_posts', 5, 2147483647, 1, 'posts-search', 'page.php?i=posts-search', '', '', '', 0, 1, 0, 'BxPostsPageBrowse', 'modules/boonex/posts/classes/BxPostsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_search';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_search', 1, 'bx_posts', '_bx_posts_page_block_title_search_form', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:8:"bx_posts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_posts_search', 1, 'bx_posts', '_bx_posts_page_block_title_search_results', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:8:"bx_posts";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 2),
('bx_posts_search', 1, 'bx_posts', '_bx_posts_page_block_title_search_form_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:13:"bx_posts_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 3),
('bx_posts_search', 1, 'bx_posts', '_bx_posts_page_block_title_search_results_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:13:"bx_posts_cmts";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 4);

-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_posts_submenu' AND `name`='posts-search';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_posts_submenu', 'bx_posts', 'posts-search', '_bx_posts_menu_item_title_system_entries_search', '_bx_posts_menu_item_title_entries_search', 'page.php?i=posts-search', '', '', '', '', 2147483647, 1, 1, 3);


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_posts';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_posts', 'bx_posts', 'bx_posts', '_bx_posts_search_extended', 1, '', ''),
('bx_posts_cmts', 'bx_posts_cmts', 'bx_posts', '_bx_posts_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_posts', 'bx_posts_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_posts', '_bx_posts', 'bx_posts', 'added', 'edited', 'deleted', '', ''),
('bx_posts_cmts', '_bx_posts_cmts', 'bx_posts', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_posts';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_posts', 'bx_posts_administration', 'id', '', ''),
('bx_posts', 'bx_posts_common', 'id', '', '');


-- STATS
DELETE FROM `sys_statistics` WHERE `module`='bx_posts';
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_posts', 'bx_posts', '_bx_posts', 'page.php?i=posts-home', 'file-text col-red3', 'SELECT COUNT(*) FROM `bx_posts_posts` WHERE 1 AND `status` = ''active'' AND `status_admin` = ''active''', @iMaxOrderStats + 1);


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` IN ('bx_posts_growth', 'bx_posts_growth_speed');
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_posts_growth', '_bx_posts_chart_growth', 'bx_posts_posts', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_posts_growth_speed', '_bx_posts_chart_growth_speed', 'bx_posts_posts', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');


-- GRIDS:
UPDATE `sys_grid_fields` SET `chars_limit`='25' WHERE `object`='bx_posts_administration' AND `name`='title';
UPDATE `sys_grid_fields` SET `chars_limit`='35' WHERE `object`='bx_posts_common' AND `name`='title';
