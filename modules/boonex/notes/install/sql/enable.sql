
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_notes', '_bx_notes', 'bx_notes@modules/boonex/notes/|std-mi.png', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_notes', '_bx_notes', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_notes_autoapproval', 'on', @iCategId, 'Activate notes after creation automatically', 'checkbox', '', '', '', 1),
('bx_notes_summary_chars', '700', @iCategId, 'Number of characters in auto-cropped note summary', 'digit', '', '', '', 2);

-- STORAGES & TRANSCODERS

SET @iTotalFilesSize = (SELECT SUM(`size`) FROM `bx_notes_photos`);
SET @iTotalFilesNum = (SELECT COUNT(*) FROM `bx_notes_photos`);

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_notes_photos', 'Local', '', 360, 2592000, 3, 'bx_notes_photos', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, @iTotalFilesSize, 0, @iTotalFilesNum, 0, 0),
('bx_notes_photos_resized', 'Local', '', 360, 2592000, 3, 'bx_notes_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, @iTotalFilesSize, 0, @iTotalFilesNum, 0, 0);

INSERT INTO `sys_objects_transcoder_images` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_notes_preview', 'bx_notes_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_notes_photos";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_images_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_notes_preview', 'Resize', 'a:4:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- PAGES

--
-- Dumping data for 'bx_notes_create_note' page
--
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_create_note', '_bx_notes_page_title_sys_create_note', '_bx_notes_page_title_create_note', 'bx_notes', 5, 2147483647, 1, 'create-note', 'page.php?i=create-note', '', '', '', 0, 1, 0, '', '');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_notes_create_note', 1, 'bx_notes', '_bx_notes_page_block_title_create_note', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_notes";s:6:"method";s:13:"entity_create";}', 0, 1, 1);

--
-- Dumping data for 'bx_notes_edit_note' page
--
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_edit_note', '_bx_notes_page_title_sys_edit_note', '_bx_notes_page_title_edit_note', 'bx_notes', 5, 2147483647, 1, 'edit-note', '', '', '', '', 0, 1, 0, 'BxNotesPageNote', 'modules/boonex/notes/classes/BxNotesPageNote.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_notes_edit_note', 1, 'bx_notes', '_bx_notes_page_block_title_edit_note', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_notes";s:6:"method";s:11:"entity_edit";}', 0, 0, 0);

--
-- Dumping data for 'bx_notes_delete_note' page
--
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_delete_note', '_bx_notes_page_title_sys_delete_note', '_bx_notes_page_title_delete_note', 'bx_notes', 5, 2147483647, 1, 'delete-note', '', '', '', '', 0, 1, 0, 'BxNotesPageNote', 'modules/boonex/notes/classes/BxNotesPageNote.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_notes_delete_note', 1, 'bx_notes', '_bx_notes_page_block_title_delete_note', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_notes";s:6:"method";s:13:"entity_delete";}', 0, 0, 0);

--
-- Dumping data for 'bx_notes_view_note' page
--
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_view_note', '_bx_notes_page_title_sys_view_note', '_bx_notes_page_title_view_note', 'bx_notes', 6, 2147483647, 1, 'view-note', '', '', '', '', 0, 1, 0, 'BxNotesPageNote', 'modules/boonex/notes/classes/BxNotesPageNote.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_notes_view_note', 1, 'bx_notes', '_bx_notes_page_block_title_note_author', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_notes\";s:6:\"method\";s:13:\"entity_author\";}', 0, 0, 0),
('bx_notes_view_note', 1, 'bx_notes', '_bx_notes_page_block_title_note_text', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_notes\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1),
('bx_notes_view_note', 1, 'bx_notes', '_bx_notes_page_block_title_note_social_sharing', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_notes\";s:6:\"method\";s:21:\"entity_social_sharing\";}', 0, 0, 2),
('bx_notes_view_note', 1, 'bx_notes', '_bx_notes_page_block_title_note_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_notes\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 3);


--
-- Dumping data for 'bx_notes_view_note_comments' page
-- 
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_view_note_comments', '_bx_notes_page_title_sys_view_note_comments', '_bx_notes_page_title_view_note_comments', 'bx_notes', 5, 2147483647, 1, 'view-note-comments', '', '', '', '', 0, 1, 0, 'BxNotesPageNote', 'modules/boonex/notes/classes/BxNotesPageNote.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_notes_view_note_comments', 1, 'bx_notes', '_bx_notes_page_block_title_note_comments', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_notes\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1);


--
-- Dumping data for 'bx_notes_home' page
-- 
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_home', '_bx_notes_page_title_sys_notes_home', '_bx_notes_page_title_notes_home', 'bx_notes', 5, 2147483647, 1, 'notes-home', 'page.php?i=notes-home', '', '', '', 0, 1, 0, 'BxNotesPageBrowse', 'modules/boonex/notes/classes/BxNotesPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_notes_home', 1, 'bx_notes', '_bx_notes_page_block_title_recent_notes', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_notes\";s:6:\"method\";s:13:\"browse_public\";}', 0, 1, 0);

--
-- Dumping data for 'bx_notes_featured' page
-- 
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_featured', '_bx_notes_page_title_sys_notes_featured', '_bx_notes_page_title_notes_featured', 'bx_notes', 5, 2147483647, 1, 'notes-featured', 'page.php?i=notes-featured', '', '', '', 0, 1, 0, 'BxNotesPageBrowse', 'modules/boonex/notes/classes/BxNotesPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_notes_featured', 1, 'bx_notes', '_bx_notes_page_block_title_featured_notes', 0, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_notes\";s:6:\"method\";s:15:\"browse_featured\";}', 0, 1, 1);

