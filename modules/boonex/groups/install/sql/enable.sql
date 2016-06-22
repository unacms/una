
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_groups', '_bx_groups', 'bx_groups@modules/boonex/groups/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_groups', '_bx_groups', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_groups_num_connections_quick', '4', @iCategId, '_bx_groups_option_num_connections_quick', 'digit', '', '', '', 10),
('bx_groups_num_rss', '10', @iCategId, '_bx_groups_option_num_rss', 'digit', '', '', '', 12);

-- PAGES

-- PAGE: create profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_create_profile', 'create-group-profile', '_bx_groups_page_title_sys_create_profile', '_bx_groups_page_title_create_profile', 'bx_groups', 5, 2147483647, 1, 'page.php?i=create-group-profile', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_create_profile', 1, 'bx_groups', '_bx_groups_page_block_title_create_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:13:\"entity_create\";}', 0, 1, 1);

-- PAGE: view profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_view_profile', 'view-group-profile', '_bx_groups_page_title_sys_view_profile', '_bx_groups_page_title_view_profile', 'bx_groups', 10, 2147483647, 1, 'page.php?i=view-group-profile', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_view_profile', 1, 'bx_groups', '', '_bx_groups_page_block_title_entry_social_sharing', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:21:\"entity_social_sharing\";}', 0, 0, 1, 0),
('bx_groups_view_profile', 2, 'bx_groups', '', '_bx_groups_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 0),
('bx_groups_view_profile', 3, 'bx_groups', '', '_bx_groups_page_block_title_fans', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:4:\"fans\";}', 0, 0, 1, 0),
('bx_groups_view_profile', 4, 'bx_groups', '', '_bx_groups_page_block_title_profile_description', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 0);

-- PAGE: view closed profile 

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_view_profile_closed', 'view-group-profile-closed', '_bx_groups_page_title_sys_view_profile_closed', '_bx_groups_page_title_view_profile', 'bx_groups', 10, 2147483647, 1, 'page.php?i=view-group-profile', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_view_profile_closed', 2, 'bx_groups', '', '_bx_groups_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 0),
('bx_groups_view_profile_closed', 3, 'bx_groups', '', '_bx_groups_page_block_title_fans', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:4:\"fans\";}', 0, 0, 1, 0);

-- PAGE: edit profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_edit_profile', 'edit-group-profile', '_bx_groups_page_title_sys_edit_profile', '_bx_groups_page_title_edit_profile', 'bx_groups', 5, 2147483647, 1, 'page.php?i=edit-group-profile', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_edit_profile', 1, 'bx_groups', '_bx_groups_page_block_title_edit_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:11:\"entity_edit\";}', 0, 0, 0);

-- PAGE: edit profile cover

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_edit_profile_cover', 'edit-group-cover', '_bx_groups_page_title_sys_edit_profile_cover', '_bx_groups_page_title_edit_profile_cover', 'bx_groups', 5, 2147483647, 1, 'page.php?i=edit-group-cover', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_edit_profile_cover', 1, 'bx_groups', '_bx_groups_page_block_title_edit_profile_cover', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:17:\"entity_edit_cover\";}', 0, 0, 0);

-- PAGE: delete profile

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_delete_profile', 'delete-group-profile', '_bx_groups_page_title_sys_delete_profile', '_bx_groups_page_title_delete_profile', 'bx_groups', 5, 2147483647, 1, 'page.php?i=delete-group-profile', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_delete_profile', 1, 'bx_groups', '_bx_groups_page_block_title_delete_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:13:\"entity_delete\";}', 0, 0, 0);

-- PAGE: profile info

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_profile_info', 'group-profile-info', '_bx_groups_page_title_sys_profile_info', '_bx_groups_page_title_profile_info', 'bx_groups', 5, 2147483647, 1, 'page.php?i=group-profile-info', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_profile_info', 1, 'bx_groups', '_bx_groups_page_block_title_system_profile_info', '_bx_groups_page_block_title_profile_info_link', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:16:\"entity_info_full\";}', 0, 0, 1, 1),
('bx_groups_profile_info', 1, 'bx_groups', '', '_bx_groups_page_block_title_profile_description', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 2);

-- PAGE: group fans

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_fans', 'group-fans', '_bx_groups_page_title_sys_group_fans', '_bx_groups_page_title_group_fans', 'bx_groups', 5, 2147483647, 1, 'page.php?i=group-fans', '', '', '', 0, 1, 0, 'BxGroupsPageEntry', 'modules/boonex/groups/classes/BxGroupsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_fans', 1, 'bx_groups', '_bx_groups_page_block_title_system_fans', '_bx_groups_page_block_title_fans_link', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:10:"fans_table";}', 0, 0, 1, 1);

