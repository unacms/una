
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
('bx_organizations_default_acl_level', '2', @iCategId, '_bx_orgs_option_default_acl_level', 'select', 'PHP:bx_import(''BxDolAcl''); return BxDolAcl::getInstance()->getMemberships(false, true);', '', '', 2);

-- STORAGES & TRANSCODERS

SET @iTotalPicturesSize = IFNULL((SELECT SUM(`size`) FROM `bx_organizations_pics`), 0);
SET @iTotalPicturesNum = IFNULL((SELECT COUNT(*) FROM `bx_organizations_pics`), 0);

SET @iTotalPicturesResizedSize = IFNULL((SELECT SUM(`size`) FROM `bx_organizations_pics_resized`), 0);
SET @iTotalPicturesResizedNum = IFNULL((SELECT COUNT(*) FROM `bx_organizations_pics_resized`), 0);

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_organizations_pics', 'Local', '', 360, 2592000, 3, 'bx_organizations_pics', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, @iTotalPicturesSize, 0, @iTotalPicturesNum, 0, 0),
('bx_organizations_pics_resized', 'Local', '', 360, 2592000, 3, 'bx_organizations_pics_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, @iTotalPicturesResizedSize, 0, @iTotalPicturesResizedNum, 0, 0);

INSERT INTO `sys_objects_transcoder_images` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_organizations_icon', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_thumb', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_avatar', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_picture', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_cover', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0'),
('bx_organizations_cover_thumb', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_images_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_organizations_icon', 'Resize', 'a:4:{s:1:"w";s:2:"32";s:1:"h";s:2:"32";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('bx_organizations_thumb', 'Resize', 'a:4:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('bx_organizations_avatar', 'Resize', 'a:4:{s:1:"w";s:2:"96";s:1:"h";s:2:"96";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('bx_organizations_picture', 'Resize', 'a:4:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";s:10:"force_type";s:3:"jpg";}', '0'),
('bx_organizations_cover', 'Resize', 'a:4:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('bx_organizations_cover_thumb', 'Resize', 'a:4:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');

-- PAGES

--
-- Dumping data for 'bx_organizations_create_profile' page
--
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_create_profile', 'create-organization-profile', '_bx_orgs_page_title_sys_create_profile', '_bx_orgs_page_title_create_profile', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=create-organization-profile', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_create_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_create_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:13:\"entity_create\";}', 0, 1, 1);

--
-- Dumping data for 'bx_organizations_view_profile' page
--
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_profile', 'view-organization-profile', '_bx_orgs_page_title_sys_view_profile', '_bx_orgs_page_title_view_profile', 'bx_organizations', 10, 2147483647, 1, 'page.php?i=view-organization-profile', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_view_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_actions', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 0),
('bx_organizations_view_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_cover', 3, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:13:\"profile_cover\";}', 0, 0, 1),
('bx_organizations_view_profile', 2, 'bx_organizations', '_bx_orgs_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 0),
('bx_organizations_view_profile', 3, 'bx_organizations', '_bx_orgs_page_block_title_profile_friends', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:15:\"profile_friends\";}', 0, 0, 0);

--
-- Dumping data for 'bx_organizations_edit_profile' page
--
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_edit_profile', 'edit-organization-profile', '_bx_orgs_page_title_sys_edit_profile', '_bx_orgs_page_title_edit_profile', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=edit-organization-profile', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_edit_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_edit_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:11:\"entity_edit\";}', 0, 0, 0);

--
-- Dumping data for 'bx_organizations_edit_profile_cover' page
--
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_edit_profile_cover', 'edit-organization-cover', '_bx_orgs_page_title_sys_edit_profile_cover', '_bx_orgs_page_title_edit_profile_cover', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=edit-organization-cover', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_edit_profile_cover', 1, 'bx_organizations', '_bx_orgs_page_block_title_edit_profile_cover', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:17:\"entity_edit_cover\";}', 0, 0, 0);

--
-- Dumping data for 'bx_organizations_delete_profile' page
--
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_delete_profile', 'delete-organization-profile', '_bx_orgs_page_title_sys_delete_profile', '_bx_orgs_page_title_delete_profile', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=delete-organization-profile', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_delete_profile', 1, 'bx_organizations', '_bx_orgs_page_block_title_delete_profile', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:13:\"entity_delete\";}', 0, 0, 0);

--
-- Dumping data for 'bx_organizations_profile_info' page
--
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_profile_info', 'organization-profile-info', '_bx_orgs_page_title_sys_profile_info', '_bx_orgs_page_title_profile_info', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=organization-profile-info', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_profile_info', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1);

--
-- Dumping data for 'bx_organizations_profile_friends' page
--
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_profile_friends', 'organization-profile-friends', '_bx_orgs_page_title_sys_profile_friends', '_bx_orgs_page_title_profile_friends', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=organization-profile-friends', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_profile_friends', 1, 'bx_organizations', '_bx_orgs_page_block_title_profile_friends', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:17:\"connections_table\";s:5:\"class\";s:23:\"TemplServiceConnections\";}', 0, 0, 1);


-- Homepage
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_home', 0, 'bx_organizations', '_bx_orgs_page_block_title_latest_profiles', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:22:"browse_recent_profiles";}', 0, 1, 0);

-- MENU

--SET @iCreateProfileMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_profiles_create' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
--INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
--('sys_profiles_create', 'bx_organizations', 'create-organization-profile', '_bx_orgs_menu_item_title_system_profile_type', '_bx_orgs_menu_item_title_profile_type', 'page.php?i=create-organization-profile', '', '', 'group', '', 2147483647, 1, 1, IFNULL(@iCreateProfileMenuOrder, 0) + 1);

SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_profile_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_profile_links', 'bx_organizations', 'create-organization-profile', '_bx_orgs_menu_item_title_system_create_profile', '_bx_orgs_menu_item_title_create_profile', 'page.php?i=create-organization-profile', '', '', 'group', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);


--
-- Dumping data for 'bx_organizations_view_actions' menu
--
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_actions', '_bx_orgs_menu_title_view_profile_actions', 'bx_organizations_view_actions', 'bx_organizations', 9, 0, 1, 'BxOrgsMenuView', 'modules/boonex/organizations/classes/BxOrgsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_view_actions', 'bx_organizations', '_bx_orgs_menu_set_title_view_profile_actions', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions', 'bx_organizations', 'profile-friend-add', '_bx_orgs_menu_item_title_system_befriend', '{title_add_friend}', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_friends\', \'add\', \'{profile_id}\')', '', 'plus', '', 2147483647, 1, 0, 10),
('bx_organizations_view_actions', 'bx_organizations', 'profile-subscribe-add', '_bx_orgs_menu_item_title_system_subscribe', '_bx_orgs_menu_item_title_subscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'add\', \'{profile_id}\')', '', 'check', '', 2147483647, 1, 0, 20),
('bx_organizations_view_actions', 'bx_organizations', 'profile-actions-more', '_bx_orgs_menu_item_title_system_more_actions', '_bx_orgs_menu_item_title_more_actions', 'javascript:void(0)', 'bx_menu_popup(''bx_organizations_view_actions_more'', this, {}, {profile_id:{profile_id}});', '', 'cog', 'bx_organizations_view_actions_more', 2147483647, 1, 0, 30);

--
-- Dumping data for 'bx_organizations_view_actions_more' menu
--
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_actions_more', '_bx_orgs_menu_title_view_profile_actions_more', 'bx_organizations_view_actions_more', 'bx_organizations', 6, 0, 1, 'BxOrgsMenuView', 'modules/boonex/organizations/classes/BxOrgsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_view_actions_more', 'bx_organizations', '_bx_orgs_menu_set_title_view_profile_actions_more', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_more', 'bx_organizations', 'profile-friend-remove', '_bx_orgs_menu_item_title_system_unfriend', '{title_remove_friend}', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_friends\', \'remove\', \'{profile_id}\')', '', 'minus', '', 2147483647, 1, 0, 10),
('bx_organizations_view_actions_more', 'bx_organizations', 'profile-subscribe-remove', '_bx_orgs_menu_item_title_system_unsubscribe', '_bx_orgs_menu_item_title_unsubscribe', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_subscriptions\', \'remove\', \'{profile_id}\')', '', 'check', '', 2147483647, 1, 0, 20),
('bx_organizations_view_actions_more', 'bx_organizations', 'edit-organization-profile', '_bx_orgs_menu_item_title_system_edit_profile', '_bx_orgs_menu_item_title_edit_profile', 'page.php?i=edit-organization-profile&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 30),
('bx_organizations_view_actions_more', 'bx_organizations', 'delete-organization-profile', '_bx_orgs_menu_item_title_system_delete_profile', '_bx_orgs_menu_item_title_delete_profile', 'page.php?i=delete-organization-profile&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 40);


--
-- Dumping data for 'bx_organizations_view_submenu' menu
--
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_submenu', '_bx_orgs_menu_title_view_profile_submenu', 'bx_organizations_view_submenu', 'bx_organizations', 8, 0, 1, 'BxOrgsMenuView', 'modules/boonex/organizations/classes/BxOrgsMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_view_submenu', 'bx_organizations', '_bx_orgs_menu_set_title_view_profile_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_submenu', 'bx_organizations', 'organization-profile-info', '_bx_orgs_menu_item_title_system_view_profile_info', '_bx_orgs_menu_item_title_view_profile_info', 'page.php?i=organization-profile-info&id={content_id}', '', '', '', '', 2147483647, 1, 0, 0),
('bx_organizations_view_submenu', 'bx_organizations', 'organization-profile-friends', '_bx_orgs_menu_item_title_system_view_profile_friends', '_bx_orgs_menu_item_title_view_profile_friends', 'page.php?i=organization-profile-friends&profile_id={profile_id}', '', '', '', '', 2147483647, 1, 0, 1);


-- notifications menu in account popup
SET @iNotifMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'bx_organizations', 'notifications-friend-requests', '_bx_orgs_menu_item_title_system_friends', '_bx_orgs_menu_item_title_friends', 'page.php?i=organization-profile-friends&profile_id={member_id}', '', '', 'group col-blue3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:31:"get_unconfirmed_connections_num";s:6:"params";a:1:{i:0;s:20:"sys_profiles_friends";}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, 1, 0, IFNULL(@iAddMenuOrder, 0) + 1);


-- profile stats
SET @iNotifMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_organizations', 'profile-stats-friend-requests', '_bx_orgs_menu_item_title_system_friend_requests', '_bx_orgs_menu_item_title_friend_requests', 'page.php?i=organization-profile-friends&profile_id={member_id}', '', '', 'group col-blue3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:31:"get_unconfirmed_connections_num";s:6:"params";a:1:{i:0;s:20:"sys_profiles_friends";}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, 1, 0, IFNULL(@iAddMenuOrder, 0) + 1);


-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'create entry', NULL, '_bx_orgs_acl_action_create_profile', '', 1, 1);
SET @iIdActionProfileCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'delete entry', NULL, '_bx_orgs_acl_action_delete_profile', '', 1, 1);
SET @iIdActionProfileDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'view entry', NULL, '_bx_orgs_acl_action_view_profile', '', 1, 1);
SET @iIdActionProfileView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_organizations', 'edit any entry', NULL, '_bx_orgs_acl_action_edit_any_profile', '', 1, 1);
SET @iIdActionProfileEditAny = LAST_INSERT_ID();


SET @iUnauthenticated = 1;
SET @iStandard = 2;
SET @iUnconfirmed = 3;
SET @iPending = 4;
SET @iSuspended = 5;
SET @iModerator = 6;
SET @iAdministrator = 7;
SET @iPremium = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- profile create
(@iStandard, @iIdActionProfileCreate),
(@iUnconfirmed, @iIdActionProfileCreate),
(@iPending, @iIdActionProfileCreate),
(@iModerator, @iIdActionProfileCreate),
(@iAdministrator, @iIdActionProfileCreate),
(@iPremium, @iIdActionProfileCreate),

-- profile delete
(@iStandard, @iIdActionProfileDelete),
(@iUnconfirmed, @iIdActionProfileDelete),
(@iPending, @iIdActionProfileDelete),
(@iModerator, @iIdActionProfileDelete),
(@iAdministrator, @iIdActionProfileDelete),
(@iPremium, @iIdActionProfileDelete),

-- profile view
(@iUnauthenticated, @iIdActionProfileView),
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
