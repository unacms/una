
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_organizations', '_bx_orgs', 'bx_organizations@modules/boonex/organizations/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_organizations', '_bx_orgs', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_organizations_autoapproval', 'on', @iCategId, '_bx_orgs_option_autoapproval', 'checkbox', '', '', '', 1),
('bx_organizations_default_acl_level', '3', @iCategId, '_bx_orgs_option_default_acl_level', 'select', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"get_memberships";s:6:"params";a:2:{i:0;b:0;i:1;b:1;}s:5:"class";s:16:"TemplAclServices";}', '', '', 2),
('bx_organizations_num_connections_quick', '4', @iCategId, '_bx_orgs_option_num_connections_quick', 'digit', '', '', '', 10),
('bx_organizations_num_rss', '10', @iCategId, '_bx_orgs_option_num_rss', 'digit', '', '', '', 12);

-- PAGES

-- PAGE: create profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_create_profile', 'create-organization-profile', '_bx_orgs_page_title_sys_create_profile', '_bx_orgs_page_title_create_profile', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=create-organization-profile', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_create_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_create_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:13:\"entity_create\";}', 0, 1, 1);

-- PAGE: view profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_profile', 'view-organization-profile', '_bx_orgs_page_title_sys_view_profile', '_bx_orgs_page_title_view_profile', 'bx_organizations', 10, 2147483647, 1, 'page.php?i=view-organization-profile', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_view_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 0),
('bx_organizations_view_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_cover', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:13:\"profile_cover\";}', 0, 0, 1),
('bx_organizations_view_profile', 2, 'bx_organizations', '_bx_orgs_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 0),
('bx_organizations_view_profile', 3, 'bx_organizations', '_bx_orgs_page_block_title_profile_friends', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:15:\"profile_friends\";}', 0, 0, 0);

-- PAGE: edit profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_edit_profile', 'edit-organization-profile', '_bx_orgs_page_title_sys_edit_profile', '_bx_orgs_page_title_edit_profile', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=edit-organization-profile', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_edit_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_edit_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:11:\"entity_edit\";}', 0, 0, 0);

-- PAGE: edit profile cover

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_edit_profile_cover', 'edit-organization-cover', '_bx_orgs_page_title_sys_edit_profile_cover', '_bx_orgs_page_title_edit_profile_cover', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=edit-organization-cover', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_edit_profile_cover', 1, 'bx_organizations', '_bx_orgs_page_block_title_edit_profile_cover', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:17:\"entity_edit_cover\";}', 0, 0, 0);

-- PAGE: delete profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_delete_profile', 'delete-organization-profile', '_bx_orgs_page_title_sys_delete_profile', '_bx_orgs_page_title_delete_profile', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=delete-organization-profile', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_delete_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_delete_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:13:\"entity_delete\";}', 0, 0, 0);

-- PAGE: profile info

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_profile_info', 'organization-profile-info', '_bx_orgs_page_title_sys_profile_info', '_bx_orgs_page_title_profile_info', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=organization-profile-info', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_profile_info', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1);

-- PAGE: profile friends

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_profile_friends', 'organization-profile-friends', '_bx_orgs_page_title_sys_profile_friends', '_bx_orgs_page_title_profile_friends', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=organization-profile-friends', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_profile_friends', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_friends', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:17:\"connections_table\";s:5:\"class\";s:23:\"TemplServiceConnections\";}', 0, 0, 1);

-- PAGE: module home

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_home', '_bx_orgs_page_title_sys_home', '_bx_orgs_page_title_home', 'bx_organizations', 5, 2147483647, 1, 'organizations-home', 'page.php?i=organizations-home', '', '', '', 0, 1, 0, 'BxOrgsPageBrowse', 'modules/boonex/organizations/classes/BxOrgsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_home', 1, 'bx_organizations', '_bx_orgs_page_block_title_latest_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:22:\"browse_recent_profiles\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 0);

-- PAGE: module manage
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_manage', '_bx_orgs_page_title_sys_manage', '_bx_orgs_page_title_manage', 'bx_organizations', 5, 2147483647, 1, 'organizations-manage', 'page.php?i=organizations-manage', '', '', '', 0, 1, 0, 'BxOrgsPageBrowse', 'modules/boonex/organizations/classes/BxOrgsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_manage', 1, 'bx_organizations', '_bx_orgs_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:12:\"manage_tools\";}}', 0, 1, 0);

-- PAGE: module moderation
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_moderation', '_bx_orgs_page_title_sys_manage', '_bx_orgs_page_title_manage', 'bx_organizations', 5, 64, 1, 'organizations-moderation', 'page.php?i=organizations-moderation', '', '', '', 0, 1, 0, 'BxOrgsPageBrowse', 'modules/boonex/organizations/classes/BxOrgsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_moderation', 1, 'bx_organizations', '_bx_orgs_page_block_title_manage', 11, 64, 'service', 'a:3:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:10:\"moderation\";}}', 0, 1, 0);

-- PAGE: module administration
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_administration', '_bx_orgs_page_title_sys_manage', '_bx_orgs_page_title_manage', 'bx_organizations', 5, 128, 1, 'organizations-administration', 'page.php?i=organizations-administration', '', '', '', 0, 1, 0, 'BxOrgsPageBrowse', 'modules/boonex/organizations/classes/BxOrgsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_administration', 1, 'bx_organizations', '_bx_orgs_page_block_title_manage', 11, 128, 'service', 'a:3:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);

-- PAGE: add block to homepage

SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_home', 1, 'bx_organizations', '_bx_orgs_page_block_title_latest_profiles', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:22:"browse_recent_profiles";}', 1, 0, IFNULL(@iBlockOrder, 0) + 1);

