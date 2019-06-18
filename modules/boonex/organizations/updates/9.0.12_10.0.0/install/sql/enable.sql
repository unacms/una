-- SETTINGS
DELETE FROM `sys_options` WHERE `name`='bx_organizations_labels';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_view_profile' AND `title` IN ('_bx_orgs_page_block_title_profile_related_me', '_bx_orgs_page_block_title_profile_relations');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_organizations_view_profile', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_related_me', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:18:"profile_related_me";}', 0, 1, 0, 0),
('bx_organizations_view_profile', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_relations', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:17:"profile_relations";}', 0, 1, 0, 0);


DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_profile_relations';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_profile_relations', 'organization-profile-relations', '_bx_orgs_page_title_sys_profile_relations', '_bx_orgs_page_title_profile_relations', 'bx_organizations', 5, 2147483647, 1, 'page.php?i=organization-profile-relations', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_profile_relations';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_profile_relations', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_profile_relations', '_bx_orgs_page_block_title_profile_relations', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:15:"relations_table";s:5:"class";s:23:"TemplServiceConnections";}', 0, 0, 1, 1),
('bx_organizations_profile_relations', 1, 'bx_organizations', '_bx_orgs_page_block_title_system_profile_related_me', '_bx_orgs_page_block_title_profile_related_me', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:16:"related_me_table";s:5:"class";s:23:"TemplServiceConnections";}', 0, 0, 1, 2);


-- MENUS
UPDATE `sys_objects_menu` SET `override_class_name`='BxOrgsMenuViewActions', `override_class_file`='modules/boonex/organizations/classes/BxOrgsMenuViewActions.php' WHERE `object`='bx_organizations_view_actions';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions' AND `name`='profile-relation-add';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_organizations_view_actions', 'bx_organizations', 'profile-relation-add', '_bx_orgs_menu_item_title_system_relation_add', '_bx_orgs_menu_item_title_relation_add', 'javascript:void(0)', 'bx_menu_popup(''sys_add_relation'', window, {}, {profile_id: {profile_id}});', '', 'sync', '', '', 0, 2147483647, '', 1, 0, 1, 15);

UPDATE `sys_objects_menu` SET `override_class_name`='BxOrgsMenuViewActions', `override_class_file`='modules/boonex/organizations/classes/BxOrgsMenuViewActions.php' WHERE `object`='bx_organizations_view_actions_more';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='profile-relation-remove';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_organizations_view_actions_more', 'bx_organizations', 'profile-relation-remove', '_bx_orgs_menu_item_title_system_relation_delete', '_bx_orgs_menu_item_title_relation_delete', 'javascript:void(0)', 'bx_conn_action(this, \'sys_profiles_relations\', \'remove\', \'{profile_id}\')', '', 'sync', '', '', 2147483647, '', 1, 0, 15);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_all' AND `name` IN ('profile-relation-add', 'profile-relation-remove', 'social-sharing-googleplus');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-relation-add', '_bx_orgs_menu_item_title_system_relation_add', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 43),
('bx_organizations_view_actions_all', 'bx_organizations', 'profile-relation-remove', '_bx_orgs_menu_item_title_system_relation_delete', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 47);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_submenu' AND `name` IN ('organization-profile-relations', 'more-auto');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_submenu', 'bx_organizations', 'organization-profile-relations', '_bx_orgs_menu_item_title_system_view_profile_relations', '_bx_orgs_menu_item_title_view_profile_relations', 'page.php?i=organization-profile-relations&profile_id={profile_id}', '', '', 'sync col-blue3', '', '', 0, 2147483647, '', 1, 0, 5),
('bx_organizations_view_submenu', 'bx_organizations', 'more-auto', '_bx_orgs_menu_item_title_system_view_profile_more_auto', '_bx_orgs_menu_item_title_view_profile_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, '', 1, 0, 9999);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='nl';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_organizations_snippet_meta', 'bx_organizations', 'nl', '_sys_menu_item_title_system_sm_nl', '_sys_menu_item_title_sm_nl', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 9);

UPDATE `sys_menu_items` SET `order`='1' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='date';
UPDATE `sys_menu_items` SET `order`='2' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='tags';
UPDATE `sys_menu_items` SET `order`='3' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='views';
UPDATE `sys_menu_items` SET `order`='4' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='comments';
UPDATE `sys_menu_items` SET `order`='5' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='members';
UPDATE `sys_menu_items` SET `order`='6' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='friends';
UPDATE `sys_menu_items` SET `order`='7' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='subscribers';
UPDATE `sys_menu_items` SET `order`='8' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='membership';
UPDATE `sys_menu_items` SET `order`='10' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='join';
UPDATE `sys_menu_items` SET `order`='11' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='leave';
UPDATE `sys_menu_items` SET `order`='12' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='befriend';
UPDATE `sys_menu_items` SET `order`='13' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='unfriend';
UPDATE `sys_menu_items` SET `order`='14' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='subscribe';
UPDATE `sys_menu_items` SET `order`='15' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='unsubscribe';

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_notifications' AND `module`='bx_organizations' AND `name`='notifications-relation-requests';
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `active` = 1 AND `order` < 9999 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'bx_organizations', 'notifications-relation-requests', '_bx_orgs_menu_item_title_system_relations', '_bx_orgs_menu_item_title_relations', 'page.php?i=organization-profile-relations&profile_id={member_id}', '', '', 'sync col-blue3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:31:"get_unconfirmed_connections_num";s:6:"params";a:1:{i:0;s:22:"sys_profiles_relations";}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, '', 1, 0, @iNotifMenuOrder + 1);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_profile_stats' AND `module`='bx_organizations' AND `name` IN ('profile-stats-relations', 'profile-stats-related-me');
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_organizations', 'profile-stats-relations', '_bx_orgs_menu_item_title_system_relations', '_bx_orgs_menu_item_title_relations', 'page.php?i=organization-profile-relations&profile_id={member_id}#relations', '', '_self', 'sync col-blue3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:25:"get_connected_content_num";s:6:"params";a:3:{i:0;s:22:"sys_profiles_relations";i:1;i:0;i:2;i:1;}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, '', 1, 0, @iNotifMenuOrder + 6),
('sys_profile_stats', 'bx_organizations', 'profile-stats-related-me', '_bx_orgs_menu_item_title_system_related_me', '_bx_orgs_menu_item_title_related_me', 'page.php?i=organization-profile-relations&profile_id={member_id}#related-me', '', '_self', 'sync col-blue3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:28:"get_connected_initiators_num";s:6:"params";a:3:{i:0;s:22:"sys_profiles_relations";i:1;i:0;i:2;i:1;}s:5:"class";s:23:"TemplServiceConnections";}', '', 2147483646, '', 1, 0, @iNotifMenuOrder + 7);

UPDATE `sys_menu_items` SET `icon`='briefcase' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='organizations-administration' AND `icon`='';


-- GRIDS:
UPDATE `sys_objects_grid` SET `filter_fields`='org_name,email' WHERE `object`='bx_organizations_administration';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_organizations' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='bx_organizations' AND `action` IN ('timeline_score', 'timeline_pin', 'timeline_promote') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_organizations', 'timeline_score', @iHandler),
('bx_organizations', 'timeline_pin', @iHandler),
('bx_organizations', 'timeline_promote', @iHandler);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_organizations_allow_post_to';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_allow_post_to', 'bx_organizations', 'post', '_bx_orgs_form_profile_input_allow_post_to', '3', '', 'bx_organizations_data', 'id', 'author', 'BxOrgsPrivacyPost', 'modules/boonex/organizations/classes/BxOrgsPrivacyPost.php');
