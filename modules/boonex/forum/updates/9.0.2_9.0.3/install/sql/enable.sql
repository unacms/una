SET @sName = 'bx_forum';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_forum_search' AND `title` IN ('_bx_forum_page_block_title_search_form', '_bx_forum_page_block_title_search_results', '_bx_forum_page_block_title_search_form_cmts', '_bx_forum_page_block_title_search_results_cmts');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_forum_search', 1, @sName, '', '_bx_forum_page_block_title_search_form', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:8:"bx_forum";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 2),
('bx_forum_search', 1, @sName, '', '_bx_forum_page_block_title_search_results', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:8:"bx_forum";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 3),
('bx_forum_search', 1, @sName, '', '_bx_forum_page_block_title_search_form_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:13:"bx_forum_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 4),
('bx_forum_search', 1, @sName, '', '_bx_forum_page_block_title_search_results_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:13:"bx_forum_cmts";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 5);


-- GRIDS
UPDATE `sys_objects_grid` SET `filter_fields`='title,text,text_comments' WHERE `object` IN (@sName, 'bx_forum_favorite', 'bx_forum_feature');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_forum';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_forum', 'bx_forum', 'bx_forum', '_bx_forum_search_extended', 1, '', ''),
('bx_forum_cmts', 'bx_forum_cmts', 'bx_forum', '_bx_forum_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Module`=@sName WHERE `Name`=@sName;


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN (@sName, 'bx_forum_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
(@sName, '_bx_forum', @sName, 'added', 'edited', 'deleted', '', ''),
('bx_forum_cmts', '_bx_forum_cmts', @sName, 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`=@sName;
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
(@sName, @sName, 'id', '', 'a:1:{s:4:"sort";a:2:{s:5:"stick";s:4:"desc";s:12:"lr_timestamp";s:4:"desc";}}'),
(@sName, 'bx_forum_administration', 'id', '', ''),
(@sName, 'bx_forum_common', 'id', '', '');


-- STATS
DELETE FROM `sys_statistics` WHERE `module`=@sName;
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
(@sName, @sName, '_bx_forum', 'page.php?i=discussions-home', 'comments-o col-blue2', 'SELECT COUNT(*) FROM `bx_forum_discussions` WHERE 1 AND `status` = ''active'' AND `status_admin` = ''active''', @iMaxOrderStats + 1);


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` IN ('bx_forum_growth', 'bx_forum_growth_speed');
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_forum_growth', '_bx_forum_chart_growth', 'bx_forum_discussions', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_forum_growth_speed', '_bx_forum_chart_growth_speed', 'bx_forum_discussions', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `action`='commentUpdated' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
(@sName, 'commentUpdated', @iHandler);