-- PAGE: module home

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_home', '_bx_groups_page_title_sys_recent', '_bx_groups_page_title_recent', 'bx_groups', 5, 2147483647, 1, 'groups-home', 'page.php?i=groups-home', '', '', '', 0, 1, 0, 'BxGroupsPageBrowse', 'modules/boonex/groups/classes/BxGroupsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_home', 1, 'bx_groups', '_bx_groups_page_block_title_latest_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:22:\"browse_recent_profiles\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 0);

-- PAGE: top profiles

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_top', '_bx_groups_page_title_sys_top', '_bx_groups_page_title_top', 'bx_groups', 5, 2147483647, 1, 'groups-top', 'page.php?i=groups-top', '', '', '', 0, 1, 0, 'BxGroupsPageBrowse', 'modules/boonex/groups/classes/BxGroupsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_top', 1, 'bx_groups', '_bx_groups_page_block_title_top_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:19:\"browse_top_profiles\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 0);

-- PAGE: module manage own
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_manage', '_bx_groups_page_title_sys_manage', '_bx_groups_page_title_manage', 'bx_groups', 5, 2147483647, 1, 'groups-manage', 'page.php?i=groups-manage', '', '', '', 0, 1, 0, 'BxGroupsPageBrowse', 'modules/boonex/groups/classes/BxGroupsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_manage', 1, 'bx_groups', '_bx_groups_page_block_title_system_manage', '_bx_groups_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:12:\"manage_tools\";}}', 0, 1, 0);

-- PAGE: module manage all
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_administration', '_bx_groups_page_title_sys_manage_administration', '_bx_groups_page_title_manage', 'bx_groups', 5, 192, 1, 'groups-administration', 'page.php?i=groups-administration', '', '', '', 0, 1, 0, 'BxGroupsPageBrowse', 'modules/boonex/groups/classes/BxGroupsPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_groups_administration', 1, 'bx_groups', '_bx_groups_page_block_title_system_manage_administration', '_bx_groups_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_groups\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);

-- PAGE: user's groups
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_joined', 'joined-groups', '_bx_groups_page_title_sys_joined_entries', '_bx_groups_page_title_joined_entries', 'bx_groups', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxGroupsPageJoinedEntries', 'modules/boonex/groups/classes/BxGroupsPageJoinedEntries.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_groups_joined', 1, 'bx_groups', '_bx_groups_page_block_title_sys_joined_entries', '_bx_groups_page_block_title_joined_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_groups";s:6:"method";s:21:"browse_joined_entries";s:6:"params";a:2:{i:0;i:0;i:1;b:1;}}', 0, 0, 1, 1);

-- PAGE: add block to homepage
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('sys_home', 1, 'bx_groups', '_bx_groups_page_block_title_latest_profiles', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:22:"browse_recent_profiles";}', 1, 0, 0, IFNULL(@iBlockOrder, 0) + 1);

-- PAGE: service blocks
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_groups', '', '_bx_groups_page_block_title_categories', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"categories_list";s:6:"params";a:2:{i:0;s:14:"bx_groups_cats";i:1;a:2:{s:10:\"show_empty\";b:1;s:21:\"show_empty_categories\";b:1;}}s:5:"class";s:20:"TemplServiceCategory";}', 1, 1, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENU

-- MENU: add to site menu

SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_groups', 'groups-home', '_bx_groups_menu_item_title_system_entries_home', '_bx_groups_menu_item_title_entries_home', 'page.php?i=groups-home', '', '', 'group col-red2', 'bx_groups_submenu', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

-- MENU: add to homepage menu

SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_groups', 'groups-home', '_bx_groups_menu_item_title_system_entries_home', '_bx_groups_menu_item_title_entries_home', 'page.php?i=groups-home', '', '', 'group col-red2', 'bx_groups_submenu', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