--
-- Dumping data for 'bx_notes_my' page
--
INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_my', 'notes-my', '_bx_notes_page_title_sys_notes_my', '_bx_notes_page_title_notes_my', 'bx_notes', 5, 254, 1, 'page.php?i=notes-my', '', '', '', 0, 1, 0, 'BxNotesPageBrowse', 'modules/boonex/notes/classes/BxNotesPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_notes_my', 1, 'bx_notes', '_bx_notes_page_block_title_my_notes', 0, 254, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_notes\";s:6:\"method\";s:9:\"browse_my\";}', 0, 1, 1);




-- MENU

SET @iSiteMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_site' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_site', 'bx_notes', 'notes-home', '_bx_notes_menu_item_title_system_notes_home', '_bx_notes_menu_item_title_notes_home', 'page.php?i=notes-home', '', '', 'file-text col-red3', 'bx_notes_submenu', 2147483647, 1, 1, IFNULL(@iSiteMenuOrder, 0) + 1);

SET @iAddMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_add_content_links' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_add_content_links', 'bx_notes', 'create-note', '_bx_notes_menu_item_title_system_create_note', '_bx_notes_menu_item_title_create_note', 'page.php?i=create-note', '', '', '', '', 2147483647, 1, 1, IFNULL(@iAddMenuOrder, 0) + 1);

--
-- Dumping data for 'bx_notes_view' menu
--
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_view', '_bx_notes_menu_title_view_note', 'bx_notes_view', 'bx_notes', 10, 0, 1, 'BxNotesMenuViewNote', 'modules/boonex/notes/classes/BxNotesMenuViewNote.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_notes_view', 'bx_notes', '_bx_notes_menu_set_title_view_note', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_notes_view', 'bx_notes', 'view-note', '_bx_notes_menu_item_title_system_view_note', '_bx_notes_menu_item_title_view_note', 'page.php?i=view-note&id={content_id}', '', '', 'eye-open', '', 0, 1, 0, 0),
('bx_notes_view', 'bx_notes', 'edit-note', '_bx_notes_menu_item_title_system_edit_note', '_bx_notes_menu_item_title_edit_note', 'page.php?i=edit-note&id={content_id}', '', '', 'pencil', '', 0, 1, 0, 1),
('bx_notes_view', 'bx_notes', 'delete-note', '_bx_notes_menu_item_title_system_delete_note', '_bx_notes_menu_item_title_delete_note', 'page.php?i=delete-note&id={content_id}', '', '', 'remove', '', 0, 1, 0, 2);

--
-- Dumping data for 'bx_notes_submenu' menu
--
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_submenu', '_bx_notes_menu_title_submenu', 'bx_notes_submenu', 'bx_notes', 8, 0, 1, '', '');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_notes_submenu', 'bx_notes', '_bx_notes_menu_set_title_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_notes_submenu', 'bx_notes', 'notes-home', '_bx_notes_menu_item_title_system_notes_public', '_bx_notes_menu_item_title_notes_public', 'page.php?i=notes-home', '', '', '', '', 2147483647, 1, 1, 1),
('bx_notes_submenu', 'bx_notes', 'notes-featured', '_bx_notes_menu_item_title_system_notes_featured', '_bx_notes_menu_item_title_notes_featured', 'page.php?i=notes-featured', '', '', '', '', 2147483647, 1, 1, 2),
('bx_notes_submenu', 'bx_notes', 'notes-my', '_bx_notes_menu_item_title_system_notes_my', '_bx_notes_menu_item_title_notes_my', 'page.php?i=notes-my', '', '', '', '', 254, 1, 1, 3);