-- MENU

-- MENU: add to site menu

SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_organizations', 'organizations-home', '_bx_orgs_menu_item_title_system_entries_home', '_bx_orgs_menu_item_title_entries_home', 'page.php?i=organizations-home', '', '', 'briefcase col-red2', 'bx_organizations_submenu', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);


--SET @iCreateProfileMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_profiles_create' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
--INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
--('sys_profiles_create', 'bx_organizations', 'create-organization-profile', '_bx_orgs_menu_item_title_system_profile_type', '_bx_orgs_menu_item_title_profile_type', 'page.php?i=create-organization-profile', '', '', 'briefcase', '', 2147483647, 1, 1, IFNULL(@iCreateProfileMenuOrder, 0) + 1);

SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_profile_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_profile_links', 'bx_organizations', 'create-organization-profile', '_bx_orgs_menu_item_title_system_create_profile', '_bx_orgs_menu_item_title_create_profile', 'page.php?i=create-organization-profile', '', '', 'briefcase', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);

-- MENU: view actions

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_actions', '_bx_orgs_menu_title_view_profile_actions', 'bx_organizations_view_actions', 'bx_organizations', 9, 0, 1, 'BxOrgsMenuView', 'modules/boonex/organizations/classes/BxOrgsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_view_actions', 'bx_organizations', '_bx_orgs_menu_set_title_view_profile_actions', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions', 'bx_organizations', 'profile-friend-add', '_bx_orgs_menu_item_title_system_befriend', '{title_add_friend}', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_friends\', \'add\', \'{profile_id}\')', '', 'plus', '', 2147483647, 1, 0, 10),
('bx_organizations_view_actions', 'bx_organizations', 'profile-subscribe-add', '_bx_orgs_menu_item_title_system_subscribe', '_bx_orgs_menu_item_title_subscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'add\', \'{profile_id}\')', '', 'check', '', 2147483647, 1, 0, 20),
('bx_organizations_view_actions', 'bx_organizations', 'profile-set-acl-level', '_sys_menu_item_title_system_set_acl_level', '_sys_menu_item_title_set_acl_level', 'javascript:void(0)', 'bx_menu_popup(''sys_set_acl_level'', window, {}, {profile_id: {profile_id}});', '', 'certificate', '', 192, 1, 0, 1000),
('bx_organizations_view_actions', 'bx_organizations', 'profile-actions-more', '_bx_orgs_menu_item_title_system_more_actions', '_bx_orgs_menu_item_title_more_actions', 'javascript:void(0)', 'bx_menu_popup(''bx_organizations_view_actions_more'', this, {}, {profile_id:{profile_id}});', '', 'cog', 'bx_organizations_view_actions_more', 2147483647, 1, 0, 9999);

