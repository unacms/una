-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_polls_search';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_polls_search', '_bx_polls_page_title_sys_entries_search', '_bx_polls_page_title_entries_search', 'bx_polls', 5, 2147483647, 1, 'polls-search', 'page.php?i=polls-search', '', '', '', 0, 1, 0, 'BxPollsPageBrowse', 'modules/boonex/polls/classes/BxPollsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_polls_search';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_polls_search', 1, 'bx_polls', '_bx_polls_page_block_title_search_form', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:8:"bx_polls";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_polls_search', 1, 'bx_polls', '_bx_polls_page_block_title_search_results', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_polls";s:6:"method";s:27:"get_results_search_extended";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:8:"bx_polls";s:10:"show_empty";b:1;}}}', 0, 1, 1, 2),
('bx_polls_search', 1, 'bx_polls', '_bx_polls_page_block_title_search_form_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:13:"bx_polls_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 3),
('bx_polls_search', 1, 'bx_polls', '_bx_polls_page_block_title_search_results_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:13:"bx_polls_cmts";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 4);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_polls_submenu' AND `name`='polls-search';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_polls_submenu', 'bx_polls', 'polls-search', '_bx_polls_menu_item_title_system_entries_search', '_bx_polls_menu_item_title_entries_search', 'page.php?i=polls-search', '', '', '', '', 2147483647, 1, 1, 3);


-- ACL
SET @iIdActionEntryVoteOld = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_polls' AND `Name`='vote entry' LIMIT 1);
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionEntryVoteOld;
DELETE FROM `sys_acl_actions` WHERE `Module`='bx_polls' AND `Name`='vote entry';

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_polls', 'vote entry', NULL, '_bx_polls_acl_action_vote_entry', '', 1, 0);
SET @iIdActionEntryVote = LAST_INSERT_ID();

SET @iStandard = 3;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iStandard, @iIdActionEntryVote),
(@iModerator, @iIdActionEntryVote),
(@iAdministrator, @iIdActionEntryVote),
(@iPremium, @iIdActionEntryVote);


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_polls';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_polls', 'bx_polls', 'bx_polls', '_bx_polls_search_extended', 1, '', ''),
('bx_polls_cmts', 'bx_polls_cmts', 'bx_polls', '_bx_polls_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_polls', 'bx_polls_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_polls', '_bx_polls', 'bx_polls', 'added', 'edited', 'deleted', '', ''),
('bx_polls_cmts', '_bx_polls_cmts', 'bx_polls', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_polls';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_polls', 'bx_polls_administration', 'id', '', ''),
('bx_polls', 'bx_polls_common', 'id', '', '');


-- STATS
DELETE FROM `sys_statistics` WHERE `module`='bx_polls';
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_polls', 'bx_polls', '_bx_polls', 'page.php?i=polls-home', 'tasks col-green1', 'SELECT COUNT(*) FROM `bx_polls_entries` WHERE 1 AND `status` = ''active'' AND `status_admin` = ''active''', @iMaxOrderStats + 1);


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` IN ('bx_polls_growth', 'bx_polls_growth_speed');
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_polls_growth', '_bx_polls_chart_growth', 'bx_polls_entries', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_polls_growth_speed', '_bx_polls_chart_growth_speed', 'bx_polls_entries', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');


-- GRIDS:
UPDATE `sys_objects_grid` SET `filter_fields`='text' WHERE `object` IN ('bx_polls_administration', 'bx_polls_common');

UPDATE `sys_grid_fields` SET `chars_limit`='25' WHERE `object`='bx_polls_administration' AND `name`='text';
UPDATE `sys_grid_fields` SET `chars_limit`='35' WHERE `object`='bx_polls_common' AND `name`='text';