--
-- Dumping data for 'bx_notes_view_submenu' menu
--
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notes_view_submenu', '_bx_notes_menu_title_view_note_submenu', 'bx_notes_view_submenu', 'bx_notes', 8, 0, 1, 'BxNotesMenuViewNote', 'modules/boonex/notes/classes/BxNotesMenuViewNote.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_notes_view_submenu', 'bx_notes', '_bx_notes_menu_set_title_view_note_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_notes_view_submenu', 'bx_notes', 'view-note', '_bx_notes_menu_item_title_system_view_note', '_bx_notes_menu_item_title_view_note_submenu_note', 'page.php?i=view-note&id={content_id}', '', '', '', '', 2147483647, 1, 0, 1),
('bx_notes_view_submenu', 'bx_notes', 'view-note-comments', '_bx_notes_menu_item_title_system_view_note_comments', '_bx_notes_menu_item_title_view_note_submenu_comments', 'page.php?i=view-note-comments&id={content_id}', '', '', '', '', 2147483647, 1, 0, 2);


-- PRIVACY 

INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_notes_allow_view_to', 'bx_notes', 'view', '_bx_notes_form_note_input_allow_view_to', '3', 'bx_notes_posts', 'id', 'author', '', '');


-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_notes', 'create note', NULL, '_bx_notes_acl_action_create_note', '', 1, 1);
SET @iIdActionNoteCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_notes', 'delete note', NULL, '_bx_notes_acl_action_delete_note', '', 1, 1);
SET @iIdActionNoteDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_notes', 'view note', NULL, '_bx_notes_acl_action_view_note', '', 1, 1);
SET @iIdActionNoteView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_notes', 'set thumb', NULL, '_bx_notes_acl_action_set_thumb', '', 1, 1);
SET @iIdActionSetThumb = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_notes', 'edit any note', NULL, '_bx_notes_acl_action_edit_any_note', '', 1, 1);
SET @iIdActionNoteEditAny = LAST_INSERT_ID();


SET @iUnauthenticated = 1;
SET @iStandard = 2;
SET @iUnconfirmed = 3;
SET @iPending = 4;
SET @iSuspended = 5;
SET @iModerator = 6;
SET @iAdministrator = 7;
SET @iPremium = 8;

INSERT INTO `sys_acl_matrix` (`IDLevel`, `IDAction`) VALUES

-- note create
(@iStandard, @iIdActionNoteCreate),
(@iModerator, @iIdActionNoteCreate),
(@iAdministrator, @iIdActionNoteCreate),
(@iPremium, @iIdActionNoteCreate),

-- note delete
(@iStandard, @iIdActionNoteDelete),
(@iModerator, @iIdActionNoteDelete),
(@iAdministrator, @iIdActionNoteDelete),
(@iPremium, @iIdActionNoteDelete),

-- note view
(@iUnauthenticated, @iIdActionNoteView),
(@iStandard, @iIdActionNoteView),
(@iUnconfirmed, @iIdActionNoteView),
(@iPending, @iIdActionNoteView),
(@iModerator, @iIdActionNoteView),
(@iAdministrator, @iIdActionNoteView),
(@iPremium, @iIdActionNoteView),

-- set note thumb
(@iStandard, @iIdActionSetThumb),
(@iModerator, @iIdActionSetThumb),
(@iAdministrator, @iIdActionSetThumb),
(@iPremium, @iIdActionSetThumb),

-- any note edit
(@iModerator, @iIdActionNoteEditAny),
(@iAdministrator, @iIdActionNoteEditAny);


-- COMMENTS

INSERT INTO `sys_objects_cmts` (`Name`, `TableCmts`, `TableTrack`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_notes', 'bx_notes_cmts', 'bx_notes_cmts_track', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page/view-note&id={object_id}', 'bx_notes_posts', 'id', 'title', 'comments', '', '');
