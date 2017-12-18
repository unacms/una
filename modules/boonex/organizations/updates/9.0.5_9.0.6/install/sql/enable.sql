-- PAGES
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_organizations_view_profile', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_subscriptions', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:21:\"profile_subscriptions\";}', 0, 1, 0, 0),
('bx_organizations_view_profile', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_subscribed_me', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:21:\"profile_subscribed_me\";}', 0, 1, 0, 0),
('bx_organizations_view_profile', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_fans', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:4:\"fans\";}', 0, 0, 1, 2),
('bx_organizations_view_profile', 3, 'bx_organizations', '', '_bx_orgs_page_block_title_admins', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:6:\"admins\";}', 0, 0, 1, 3);

UPDATE `sys_pages_blocks` SET `order`='1' WHERE `object`='bx_organizations_view_profile' AND `title`='_bx_orgs_page_block_title_profile_description';
UPDATE `sys_pages_blocks` SET `order`='3' WHERE `object`='bx_organizations_view_profile' AND `title`='_bx_orgs_page_block_title_profile_friends';
UPDATE `sys_pages_blocks` SET `order`='4' WHERE `object`='bx_organizations_view_profile' AND `title`='_bx_orgs_page_block_title_profile_location';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_view_profile_closed' AND `title` IN ('_bx_orgs_page_block_title_profile_private', '_bx_orgs_page_block_title_profile_info', '_bx_orgs_page_block_title_fans');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_view_profile_closed', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 0),
('bx_organizations_view_profile_closed', 3, 'bx_organizations', '', '_bx_orgs_page_block_title_fans', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:4:\"fans\";}', 0, 0, 1, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_invite';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_invite', 'invite-to-organization', '_bx_orgs_page_title_sys_invite_to_organization', '_bx_orgs_page_title_invite_to_organization', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=invite-to-organization', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_invite';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_invite', 1, 'bx_organizations', '_bx_orgs_page_block_title_invite_to_organization', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:13:\"entity_invite\";}', 0, 0, 0);

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_fans';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_fans', 'organization-profile-fans', '_bx_orgs_page_title_sys_fans', '_bx_orgs_page_title_fans', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=organization-profile-fans', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_fans';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_fans', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_fans', '_bx_orgs_page_block_title_fans_link', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:10:"fans_table";}', 0, 0, 1, 1);

UPDATE `sys_pages_blocks` SET `copyable`='0' WHERE `object`='bx_organizations_profile_subscriptions' AND `title` IN ('_bx_orgs_page_block_title_profile_subscriptions', '_bx_orgs_page_block_title_profile_subscribed_me');

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_joined';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_joined', 'joined-organizations', '_bx_orgs_page_title_sys_joined_entries', '_bx_orgs_page_title_joined_entries', 'bx_organizations', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxOrgsPageJoinedEntries', 'modules/boonex/organizations/classes/BxOrgsPageJoinedEntries.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_joined';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_organizations_joined', 1, 'bx_organizations', '_bx_orgs_page_block_title_sys_entries_actions', '_bx_orgs_page_block_title_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:18:"my_entries_actions";}', 0, 0, 1, 1),
('bx_organizations_joined', 1, 'bx_organizations', '_bx_orgs_page_block_title_sys_favorites_of_author', '_bx_orgs_page_block_title_favorites_of_author', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:15:"browse_favorite";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 1, 1),
('bx_organizations_joined', 1, 'bx_organizations', '_bx_orgs_page_block_title_sys_joined_entries', '_bx_orgs_page_block_title_joined_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:21:"browse_joined_entries";s:6:"params";a:2:{i:0;i:0;i:1;b:1;}}', 0, 0, 1, 2);