-- MENU: view actions more

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_actions_more', '_bx_orgs_menu_title_view_profile_actions_more', 'bx_organizations_view_actions_more', 'bx_organizations', 6, 0, 1, 'BxOrgsMenuView', 'modules/boonex/organizations/classes/BxOrgsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_view_actions_more', 'bx_organizations', '_bx_orgs_menu_set_title_view_profile_actions_more', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_more', 'bx_organizations', 'profile-friend-remove', '_bx_orgs_menu_item_title_system_unfriend', '{title_remove_friend}', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_friends\', \'remove\', \'{profile_id}\')', '', 'minus', '', 2147483647, 1, 0, 10),
('bx_organizations_view_actions_more', 'bx_organizations', 'profile-subscribe-remove', '_bx_orgs_menu_item_title_system_unsubscribe', '_bx_orgs_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'remove\', \'{profile_id}\')', '', 'check', '', 2147483647, 1, 0, 20),
('bx_organizations_view_actions_more', 'bx_organizations', 'edit-organization-profile', '_bx_orgs_menu_item_title_system_edit_profile', '_bx_orgs_menu_item_title_edit_profile', 'page.php?i=edit-organization-profile&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 30),
('bx_organizations_view_actions_more', 'bx_organizations', 'delete-organization-profile', '_bx_orgs_menu_item_title_system_delete_profile', '_bx_orgs_menu_item_title_delete_profile', 'page.php?i=delete-organization-profile&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 40);

-- MENU: module sub-menu

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_submenu', '_bx_orgs_menu_title_submenu', 'bx_organizations_submenu', 'bx_organizations', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_submenu', 'bx_organizations', '_bx_orgs_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_submenu', 'bx_organizations', 'organizations-home', '_bx_orgs_menu_item_title_system_entries_public', '_bx_orgs_menu_item_title_entries_public', 'page.php?i=organizations-home', '', '', '', '', 2147483647, 1, 1, 1),
('bx_organizations_submenu', 'bx_organizations', 'organizations-manage', '_bx_orgs_menu_item_title_system_entries_manage', '_bx_orgs_menu_item_title_entries_manage', 'page.php?i=organizations-manage', '', '', '', '', 2147483646, 1, 1, 2);

-- MENU: view submenu

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_submenu', '_bx_orgs_menu_title_view_profile_submenu', 'bx_organizations_view_submenu', 'bx_organizations', 8, 0, 1, 'BxOrgsMenuView', 'modules/boonex/organizations/classes/BxOrgsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_view_submenu', 'bx_organizations', '_bx_orgs_menu_set_title_view_profile_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_submenu', 'bx_organizations', 'view-organization-profile', '_bx_orgs_menu_item_title_system_view_profile_view', '_bx_orgs_menu_item_title_view_profile_view', 'page.php?i=view-organization-profile&id={content_id}', '', '', '', '', 2147483647, 1, 0, 1),
('bx_organizations_view_submenu', 'bx_organizations', 'organization-profile-info', '_bx_orgs_menu_item_title_system_view_profile_info', '_bx_orgs_menu_item_title_view_profile_info', 'page.php?i=organization-profile-info&id={content_id}', '', '', '', '', 2147483647, 1, 0, 2),
('bx_organizations_view_submenu', 'bx_organizations', 'organization-profile-friends', '_bx_orgs_menu_item_title_system_view_profile_friends', '_bx_orgs_menu_item_title_view_profile_friends', 'page.php?i=organization-profile-friends&profile_id={profile_id}', '', '', '', '', 2147483647, 1, 0, 3);

-- MENU: notifications menu in account popup

SET @iNotifMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'bx_organizations', 'notifications-friend-requests', '_bx_orgs_menu_item_title_system_friends', '_bx_orgs_menu_item_title_friends', 'page.php?i=organization-profile-friends&profile_id={member_id}', '', '', 'group col-red2', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:31:"get_unconfirmed_connections_num";s:6:"params";a:1:{i:0;s:20:"sys_profiles_friends";}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, 1, 0, IFNULL(@iNotifMenuOrder, 0) + 1);

