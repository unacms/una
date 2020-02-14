-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_view_profile' AND `title_system`='_bx_events_page_block_title_sys_entry_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `hidden_on`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_events_view_profile', 3, 'bx_events', '_bx_events_page_block_title_sys_entry_context', '_bx_events_page_block_title_entry_context', 13, '', 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:14:\"entity_context\";}', 0, 0, 1, 1);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_fans' AND `title_system`='_bx_events_page_block_title_system_invites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_fans', 1, 'bx_events', '_bx_events_page_block_title_system_invites', '_bx_events_page_block_title_fans_invites', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:13:"invites_table";}', 0, 0, 1, 3);

DELETE FROM `sys_objects_page` WHERE `object`='bx_events_context';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_context', 'events-context', '_bx_events_page_title_sys_entries_in_context', '_bx_events_page_title_entries_in_context', 'bx_events', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxEventsPageJoinedEntries', 'modules/boonex/events/classes/BxEventsPageJoinedEntries.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_events_context', 1, 'bx_events', '_bx_events_page_block_title_sys_entries_in_context', '_bx_events_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:14:\"browse_context\";}', 0, 0, 1, 1),
('bx_events_context', 1, 'bx_events', '_bx_events_page_block_title_sys_calendar_in_context', '_bx_events_page_block_title_calendar_in_context', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:9:"bx_events";s:6:"method";s:8:"calendar";s:12:"ignore_cache";b:1;s:6:"params";a:1:{i:0;a:1:{s:10:"context_id";s:12:"{profile_id}";}}}', 0, 0, 1, 2);

DELETE FROM `sys_objects_page` WHERE `object`='bx_joined_events';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_joined_events', '_bx_events_page_title_sys_joined', '_bx_events_page_title_joined', 'bx_events', 5, 2147483647, 1, 'events-joined', 'page.php?i=events-joined', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_joined_events';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_joined_events', 1, 'bx_events', '', '_bx_events_page_block_title_joined_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:21:"browse_joined_entries";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;b:1;}}', 0, 1, 0);


DELETE FROM `sys_pages_blocks` WHERE `module`='bx_events' AND `title` IN ('_bx_events_page_block_title_calendar_for_context', '_bx_events_page_block_title_browse_for_context');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`, `active`) VALUES
('trigger_page_group_view_entry', 2, 'bx_events', '', '_bx_events_page_block_title_calendar_for_context', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:9:"bx_events";s:6:"method";s:8:"calendar";s:12:"ignore_cache";b:1;s:6:"params";a:1:{i:0;a:1:{s:10:"context_id";s:12:"{profile_id}";}}}', 0, 0, 0, 0),
('trigger_page_group_view_entry', 4, 'bx_events', '', '_bx_events_page_block_title_browse_for_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:14:"browse_context";s:6:"params";a:1:{s:10:"context_id";s:12:"{profile_id}";}}', 0, 0, 1, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions' AND `name`='profile-set-badges';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_events_view_actions', 'bx_events', 'profile-set-badges', '_sys_menu_item_title_system_set_badges', '_sys_menu_item_title_set_badges', 'javascript:void(0)', 'bx_menu_popup(''sys_set_badges'', window, {}, {module: ''bx_events'', content_id: {content_id}});', '', 'check-circle', '', '', 0, 192, 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 1, 30);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_all' AND `name`='profile-set-badges';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_actions_all', 'bx_events', 'profile-set-badges', '_sys_menu_item_title_system_set_badges', '', '', '', '', '', '', '', 0, 192, 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 50);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_submenu' AND `name`='events-joined';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_submenu', 'bx_events', 'events-joined', '_bx_events_menu_item_title_system_entries_joined', '_bx_events_menu_item_title_entries_joined', 'page.php?i=events-joined', '', '', '', '', '', 2147483647, '', 1, 1, 6);

DELETE FROM `sys_menu_items` WHERE `module`='bx_events' AND `name`='events-context' AND `title_system`='_bx_events_menu_item_title_system_view_entries_in_context';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('trigger_group_view_submenu', 'bx_events', 'events-context', '_bx_events_menu_item_title_system_view_entries_in_context', '_bx_events_menu_item_title_view_entries_in_context', 'page.php?i=events-context&profile_id={profile_id}', '', '', 'calendar col-red2', '', '', 2147483647, 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:21:"is_enable_for_context";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 1, 0, 0);


-- ACL
SET @iIdActionProfileDeleteInvites = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_events' AND `Name`='delete invites' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `Module`='bx_events' AND `Name`='delete invites';
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionProfileDeleteInvites;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_events', 'delete invites', NULL, '_bx_events_acl_action_delete_invites', '', 1, 3);
SET @iIdActionProfileDeleteInvites = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionProfileDeleteInvites),
(@iAdministrator, @iIdActionProfileDeleteInvites);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_events_invites';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `override_class_name`, `override_class_file`) VALUES
('bx_events_invites', 'Sql', 'SELECT `bx_events_invites`.`id`, `bx_events_invites`.`invited_profile_id`, `bx_events_invites`.`added`, `bx_events_invites`.`author_profile_id` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) INNER JOIN `bx_events_invites` ON `bx_events_invites`.`invited_profile_id` = `p`.`id` ', 'bx_events_invites', 'id', 'bx_events_invites`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 0, 'BxEventsGridInvites', 'modules/boonex/events/classes/BxEventsGridInvites.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_events_invites';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_events_invites', 'name', '_sys_name', '33%', '', 10),
('bx_events_invites', 'added', '_sys_added', '33%', '', 20),
('bx_events_invites', 'actions', '', '34%', '', 30);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_events_invites';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_events_invites', 'single', 'delete', '', 'remove', 1, 10);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_events_administration' AND `name` IN ('audit_content', 'audit_context');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_events_administration', 'single', 'audit_content', '_bx_events_grid_action_title_adm_audit_content', 'search', 1, 0, 3),
('bx_events_administration', 'single', 'audit_context', '_bx_events_grid_action_title_adm_audit_context', 'search-location', 1, 0, 4);


-- PRIVACY 
UPDATE `sys_objects_privacy` SET `spaces`='bx_groups' WHERE `object`='bx_events_allow_view_to';
