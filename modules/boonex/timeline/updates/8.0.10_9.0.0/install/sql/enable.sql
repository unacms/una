-- PAGES
UPDATE `sys_objects_page` SET `override_class_name`='BxTimelinePageView', `override_class_file`='modules/boonex/timeline/classes/BxTimelinePageView.php' WHERE `object`='bx_timeline_view';

SET @iPBCellProfile = 2;
SET @iPBCellGroup = 4;
DELETE FROM `sys_pages_blocks` WHERE `object`='trigger_page_persons_view_entry' AND `title`IN ('_bx_timeline_page_block_title_post_profile_persons', '_bx_timeline_page_block_title_view_profile_persons');
DELETE FROM `sys_pages_blocks` WHERE `object`='trigger_page_organizations_view_entry' AND `title`IN ('_bx_timeline_page_block_title_post_profile_organizations', '_bx_timeline_page_block_title_view_profile_organizations');
DELETE FROM `sys_pages_blocks` WHERE `object`='trigger_page_profile_view_entry' AND `title`IN ('_bx_timeline_page_block_title_post_profile_persons', '_bx_timeline_page_block_title_view_profile_persons');
DELETE FROM `sys_pages_blocks` WHERE `object`='trigger_page_group_view_entry' AND `title`IN ('_bx_timeline_page_block_title_post_profile_persons', '_bx_timeline_page_block_title_view_profile_persons');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_post_profile_persons', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_post_profile";s:6:"params";a:1:{i:0;s:6:"{type}";}}', 0, 0, 0),
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_view_profile_persons', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_profile";s:6:"params";a:1:{i:0;s:6:"{type}";}}', 0, 0, 0),
('trigger_page_group_view_entry', @iPBCellGroup, 'bx_timeline', '_bx_timeline_page_block_title_post_profile_persons', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_post_profile";s:6:"params";a:1:{i:0;s:6:"{type}";}}', 0, 0, 0),
('trigger_page_group_view_entry', @iPBCellGroup, 'bx_timeline', '_bx_timeline_page_block_title_view_profile_persons', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_profile";s:6:"params";a:1:{i:0;s:6:"{type}";}}', 0, 0, 0);


-- MENUS
UPDATE `sys_menu_items` SET `order`='2' WHERE `set_name`='bx_timeline_menu_item_manage' AND `name`='item-delete';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_manage' AND `name` IN ('item-pin', 'item-unpin');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-pin', '_bx_timeline_menu_item_title_system_item_pin', '_bx_timeline_menu_item_title_item_pin', 'javascript:void(0)', 'javascript:{js_object_view}.pinPost(this, {content_id})', '_self', 'thumb-tack', '', 2147483647, 1, 0, 0),
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-unpin', '_bx_timeline_menu_item_title_system_item_unpin', '_bx_timeline_menu_item_title_item_unpin', 'javascript:void(0)', 'javascript:{js_object_view}.unpinPost(this, {content_id})', '_self', 'thumb-tack', '', 2147483647, 1, 0, 1);

UPDATE `sys_menu_items` SET `order`='5' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-more';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name` IN ('item-report');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-report', '_bx_timeline_menu_item_title_system_item_report', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 1, 0, 0, 4);

SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_add_content_links' AND `module`='bx_timeline' AND `name` IN ('create-post');
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', 'bx_timeline', 'create-post', '_bx_timeline_menu_item_title_system_create_entry', '_bx_timeline_menu_item_title_create_entry', 'page.php?i=timeline-view', '', '', 'clock-o col-green1', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);

DELETE FROM `sys_menu_items` WHERE `set_name` IN ('trigger_profile_view_submenu', 'trigger_group_view_submenu') AND `name`='timeline-view';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_profile_view_submenu', 'bx_timeline', 'timeline-view', '_bx_timeline_menu_item_title_system_view_timeline_view', '_bx_timeline_menu_item_title_view_timeline_view', 'page.php?i=timeline-view&profile_id={profile_id}', '', '', 'clock-o col-green1', '', 2147483647, 1, 0, 0),
('trigger_group_view_submenu', 'bx_timeline', 'timeline-view', '_bx_timeline_menu_item_title_system_view_timeline_view', '_bx_timeline_menu_item_title_view_timeline_view', 'page.php?i=timeline-view&profile_id={profile_id}', '', '', 'clock-o col-green1', '', 2147483647, 1, 0, 0);


-- ACL
SET @iModerator = 7;
SET @iAdministrator = 8;

SET @iIdActionPin = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_timeline' AND `Name`='pin' LIMIT 1);
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionPin;
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionPin;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'pin', NULL, '_bx_timeline_acl_action_pin', '', 1, 3);
SET @iIdActionPin = LAST_INSERT_ID();

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionPin),
(@iAdministrator, @iIdActionPin);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `TriggerFieldAuthor`='object_id' WHERE `Name`='bx_timeline';


-- VOTES
UPDATE `sys_objects_vote` SET `TriggerFieldAuthor`='object_id' WHERE `Name`='bx_timeline';


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_timeline';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_timeline', 'bx_timeline_reports', 'bx_timeline_reports_track', '1', 'page.php?i=timeline-item&id={object_id}', 'bx_timeline_events', 'id', 'owner_id', 'reports',  'BxTimelineReport', 'modules/boonex/timeline/classes/BxTimelineReport.php');