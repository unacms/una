-- PAGES & BLOCKS
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_view', '_bx_timeline_page_title_sys_view', '_bx_timeline_page_title_view', 'bx_timeline', 5, 2147483647, 1, 'view', 'page.php?i=view', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_timeline_view', 1, 'bx_timeline', '_bx_timeline_page_block_title_post', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:14:"get_block_post";}', 0, 0, 1),
('bx_timeline_view', 1, 'bx_timeline', '_bx_timeline_page_block_title_view', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:14:"get_block_view";}', 0, 1, 1);

SET @iPBCellProfile = 1;
SET @iPBOrderProfile = (SELECT MAX(`order`) FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `cell_id` = @iPBCellProfile LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_persons_view_profile', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_post_profile', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_post_profile";}', 0, 0, @iPBOrderProfile + 1),
('bx_persons_view_profile', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_view_profile', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_profile";}', 0, 1, @iPBOrderProfile + 2);


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_timeline', '_bx_timeline', 'bx_timeline@modules/boonex/timeline/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_timeline', '_bx_timeline', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_guest_comments', '', @iCategId, 'Allow non-members to post in Timeline', 'checkbox', '', '', '', 1),
('bx_timeline_enable_delete', 'on', @iCategId, 'Allow Timeline owner to remove events', 'checkbox', '', '', '', 2),
('bx_timeline_events_per_page_profile', '10', @iCategId, 'Number of events are displayed on Profile page', 'digit', '', '', '', 3),
('bx_timeline_events_per_page_account', '20', @iCategId, 'Number of events are displayed on Account page', 'digit', '', '', '', 4),
('bx_timeline_rss_length', '5', @iCategId, 'The length of RSS feed', 'digit', '', '', '', 5),
('bx_timeline_events_hide', '', @iCategId, 'Hide events from Timeline', 'list', '', '', 'PHP:return BxDolService::call(\'bx_timeline\', \'get_actions_checklist\');', 6),
('bx_timeline_chars_display_max', '300', @iCategId, 'Max number of displayed character in text post', 'digit', '', '', '', 7);


-- UPLOADERS
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_timeline_simple', 1, 'BxTemplUploaderSimple', '');

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_timeline_photos', 'Local', '', 360, 2592000, 3, 'bx_timeline_photos', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_timeline_photos_preview', 'Local', '', 360, 2592000, 3, 'bx_timeline_photos_preview', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder_images` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES
('bx_timeline_photos_preview', 'bx_timeline_photos_preview', 'Storage', 'a:1:{s:6:"object";s:18:"bx_timeline_photos";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_images_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES
('bx_timeline_photos_preview', 'Resize', 'a:4:{s:1:"w";s:3:"319";s:1:"h";s:3:"319";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'post', NULL, '_bx_timeline_acl_action_post', '', 1, 1);
SET @iIdActionPost = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'delete', NULL, '_bx_timeline_acl_action_delete', '', 1, 1);
SET @iIdActionDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'comment', NULL, '_bx_timeline_acl_action_comment', '', 1, 1);
SET @iIdActionComment = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iStandard = 2;
SET @iUnconfirmed = 3;
SET @iPending = 4;
SET @iSuspended = 5;
SET @iModerator = 6;
SET @iAdministrator = 7;
SET @iPremium = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- post
(@iStandard, @iIdActionPost),
(@iModerator, @iIdActionPost),
(@iAdministrator, @iIdActionPost),
(@iPremium, @iIdActionPost),

-- delete
(@iModerator, @iIdActionDelete),
(@iAdministrator, @iIdActionDelete),

-- comment
(@iStandard, @iIdActionComment),
(@iModerator, @iIdActionComment),
(@iAdministrator, @iIdActionComment),
(@iPremium, @iIdActionComment);


-- ALERTS
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `eval`) VALUES 
('bx_timeline', '', '', 'BxDolService::call(\'bx_timeline\', \'response\', array($this));');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `TableCmts`, `TableTrack`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_timeline', 'bx_timeline_comments', 'bx_timeline_comments_track', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', '', 'bx_timeline_events', 'id', 'title', 'comments', 'BxTimelineCmts', 'modules/boonex/timeline/classes/BxTimelineCmts.php');
