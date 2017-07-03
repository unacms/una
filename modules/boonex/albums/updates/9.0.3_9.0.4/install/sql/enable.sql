-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_albums_search';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_search', '_bx_albums_page_title_sys_entries_search', '_bx_albums_page_title_entries_search', 'bx_albums', 11, 2147483647, 1, 'albums-search', 'page.php?i=albums-search', '', '', '', 0, 1, 0, 'BxAlbumsPageBrowse', 'modules/boonex/albums/classes/BxAlbumsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_search';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_search', 2, 'bx_albums', '_bx_albums_page_block_title_search_form', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:9:"bx_albums";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_albums_search', 1, 'bx_albums', '_bx_albums_page_block_title_search_results', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:9:"bx_albums";s:10:"show_empty";b:0;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_albums_search', 3, 'bx_albums', '_bx_albums_page_block_title_search_form_media', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:15:"bx_albums_media";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_albums_search', 1, 'bx_albums', '_bx_albums_page_block_title_search_results_media', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:15:"bx_albums_media";s:10:"show_empty";b:0;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 2),
('bx_albums_search', 2, 'bx_albums', '_bx_albums_page_block_title_search_form_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:14:"bx_albums_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 2),
('bx_albums_search', 1, 'bx_albums', '_bx_albums_page_block_title_search_results_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:14:"bx_albums_cmts";s:10:"show_empty";b:0;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 3),
('bx_albums_search', 3, 'bx_albums', '_bx_albums_page_block_title_search_form_media_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:20:"bx_albums_media_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 2),
('bx_albums_search', 1, 'bx_albums', '_bx_albums_page_block_title_search_results_media_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:20:"bx_albums_media_cmts";s:10:"show_empty";b:0;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 4);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_submenu' AND `name`='albums-search';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_submenu', 'bx_albums', 'albums-search', '_bx_albums_menu_item_title_system_entries_search', '_bx_albums_menu_item_title_entries_search', 'page.php?i=albums-search', '', '', '', '', 2147483647, 1, 1, 4);


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_albums';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_albums', 'bx_albums', 'bx_albums', '_bx_albums_search_extended', 1, '', ''),
('bx_albums_media', 'bx_albums_media', 'bx_albums', '_bx_albums_search_extended_media', 1, '', ''),
('bx_albums_cmts', 'bx_albums_cmts', 'bx_albums', '_bx_albums_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', ''),
('bx_albums_media_cmts', 'bx_albums_media_cmts', 'bx_albums', '_bx_albums_search_extended_media_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_albums', 'bx_albums_media', 'bx_albums_cmts', 'bx_albums_media_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_albums', '_bx_albums', 'bx_albums', 'added', 'edited', 'deleted', '', ''),
('bx_albums_media', '_bx_albums_media', 'bx_albums', 'media_added', '', 'media_deleted', 'BxAlbumsContentInfoMedia', 'modules/boonex/albums/classes/BxAlbumsContentInfoMedia.php'),
('bx_albums_cmts', '_bx_albums_cmts', 'bx_albums', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', ''),
('bx_albums_media_cmts', '_bx_albums_media_cmts', 'bx_albums_media', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_albums';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_albums', 'bx_albums_administration', 'id', '', ''),
('bx_albums', 'bx_albums_common', 'id', '', '');


-- STATS
DELETE FROM `sys_statistics` WHERE `module`='bx_albums';
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_albums', 'bx_albums', '_bx_albums', 'page.php?i=albums-home', 'picture-o col-blue1', 'SELECT COUNT(*) FROM `bx_albums_albums` WHERE 1 AND `status` = ''active'' AND `status_admin` = ''active''', @iMaxOrderStats + 1),
('bx_albums', 'bx_albums_media', '_bx_albums_media', '', 'picture-o col-blue1', 'SELECT COUNT(*) FROM `bx_albums_files` WHERE 1', @iMaxOrderStats + 2);


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` IN ('bx_albums_growth', 'bx_albums_growth_speed', 'bx_albums_growth_media', 'bx_albums_growth_speed_media');
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_albums_growth', '_bx_albums_chart_growth', 'bx_albums_albums', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_albums_growth_speed', '_bx_albums_chart_growth_speed', 'bx_albums_albums', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', ''),
('bx_albums_growth_media', '_bx_albums_chart_growth_media', 'bx_albums_files', 'added', '', '', '', 1, @iMaxOrderCharts + 3, 'BxDolChartGrowth', ''),
('bx_albums_growth_speed_media', '_bx_albums_chart_growth_speed_media', 'bx_albums_files', 'added', '', '', '', 1, @iMaxOrderCharts + 4, 'BxDolChartGrowthSpeed', '');
