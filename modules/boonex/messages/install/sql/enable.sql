
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_messages', '_bx_msg', 'bx_messages@modules/boonex/messages/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_messages', '_bx_msg', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_messages_per_page_browse', '12', @iCategId, '_bx_msg_option_per_page_browse', 'digit', '', '', '', 1);

-- STORAGES & TRANSCODERS

SET @iTotalFilesSize = (SELECT SUM(`size`) FROM `bx_messages_photos`);
SET @iTotalFilesNum = (SELECT COUNT(*) FROM `bx_messages_photos`);
SET @iTotalResizedSize = (SELECT SUM(`size`) FROM `bx_messages_photos_resized`);
SET @iTotalResizedNum = (SELECT COUNT(*) FROM `bx_messages_photos_resized`);

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_messages_photos', 'Local', '', 360, 2592000, 3, 'bx_messages_photos', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, @iTotalFilesSize, 0, @iTotalFilesNum, 0, 0),
('bx_messages_photos_resized', 'Local', '', 360, 2592000, 3, 'bx_messages_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, @iTotalResizedSize, 0, @iTotalResizedNum, 0, 0);

INSERT INTO `sys_objects_transcoder_images` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_messages_preview', 'bx_messages_photos_resized', 'Storage', 'a:1:{s:6:"object";s:18:"bx_messages_photos";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_images_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_messages_preview', 'Resize', 'a:4:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- PAGE: create entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_messages_create_entry', '_bx_msg_page_title_sys_create_entry', '_bx_msg_page_title_create_entry', 'bx_messages', 5, 2147483647, 1, 'compose-message', 'page.php?i=compose-message', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_messages_create_entry', 1, 'bx_messages', '_bx_msg_page_block_title_create_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_messages";s:6:"method";s:13:"entity_create";}', 0, 1, 1);


-- PAGE: delete entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_messages_delete_entry', '_bx_msg_page_title_sys_delete_entry', '_bx_msg_page_title_delete_entry', 'bx_messages', 5, 2147483647, 1, 'delete-message', '', '', '', '', 0, 1, 0, 'BxMsgPageEntry', 'modules/boonex/messages/classes/BxMsgPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_messages_delete_entry', 1, 'bx_messages', '_bx_msg_page_block_title_delete_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:11:"bx_messages";s:6:"method";s:13:"entity_delete";}', 0, 0, 0);


-- PAGE: view entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_messages_view_entry', '_bx_msg_page_title_sys_view_entry', '_bx_msg_page_title_view_entry', 'bx_messages', 11, 2147483647, 1, 'view-message', '', '', '', '', 0, 1, 0, 'BxMsgPageEntry', 'modules/boonex/messages/classes/BxMsgPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_messages_view_entry', 2, 'bx_messages', '_bx_msg_page_block_title_entry_author', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_messages\";s:6:\"method\";s:13:\"entity_author\";}', 0, 0, 0),
('bx_messages_view_entry', 3, 'bx_messages', '_bx_msg_page_block_title_entry_actions', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_messages\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 0),
('bx_messages_view_entry', 1, 'bx_messages', '_bx_msg_page_block_title_entry_text', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_messages\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 0),
('bx_messages_view_entry', 1, 'bx_messages', '_bx_msg_page_block_title_entry_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_messages\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 2);


-- PAGE: entries of author

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_messages_author', 'messages', '_bx_msg_page_title_sys_entries_of_author', '_bx_msg_page_title_entries_of_author', 'bx_messages', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxMsgPageAuthor', 'modules/boonex/messages/classes/BxMsgPageAuthor.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_messages_author', 1, 'bx_messages', '_bx_msg_page_block_title_entries_actions', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_messages\";s:6:\"method\";s:18:\"my_entries_actions\";}', 0, 0, 0),
('bx_messages_author', 1, 'bx_messages', '_bx_msg_page_block_title_entries_of_author', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_messages\";s:6:\"method\";s:13:\"browse_author\";}', 0, 0, 1);


-- MENU: actions menu for view entry 

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_messages_view', '_bx_msg_menu_title_view_entry', 'bx_messages_view', 'bx_messages', 9, 0, 1, 'BxMsgMenuView', 'modules/boonex/messages/classes/BxMsgMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_messages_view', 'bx_messages', '_bx_msg_menu_set_title_view_entry', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_messages_view', 'bx_messages', 'delete-message', '_bx_msg_menu_item_title_system_delete_entry', '_bx_msg_menu_item_title_delete_entry', 'page.php?i=delete-message&id={content_id}', '', '', 'remove', '', 0, 1, 0, 2);


-- MENU: actions menu for my entries

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_messages_my', '_bx_msg_menu_title_entries_my', 'bx_messages_my', 'bx_messages', 9, 0, 1, 'BxMsgMenu', 'modules/boonex/messages/classes/BxMsgMenu.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_messages_my', 'bx_messages', '_bx_msg_menu_set_title_entries_my', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_messages_my', 'bx_messages', 'compose-message', '_bx_msg_menu_item_title_system_create_entry', '_bx_msg_menu_item_title_create_entry', 'page.php?i=compose-message', '', '', 'plus', '', 0, 1, 0, 0);


