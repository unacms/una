-- PAGES & BLOCKS
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_view', '_bx_timeline_page_title_sys_view', '_bx_timeline_page_title_view', 'bx_timeline', 5, 2147483647, 1, 'timeline-view', 'page.php?i=timeline-view', '', '', '', 0, 1, 0, '', ''),
('bx_timeline_item', '_bx_timeline_page_title_sys_item', '_bx_timeline_page_title_item', 'bx_timeline', 5, 2147483647, 1, 'timeline-item', 'page.php?i=timeline-item', '', '', '', 0, 1, 0, '', ''),
('bx_timeline_account', '_bx_timeline_page_title_sys_account', '_bx_timeline_page_title_account', 'bx_timeline', 1, 2147483647, 1, 'timeline-account', 'page.php?i=timeline-account', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_timeline_view', 1, 'bx_timeline', '_bx_timeline_page_block_title_post', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:14:"get_block_post";}', 0, 0, 1),
('bx_timeline_view', 1, 'bx_timeline', '_bx_timeline_page_block_title_view', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:14:"get_block_view";}', 0, 1, 2),

('bx_timeline_item', 1, 'bx_timeline', '_bx_timeline_page_block_title_item', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:14:"get_block_item";}', 0, 1, 1),

('bx_timeline_account', 1, 'bx_timeline', '_sys_page_block_title_account_settings_menu', 13, 2147483647, 'menu', 'sys_account_settings', 1, 1, 1),
('bx_timeline_account', 2, 'bx_timeline', '_bx_timeline_page_block_title_view_account', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_account";}', 0, 1, 1);

SET @iPBCellProfile = 4;
SET @iPBOrderProfile = (SELECT MAX(`order`) FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `cell_id` = @iPBCellProfile LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_persons_view_profile', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_post_profile', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_post_profile";}', 0, 0, IFNULL(@iPBOrderProfile, 0) + 1),
('bx_persons_view_profile', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_view_profile', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_profile";}', 0, 1, IFNULL(@iPBOrderProfile, 0) + 2);


-- MENUS

-- MENU: Post Item (Text, Link, Photo)
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_post', '_bx_timeline_menu_title_post', 'bx_timeline_menu_post', 'bx_timeline', 11, 0, 1, 'BxTimelineMenuPost', 'modules/boonex/timeline/classes/BxTimelineMenuPost.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_post', 'bx_timeline', '_bx_timeline_menu_set_title_post', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_post', 'bx_timeline', 'post-text', '_bx_timeline_menu_item_title_system_post_text', '_bx_timeline_menu_item_title_post_text', 'javascript:void(0)', 'javascript:{js_object_post}.changePostType(this, ''text'')', '_self', '', '', 2147483647, 1, 0, 0),
('bx_timeline_menu_post', 'bx_timeline', 'post-link', '_bx_timeline_menu_item_title_system_post_link', '_bx_timeline_menu_item_title_post_link', 'javascript:void(0)', 'javascript:{js_object_post}.changePostType(this, ''link'')', '_self', '', '', 2147483647, 1, 0, 0),
('bx_timeline_menu_post', 'bx_timeline', 'post-photo', '_bx_timeline_menu_item_title_system_post_photo', '_bx_timeline_menu_item_title_post_photo', 'javascript:void(0)', 'javascript:{js_object_post}.changePostType(this, ''photo'')', '_self', '', '', 2147483647, 1, 0, 0);

-- MENU: Item Manage (Delete)
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_item_manage', '_bx_timeline_menu_title_item_manage', 'bx_timeline_menu_item_manage', 'bx_timeline', 6, 0, 1, 'BxTimelineMenuItem', 'modules/boonex/timeline/classes/BxTimelineMenuItem.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_item_manage', 'bx_timeline', '_bx_timeline_menu_set_title_item_manage', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_item_manage', 'bx_timeline', 'item-delete', '_bx_timeline_menu_item_title_system_item_delete', '_bx_timeline_menu_item_title_item_delete', 'javascript:void(0)', 'javascript:{js_object_view}.deletePost(this, {content_id})', '_self', 'remove', '', 2147483647, 1, 0, 0);

-- MENU: Item Actions (Comment, Plus)
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_menu_item_actions', '_bx_timeline_menu_title_item_actions', 'bx_timeline_menu_item_actions', 'bx_timeline', 10, 0, 1, 'BxTimelineMenuItem', 'modules/boonex/timeline/classes/BxTimelineMenuItem.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_timeline_menu_item_actions', 'bx_timeline', '_bx_timeline_menu_set_title_item_actions', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-comment', '_bx_timeline_menu_item_title_system_item_comment', '_bx_timeline_menu_item_title_item_comment', 'javascript:void(0)', 'javascript:{comment_onclick}', '_self', 'comment', '', 2147483647, 1, 0, 1),
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-vote', '_bx_timeline_menu_item_title_system_item_vote', '_bx_timeline_menu_item_title_item_vote', 'javascript:void(0)', 'javascript:{vote_onclick}', '_self', 'plus', '', 2147483647, 1, 0, 2),
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-share', '_bx_timeline_menu_item_title_system_item_share', '_bx_timeline_menu_item_title_item_share', 'javascript:void(0)', 'javascript:{share_onclick}', '_self', 'share-square-o', '', 2147483647, 1, 0, 3);

-- MENU: Account Settings
SET @iMIOrder = (SELECT MAX(`order`) FROM `sys_menu_items` WHERE `set_name`='sys_account_settings' LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_settings', 'bx_timeline', 'account-settings-timeline', '_bx_timeline_menu_item_title_system_timeline', '_bx_timeline_menu_item_title_timeline', 'page.php?i=timeline-account', '', '', 'clock-o', '', 2147483646, 1, 1, @iMIOrder + 1);


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
('bx_timeline', 'vote', NULL, '_bx_timeline_acl_action_vote', '', 1, 1);
SET @iIdActionVote = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'share', NULL, '_bx_timeline_acl_action_share', '', 1, 1);
SET @iIdActionShare = LAST_INSERT_ID();

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

-- vote
(@iStandard, @iIdActionVote),
(@iModerator, @iIdActionVote),
(@iAdministrator, @iIdActionVote),
(@iPremium, @iIdActionVote),

-- share
(@iStandard, @iIdActionShare),
(@iModerator, @iIdActionShare),
(@iAdministrator, @iIdActionShare),
(@iPremium, @iIdActionShare);


-- ALERTS
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `eval`) VALUES 
('bx_timeline', '', '', 'BxDolService::call(\'bx_timeline\', \'response\', array($this));');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `TableCmts`, `TableTrack`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_timeline', 'bx_timeline_comments', 'bx_timeline_comments_track', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', '', 'bx_timeline_events', 'id', 'title', 'comments', 'BxTimelineCmts', 'modules/boonex/timeline/classes/BxTimelineCmts.php');


-- VOTES
INSERT INTO `sys_objects_vote`(`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_timeline', 'bx_timeline_votes', 'bx_timeline_votes_track', '604800', '1', '1', '1', '1', 'bx_timeline_events', 'id', 'rate', 'votes', 'BxTimelineVote', 'modules/boonex/timeline/classes/BxTimelineVote.php');
