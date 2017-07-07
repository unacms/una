-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_licenses' AND `title`='_bx_market_page_block_title_licenses_note';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_licenses', 1, 'bx_market', '', '_bx_market_page_block_title_licenses_note', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_market\";s:6:\"method\";s:19:\"block_licenses_note\";}', 0, 0, 1, 0);
UPDATE `sys_pages_blocks` SET `order`='1' WHERE `object`='bx_market_licenses' AND `title`='_bx_market_page_block_title_licenses';

DELETE FROM `sys_objects_page` WHERE `object`='bx_market_search';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_search', '_bx_market_page_title_sys_entries_search', '_bx_market_page_title_entries_search', 'bx_market', 5, 2147483647, 1, 'products-search', 'page.php?i=products-search', '', '', '', 0, 1, 0, 'BxMarketPageBrowse', 'modules/boonex/market/classes/BxMarketPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_market_search';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_market_search', 1, 'bx_market', '_bx_market_page_block_title_search_form', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:9:"bx_market";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_market_search', 1, 'bx_market', '_bx_market_page_block_title_search_results', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:9:"bx_market";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 2),
('bx_market_search', 1, 'bx_market', '_bx_market_page_block_title_search_form_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:14:"bx_market_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 3),
('bx_market_search', 1, 'bx_market', '_bx_market_page_block_title_search_results_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:14:"bx_market_cmts";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 4);


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_market_submenu' AND `name`='products-search';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_market_submenu', 'bx_market', 'products-search', '_bx_market_menu_item_title_system_entries_search', '_bx_market_menu_item_title_entries_search', 'page.php?i=products-search', '', '', '', '', 2147483647, 1, 1, 4);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_notifications' AND `name`='notifications-licenses';
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' AND `name`='dashboard-licenses';
SET @iMoAccountDashboard = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('sys_account_dashboard', 'bx_market', 'dashboard-licenses', '_bx_market_menu_item_title_system_licenses', '_bx_market_menu_item_title_licenses', 'page.php?i=products-licenses', '', '', 'certificate col-green2', '', '', 2147483646, 1, 0, 1, @iMoAccountDashboard + 1);


-- ACL
SET @iIdActionSetSubentriesOld = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_market' AND `Name`='set subentries' LIMIT 1);
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionSetSubentriesOld;
DELETE FROM `sys_acl_actions` WHERE `Module`='bx_market' AND `Name`='set subentries';

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_market', 'set subentries', NULL, '_bx_market_acl_action_set_subentries', '', 1, 3);
SET @iIdActionSetSubentries = LAST_INSERT_ID();

SET @iStandard = 3;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iStandard, @iIdActionSetSubentries),
(@iModerator, @iIdActionSetSubentries),
(@iAdministrator, @iIdActionSetSubentries),
(@iPremium, @iIdActionSetSubentries);


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_market';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_market', 'bx_market', 'bx_market', '_bx_market_search_extended', 1, '', ''),
('bx_market_cmts', 'bx_market_cmts', 'bx_market', '_bx_market_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object`='bx_market_subentries';
INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('bx_market_subentries', 'bx_market_subproducts', 'one-way', '', '');


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_market', 'bx_market_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_market', '_bx_market', 'bx_market', 'added', 'edited', 'deleted', '', ''),
('bx_market_cmts', '_bx_market_cmts', 'bx_market', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_market';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_market', 'bx_market_administration', 'id', '', ''),
('bx_market', 'bx_market_common', 'id', '', '');


-- STATS
DELETE FROM `sys_statistics` WHERE `module`='bx_market';
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_market', 'bx_market', '_bx_market', 'page.php?i=products-home', 'shopping-cart col-green3', 'SELECT COUNT(*) FROM `bx_market_products` WHERE 1 AND `status` = ''active'' AND `status_admin` = ''active''', @iMaxOrderStats + 1);


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` IN ('bx_market_growth', 'bx_market_growth_speed');
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_market_growth', '_bx_market_chart_growth', 'bx_market_products', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_market_growth_speed', '_bx_market_chart_growth_speed', 'bx_market_products', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');