-- MENU: module sub-menu

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_messages_submenu', '_bx_msg_menu_title_submenu', 'bx_messages_submenu', 'bx_messages', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_messages_submenu', 'bx_messages', '_bx_msg_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_messages_submenu', 'bx_messages', 'messages-inbox', '_bx_msg_menu_item_title_system_entries_inbox', '_bx_msg_menu_item_title_entries_inbox', 'page.php?i=messages-inbox', '', '', '', '', 2147483647, 1, 1, 1),
('bx_messages_submenu', 'bx_messages', 'messages-sent', '_bx_msg_menu_item_title_system_entries_sent', '_bx_msg_menu_item_title_entries_sent', 'page.php?i=messages-sent', '', '', '', '', 2147483647, 1, 1, 2);


-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_messages', 'create entry', NULL, '_bx_msg_acl_action_create_entry', '', 1, 1);
SET @iIdActionEntryCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_messages', 'delete entry', NULL, '_bx_msg_acl_action_delete_entry', '', 1, 1);
SET @iIdActionEntryDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_messages', 'view entry', NULL, '_bx_msg_acl_action_view_entry', '', 1, 1);
SET @iIdActionEntryView = LAST_INSERT_ID();


SET @iUnauthenticated = 1;
SET @iStandard = 2;
SET @iUnconfirmed = 3;
SET @iPending = 4;
SET @iSuspended = 5;
SET @iModerator = 6;
SET @iAdministrator = 7;
SET @iPremium = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- entry create
(@iStandard, @iIdActionEntryCreate),
(@iModerator, @iIdActionEntryCreate),
(@iAdministrator, @iIdActionEntryCreate),
(@iPremium, @iIdActionEntryCreate),

-- entry delete
(@iStandard, @iIdActionEntryDelete),
(@iModerator, @iIdActionEntryDelete),
(@iAdministrator, @iIdActionEntryDelete),
(@iPremium, @iIdActionEntryDelete),

-- entry view
(@iUnauthenticated, @iIdActionEntryView),
(@iStandard, @iIdActionEntryView),
(@iUnconfirmed, @iIdActionEntryView),
(@iPending, @iIdActionEntryView),
(@iModerator, @iIdActionEntryView),
(@iAdministrator, @iIdActionEntryView),
(@iPremium, @iIdActionEntryView);


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_messages', 'bx_messages_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 0, -3, 1, 'cmt', 'page/view-message&id={object_id}', '', 'bx_messages_msg', 'id', '', 'comments', '', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_messages', 'bx_messages_views_track', '86400', '1', 'bx_messages_msg', 'id', 'views', '', '');

