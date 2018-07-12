SET @sName = 'bx_timeline';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_timeline_administration';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_administration', '_bx_timeline_page_title_sys_manage_administration', '_bx_timeline_page_title_manage', 'bx_timeline', 5, 192, 1, 'timeline-administration', 'page.php?i=timeline-administration', '', '', '', 0, 1, 0, 'BxTimelinePageBrowse', 'modules/boonex/timeline/classes/BxTimelinePageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_administration';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_timeline_administration', 1, 'bx_timeline', '_bx_timeline_page_block_title_system_manage_administration', '_bx_timeline_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:\"module\";s:11:\"bx_timeline\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_manage' AND `name` IN ('item-stick', 'item-unstick');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-stick', '_bx_timeline_menu_item_title_system_item_stick', '_bx_timeline_menu_item_title_item_stick', 'javascript:void(0)', 'javascript:{js_object_view}.stickPost(this, {content_id}, 1)', '_self', 'thumbtack', '', 2147483647, 1, 0, 2),
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-unstick', '_bx_timeline_menu_item_title_system_item_unstick', '_bx_timeline_menu_item_title_item_unstick', 'javascript:void(0)', 'javascript:{js_object_view}.stickPost(this, {content_id}, 0)', '_self', 'thumbtack', '', 2147483647, 1, 0, 3);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='timeline-administration';
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_timeline', 'timeline-administration', '_bx_timeline_menu_item_title_system_admt_timeline', '_bx_timeline_menu_item_title_admt_timeline', 'page.php?i=timeline-administration', '', '_self', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_chars_display_max', 'bx_timeline_enable_editor_toolbar');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_editor_toolbar', '', @iCategId, '_bx_timeline_option_enable_editor_toolbar', 'checkbox', '', '', '', '', 100);


-- ACL
SET @iIdActionStick = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='stick' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionStick;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionStick;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'stick', NULL, '_bx_timeline_acl_action_stick', '', 1, 3);
SET @iIdActionStick = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

-- stick any post
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionStick),
(@iAdministrator, @iIdActionStick);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_timeline_administration';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_administration', 'Sql', 'SELECT * FROM `bx_timeline_events` WHERE 1 ', 'bx_timeline_events', 'id', 'date', 'active', '', 20, NULL, 'start', '', 'title,description', '', 'like', 'reports', '', 192, 'BxTimelineGridAdministration', 'modules/boonex/timeline/classes/BxTimelineGridAdministration.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_timeline_administration';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_timeline_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_timeline_administration', 'switcher', '_bx_timeline_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_timeline_administration', 'reports', '_sys_txt_reports_title', '5%', 0, '', '', 2),
('bx_timeline_administration', 'description', '_bx_timeline_grid_column_title_adm_description', '25%', 0, '25', '', 3),
('bx_timeline_administration', 'date', '_bx_timeline_grid_column_title_adm_added', '20%', 1, '25', '', 5),
('bx_timeline_administration', 'owner_id', '_bx_timeline_grid_column_title_adm_author', '20%', 0, '25', '', 6),
('bx_timeline_administration', 'actions', '', '20%', 0, '', '', 7);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_timeline_administration';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_timeline_administration', 'bulk', 'delete', '_bx_timeline_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_timeline_administration', 'single', 'delete', '_bx_timeline_grid_action_title_adm_delete', 'remove', 1, 1, 2);