SET @iPBCellProfile = 2;
DELETE FROM `sys_pages_blocks` WHERE (`object`='trigger_page_profile_view_entry' AND `module`='bx_organizations') OR (`object` LIKE '%view_profile' AND `title`='_bx_orgs_page_block_title_joined_entries');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_organizations', '_bx_orgs_page_block_title_sys_joined_entries', '_bx_orgs_page_block_title_joined_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:21:"browse_joined_entries";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;b:0;}}', 0, 0, 1, 0);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions' AND `name`='profile-fan-add';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_organizations_view_actions', 'bx_organizations', 'profile-fan-add', '_bx_orgs_menu_item_title_system_become_fan', '{title_add_fan}', 'javascript:void(0)', 'bx_conn_action(this, \'bx_organizations_fans\', \'add\', \'{profile_id}\')', '', 'user-plus', '', 0, 2147483647, 1, 0, 0, 5);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_more' AND `name` IN ('profile-fan-remove', 'invite-to-organization');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_organizations_view_actions_more', 'bx_organizations', 'profile-fan-remove', '_bx_orgs_menu_item_title_system_leave_organization', '{title_remove_fan}', 'javascript:void(0)', 'bx_conn_action(this, \'bx_organizations_fans\', \'remove\', \'{profile_id}\')', '', 'user-times', '', 2147483647, 1, 0, 10),
('bx_organizations_view_actions_more', 'bx_organizations', 'invite-to-organization', '_bx_orgs_menu_item_title_system_invite', '_bx_orgs_menu_item_title_invite', 'page.php?i=invite-to-organization&id={content_id}', '', '', 'user-plus', '', 2147483647, 1, 0, 32);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_organizations_my';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_my', '_bx_orgs_menu_title_entries_my', 'bx_organizations_my', 'bx_organizations', 9, 0, 1, 'BxOrgsMenu', 'modules/boonex/organizations/classes/BxOrgsMenu.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_organizations_my';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_my', 'bx_organizations', '_bx_orgs_menu_set_title_entries_my', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_my';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_my', 'bx_organizations', 'create-organization-profile', '_bx_orgs_menu_item_title_system_new_profile', '_bx_orgs_menu_item_title_new_profile', 'page.php?i=create-organization-profile', '', '', 'plus', '', 2147483647, 1, 0, 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_submenu' AND `name`='organization-profile-fans';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_submenu', 'bx_organizations', 'organization-profile-fans', '_bx_orgs_menu_item_title_system_view_profile_fans', '_bx_orgs_menu_item_title_view_profile_fans', 'page.php?i=organization-profile-fans&profile_id={profile_id}', '', '', 'group col-blue3', '', 2147483647, 1, 0, 3);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_organizations_snippet_meta';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_snippet_meta', '_sys_menu_title_snippet_meta', 'bx_organizations_snippet_meta', 'bx_organizations', 15, 0, 1, 'BxOrgsMenuSnippetMeta', 'modules/boonex/organizations/classes/BxOrgsMenuSnippetMeta.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_organizations_snippet_meta';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_organizations_snippet_meta', 'bx_organizations', '_sys_menu_set_title_snippet_meta', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_snippet_meta';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_organizations_snippet_meta', 'bx_organizations', 'join', '_sys_menu_item_title_system_sm_join', '_sys_menu_item_title_sm_join', '', '', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_organizations_snippet_meta', 'bx_organizations', 'leave', '_sys_menu_item_title_system_sm_leave', '_sys_menu_item_title_sm_leave', '', '', '', '', '', 2147483647, 0, 0, 1, 2),
('bx_organizations_snippet_meta', 'bx_organizations', 'befriend', '_sys_menu_item_title_system_sm_befriend', '_sys_menu_item_title_sm_befriend', '', '', '', '', '', 2147483647, 0, 0, 1, 3),
('bx_organizations_snippet_meta', 'bx_organizations', 'unfriend', '_sys_menu_item_title_system_sm_unfriend', '_sys_menu_item_title_sm_unfriend', '', '', '', '', '', 2147483647, 0, 0, 1, 4),
('bx_organizations_snippet_meta', 'bx_organizations', 'subscribe', '_sys_menu_item_title_system_sm_subscribe', '_sys_menu_item_title_sm_subscribe', '', '', '', '', '', 2147483647, 0, 0, 1, 5),
('bx_organizations_snippet_meta', 'bx_organizations', 'unsubscribe', '_sys_menu_item_title_system_sm_unsubscribe', '_sys_menu_item_title_sm_unsubscribe', '', '', '', '', '', 2147483647, 0, 0, 1, 6),
('bx_organizations_snippet_meta', 'bx_organizations', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', 2147483647, 0, 0, 1, 7),
('bx_organizations_snippet_meta', 'bx_organizations', 'tags', '_sys_menu_item_title_system_sm_tags', '_sys_menu_item_title_sm_tags', '', '', '', '', '', 2147483647, 0, 0, 1, 8),
('bx_organizations_snippet_meta', 'bx_organizations', 'views', '_sys_menu_item_title_system_sm_views', '_sys_menu_item_title_sm_views', '', '', '', '', '', 2147483647, 0, 0, 1, 9),
('bx_organizations_snippet_meta', 'bx_organizations', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', '', '', 2147483647, 0, 0, 1, 10),
('bx_organizations_snippet_meta', 'bx_organizations', 'members', '_sys_menu_item_title_system_sm_members', '_sys_menu_item_title_sm_members', '', '', '', '', '', 2147483647, 1, 0, 1, 11),
('bx_organizations_snippet_meta', 'bx_organizations', 'friends', '_sys_menu_item_title_system_sm_friends', '_sys_menu_item_title_sm_friends', '', '', '', '', '', 2147483647, 0, 0, 1, 12),
('bx_organizations_snippet_meta', 'bx_organizations', 'subscribers', '_sys_menu_item_title_system_sm_subscribers', '_sys_menu_item_title_sm_subscribers', '', '', '', '', '', 2147483647, 0, 0, 1, 13);


DELETE FROM `sys_menu_items` WHERE `module`='bx_organizations' AND `name`='joined-organizations';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_submenu', 'bx_organizations', 'joined-organizations', '_bx_orgs_menu_item_title_system_view_joined_groups', '_bx_orgs_menu_item_title_view_joined_groups', 'page.php?i=joined-organizations&profile_id={profile_id}', '', '', 'briefcase col-red2', '', 2147483647, 1, 0, 0);


-- SEARCH
DELETE FROM `sys_objects_search` WHERE `ObjectName`='bx_organizations_cmts';
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
('bx_organizations_cmts', '_bx_orgs_cmts', @iSearchOrder + 1, 'BxOrgsCmtsSearchResult', 'modules/boonex/organizations/classes/BxOrgsCmtsSearchResult.php');


-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object`='bx_organizations_fans';
INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_fans', 'bx_organizations_fans', 'mutual', '', '');


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_organizations_fans';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_fans', 'Sql', 'SELECT `p`.`id`, `c`.`added`, `c`.`mutual` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 'BxOrgsGridConnections', 'modules/boonex/organizations/classes/BxOrgsGridConnections.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_organizations_fans';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_organizations_fans', 'name', '_sys_name', '50%', '', 10),
('bx_organizations_fans', 'actions', '', '50%', '', 20);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_organizations_fans';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_organizations_fans', 'single', 'accept', '_sys_accept', '', 0, 10),
('bx_organizations_fans', 'single', 'to_admins', '_bx_orgs_txt_to_admins', '', 0, 20),
('bx_organizations_fans', 'single', 'from_admins', '_bx_orgs_txt_from_admins', '', 0, 30),
('bx_organizations_fans', 'single', 'delete', '', 'remove', 1, 40);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_organizations' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `handler_id`=@iHandler AND ((`unit`='account' AND `action`='check_switch_context') OR (`unit`='bx_organizations_fans' AND `action` IN ('connection_added', 'connection_removed')) OR (`unit`='bx_organizations' AND `action` IN ('fan_added', 'join_invitation', 'join_request', 'join_request_accepted')));
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('account', 'check_switch_context', @iHandler),
('bx_organizations_fans', 'connection_added', @iHandler),
('bx_organizations_fans', 'connection_removed', @iHandler),
('bx_organizations', 'fan_added', @iHandler),
('bx_organizations', 'join_invitation', @iHandler),
('bx_organizations', 'join_request', @iHandler),
('bx_organizations', 'join_request_accepted', @iHandler);


-- PRIVACY
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_organizations_allow_view_notification_to';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_allow_view_notification_to', 'bx_organizations', 'view_event', '_bx_orgs_form_profile_input_allow_view_notification_to', '3', 'bx_notifications_events', 'id', 'object_owner_id', 'BxOrgsPrivacyNotifications', 'modules/boonex/organizations/classes/BxOrgsPrivacyNotifications.php');


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name` IN ('bx_organizations_join_request', 'bx_organizations_join_reject', 'bx_organizations_join_confirm', 'bx_organizations_fan_remove', 'bx_organizations_fan_become_admin', 'bx_organizations_admin_become_fan', 'bx_organizations_invitation');
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_organizations', '_bx_orgs_email_join_request', 'bx_organizations_join_request', '_bx_orgs_email_join_request_subject', '_bx_orgs_email_join_request_body'),
('bx_organizations', '_bx_orgs_email_join_reject', 'bx_organizations_join_reject', '_bx_orgs_email_join_reject_subject', '_bx_orgs_email_join_reject_body'),
('bx_organizations', '_bx_orgs_email_join_confirm', 'bx_organizations_join_confirm', '_bx_orgs_email_join_confirm_subject', '_bx_orgs_email_join_confirm_body'),
('bx_organizations', '_bx_orgs_email_fan_remove', 'bx_organizations_fan_remove', '_bx_orgs_email_fan_remove_subject', '_bx_orgs_email_fan_remove_body'),
('bx_organizations', '_bx_orgs_email_fan_become_admin', 'bx_organizations_fan_become_admin', '_bx_orgs_email_fan_become_admin_subject', '_bx_orgs_email_fan_become_admin_body'),
('bx_organizations', '_bx_orgs_email_admin_become_fan', 'bx_organizations_admin_become_fan', '_bx_orgs_email_admin_become_fan_subject', '_bx_orgs_email_admin_become_fan_body'),
('bx_organizations', '_bx_orgs_email_invitation', 'bx_organizations_invitation', '_bx_orgs_email_invitation_subject', '_bx_orgs_email_invitation_body');