-- MENU: add to "add content" menu

SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', 'bx_groups', 'create-group-profile', '_bx_groups_menu_item_title_system_create_profile', '_bx_groups_menu_item_title_create_profile', 'page.php?i=create-group-profile', '', '', 'group col-red2', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);

-- MENU: view actions

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_view_actions', '_bx_groups_menu_title_view_profile_actions', 'bx_groups_view_actions', 'bx_groups', 9, 0, 1, 'BxGroupsMenuView', 'modules/boonex/groups/classes/BxGroupsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_groups_view_actions', 'bx_groups', '_bx_groups_menu_set_title_view_profile_actions', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions', 'bx_groups', 'profile-fan-add', '_bx_groups_menu_item_title_system_become_fan', '{title_add_fan}', 'javascript:void(0)', 'bx_conn_action(this, \'bx_groups_fans\', \'add\', \'{profile_id}\')', '', 'user-plus', '', 0, 2147483647, 1, 0, 5),
('bx_groups_view_actions', 'bx_groups', 'profile-subscribe-add', '_bx_groups_menu_item_title_system_subscribe', '_bx_groups_menu_item_title_subscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'add\', \'{profile_id}\')', '', 'check', '', 0, 2147483647, 1, 0, 20),
('bx_groups_view_actions', 'bx_groups', 'profile-actions-more', '_bx_groups_menu_item_title_system_more_actions', '_bx_groups_menu_item_title_more_actions', 'javascript:void(0)', 'bx_menu_popup(''bx_groups_view_actions_more'', this, {}, {profile_id:{profile_id}});', '', 'cog', 'bx_groups_view_actions_more', 1, 2147483647, 1, 0, 9999);

