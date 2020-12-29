SET @sName = 'bx_timeline';


-- CONTENT PLACEHOLDERS
DELETE FROM `sys_pages_content_placeholders` WHERE `module`=@sName AND `title`='_bx_timeline_page_content_ph_timeline';
SET @iCPHOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_content_placeholders` ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_content_placeholders` (`module`, `title`, `template`, `order`) VALUES
('bx_timeline', '_bx_timeline_page_content_ph_timeline', 'block_async_timeline.html', @iCPHOrder + 1);


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_timeline_manage';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_manage', '_bx_timeline_page_title_sys_manage', '_bx_timeline_page_title_manage', 'bx_timeline', 5, 2147483647, 1, 'timeline-manage', 'page.php?i=timeline-manage', '', '', '', 0, 1, 0, '', '');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_manage';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_timeline_manage', 1, 'bx_timeline', '', '_bx_timeline_page_block_title_muted', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:15:"get_block_muted";}', 0, 0, 1, 1);

UPDATE `sys_pages_blocks` SET `designbox_id`='11', `tabs`='1' WHERE `object`='sys_dashboard' AND `title`='_bx_timeline_page_block_title_views_timeline';
UPDATE `sys_pages_blocks` SET `designbox_id`='11', `tabs`='1' WHERE `object`='sys_dashboard' AND `title`='_bx_timeline_page_block_title_views_outline';

UPDATE `sys_pages_blocks` SET `designbox_id`='11', `tabs`='1' WHERE `object`='sys_home' AND `title`='_bx_timeline_page_block_title_views_timeline';

UPDATE `sys_pages_blocks` SET `designbox_id`='11', `tabs`='1' WHERE `object`='' AND `title`='_bx_timeline_page_block_title_views_timeline';
UPDATE `sys_pages_blocks` SET `designbox_id`='11', `tabs`='1' WHERE `object`='' AND `title`='_bx_timeline_page_block_title_views_outline';


-- MENUS
UPDATE `sys_objects_menu` SET `template_id`='15' WHERE `object`='bx_timeline_menu_view';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_manage' AND `name`='item-mute';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-mute', '_bx_timeline_menu_item_title_system_item_mute', '_bx_timeline_menu_item_title_item_mute', 'javascript:void(0)', 'javascript:{js_object_view}.muteAuthor(this, {content_id})', '_self', 'user-slash', '', '', '', 2147483647, '', 1, 0, 7);

UPDATE `sys_menu_items` SET `icon`='ellipsis-v', `active`='0' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-more';

UPDATE `sys_objects_menu` SET `active`='0' WHERE `object`='bx_timeline_menu_item_actions_all';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions_all' AND `name`='item-mute';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_item_actions_all', 'bx_timeline', 'item-mute', '_bx_timeline_menu_item_title_system_item_mute', '', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1, 165);

UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object}.showAttachLink(this, {content_id});' WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name`='add-link';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object_add_photo_simple}.showUploaderForm();' WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name`='add-photo-simple';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object_add_photo_html5}.showUploaderForm();' WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name`='add-photo-html5';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object_add_video_simple}.showUploaderForm();' WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name`='add-video-simple';
UPDATE `sys_menu_items` SET `onclick`='javascript:{js_object_add_video_html5}.showUploaderForm();' WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name`='add-video-html5';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name`='add-video-record';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-video-record', '_bx_timeline_menu_item_title_system_add_video_record', '_bx_timeline_menu_item_title_add_video_record', 'javascript:void(0)', 'javascript:{js_object_add_video_record}.showUploaderForm();', '_self', 'fas circle', '', '', '', 2147483647, '', 1, 0, 1, 6);

DELETE FROM `sys_menu_items` WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-timeline';
SET @iPStatsMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_timeline', 'profile-stats-manage-timeline', '_bx_timeline_menu_item_title_system_manage_my_timeline', '_bx_timeline_menu_item_title_manage_my_timeline', 'page.php?i=timeline-manage', '', '_self', 'far clock col-green1', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:28:"get_menu_addon_profile_stats";}', '', '', 2147483646, '', 1, 0, @iPStatsMenuOrder + 1);


-- ACL
SET @iIdActionMute = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`=@sName AND `Name`='mute' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionMute;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionMute;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'mute', NULL, '_bx_timeline_acl_action_mute', '', 1, 3);
SET @iIdActionMute = LAST_INSERT_ID();

SET @iStandard = 3;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iStandard, @iIdActionMute),
(@iModerator, @iIdActionMute),
(@iAdministrator, @iIdActionMute),
(@iPremium, @iIdActionMute);


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object`='bx_timeline_mute';
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `responsive`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_mute', 'Sql', 'SELECT `p`.`id`, `c`.`added`, `c`.`mutual` FROM `sys_profiles` AS `p` INNER JOIN `sys_accounts` AS `a` ON (`a`.`id` = `p`.`account_id`) {join_connections}', 'sys_profiles', 'id', 'c`.`added', '', '', 10, NULL, 'start', '', 'name,email', '', 'auto', '', '', 2147483647, 0, 'BxTimelineGridMute', 'modules/boonex/timeline/classes/BxTimelineGridMute.php');

DELETE FROM `sys_grid_fields` WHERE `object`='bx_timeline_mute';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `hidden_on`, `order`) VALUES
('bx_timeline_mute', 'name', '_sys_name', '60%', '', '', 1),
('bx_timeline_mute', 'info', '', '20%', '', '1', 2),
('bx_timeline_mute', 'actions', '', '20%', '', '', 3);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_timeline_mute';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_timeline_mute', 'single', 'delete', '', 'remove', 0, 1, 2);


-- CONNECTIONS
DELETE FROM `sys_objects_connection` WHERE `object`='bx_timeline_mute';
INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_mute', 'bx_timeline_mute', 'one-way', '', '');
