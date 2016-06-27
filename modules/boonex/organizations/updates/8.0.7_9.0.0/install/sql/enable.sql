-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='7' WHERE `object`='bx_organizations_view_profile';

UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_organizations_view_profile' AND `title`='_bx_orgs_page_block_title_profile_info';
UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='1' WHERE `object`='bx_organizations_view_profile' AND `title`='_bx_orgs_page_block_title_profile_friends';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_view_profile' AND `title` IN ('_bx_orgs_page_block_title_profile_actions', '_bx_orgs_page_block_title_profile_cover', '_bx_orgs_page_block_title_profile_description', '_bx_orgs_page_block_title_profile_membership');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_view_profile', 2, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_description', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 0),
('bx_organizations_view_profile', 3, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_membership', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:18:\"profile_membership\";}', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_view_profile_closed';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_view_profile_closed', 'view-organization-profile-closed', '_bx_orgs_page_title_sys_view_profile_closed', '_bx_orgs_page_title_view_profile', 'bx_organizations', 10, 2147483647, 1, 'page.php?i=view-organization-profile', '', '', '', 0, 1, 0, 'BxOrgsPageEntry', 'modules/boonex/organizations/classes/BxOrgsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_view_profile_closed' AND `title` IN ('_bx_orgs_page_block_title_profile_private');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_view_profile_closed', 1, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_private', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:19:\"private_profile_msg\";}', 0, 1, 1, 0);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_profile_info' AND `title` IN ('_bx_orgs_page_block_title_profile_cover', '_bx_orgs_page_block_title_profile_description');
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:16:\"entity_info_full\";}' WHERE `object`='bx_organizations_profile_info' AND `title`='_bx_orgs_page_block_title_profile_info_link';

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_organizations_profile_info', 1, 'bx_organizations', '', '_bx_orgs_page_block_title_profile_description', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 2);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_profile_friends' AND `title` IN ('_bx_orgs_page_block_title_profile_cover');

DELETE FROM `sys_objects_page` WHERE `object`='bx_organizations_online';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_online', '_bx_orgs_page_title_sys_online', '_bx_orgs_page_title_online', 'bx_organizations', 5, 2147483647, 1, 'organizations-online', 'page.php?i=organizations-online', '', '', '', 0, 1, 0, 'BxOrgsPageBrowse', 'modules/boonex/organizations/classes/BxOrgsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_organizations_online' AND `title` IN ('_bx_orgs_page_block_title_online_profiles');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_organizations_online', 1, 'bx_organizations', '_bx_orgs_page_block_title_online_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:16:\"bx_organizations\";s:6:\"method\";s:22:\"browse_online_profiles\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 0);

UPDATE `sys_pages_blocks` SET `content`='a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"categories_list";s:6:"params";a:2:{i:0;s:21:"bx_organizations_cats";i:1;a:2:{s:10:\"show_empty\";b:1;s:21:\"show_empty_categories\";b:1;}}s:5:"class";s:20:"TemplServiceCategory";}' WHERE `module`='bx_organizations' AND `title`='_bx_orgs_page_block_title_categories';


-- MENU
UPDATE `sys_menu_items` SET `icon`='briefcase col-red2' WHERE `set_name`='sys_add_profile_links' AND `name`='create-organization-profile';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_organizations_view_actions_popup';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='edit-organization-cover';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_view_actions_more', 'bx_organizations', 'edit-organization-cover', '_bx_orgs_menu_item_title_system_edit_cover', '_bx_orgs_menu_item_title_edit_cover', 'page.php?i=edit-organization-cover&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 25);

UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object`='bx_organizations_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_organizations_submenu' AND `name`='organizations-online';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_organizations_submenu', 'bx_organizations', 'organizations-online', '_bx_orgs_menu_item_title_system_entries_online', '_bx_orgs_menu_item_title_entries_online', 'page.php?i=organizations-online', '', '', '', '', 2147483647, 1, 1, 3);

UPDATE `sys_menu_items` SET `order`='4' WHERE `set_name`='bx_organizations_submenu' AND `name`='organizations-manage';

UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object`='bx_organizations_view_submenu';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_organizations' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_organizations', 'BxOrgsAlertsResponse', 'modules/boonex/organizations/classes/BxOrgsAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_timeline', 'post_common', @iHandler),
('bx_organizations_pics', 'file_deleted', @iHandler),
('bx_organizations', 'timeline_view', @iHandler),
('bx_organizations', 'timeline_post', @iHandler),
('bx_organizations', 'timeline_delete', @iHandler),
('bx_organizations', 'timeline_comment', @iHandler),
('bx_organizations', 'timeline_vote', @iHandler),
('bx_organizations', 'timeline_report', @iHandler),
('bx_organizations', 'timeline_share', @iHandler);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object` IN('bx_organizations_allow_view_to');
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_allow_view_to', 'bx_organizations', 'view', '_bx_orgs_form_profile_input_allow_view_to', '3', 'bx_organizations_data', 'id', 'author', 'BxOrgsPrivacy', 'modules/boonex/organizations/classes/BxOrgsPrivacy.php');


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_organizations_cover_crop', 'bx_organizations_picture_crop');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_organizations_cover_crop', 1, 'BxOrgsUploaderCoverCrop', 'modules/boonex/organizations/classes/BxOrgsUploaderCoverCrop.php'),
('bx_organizations_picture_crop', 1, 'BxOrgsUploaderPictureCrop', 'modules/boonex/organizations/classes/BxOrgsUploaderPictureCrop.php');