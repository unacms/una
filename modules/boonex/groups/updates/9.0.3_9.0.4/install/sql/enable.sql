-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_groups' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_groups_searchable_fields');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_groups_searchable_fields', 'group_name,group_desc', @iCategId, '_bx_groups_option_searchable_fields', 'list', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:21:"get_searchable_fields";}', '', '', 30);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_view_profile' AND `title` IN ('_bx_groups_page_block_title_admins', '_bx_groups_page_block_title_profile_comments');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_groups_view_profile', 3, 'bx_groups', '', '_bx_groups_page_block_title_admins', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:6:\"admins\";}', 0, 0, 1, 1),
('bx_groups_view_profile', 4, 'bx_groups', '', '_bx_groups_page_block_title_profile_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 0, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_profile_comments';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_profile_comments', '_bx_groups_page_title_sys_profile_comments', '_bx_groups_page_title_profile_comments', 'bx_groups', 5, 2147483647, 1, 'group-profile-comments', '', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_profile_comments';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_profile_comments', 1, 'bx_groups', '_bx_groups_page_block_title_profile_comments', '_bx_groups_page_block_title_profile_comments_link', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 0, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_groups_search';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_search', '_bx_groups_page_title_sys_entries_search', '_bx_groups_page_title_entries_search', 'bx_groups', 5, 2147483647, 1, 'groups-search', 'page.php?i=groups-search', '', '', '', 0, 1, 0, 'BxGroupsPageBrowse', 'modules/boonex/groups/classes/BxGroupsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_search';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_search', 1, 'bx_groups', '_bx_groups_page_block_title_search_form', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:9:"bx_groups";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_groups_search', 1, 'bx_groups', '_bx_groups_page_block_title_search_results', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:9:"bx_groups";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 2),
('bx_groups_search', 1, 'bx_groups', '_bx_groups_page_block_title_search_form_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:14:"bx_groups_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 3),
('bx_groups_search', 1, 'bx_groups', '_bx_groups_page_block_title_search_results_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:14:"bx_groups_cmts";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 4);


-- MENUS
UPDATE `sys_menu_items` SET `editable`='0' WHERE `set_name`='bx_groups_view_actions' AND `name`='profile-fan-add';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_submenu' AND `name`='groups-search';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_submenu', 'bx_groups', 'groups-search', '_bx_groups_menu_item_title_system_entries_search', '_bx_groups_menu_item_title_entries_search', 'page.php?i=groups-search', '', '', '', '', 2147483647, 1, 1, 3);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_submenu' AND `name`='group-profile-comments';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_submenu', 'bx_groups', 'group-profile-comments', '_bx_groups_menu_item_title_system_view_profile_comments', '_bx_groups_menu_item_title_view_profile_comments', 'page.php?i=group-profile-comments&id={content_id}', '', '', '', '', 2147483647, 0, 0, 3);


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_groups';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_groups', 'bx_groups', 'bx_groups_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-group-profile&id={object_id}', '', 'bx_groups_data', 'id', 'author', 'group_name', 'comments', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_groups';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_groups', 'bx_groups', 'bx_groups', '_bx_groups_search_extended', 1, '', ''),
('bx_groups_cmts', 'bx_groups_cmts', 'bx_groups', '_bx_groups_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_groups', 'bx_groups_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_groups', '_bx_groups', 'bx_groups', 'added', 'edited', 'deleted', '', ''),
('bx_groups_cmts', '_bx_groups_cmts', 'bx_groups', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_groups';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_groups', 'bx_groups_administration', 'td`.`id', '', ''),
('bx_groups', 'bx_groups_common', 'td`.`id', '', '');


-- STATS
DELETE FROM `sys_statistics` WHERE `module`='bx_groups';
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_groups', 'bx_groups', '_bx_groups', 'page.php?i=groups-home', 'group col-red2', 'SELECT COUNT(*) FROM `bx_groups_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_groups'' WHERE 1 AND `tp`.`status`=''active''', @iMaxOrderStats + 1);


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` IN ('bx_groups_growth', 'bx_groups_growth_speed');
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_groups_growth', '_bx_groups_chart_growth', 'bx_groups_data', 'added', '', '', 'SELECT {field_date_formatted} AS `period`, COUNT(*) AS {object} FROM {table} LEFT JOIN `sys_profiles` AS `tp` ON {table}.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_groups'' WHERE 1 AND `tp`.`status`=''active'' {where_inteval} GROUP BY `period` ORDER BY {table}.{field_date} ASC', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_groups_growth_speed', '_bx_groups_chart_growth_speed', 'bx_groups_data', 'added', '', '', 'SELECT {field_date_formatted} AS `period`, COUNT(*) AS {object} FROM {table} LEFT JOIN `sys_profiles` AS `tp` ON {table}.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_groups'' WHERE 1 AND `tp`.`status`=''active'' {where_inteval} GROUP BY `period` ORDER BY {table}.{field_date} ASC', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_groups' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `action`='save_setting' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler);