-- MENU: view actions more

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_view_actions_more', '_bx_groups_menu_title_view_profile_actions_more', 'bx_groups_view_actions_more', 'bx_groups', 6, 0, 1, 'BxGroupsMenuView', 'modules/boonex/groups/classes/BxGroupsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_groups_view_actions_more', 'bx_groups', '_bx_groups_menu_set_title_view_profile_actions_more', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_actions_more', 'bx_groups', 'profile-fan-remove', '_bx_groups_menu_item_title_system_leave_group', '{title_remove_fan}', 'javascript:void(0)', 'bx_conn_action(this, \'bx_groups_fans\', \'remove\', \'{profile_id}\')', '', 'user-times', '', 2147483647, 1, 0, 10),
('bx_groups_view_actions_more', 'bx_groups', 'profile-subscribe-remove', '_bx_groups_menu_item_title_system_unsubscribe', '_bx_groups_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'remove\', \'{profile_id}\')', '', 'check', '', 2147483647, 1, 0, 20),
('bx_groups_view_actions_more', 'bx_groups', 'edit-group-cover', '_bx_groups_menu_item_title_system_edit_cover', '_bx_groups_menu_item_title_edit_cover', 'page.php?i=edit-group-cover&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 30),
('bx_groups_view_actions_more', 'bx_groups', 'edit-group-profile', '_bx_groups_menu_item_title_system_edit_profile', '_bx_groups_menu_item_title_edit_profile', 'page.php?i=edit-group-profile&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 40),
('bx_groups_view_actions_more', 'bx_groups', 'delete-group-profile', '_bx_groups_menu_item_title_system_delete_profile', '_bx_groups_menu_item_title_delete_profile', 'page.php?i=delete-group-profile&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 50);

-- MENU: module sub-menu

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_submenu', '_bx_groups_menu_title_submenu', 'bx_groups_submenu', 'bx_groups', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_groups_submenu', 'bx_groups', '_bx_groups_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_submenu', 'bx_groups', 'groups-home', '_bx_groups_menu_item_title_system_entries_recent', '_bx_groups_menu_item_title_entries_recent', 'page.php?i=groups-home', '', '', '', '', 2147483647, 1, 1, 1),
('bx_groups_submenu', 'bx_groups', 'groups-top', '_bx_groups_menu_item_title_system_entries_top', '_bx_groups_menu_item_title_entries_top', 'page.php?i=groups-top', '', '', '', '', 2147483647, 1, 1, 2),
('bx_groups_submenu', 'bx_groups', 'groups-manage', '_bx_groups_menu_item_title_system_entries_manage', '_bx_groups_menu_item_title_entries_manage', 'page.php?i=groups-manage', '', '', '', '', 2147483646, 1, 1, 3);

-- MENU: view submenu

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_view_submenu', '_bx_groups_menu_title_view_profile_submenu', 'bx_groups_view_submenu', 'bx_groups', 8, 0, 1, 'BxGroupsMenuView', 'modules/boonex/groups/classes/BxGroupsMenuView.php'),
('bx_groups_view_submenu_cover', '_bx_groups_menu_title_view_profile_submenu_cover', 'bx_groups_view_submenu', 'bx_groups', 7, 0, 1, 'BxGroupsMenuView', 'modules/boonex/groups/classes/BxGroupsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_groups_view_submenu', 'bx_groups', '_bx_groups_menu_set_title_view_profile_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_view_submenu', 'bx_groups', 'view-group-profile', '_bx_groups_menu_item_title_system_view_profile_view', '_bx_groups_menu_item_title_view_profile_view', 'page.php?i=view-group-profile&id={content_id}', '', '', 'group col-red2', '', 2147483647, 1, 0, 1),
('bx_groups_view_submenu', 'bx_groups', 'group-profile-info', '_bx_groups_menu_item_title_system_view_profile_info', '_bx_groups_menu_item_title_view_profile_info', 'page.php?i=group-profile-info&id={content_id}', '', '', 'info-circle col-gray', '', 2147483647, 1, 0, 2),
('bx_groups_view_submenu', 'bx_groups', 'group-fans', '_bx_groups_menu_item_title_system_view_fans', '_bx_groups_menu_item_title_view_fans', 'page.php?i=group-fans&profile_id={profile_id}', '', '', 'group col-blue3', '', 2147483647, 1, 0, 3);

-- MENU: profile stats
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_groups', 'profile-stats-manage-groups', '_bx_groups_menu_item_title_system_manage_my_groups', '_bx_groups_menu_item_title_manage_my_groups', 'page.php?i=groups-manage', '', '_self', 'group col-red2', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:41:"get_menu_addon_manage_tools_profile_stats";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 2);

-- MENU: manage tools submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_menu_manage_tools', '_bx_groups_menu_title_manage_tools', 'bx_groups_menu_manage_tools', 'bx_groups', 6, 0, 1, 'BxGroupsMenuManageTools', 'modules/boonex/groups/classes/BxGroupsMenuManageTools.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_groups_menu_manage_tools', 'bx_groups', '_bx_groups_menu_set_title_manage_tools', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_groups_menu_manage_tools', 'bx_groups', 'delete', '_bx_groups_menu_item_title_system_delete', '_bx_groups_menu_item_title_delete', 'javascript:void(0)', 'javascript:{js_object}.onClickDelete({content_id});', '_self', 'trash-o', '', 2147483647, 1, 0, 1),
('bx_groups_menu_manage_tools', 'bx_groups', 'delete-with-content', '_bx_groups_menu_item_title_system_delete_with_content', '_bx_groups_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id});', '_self', 'trash-o', '', 2147483647, 1, 0, 2);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_groups', 'groups-administration', '_bx_groups_menu_item_title_system_admt_groups', '_bx_groups_menu_item_title_admt_groups', 'page.php?i=groups-administration', '', '_self', '', 'a:2:{s:6:"module";s:9:"bx_groups";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);

-- MENU: add menu item to profiles modules (trigger* menu sets are processed separately upon modules enable/disable)

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_submenu', 'bx_groups', 'joined-groups', '_bx_groups_menu_item_title_system_view_joined_groups', '_bx_groups_menu_item_title_view_joined_groups', 'page.php?i=joined-groups&profile_id={profile_id}', '', '', 'group col-red2', '', 2147483647, 1, 0, 0);

-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_groups', 'create entry', NULL, '_bx_groups_acl_action_create_profile', '', 1, 1);
SET @iIdActionProfileCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_groups', 'delete entry', NULL, '_bx_groups_acl_action_delete_profile', '', 1, 1);
SET @iIdActionProfileDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_groups', 'view entry', NULL, '_bx_groups_acl_action_view_profile', '', 1, 0);
SET @iIdActionProfileView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_groups', 'edit any entry', NULL, '_bx_groups_acl_action_edit_any_profile', '', 1, 3);
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
('bx_groups', 'bx_groups_views_track', '86400', '1', 'bx_groups_data', 'id', 'views', '', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_groups', 'bx_groups_votes', 'bx_groups_votes_track', '604800', '1', '1', '0', '1', 'bx_groups_data', 'id', 'author', 'rate', 'votes', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_groups', 'bx_groups_reports', 'bx_groups_reports_track', '1', 'page.php?i=view-group-profile&id={object_id}', 'bx_groups_data', 'id', 'author', 'reports', '', '');

-- METATAGS
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_groups', 'bx_groups_meta_keywords', '', '', '', '');

-- CATEGORY
INSERT INTO `sys_objects_category` (`object`, `search_object`, `form_object`, `list_name`, `table`, `field`, `join`, `where`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_cats', 'bx_groups', 'bx_group', 'bx_groups_cats', 'bx_groups_data', 'group_cat', 'INNER JOIN `sys_profiles` ON (`sys_profiles`.`content_id` = `bx_groups_data`.`id` AND `sys_profiles`.`type` = ''bx_groups'')', 'AND `sys_profiles`.`status` = ''active''', '', '');

-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
('bx_groups', '_bx_groups', @iSearchOrder + 1, 'BxGroupsSearchResult', 'modules/boonex/groups/classes/BxGroupsSearchResult.php');

-- CONNECTIONS
INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_fans', 'bx_groups_fans', 'mutual', '', '');

-- GRID: connections

INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_fans', 'Sql', 'SELECT `p`.`id`, `c`.`added`, `c`.`mutual` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxGroupsGridConnections', 'modules/boonex/groups/classes/BxGroupsGridConnections.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_groups_fans', 'name', '_sys_name', '50%', '', 10),
('bx_groups_fans', 'actions', '', '50%', '', 20);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_groups_fans', 'single', 'accept', '_sys_accept', '', 0, 10),
('bx_groups_fans', 'single', 'to_admins', '_bx_groups_txt_to_admins', '', 0, 20),
('bx_groups_fans', 'single', 'from_admins', '_bx_groups_txt_from_admins', '', 0, 30),
('bx_groups_fans', 'single', 'delete', '', 'remove', 1, 40);

-- GRIDS: administration

INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_administration', 'Sql', 'SELECT `td`.*, `ta`.`email` AS `account`, `td`.`added` AS `added_ts`, `tp`.`status` AS `status` FROM `bx_groups_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_groups'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_groups_data', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'group_name', '', 'like', '', '', 'BxGroupsGridAdministration', 'modules/boonex/groups/classes/BxGroupsGridAdministration.php'),
('bx_groups_common', 'Sql', 'SELECT `td`.*, `ta`.`email` AS `account`, `ta`.`added` AS `added_ts`, `tp`.`status` AS `status` FROM `bx_groups_data` AS `td` LEFT JOIN `sys_profiles` AS `tp` ON `td`.`id`=`tp`.`content_id` AND `tp`.`type`=''bx_groups'' LEFT JOIN `sys_accounts` AS `ta` ON `tp`.`account_id`=`ta`.`id` WHERE 1 ', 'bx_groups_data', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'group_name', '', 'like', '', '', 'BxGroupsGridCommon', 'modules/boonex/groups/classes/BxGroupsGridCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_groups_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_groups_administration', 'switcher', '_bx_groups_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_groups_administration', 'group_name', '_bx_groups_grid_column_title_adm_group_name', '25%', 0, '', '', 3),
('bx_groups_administration', 'added_ts', '_bx_groups_grid_column_title_adm_added', '20%', 1, '25', '', 4),
('bx_groups_administration', 'account', '_bx_groups_grid_column_title_adm_account', '25%', 0, '25', '', 5),
('bx_groups_administration', 'actions', '', '20%', 0, '', '', 6),
('bx_groups_common', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_groups_common', 'group_name', '_bx_groups_grid_column_title_adm_group_name', '48%', 0, '', '', 2),
('bx_groups_common', 'added_ts', '_bx_groups_grid_column_title_adm_added', '30%', 1, '25', '', 3),
('bx_groups_common', 'actions', '', '20%', 0, '', '', 4);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_groups_administration', 'bulk', 'delete', '_bx_groups_grid_action_title_adm_delete', '', 0, 1, 2),
('bx_groups_administration', 'bulk', 'delete_with_content', '_bx_groups_grid_action_title_adm_delete_with_content', '', 0, 1, 3),
('bx_groups_administration', 'single', 'settings', '_bx_groups_grid_action_title_adm_more_actions', 'cog', 1, 0, 2),
('bx_groups_common', 'bulk', 'delete', '_bx_groups_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_groups_common', 'bulk', 'delete_with_content', '_bx_groups_grid_action_title_adm_delete_with_content', '', 0, 1, 2),
('bx_groups_common', 'single', 'settings', '_bx_groups_grid_action_title_adm_more_actions', 'cog', 1, 0, 1);


-- ALERTS

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_groups', 'BxGroupsAlertsResponse', 'modules/boonex/groups/classes/BxGroupsAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_timeline', 'post_common', @iHandler),
('bx_groups_pics', 'file_deleted', @iHandler),
('bx_groups_fans', 'connection_added', @iHandler),
('bx_groups_fans', 'connection_removed', @iHandler),
('profile', 'delete', @iHandler),
('bx_groups', 'fan_added', @iHandler),
('bx_groups', 'join_invitation', @iHandler),
('bx_groups', 'join_request', @iHandler),
('bx_groups', 'join_request_accepted', @iHandler),
('bx_groups', 'timeline_view', @iHandler),
('bx_groups', 'timeline_post', @iHandler),
('bx_groups', 'timeline_delete', @iHandler),
('bx_groups', 'timeline_comment', @iHandler),
('bx_groups', 'timeline_vote', @iHandler),
('bx_groups', 'timeline_report', @iHandler),
('bx_groups', 'timeline_share', @iHandler);

-- PRIVACY 

INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_allow_view_to', 'bx_groups', 'view', '_bx_groups_form_profile_input_allow_view_to', '3', 'bx_groups_data', 'id', 'author', 'BxGroupsPrivacy', 'modules/boonex/groups/classes/BxGroupsPrivacy.php'),
('bx_groups_allow_view_notification_to', 'bx_groups', 'view_event', '_bx_groups_form_profile_input_allow_view_notification_to', '3', 'bx_notifications_events', 'id', 'object_owner_id', 'BxGroupsPrivacyNotifications', 'modules/boonex/groups/classes/BxGroupsPrivacyNotifications.php');

-- EMAIL TEMPLATES

INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_groups', '_bx_groups_email_join_request', 'bx_groups_join_request', '_bx_groups_email_join_request_subject', '_bx_groups_email_join_request_body'),
('bx_groups', '_bx_groups_email_join_reject', 'bx_groups_join_reject', '_bx_groups_email_join_reject_subject', '_bx_groups_email_join_reject_body'),
('bx_groups', '_bx_groups_email_join_confirm', 'bx_groups_join_confirm', '_bx_groups_email_join_confirm_subject', '_bx_groups_email_join_confirm_body'),
('bx_groups', '_bx_groups_email_fan_remove', 'bx_groups_fan_remove', '_bx_groups_email_fan_remove_subject', '_bx_groups_email_fan_remove_body'),
('bx_groups', '_bx_groups_email_fan_become_admin', 'bx_groups_fan_become_admin', '_bx_groups_email_fan_become_admin_subject', '_bx_groups_email_fan_become_admin_body'),
('bx_groups', '_bx_groups_email_admin_become_fan', 'bx_groups_admin_become_fan', '_bx_groups_email_admin_become_fan_subject', '_bx_groups_email_admin_become_fan_body'),
('bx_groups', '_bx_groups_email_invitation', 'bx_groups_invitation', '_bx_groups_email_invitation_subject', '_bx_groups_email_invitation_body');

-- UPLOADERS

INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_groups_cover_crop', 1, 'BxGroupsUploaderCoverCrop', 'modules/boonex/groups/classes/BxGroupsUploaderCoverCrop.php'),
('bx_groups_picture_crop', 1, 'BxGroupsUploaderPictureCrop', 'modules/boonex/groups/classes/BxGroupsUploaderPictureCrop.php');

