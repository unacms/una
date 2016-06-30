-- PAGES
UPDATE `sys_objects_page` SET `layout_id`='7' WHERE `object`='bx_persons_view_profile';

UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_persons_view_profile' AND `title`='_bx_persons_page_block_title_profile_info';
UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='1' WHERE `object`='bx_persons_view_profile' AND `title`='_bx_persons_page_block_title_profile_friends';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `title` IN ('_bx_persons_page_block_title_profile_actions', '_bx_persons_page_block_title_profile_cover', '_bx_persons_page_block_title_profile_description', '_bx_persons_page_block_title_profile_membership');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_persons_view_profile', 2, 'bx_persons', '', '_bx_persons_page_block_title_profile_description', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 0),
('bx_persons_view_profile', 3, 'bx_persons', '', '_bx_persons_page_block_title_profile_membership', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:18:\"profile_membership\";}', 0, 0, 1, 1);

DELETE FROM `sys_objects_page` WHERE `object`='bx_persons_view_profile_closed';
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_view_profile_closed', 'view-person-profile-closed', '_bx_persons_page_title_sys_view_profile_closed', '_bx_persons_page_title_view_profile', 'bx_persons', 10, 2147483647, 1, 'page.php?i=view-person-profile', '', '', '', 0, 1, 0, 'BxPersonsPageEntry', 'modules/boonex/persons/classes/BxPersonsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile_closed' AND `title` IN ('_bx_persons_page_block_title_profile_private');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_persons_view_profile_closed', 1, 'bx_persons', '', '_bx_persons_page_block_title_profile_private', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:19:\"private_profile_msg\";}', 0, 1, 1, 0);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_profile_info' AND `title` IN ('_bx_persons_page_block_title_profile_cover', '_bx_persons_page_block_title_profile_description');
UPDATE `sys_pages_blocks` SET `content`='a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:16:\"entity_info_full\";}' WHERE `object`='bx_persons_profile_info' AND `title`='_bx_persons_page_block_title_profile_info_link';

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_persons_profile_info', 1, 'bx_persons', '', '_bx_persons_page_block_title_profile_description', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 2);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_profile_friends' AND `title` IN ('_bx_persons_page_block_title_profile_cover');

DELETE FROM `sys_objects_page` WHERE `object`='bx_persons_online';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_online', '_bx_persons_page_title_sys_online', '_bx_persons_page_title_online', 'bx_persons', 5, 2147483647, 1, 'persons-online', 'page.php?i=persons-online', '', '', '', 0, 1, 0, 'BxPersonsPageBrowse', 'modules/boonex/persons/classes/BxPersonsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_persons_online' AND `title` IN ('_bx_persons_page_block_title_online_profiles');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_persons_online', 1, 'bx_persons', '_bx_persons_page_block_title_online_profiles', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_persons\";s:6:\"method\";s:22:\"browse_online_profiles\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 0);


-- MENU
UPDATE `sys_menu_items` SET `icon`='user col-blue3' WHERE `set_name`='sys_add_profile_links' AND `name`='create-persons-profile';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_persons_view_actions_popup';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_view_actions_more' AND `name`='edit-persons-cover';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_persons_view_actions_more', 'bx_persons', 'edit-persons-cover', '_bx_persons_menu_item_title_system_edit_cover', '_bx_persons_menu_item_title_edit_cover', 'page.php?i=edit-persons-cover&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 25);

UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object`='bx_persons_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_submenu' AND `name`='persons-online';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_persons_submenu', 'bx_persons', 'persons-online', '_bx_persons_menu_item_title_system_entries_online', '_bx_persons_menu_item_title_entries_online', 'page.php?i=persons-online', '', '', '', '', 2147483647, 1, 1, 3);

UPDATE `sys_menu_items` SET `order`='4' WHERE `set_name`='bx_persons_submenu' AND `name`='persons-manage';

UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object`='bx_persons_view_submenu';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_persons' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_persons', 'BxPersonsAlertsResponse', 'modules/boonex/persons/classes/BxPersonsAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_timeline', 'post_common', @iHandler),
('bx_persons_pictures', 'file_deleted', @iHandler),
('bx_persons', 'timeline_view', @iHandler),
('bx_persons', 'timeline_post', @iHandler),
('bx_persons', 'timeline_delete', @iHandler),
('bx_persons', 'timeline_comment', @iHandler),
('bx_persons', 'timeline_vote', @iHandler),
('bx_persons', 'timeline_report', @iHandler),
('bx_persons', 'timeline_share', @iHandler);


-- PRIVACY
DELETE FROM `sys_objects_privacy` WHERE `object` IN('bx_persons_allow_view_to');
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_persons_allow_view_to', 'bx_persons', 'view', '_bx_persons_form_profile_input_allow_view_to', '3', 'bx_persons_data', 'id', 'author', 'BxPersonsPrivacy', 'modules/boonex/persons/classes/BxPersonsPrivacy.php');


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object` IN('bx_persons_cover_crop', 'bx_persons_picture_crop');
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_persons_cover_crop', 1, 'BxPersonsUploaderCoverCrop', 'modules/boonex/persons/classes/BxPersonsUploaderCoverCrop.php'),
('bx_persons_picture_crop', 1, 'BxPersonsUploaderPictureCrop', 'modules/boonex/persons/classes/BxPersonsUploaderPictureCrop.php');