-- MENU: profile stats
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_organizations', 'profile-stats-friend-requests', '_bx_orgs_menu_item_title_system_friend_requests', '_bx_orgs_menu_item_title_friend_requests', 'page.php?i=organization-profile-friends&profile_id={member_id}', '', '', 'briefcase col-red2', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:31:"get_unconfirmed_connections_num";s:6:"params";a:1:{i:0;s:20:"sys_profiles_friends";}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 1),
('sys_profile_stats', 'bx_organizations', 'profile-stats-manage-organizations', '_bx_orgs_menu_item_title_system_manage_my_organizations', '_bx_orgs_menu_item_title_manage_my_organizations', 'page.php?i=organizations-manage', '', '_self', 'briefcase col-red2', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:41:"get_menu_addon_manage_tools_profile_stats";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 2);

-- MENU: manage tools submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_menu_manage_tools', '_bx_orgs_menu_title_manage_tools', 'bx_organizations_menu_manage_tools', 'bx_organizations', 6, 0, 1, 'BxOrgsMenuManageTools', 'modules/boonex/organizations/classes/BxOrgsMenuManageTools.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_menu_manage_tools', 'bx_organizations', '_bx_orgs_menu_set_title_manage_tools', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_menu_manage_tools', 'bx_organizations', 'delete-with-content', '_bx_orgs_menu_item_title_system_delete_with_content', '_bx_orgs_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id});', '_self', 'trash-o', '', 128, 1, 0, 0);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_organizations', 'organizations-moderation', '_bx_orgs_menu_item_title_system_admt_organizations', '_bx_orgs_menu_item_title_admt_organizations', 'page.php?i=organizations-moderation', '', '_self', '', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 64, 1, 0, @iManageMenuOrder + 1),
('sys_account_dashboard_manage_tools', 'bx_organizations', 'organizations-administration', '_bx_orgs_menu_item_title_system_admt_organizations', '_bx_orgs_menu_item_title_admt_organizations', 'page.php?i=organizations-administration', '', '_self', '', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 128, 1, 0, @iManageMenuOrder + 2);


-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'create entry', NULL, '_bx_orgs_acl_action_create_profile', '', 1, 1);
SET @iIdActionProfileCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'delete entry', NULL, '_bx_orgs_acl_action_delete_profile', '', 1, 1);
SET @iIdActionProfileDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'view entry', NULL, '_bx_orgs_acl_action_view_profile', '', 1, 0);
SET @iIdActionProfileView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'edit any entry', NULL, '_bx_orgs_acl_action_edit_any_profile', '', 1, 3);
SET @iIdActionProfileEditAny = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- profile create
(@iAccount, @iIdActionProfileCreate),
(@iStandard, @iIdActionProfileCreate),
(@iUnconfirmed, @iIdActionProfileCreate),
(@iPending, @iIdActionProfileCreate),
(@iModerator, @iIdActionProfileCreate),
(@iAdministrator, @iIdActionProfileCreate),
(@iPremium, @iIdActionProfileCreate),

-- profile delete
(@iAccount, @iIdActionProfileDelete),
(@iStandard, @iIdActionProfileDelete),
(@iUnconfirmed, @iIdActionProfileDelete),
(@iPending, @iIdActionProfileDelete),
(@iModerator, @iIdActionProfileDelete),
(@iAdministrator, @iIdActionProfileDelete),
(@iPremium, @iIdActionProfileDelete),

-- profile view
(@iUnauthenticated, @iIdActionProfileView),
(@iAccount, @iIdActionProfileView),
(@iStandard, @iIdActionProfileView),
(@iUnconfirmed, @iIdActionProfileView),
(@iPending, @iIdActionProfileView),
(@iModerator, @iIdActionProfileView),
(@iAdministrator, @iIdActionProfileView),
(@iPremium, @iIdActionProfileView),

-- any profile edit
(@iModerator, @iIdActionProfileEditAny),
(@iAdministrator, @iIdActionProfileEditAny);


-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_organizations', 'bx_organizations_views_track', '86400', '1', 'bx_organizations_data', 'id', 'views', '', '');

-- METATAGS
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations', 'bx_organizations_meta_keywords', '', '', '', '');

