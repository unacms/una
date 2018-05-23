SET @sName = 'bx_timeline';


-- PAGES
UPDATE `sys_objects_page` SET `uri`='item', `url`='page.php?i=item' WHERE `object`='bx_timeline_item';
UPDATE `sys_objects_page` SET `uri`='item-quick', `url`='page.php?i=item-quick' WHERE `object`='bx_timeline_item_brief';

DELETE FROM `sys_objects_page` WHERE `object`='bx_timeline_view_hot';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_view_hot', '_bx_timeline_page_title_sys_view_hot', '_bx_timeline_page_title_view_hot', 'bx_timeline', 5, 2147483647, 1, 'timeline-view-hot', 'page.php?i=timeline-view-hot', '', '', '', 0, 1, 0, '', '');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_view_hot';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_timeline_view_hot', 1, 'bx_timeline', '_bx_timeline_page_block_title_system_view_hot', '_bx_timeline_page_block_title_view_hot', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:18:"get_block_view_hot";}', 0, 1, 0, 1),
('bx_timeline_view_hot', 1, 'bx_timeline', '_bx_timeline_page_block_title_system_view_hot_outline', '_bx_timeline_page_block_title_view_hot_outline', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:26:"get_block_view_hot_outline";}', 0, 1, 1, 2);

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_timeline_item' AND `title`='_bx_timeline_page_block_title_item_comments';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_timeline_item', 1, 'bx_timeline', '', '_bx_timeline_page_block_title_item_comments', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:23:"get_block_item_comments";}', 0, 0, 1, 2);


-- MENUS
UPDATE `sys_objects_menu` SET `template_id`='20' WHERE `object`='bx_timeline_menu_item_manage';

UPDATE `sys_menu_items` SET `order`='6' WHERE `set_name`='bx_timeline_menu_item_manage' AND `name`='item-delete';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_manage' AND `name` IN('item-promote', 'item-unpromote', 'item-report', 'item-edit');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-promote', '_bx_timeline_menu_item_title_system_item_promote', '_bx_timeline_menu_item_title_item_promote', 'javascript:void(0)', 'javascript:{js_object_view}.promotePost(this, {content_id}, 1)', '_self', 'certificate ', '', 2147483647, 1, 0, 2),
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-unpromote', '_bx_timeline_menu_item_title_system_item_unpromote', '_bx_timeline_menu_item_title_item_unpromote', 'javascript:void(0)', 'javascript:{js_object_view}.promotePost(this, {content_id}, 0)', '_self', 'certificate', '', 2147483647, 1, 0, 3),
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-report', '_bx_timeline_menu_item_title_system_item_report', '', 'javascript:void(0)', '', '', '', '', 2147483647, 1, 0, 4),
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-edit', '_bx_timeline_menu_item_title_system_item_edit', '_bx_timeline_menu_item_title_item_edit', 'javascript:void(0)', 'javascript:{js_object_view}.editPost(this, {content_id})', '_self', 'pencil', '', 2147483647, 1, 0, 5);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_item_actions' AND `name` IN ('item-score', 'item-report');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-score', '_bx_timeline_menu_item_title_system_item_score', '', 'javascript:void(0)', '', '', '', '', '', 0, 2147483647, 0, 0, 1, 3);

UPDATE `sys_menu_items` SET `onclick`='bx_menu_popup(''bx_timeline_menu_item_manage'', this, {''id'':''bx_timeline_menu_item_manage_{content_id}''}, {content_id:{content_id}, view:''{view}''});' WHERE `set_name`='bx_timeline_menu_item_actions' AND `name`='item-more';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_timeline_menu_post_attachments' AND `name`='add-emoji';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_timeline_menu_post_attachments', 'bx_timeline', 'add-emoji', '_bx_timeline_menu_item_title_system_add_emoji', '_bx_timeline_menu_item_title_add_emoji', 'javascript:void(0)', '', '_self', 'smile-o', '', '', 2147483647, 1, 0, 1, 0);


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_timeline_enable_edit', 'bx_timeline_enable_show_all', 'bx_timeline_videos_autoplay', 'bx_timeline_enable_hot', 'bx_timeline_hot_interval');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_edit', 'on', @iCategId, '_bx_timeline_option_enable_edit', 'checkbox', '', '', '', '', 0),
('bx_timeline_enable_show_all', '', @iCategId, '_bx_timeline_option_enable_show_all', 'checkbox', '', '', '', '', 5),
('bx_timeline_videos_autoplay', 'off', @iCategId, '_bx_timeline_option_videos_autoplay', 'select', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_options_videos_autoplay";}', 50),
('bx_timeline_enable_hot', '', @iCategId, '_bx_timeline_option_enable_hot', 'checkbox', '', '', '', '', 60),
('bx_timeline_hot_interval', '48', @iCategId, '_bx_timeline_option_hot_interval', 'digit', '', '', '', '', 61);


-- ACL
SET @iIdActionEdit = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_timeline' AND `Name`='edit' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionEdit;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionEdit;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'edit', NULL, '_bx_timeline_acl_action_edit', '', 1, 3);
SET @iIdActionEdit = LAST_INSERT_ID();

SET @iIdActionPromote = (SELECT `ID` FROM `sys_acl_actions` WHERE `Module`='bx_timeline' AND `Name`='promote' LIMIT 1);
DELETE FROM `sys_acl_actions` WHERE `ID`=@iIdActionPromote;
DELETE FROM `sys_acl_matrix` WHERE `IDAction`=@iIdActionPromote;

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'promote', NULL, '_bx_timeline_acl_action_promote', '', 1, 3);
SET @iIdActionPromote = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

-- edit any post
INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES
(@iModerator, @iIdActionEdit),
(@iAdministrator, @iIdActionEdit),

-- promote
(@iModerator, @iIdActionPromote),
(@iAdministrator, @iIdActionPromote);

-- COMMENTS
UPDATE `sys_objects_cmts` SET `Html`='3', `BaseUrl`='page.php?i=item&id={object_id}' WHERE `Name`='bx_timeline';


-- SCORES
DELETE FROM `sys_objects_score` WHERE `name`='bx_timeline';
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_timeline', 'bx_timeline', 'bx_timeline_scores', 'bx_timeline_scores_track', '604800', '0', 'bx_timeline_events', 'id', 'object_id', 'score', 'sc_up', 'sc_down', '', '');


-- REPORTS 
UPDATE `sys_objects_report` SET `base_url`='page.php?i=item&id={object_id}' WHERE `name`='bx_timeline';


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_timeline_hot';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_timeline_hot', '0 * * * *', 'BxTimelineCronHot', 'modules/boonex/timeline/classes/BxTimelineCronHot.php', '');
