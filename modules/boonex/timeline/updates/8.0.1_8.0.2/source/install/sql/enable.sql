-- PAGES & BLOCKS
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_timeline_view', '_bx_timeline_page_title_sys_view', '_bx_timeline_page_title_view', 'bx_timeline', 5, 2147483647, 1, 'timeline-view', 'page.php?i=timeline-view', '', '', '', 0, 1, 0, '', ''),
('bx_timeline_item', '_bx_timeline_page_title_sys_item', '_bx_timeline_page_title_item', 'bx_timeline', 5, 2147483647, 1, 'timeline-item', 'page.php?i=timeline-item', '', '', '', 0, 1, 0, '', '');


INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_timeline_view', 1, 'bx_timeline', '_bx_timeline_page_block_title_post', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:14:"get_block_post";}', 0, 0, 1),
('bx_timeline_view', 1, 'bx_timeline', '_bx_timeline_page_block_title_view', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:14:"get_block_view";}', 0, 0, 2),

('bx_timeline_item', 1, 'bx_timeline', '_bx_timeline_page_block_title_item', 0, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:14:"get_block_item";}', 0, 0, 1);

SET @iPBCellProfile = 4;
SET @iPBOrderProfile = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object`='bx_persons_view_profile' AND `cell_id` = @iPBCellProfile LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_persons_view_profile', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_post_profile', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_post_profile";}', 0, 0, @iPBOrderProfile + 1),
('bx_persons_view_profile', @iPBCellProfile, 'bx_timeline', '_bx_timeline_page_block_title_view_profile', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_profile";}', 0, 0, @iPBOrderProfile + 2);

SET @iPBCellDashboard = 1;
SET @iPBOrderDashboard = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'bx_persons_view_profile' AND `cell_id` = @iPBCellProfile LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('sys_dashboard', @iPBCellDashboard, 'bx_timeline', '_bx_timeline_page_block_title_view_account', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:22:"get_block_view_account";}', 0, 1, @iPBOrderDashboard + 1);

SET @iPBCellHome = 1;
SET @iPBOrderHome = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = @iPBCellHome ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('sys_home', @iPBCellHome, 'bx_timeline', '_bx_timeline_page_block_title_view_home', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:19:"get_block_view_home";}', 0, 1, @iPBOrderHome + 1);


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

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-comment', '_bx_timeline_menu_item_title_system_item_comment', '_bx_timeline_menu_item_title_item_comment', 'javascript:void(0)', 'javascript:{comment_onclick}', '_self', 'comment', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:27:"get_menu_item_addon_comment";s:6:"params";a:2:{i:0;s:16:"{comment_system}";i:1;s:16:"{comment_object}";}}', '', 2147483647, 1, 0, 1),
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-vote', '_bx_timeline_menu_item_title_system_item_vote', '_bx_timeline_menu_item_title_item_vote', 'javascript:void(0)', 'javascript:{vote_onclick}', '_self', 'plus', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:24:"get_menu_item_addon_vote";s:6:"params";a:2:{i:0;s:13:"{vote_system}";i:1;s:13:"{vote_object}";}}', '', 2147483647, 1, 0, 2),
('bx_timeline_menu_item_actions', 'bx_timeline', 'item-share', '_bx_timeline_menu_item_title_system_item_share', '_bx_timeline_menu_item_title_item_share', 'javascript:void(0)', 'javascript:{share_onclick}', '_self', 'share', 'a:3:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:25:"get_menu_item_addon_share";s:6:"params";a:3:{i:0;s:12:"{share_type}";i:1;s:14:"{share_action}";i:2;s:14:"{share_object}";}}', '', 2147483647, 1, 0, 3);


-- SETTINGS
SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_timeline', '_bx_timeline', 'bx_timeline@modules/boonex/timeline/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_timeline', '_bx_timeline', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_delete', 'on', @iCategId, '_bx_timeline_option_enable_delete', 'checkbox', '', '', '', '', 1),
('bx_timeline_events_per_page_profile', '10', @iCategId, '_bx_timeline_option_events_per_page_profile', 'digit', '', '', '', '', 2),
('bx_timeline_events_per_page_account', '20', @iCategId, '_bx_timeline_option_events_per_page_account', 'digit', '', '', '', '', 3),
('bx_timeline_events_per_page_home', '20', @iCategId, '_bx_timeline_option_events_per_page_home', 'digit', '', '', '', '', 4),
('bx_timeline_rss_length', '5', @iCategId, '_bx_timeline_option_rss_length', 'digit', '', '', '', '', 5),
('bx_timeline_events_hide', '', @iCategId, '_bx_timeline_option_events_hide', 'rlist', '', '', '', 'a:2:{s:6:"module";s:11:"bx_timeline";s:6:"method";s:21:"get_actions_checklist";}', 6),
('bx_timeline_chars_display_max', '300', @iCategId, '_bx_timeline_option_chars_display_max', 'digit', 'GreaterThan', 'a:1:{s:3:"min";i:150;}', '_bx_timeline_option_err_chars_display_max', '', 7);


-- ACL
INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'post', NULL, '_bx_timeline_acl_action_post', '', 1, 3);
SET @iIdActionPost = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'delete', NULL, '_bx_timeline_acl_action_delete', '', 1, 3);
SET @iIdActionDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'vote', NULL, '_bx_timeline_acl_action_vote', '', 1, 0);
SET @iIdActionVote = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_timeline', 'share', NULL, '_bx_timeline_acl_action_share', '', 1, 3);
SET @iIdActionShare = LAST_INSERT_ID();

SET @iUnauthenticated = 1;
SET @iAccount = 2;
SET @iStandard = 3;
SET @iUnconfirmed = 4;
SET @iPending = 5;
SET @iSuspended = 6;
SET @iModerator = 7;
SET @iAdministrator = 8;
SET @iPremium = 9;

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
INSERT INTO `sys_alerts_handlers`(`name`, `class`, `file`, `service_call`) VALUES 
('bx_timeline', 'BxTimelineResponse', 'modules/boonex/timeline/classes/BxTimelineResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'delete', @iHandler);


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_timeline', 'bx_timeline_comments', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 0, -3, 1, 'cmt', 'page.php?i=timeline-item&id={object_id}', '', 'bx_timeline_events', 'id', 'title', 'comments', 'BxTimelineCmts', 'modules/boonex/timeline/classes/BxTimelineCmts.php');


-- VOTES
INSERT INTO `sys_objects_vote`(`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_timeline', 'bx_timeline_votes', 'bx_timeline_votes_track', '604800', '1', '1', '1', '1', 'bx_timeline_events', 'id', 'rate', 'votes', 'BxTimelineVote', 'modules/boonex/timeline/classes/BxTimelineVote.php');


-- SEARCH
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `ClassName`, `ClassPath`) VALUES
('bx_timeline_cmts', '_bx_timeline_cmts', 'BxTimelineCmtsSearchResult', 'modules/boonex/timeline/classes/BxTimelineCmtsSearchResult.php');


-- MODULES' CONNECTIONS
INSERT INTO `sys_modules_relations` (`module`, `on_install`, `on_uninstall`, `on_enable`, `on_disable`) VALUES
('bx_timeline', '', 'delete_module_events', 'add_handlers', 'delete_handlers');