-- SEARCH
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `ClassName`, `ClassPath`) VALUES
('bx_organizations', '_bx_orgs', 'BxOrgsSearchResult', 'modules/boonex/organizations/classes/BxOrgsSearchResult.php');

-- GRIDS: administration
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_administration', 'Sql', 'SELECT `td`.*, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status` FROM `bx_organizations_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_organizations'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_organizations_data', 'id', '', 'status', '', 20, NULL, 'start', '', 'org_name', '', 'like', '', '', 'BxOrgsGridAdministration', 'modules/boonex/organizations/classes/BxOrgsGridAdministration.php'),
('bx_organizations_moderation', 'Sql', 'SELECT `td`.*, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status` FROM `bx_organizations_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_organizations'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_organizations_data', 'id', '', 'status', '', 20, NULL, 'start', '', 'org_name', '', 'like', '', '', 'BxOrgsGridModeration', 'modules/boonex/organizations/classes/BxOrgsGridModeration.php'),
('bx_organizations_common', 'Sql', 'SELECT `td`.*, `ta`.`email` AS `account`, `ta`.`logged` AS `last_online`, `tp`.`status` AS `status` FROM `bx_organizations_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_organizations'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_organizations_data', 'id', '', 'status', '', 20, NULL, 'start', '', 'org_name', '', 'like', '', '', 'BxOrgsGridCommon', 'modules/boonex/organizations/classes/BxOrgsGridCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_organizations_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_organizations_administration', 'switcher', '_bx_orgs_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_organizations_administration', 'org_name', '_bx_orgs_grid_column_title_adm_org_name', '25%', 0, '', '', 3),
('bx_organizations_administration', 'last_online', '_bx_orgs_grid_column_title_adm_last_online', '20%', 1, '25', '', 4),
('bx_organizations_administration', 'account', '_bx_orgs_grid_column_title_adm_account', '25%', 0, '25', '', 5),
('bx_organizations_administration', 'actions', '', '20%', 0, '', '', 6),
('bx_organizations_moderation', 'switcher', '', '10%', 0, '', '', 1),
('bx_organizations_moderation', 'org_name', '_bx_orgs_grid_column_title_adm_org_name', '25%', 0, '', '', 2),
('bx_organizations_moderation', 'last_online', '_bx_orgs_grid_column_title_adm_last_online', '25%', 1, '25', '', 3),
('bx_organizations_moderation', 'account', '_bx_orgs_grid_column_title_adm_account', '25%', 0, '25', '', 4),
('bx_organizations_moderation', 'actions', '', '15%', 0, '', '', 5),
('bx_organizations_common', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_organizations_common', 'org_name', '_bx_orgs_grid_column_title_adm_org_name', '48%', 0, '', '', 2),
('bx_organizations_common', 'last_online', '_bx_orgs_grid_column_title_adm_last_online', '30%', 1, '25', '', 3),
('bx_organizations_common', 'actions', '', '20%', 0, '', '', 4);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_organizations_administration', 'bulk', 'set_acl_level', '_bx_orgs_grid_action_title_adm_set_acl_level', '', 0, 0, 1),
('bx_organizations_administration', 'bulk', 'delete', '_bx_orgs_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_organizations_administration', 'bulk', 'delete_with_content', '_bx_orgs_grid_action_title_adm_delete_with_content', '', 0, 1, 3),
('bx_organizations_administration', 'single', 'set_acl_level', '_bx_orgs_grid_action_title_adm_set_acl_level', 'certificate', 1, 0, 1),
('bx_organizations_administration', 'single', 'delete', '_bx_orgs_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_organizations_administration', 'single', 'settings', '_bx_orgs_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),
('bx_organizations_moderation', 'single', 'settings', '_bx_orgs_grid_action_title_adm_more_actions', 'cog', 1, 0, 1),
('bx_organizations_common', 'bulk', 'delete', '_bx_orgs_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_organizations_common', 'bulk', 'delete_with_content', '_bx_orgs_grid_action_title_adm_delete_with_content', '', 0, 1, 3),
('bx_organizations_common', 'single', 'delete', '_bx_orgs_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_organizations_common', 'single', 'settings', '_bx_orgs_grid_action_title_adm_more_actions', 'cog', 1, 0, 3);
