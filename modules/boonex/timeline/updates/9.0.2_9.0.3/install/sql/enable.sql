-- PAGE
DELETE FROM `sys_objects_page` WHERE `object`='bx_timeline_view_home';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_view_home', '_bx_timeline_page_title_sys_view_home', '_bx_timeline_page_title_view_home', 'bx_timeline', 5, 2147483647, 1, 'timeline-view-home', 'page.php?i=timeline-view-home', '', '', '', 0, 1, 0, '', '');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_view' AND `title` IN ('_bx_timeline_page_block_title_view_outline');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_timeline_view', 1, 'bx_timeline', '_bx_timeline_page_block_title_view_outline', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_outline";}', 0, 0, 3);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_view_home' AND `title` IN ('_bx_timeline_page_block_title_post_home', '_bx_timeline_page_block_title_view_home', '_bx_timeline_page_block_title_view_home_outline');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_timeline_view_home', 1, 'bx_timeline', '_bx_timeline_page_block_title_post_home', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:19:"get_block_post_home";}', 0, 0, 1),
('bx_timeline_view_home', 1, 'bx_timeline', '_bx_timeline_page_block_title_view_home', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:19:"get_block_view_home";}', 0, 0, 2),
('bx_timeline_view_home', 1, 'bx_timeline', '_bx_timeline_page_block_title_view_home_outline', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_block_view_home_outline";}', 0, 0, 3);

DELETE FROM `sys_pages_blocks` WHERE `object`='sys_dashboard' AND `title` IN ('_bx_timeline_page_block_title_view_account_outline');
SET @iPBCellDashboard = 1;
SET @iPBOrderDashboard = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_dashboard' AND `cell_id` = @iPBCellDashboard LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_dashboard', @iPBCellDashboard, 'bx_timeline', '_bx_timeline_page_block_title_view_account_outline', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:30:"get_block_view_account_outline";}', 0, 1, @iPBOrderDashboard + 1);

DELETE FROM `sys_pages_blocks` WHERE `object`='sys_home' AND `title` IN ('_bx_timeline_page_block_title_post_home', '_bx_timeline_page_block_title_view_home_outline');
SET @iPBCellHome = 1;
SET @iPBOrderHome = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = @iPBCellHome ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('sys_home', @iPBCellHome, 'bx_timeline', '_bx_timeline_page_block_title_post_home', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:19:"get_block_post_home";}', 0, 1, @iPBOrderHome + 1),
('sys_home', @iPBCellHome, 'bx_timeline', '_bx_timeline_page_block_title_view_home_outline', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_block_view_home_outline";}', 0, 1, @iPBOrderHome + 2);


UPDATE `sys_pages_blocks` SET `title`='_bx_timeline_page_block_title_post_profile' WHERE `title`='_bx_timeline_page_block_title_post_profile_persons';
UPDATE `sys_pages_blocks` SET `title`='_bx_timeline_page_block_title_view_profile' WHERE `title`='_bx_timeline_page_block_title_view_profile_persons';

SET @iPBCellProfile = 2;
SET @iPBCellGroup = 4;

DELETE FROM `sys_pages_blocks` WHERE `title` IN ('_bx_timeline_page_block_title_view_profile_outline');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_view_profile_outline', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:30:"get_block_view_profile_outline";s:6:"params";a:1:{i:0;s:6:"{type}";}}', 0, 0, 0),
('trigger_page_group_view_entry', @iPBCellGroup, 'bx_timeline', '_bx_timeline_page_block_title_view_profile_outline', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_profile_outline";s:6:"params";a:1:{i:0;s:6:"{type}";}}', 0, 0, 0);


-- MENU
DELETE FROM `sys_objects_menu` WHERE `object`='bx_timeline_menu_item_share';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_item_share', '_bx_timeline_menu_title_item_share', 'bx_timeline_menu_item_share', 'bx_timeline', 6, 0, 1, 'BxTimelineMenuItemShare', 'modules/boonex/timeline/classes/BxTimelineMenuItemShare.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_timeline_menu_item_share';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_item_share', 'bx_timeline', '_bx_timeline_menu_set_title_item_share', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_share';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('bx_timeline_menu_item_share', 'bx_timeline', 'item-repost', '_bx_timeline_menu_item_title_system_item_repost', '_bx_timeline_menu_item_title_item_repost', 'javascript:void(0)', 'javascript:{js_onclick_repost}', '_self', 'repeat', '', 2147483647, 1, 0, 1),
('bx_timeline_menu_item_share', 'bx_timeline', 'item-send', '_bx_timeline_menu_item_title_system_item_send', '_bx_timeline_menu_item_title_item_send', 'page.php?i=start-convo&et={et_send}', '', '_self', 'envelope', '', 2147483647, 1, 0, 2);

UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object_view}.pinPost(this, {content_id}, 1)' WHERE `set_name`='bx_timeline_menu_item_manage' AND `name`='item-pin';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object_view}.pinPost(this, {content_id}, 0)' WHERE `set_name`='bx_timeline_menu_item_manage' AND `name`='item-unpin';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name` IN ('item-view');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-view', '_bx_timeline_menu_item_title_system_item_view', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 1, 0, 0, 0);

UPDATE `sys_menu_items` SET `addon`='a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_menu_item_addon_comment";s:6:"params";a:3:{i:0;s:16:"{comment_system}";i:1;s:16:"{comment_object}";i:2;s:6:"{view}";}}' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-comment';
UPDATE `sys_menu_items` SET `onclick`='bx_menu_popup(''bx_timeline_menu_item_share'', this, {''id'':''bx_timeline_menu_item_share_{content_id}''}, {content_id:{content_id}});', `icon`='share-alt', `submenu_object`='bx_timeline_menu_item_share', `submenu_popup`='1' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-share';


-- ACL
SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

UPDATE `sys_acl_actions` SET `Name`='repost', `Title`='_bx_timeline_acl_action_repost' WHERE `Module`='bx_timeline' AND `Name`='share';

SET @iIdActionSend = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_timeline' AND `Name`='send' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionSend;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionSend;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'send', NULL, '_bx_timeline_acl_action_send', '', 1, 3);
SET @iIdActionSend = LAST_INSERT_ID();

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iStandard, @iIdActionSend),
(@iModerator, @iIdActionSend),
(@iAdministrator, @iIdActionSend),
(@iPremium, @iIdActionSend);


-- VIEWS
DELETE FROM `sys_objects_view` WHERE `name`='bx_timeline';
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_timeline', 'bx_timeline_views_track', '86400', '1', 'bx_timeline_events', 'id', 'object_id', 'views', '', '');


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_timeline_send';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_timeline', '_bx_timeline_et_txt_name_send', 'bx_timeline_send', '_bx_timeline_et_txt_subject_send', '_bx_timeline_et_txt_body_send');