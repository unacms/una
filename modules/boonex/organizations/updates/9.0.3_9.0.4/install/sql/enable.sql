-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_organizations' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_organizations_redirect_aadd', 'bx_organizations_redirect_aadd_custom_url', 'bx_organizations_public_subscriptions', 'bx_organizations_public_subscribed_me');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_organizations_redirect_aadd', 'profile', @iCategId, '_bx_orgs_option_redirect_aadd', 'select', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:30:"get_options_redirect_after_add";}', '', '', 3),
('bx_organizations_redirect_aadd_custom_url', '', @iCategId, '_bx_orgs_option_redirect_aadd_custom_url', 'digit', '', '', '', 4),
('bx_organizations_public_subscriptions', '', @iCategId, '_bx_orgs_option_public_subscriptions', 'checkbox', '', '', '', 30),
('bx_organizations_public_subscribed_me', '', @iCategId, '_bx_orgs_option_public_subscribed_me', 'checkbox', '', '', '', 31);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_create_profile' AND `title`='_bx_orgs_page_block_title_choose_type';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_organizations_create_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_choose_type', 11, 2147483647, 'menu', 'sys_add_profile_vertical', 0, 1, 0, 0);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_view_profile' AND `title` IN ('_bx_orgs_page_block_title_profile_comments', '_bx_orgs_page_block_title_profile_location');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_organizations_view_profile', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 0, 0),
('bx_organizations_view_profile', 3, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_location', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:15:\"entity_location\";}', 0, 0, 0, 0),
('bx_organizations_view_profile', 3, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_location', 3, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:13:\"locations_map\";s:6:\"params\";a:2:{i:0;s:16:\"bx_organizations\";i:1;s:12:\"{content_id}\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 0, 0, 1, 3);

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_profile_subscriptions';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_profile_subscriptions', 'organization-profile-subscriptions', '_bx_orgs_page_title_sys_profile_subscriptions', '_bx_orgs_page_title_profile_subscriptions', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=organization-profile-subscriptions', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_profile_subscriptions';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_profile_subscriptions', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_profile_subscriptions', '_bx_orgs_page_block_title_profile_subscriptions', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:19:\"subscriptions_table\";s:5:\"class\";s:23:\"TemplServiceConnections\";}', 0, 1, 1, 1),
('bx_organizations_profile_subscriptions', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_profile_subscribed_me', '_bx_orgs_page_block_title_profile_subscribed_me', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:19:\"subscribed_me_table\";s:5:\"class\";s:23:\"TemplServiceConnections\";}', 0, 1, 1, 2);

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_profile_comments';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_profile_comments', '_bx_orgs_page_title_sys_profile_comments', '_bx_orgs_page_title_profile_comments', 'bx_organizations', 5, 2147483647, 1, 'organization-profile-comments', '', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_profile_comments';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_profile_comments', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_comments', '_bx_orgs_page_block_title_profile_comments_link', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 0, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_search';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_search', '_bx_orgs_page_title_sys_entries_search', '_bx_orgs_page_title_entries_search', 'bx_organizations', 5, 2147483647, 1, 'organizations-search', 'page.php?i=organizations-search', '', '', '', 0, 1, 0, 'BxOrgsPageBrowse', 'modules/boonex/organizations/classes/BxOrgsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_search';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_search', 1, 'bx_organizations', '_bx_orgs_page_block_title_search_form', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:16:"bx_organizations";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 1),
('bx_organizations_search', 1, 'bx_organizations', '_bx_orgs_page_block_title_search_results', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:16:"bx_organizations";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 1, 2),
('bx_organizations_search', 1, 'bx_organizations', '_bx_orgs_page_block_title_search_form_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:8:"get_form";s:6:"params";a:1:{i:0;a:1:{s:6:"object";s:21:"bx_organizations_cmts";}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 3),
('bx_organizations_search', 1, 'bx_organizations', '_bx_orgs_page_block_title_search_results_cmts', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_results";s:6:"params";a:1:{i:0;a:2:{s:6:"object";s:21:"bx_organizations_cmts";s:10:"show_empty";b:1;}}s:5:"class";s:27:"TemplSearchExtendedServices";}', 0, 1, 0, 4);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='delete-organization-account-content';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_more', 'bx_organizations', 'delete-organization-account-content', '_bx_orgs_menu_item_title_system_delete_account_content', '_bx_orgs_menu_item_title_delete_account_content', 'page.php?i=account-settings-delete&id={account_id}&content=1', '', '', 'remove', '', 128, 1, 0, 60);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_submenu' AND `name`='organizations-search';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_submenu', 'bx_organizations', 'organizations-search', '_bx_orgs_menu_item_title_system_entries_search', '_bx_orgs_menu_item_title_entries_search', 'page.php?i=organizations-search', '', '', '', '', 2147483647, 1, 1, 4);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_submenu' AND `name` IN ('organization-profile-subscriptions', 'organization-profile-comments');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_submenu', 'bx_organizations', 'organization-profile-subscriptions', '_bx_orgs_menu_item_title_system_view_profile_subscriptions', '_bx_orgs_menu_item_title_view_profile_subscriptions', 'page.php?i=organization-profile-subscriptions&profile_id={profile_id}', '', '', 'check col-blue3', '', 2147483647, 1, 0, 4),
('bx_organizations_view_submenu', 'bx_organizations', 'organization-profile-comments', '_bx_orgs_menu_item_title_system_view_profile_comments', '_bx_orgs_menu_item_title_view_profile_comments', 'page.php?i=organization-profile-comments&id={content_id}', '', '', '', '', 2147483647, 0, 0, 5);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_profile_stats' AND `module`='bx_organizations' AND `name` IN ('profile-stats-subscriptions', 'profile-stats-subscribed-me');
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_organizations', 'profile-stats-subscriptions', '_bx_orgs_menu_item_title_system_subscriptions', '_bx_orgs_menu_item_title_subscriptions', 'page.php?i=organization-profile-subscriptions&profile_id={member_id}#subscriptions', '', '_self', 'rss col-red2', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:25:"get_connected_content_num";s:6:"params";a:1:{i:0;s:26:"sys_profiles_subscriptions";}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 1),
('sys_profile_stats', 'bx_organizations', 'profile-stats-subscribed-me', '_bx_orgs_menu_item_title_system_subscribed_me', '_bx_orgs_menu_item_title_subscribed_me', 'page.php?i=organization-profile-subscribed-me&profile_id={member_id}#subscribers', '', '_self', 'rss col-red2', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:28:"get_connected_initiators_num";s:6:"params";a:1:{i:0;s:26:"sys_profiles_subscriptions";}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 2);


-- COMMENTS
DELETE FROM `sys_objects_cmts` WHERE `Name`='bx_organizations';
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_organizations', 'bx_organizations', 'bx_organizations_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-organization-profile&id={object_id}', '', 'bx_organizations_data', 'id', 'author', 'org_name', 'comments', '', '');


-- FAFORITES
UPDATE `sys_objects_favorite` SET `class_name`='BxOrgsFavorite', `class_file`='modules/boonex/organizations/classes/BxOrgsFavorite.php' WHERE `name`='bx_organizations';


-- REPORTS
UPDATE `sys_objects_report` SET `class_name`='BxOrgsReport', `class_file`='modules/boonex/organizations/classes/BxOrgsReport.php' WHERE `name`='bx_organizations';


-- METATAGS
UPDATE `sys_objects_metatags` SET `table_locations`='bx_organizations_meta_locations' WHERE `object`='bx_organizations';


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_organizations';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_organizations', 'bx_organizations', 'bx_organizations', '_bx_orgs_search_extended', 1, '', ''),
('bx_organizations_cmts', 'bx_organizations_cmts', 'bx_organizations', '_bx_orgs_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_organizations', 'bx_organizations_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_organizations', '_bx_orgs', 'bx_organizations', 'added', 'edited', 'deleted', '', ''),
('bx_organizations_cmts', '_bx_orgs_cmts', 'bx_organizations', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_organizations';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_organizations', 'bx_organizations_administration', 'td`.`id', '', ''),
('bx_organizations', 'bx_organizations_common', 'td`.`id', '', '');


-- STATS
DELETE FROM `sys_statistics` WHERE `module`='bx_organizations';
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_organizations', 'bx_organizations', '_bx_orgs', 'page.php?i=organizations-home', 'briefcase col-red2', 'SELECT COUNT(*) FROM `bx_organizations_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_organizations'' WHERE 1 AND `tp`.`status`=''active''', @iMaxOrderStats + 1);


-- CHARTS
DELETE FROM `sys_objects_chart` WHERE `object` IN ('bx_organizations_growth', 'bx_organizations_growth_speed');
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_organizations_growth', '_bx_orgs_chart_growth', 'bx_organizations_data', 'added', '', '', 'SELECT {field_date_formatted} AS `period`, COUNT(*) AS {object} FROM {table} LEFT JOIN `sys_profiles` AS `tp` ON {table}.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_organizations'' WHERE 1 AND `tp`.`status`=''active'' {where_inteval} GROUP BY `period` ORDER BY {table}.{field_date} ASC', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_organizations_growth_speed', '_bx_orgs_chart_growth_speed', 'bx_organizations_data', 'added', '', '', 'SELECT {field_date_formatted} AS `period`, COUNT(*) AS {object} FROM {table} LEFT JOIN `sys_profiles` AS `tp` ON {table}.`id` = `tp`.`content_id` AND `tp`.`type`=''bx_organizations'' WHERE 1 AND `tp`.`status`=''active'' {where_inteval} GROUP BY `period` ORDER BY {table}.{field_date} ASC', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');


-- LIVE UPDATES
DELETE FROM `sys_objects_live_updates` WHERE `name`='bx_organizations_friend_requests';
INSERT INTO `sys_objects_live_updates`(`name`, `frequency`, `service_call`, `active`) VALUES
('bx_organizations_friend_requests', 1, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:40:"get_live_updates_unconfirmed_connections";s:6:"params";a:5:{i:0;s:16:"bx_organizations";i:1;s:20:"sys_profiles_friends";i:2;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:3;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:29:"notifications-friend-requests";}i:4;s:7:"{count}";}s:5:"class";s:23:"TemplServiceConnections";}', 1);
