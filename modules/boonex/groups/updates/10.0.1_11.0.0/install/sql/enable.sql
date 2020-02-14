-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_groups_fans' AND `title_system`='_bx_groups_page_block_title_system_invites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_fans', 1, 'bx_groups', '_bx_groups_page_block_title_system_invites', '_bx_groups_page_block_title_fans_invites', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:13:"invites_table";}', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_joined_groups';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_joined_groups', '_bx_groups_page_title_sys_joined', '_bx_groups_page_title_joined', 'bx_groups', 5, 2147483647, 1, 'groups-joined', 'page.php?i=groups-joined', '', '', '', 0, 1, 0, 'BxGroupsPageBrowse', 'modules/boonex/groups/classes/BxGroupsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_joined_groups';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_joined_groups', 1, 'bx_groups', '', '_bx_groups_page_block_title_joined_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:21:"browse_joined_entries";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;b:1;}}', 0, 1, 0);

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `module`='bx_groups' AND `title_system` IN ('_bx_groups_page_block_title_sys_labels_tree', '_bx_groups_page_block_title_sys_groups_browse_by_label', '_bx_groups_page_block_title_sys_labels_breadcrumbs');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_groups', '_bx_groups_page_block_title_sys_labels_tree', '_bx_groups_page_block_title_labels_tree', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:15:"get_lables_tree";}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1),
('', 0, 'bx_groups', '_bx_groups_page_block_title_sys_groups_browse_by_label', '_bx_groups_page_block_title_groups_browse_by_label', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:15:"browse_by_label";}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 2),
('', 0, 'bx_groups', '_bx_groups_page_block_title_sys_labels_breadcrumbs', '_bx_groups_page_block_title_labels_breadcrumbs', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:22:"get_lables_breadcrumbs";}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 3);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions' AND `name`='profile-set-badges';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_groups_view_actions', 'bx_groups', 'profile-set-badges', '_sys_menu_item_title_system_set_badges', '_sys_menu_item_title_set_badges', 'javascript:void(0)', 'bx_menu_popup(''sys_set_badges'', window, {}, {module: ''bx_groups'', content_id: {content_id}});', '', 'check-circle', '', '', 0, 192, 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 1, 30);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_view_actions_all' AND `name`='profile-set-badges';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_all', 'bx_groups', 'profile-set-badges', '_sys_menu_item_title_system_set_badges', '', '', '', '', '', '', '', 0, 192, 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 50);


DELETE FROM `sys_menu_items` WHERE `set_name`='bx_groups_submenu' AND `name`='groups-joined';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_groups_submenu', 'bx_groups', 'groups-joined', '_bx_groups_menu_item_title_system_entries_joined', '_bx_groups_menu_item_title_entries_joined', 'page.php?i=groups-joined', '', '', '', '', '', 2147483647, '', 1, 1, 4);


-- ACL
SET @iIdActionProfileDeleteInvites = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_groups' AND `Name`='delete invites' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `Module`='bx_groups' AND `Name`='delete invites';
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionProfileDeleteInvites;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_groups', 'delete invites', NULL, '_bx_groups_acl_action_delete_invites', '', 1, 3);
SET @iIdActionProfileDeleteInvites = LAST_INSERT_ID();

SET @iModerator = 7;
SET @iAdministrator = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionProfileDeleteInvites),
(@iAdministrator, @iIdActionProfileDeleteInvites);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_groups_invites';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_invites', 'Sql', 'SELECT `bx_groups_invites`.`id`, `bx_groups_invites`.`invited_profile_id`, `bx_groups_invites`.`added`, `bx_groups_invites`.`author_profile_id` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) INNER JOIN `bx_groups_invites` ON `bx_groups_invites`.`invited_profile_id` = `p`.`id` ', 'bx_groups_invites', 'id', 'bx_groups_invites`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 0, 'BxGroupsGridInvites', 'modules/boonex/groups/classes/BxGroupsGridInvites.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_groups_invites';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_groups_invites', 'name', '_sys_name', '33%', '', 10),
('bx_groups_invites', 'added', '_sys_added', '33%', '', 20),
('bx_groups_invites', 'actions', '', '34%', '', 30);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_groups_invites';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_groups_invites', 'single', 'delete', '', 'remove', 1, 10);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_groups_administration' AND `name` IN ('audit_content', 'audit_context');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_groups_administration', 'single', 'audit_content', '_bx_groups_grid_action_title_adm_audit_content', 'search', 1, 0, 3),
('bx_groups_administration', 'single', 'audit_context', '_bx_groups_grid_action_title_adm_audit_context', 'search-location', 1, 0, 4);
