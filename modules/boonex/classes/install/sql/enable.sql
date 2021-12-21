
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_classes', '_bx_classes', 'bx_classes@modules/boonex/classes/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_classes', '_bx_classes', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_classes_enable_auto_approve', 'on', @iCategId, '_bx_classes_option_enable_auto_approve', 'checkbox', '', '', '', 0),
('bx_classes_summary_chars', '700', @iCategId, '_bx_classes_option_summary_chars', 'digit', '', '', '', 1),
('bx_classes_plain_summary_chars', '240', @iCategId, '_bx_classes_option_plain_summary_chars', 'digit', '', '', '', 2),
('bx_classes_per_page_browse', '12', @iCategId, '_bx_classes_option_per_page_browse', 'digit', '', '', '', 10),
('bx_classes_per_page_profile', '6', @iCategId, '_bx_classes_option_per_page_profile', 'digit', '', '', '', 12),
('bx_classes_per_page_browse_showcase', '32', @iCategId, '_sys_option_per_page_browse_showcase', 'digit', '', '', '', 15),
('bx_classes_rss_num', '10', @iCategId, '_bx_classes_option_rss_num', 'digit', '', '', '', 20),
('bx_classes_searchable_fields', 'title,text', @iCategId, '_bx_classes_option_searchable_fields', 'list', '', '', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:21:"get_searchable_fields";}', 30),
('bx_classes_auto_activation_for_categories', 'on', @iCategId, '_bx_classes_option_auto_activation_for_categories', 'checkbox', '', '', '', 35);

-- PAGE: create entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_create_entry', '_bx_classes_page_title_sys_create_entry', '_bx_classes_page_title_create_entry', 'bx_classes', 5, 2147483647, 1, 'create-class', 'page.php?i=create-class', '', '', '', 0, 1, 0, 'BxClssPageBrowse', 'modules/boonex/classes/classes/BxClssPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_classes_create_entry', 1, 'bx_classes', '_bx_classes_page_block_title_create_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:13:"entity_create";}', 0, 1, 1);


-- PAGE: edit entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_edit_entry', '_bx_classes_page_title_sys_edit_entry', '_bx_classes_page_title_edit_entry', 'bx_classes', 5, 2147483647, 1, 'edit-class', '', '', '', '', 0, 1, 0, 'BxClssPageEntry', 'modules/boonex/classes/classes/BxClssPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_classes_edit_entry', 1, 'bx_classes', '_bx_classes_page_block_title_edit_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:11:"entity_edit";}', 0, 0, 0);


-- PAGE: delete entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_delete_entry', '_bx_classes_page_title_sys_delete_entry', '_bx_classes_page_title_delete_entry', 'bx_classes', 5, 2147483647, 1, 'delete-class', '', '', '', '', 0, 1, 0, 'BxClssPageEntry', 'modules/boonex/classes/classes/BxClssPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_classes_delete_entry', 1, 'bx_classes', '_bx_classes_page_block_title_delete_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:13:"entity_delete";}', 0, 0, 0);


-- PAGE: view entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_view_entry', '_bx_classes_page_title_sys_view_entry', '_bx_classes_page_title_view_entry', 'bx_classes', 12, 2147483647, 1, 'view-class', '', '', '', '', 0, 1, 0, 'BxClssPageEntry', 'modules/boonex/classes/classes/BxClssPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_entry_author', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:13:\"entity_author\";}', 0, 0, 0, 1),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_prev_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:10:"prev_class";}', 0, 0, 1, 2),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_entry_text', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 3),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_entry_all_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:18:\"entity_all_actions\";}', 0, 0, 1, 4),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_next_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:10:"next_class";}', 0, 0, 1, 5),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_entry_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 0, 6),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_entry_social_sharing', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:21:\"entity_social_sharing\";}', 0, 0, 0, 7),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_entry_attachments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:18:\"entity_attachments\";}', 0, 0, 1, 8),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_entry_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1, 9),

('bx_classes_view_entry', 3, 'bx_classes', '_bx_classes_page_block_title_sys_entry_context', '_bx_classes_page_block_title_entry_context', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:14:\"entity_context\";}', 0, 0, 1, 1),
('bx_classes_view_entry', 3, 'bx_classes', '', '_bx_classes_page_block_title_entry_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 2),
('bx_classes_view_entry', 3, 'bx_classes', '', '_bx_classes_page_block_title_entry_location', 3, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:13:\"locations_map\";s:6:\"params\";a:2:{i:0;s:10:\"bx_classes\";i:1;s:4:\"{id}\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 0, 0, 0, 3),
('bx_classes_view_entry', 3, 'bx_classes', '', '_bx_classes_page_block_title_entry_location', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:15:\"entity_location\";}', 0, 0, 1, 4),
('bx_classes_view_entry', 3, 'bx_classes', '', '_bx_classes_page_block_title_entry_polls', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:12:\"entity_polls\";}', 0, 0, 1, 5),
('bx_classes_view_entry', 3, 'bx_classes', '', '_bx_classes_page_block_title_entry_students_completed_class', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:31:"browse_students_completed_class";}', 0, 0, 1, 6),
('bx_classes_view_entry', 3, 'bx_classes', '', '_bx_classes_page_block_title_entry_students_not_completed_class', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:35:"browse_students_not_completed_class";}', 0, 0, 1, 7),
('bx_classes_view_entry', 2, 'bx_classes', '', '_bx_classes_page_block_title_entry_reports', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:14:\"entity_reports\";}', 0, 0, 1, 8);




-- PAGE: view entry comments

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_view_entry_comments', '_bx_classes_page_title_sys_view_entry_comments', '_bx_classes_page_title_view_entry_comments', 'bx_classes', 5, 2147483647, 1, 'view-class-comments', '', '', '', '', 0, 1, 0, 'BxClssPageEntry', 'modules/boonex/classes/classes/BxClssPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_classes_view_entry_comments', 1, 'bx_classes', '_bx_classes_page_block_title_entry_comments', '_bx_classes_page_block_title_entry_comments_link', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1);

-- PAGE: entries in context

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_context', 'classes-context', '_bx_classes_page_title_sys_entries_in_context', '_bx_classes_page_title_entries_in_context', 'bx_classes', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxClssPageAuthor', 'modules/boonex/classes/classes/BxClssPageAuthor.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_classes_context', 1, 'bx_classes', '', '_bx_classes_page_block_title_entries_current_classes_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_classes";s:6:"method";s:22:"browse_next_in_context";s:6:"params";a:2:{s:18:"context_profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 0, 1),
('bx_classes_context', 1, 'bx_classes', '_bx_classes_page_block_title_sys_entries_in_context', '_bx_classes_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_classes";s:6:"method";s:18:"classes_in_context";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 1, 2);

-- PAGE: module manage own
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_manage', '_bx_classes_page_title_sys_manage', '_bx_classes_page_title_manage', 'bx_classes', 5, 2147483647, 1, 'classes-manage', 'page.php?i=classes-manage', '', '', '', 0, 1, 0, 'BxClssPageBrowse', 'modules/boonex/classes/classes/BxClssPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_classes_manage', 1, 'bx_classes', '_bx_classes_page_block_title_system_manage', '_bx_classes_page_block_title_manage', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:12:\"manage_tools\";}}', 0, 1, 0);

-- PAGE: module manage all
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_administration', '_bx_classes_page_title_sys_manage_administration', '_bx_classes_page_title_manage', 'bx_classes', 5, 192, 1, 'classes-administration', 'page.php?i=classes-administration', '', '', '', 0, 1, 0, 'BxClssPageBrowse', 'modules/boonex/classes/classes/BxClssPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_classes_administration', 1, 'bx_classes', '_bx_classes_page_block_title_system_manage_administration', '_bx_classes_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:\"module\";s:10:\"bx_classes\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);

-- PAGES: add page block to profiles modules (trigger* page objects are processed separately upon modules enable/disable)
SET @iPBCellGroup = 4;
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('trigger_page_group_view_entry', @iPBCellGroup, 'bx_classes', '_bx_classes_page_block_title_group_entries_current', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_classes";s:6:"method";s:22:"browse_next_in_context";s:6:"params";a:2:{s:18:"context_profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 0, 0),
('trigger_page_group_view_entry', @iPBCellGroup, 'bx_classes', '_bx_classes_page_block_title_group_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:10:"bx_classes";s:6:"method";s:18:"classes_in_context";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 0, 0);

-- MENU: create post form attachments (link, photo, video, etc)

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_entry_attachments', '_bx_classes_menu_title_entry_attachments', 'bx_classes_entry_attachments', 'bx_classes', 23, 0, 1, 'BxClssMenuAttachments', 'modules/boonex/classes/classes/BxClssMenuAttachments.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_classes_entry_attachments', 'bx_classes', '_bx_classes_menu_set_title_entry_attachments', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_classes_entry_attachments', 'bx_classes', 'photo_simple', '_bx_classes_menu_item_title_system_cpa_photo_simple', '_bx_classes_menu_item_title_cpa_photo_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_photos_simple}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, '', 0, 0, 1, 1),
('bx_classes_entry_attachments', 'bx_classes', 'photo_html5', '_bx_classes_menu_item_title_system_cpa_photo_html5', '_bx_classes_menu_item_title_cpa_photo_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_photos_html5}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, '', 1, 0, 1, 2),
('bx_classes_entry_attachments', 'bx_classes', 'video_simple', '_bx_classes_menu_item_title_system_cpa_video_simple', '_bx_classes_menu_item_title_cpa_video_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_simple}.showUploaderForm();', '_self', 'video', '', '', 2147483647, '', 0, 0, 1, 3),
('bx_classes_entry_attachments', 'bx_classes', 'video_html5', '_bx_classes_menu_item_title_system_cpa_video_html5', '_bx_classes_menu_item_title_cpa_video_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_html5}.showUploaderForm();', '_self', 'video', '', '', 2147483647, '', 1, 0, 1, 4),
('bx_classes_entry_attachments', 'bx_classes', 'video_record_video', '_bx_classes_menu_item_title_system_cpa_video_record', '_bx_classes_menu_item_title_cpa_video_record', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_record_video}.showUploaderForm();', '_self', 'fas circle', '', '', 2147483647, '', 1, 0, 1, 5),
('bx_classes_entry_attachments', 'bx_classes', 'sound_simple', '_bx_classes_menu_item_title_system_cpa_sound_simple', '_bx_classes_menu_item_title_cpa_sound_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_sounds_simple}.showUploaderForm();', '_self', 'music', '', '', 2147483647, '', 0, 0, 1, 6),
('bx_classes_entry_attachments', 'bx_classes', 'sound_html5', '_bx_classes_menu_item_title_system_cpa_sound_html5', '_bx_classes_menu_item_title_cpa_sound_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_sounds_html5}.showUploaderForm();', '_self', 'music', '', '', 2147483647, '', 1, 0, 1, 7),
('bx_classes_entry_attachments', 'bx_classes', 'file_simple', '_bx_classes_menu_item_title_system_cpa_file_simple', '_bx_classes_menu_item_title_cpa_file_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_files_simple}.showUploaderForm();', '_self', 'file', '', '', 2147483647, '', 0, 0, 1, 8),
('bx_classes_entry_attachments', 'bx_classes', 'file_html5', '_bx_classes_menu_item_title_system_cpa_file_html5', '_bx_classes_menu_item_title_cpa_file_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_files_html5}.showUploaderForm();', '_self', 'file', '', '', 2147483647, '', 1, 0, 1, 9),
('bx_classes_entry_attachments', 'bx_classes', 'poll', '_bx_classes_menu_item_title_system_cpa_poll', '_bx_classes_menu_item_title_cpa_poll', 'javascript:void(0)', 'javascript:{js_object}.showPollForm(this);', '_self', 'tasks', '', '', 2147483647, '', 1, 0, 1, 9);

-- MENU: actions menu for view entry 

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_view', '_bx_classes_menu_title_view_entry', 'bx_classes_view', 'bx_classes', 9, 0, 1, 'BxClssMenuView', 'modules/boonex/classes/classes/BxClssMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_classes_view', 'bx_classes', '_bx_classes_menu_set_title_view_entry', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_classes_view', 'bx_classes', 'complete-class', '', '_bx_classes_menu_item_title_complete_class', 'javascript:void(0);', 'bx_classes_mark_as_completed(''{content_id}'');', '', 'check', '', 2147483647, 1, 0, 1),
('bx_classes_view', 'bx_classes', 'edit-class', '_bx_classes_menu_item_title_system_edit_entry', '_bx_classes_menu_item_title_edit_entry', 'page.php?i=edit-class&id={content_id}', '', '', 'pencil-alt', '', 2147483647, 1, 0, 2),
('bx_classes_view', 'bx_classes', 'delete-class', '_bx_classes_menu_item_title_system_delete_entry', '_bx_classes_menu_item_title_delete_entry', 'page.php?i=delete-class&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 3),
('bx_classes_view', 'bx_classes', 'approve', '_sys_menu_item_title_system_va_approve', '_sys_menu_item_title_va_approve', 'javascript:void(0)', 'javascript:bx_approve(this, ''{module_uri}'', {content_id});', '', 'check', '', 2147483647, 1, 0, 4);


-- MENU: all actions menu for view entry 

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_view_actions', '_sys_menu_title_view_actions', 'bx_classes_view_actions', 'bx_classes', 15, 0, 1, 'BxClssMenuViewActions', 'modules/boonex/classes/classes/BxClssMenuViewActions.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_classes_view_actions', 'bx_classes', '_sys_menu_set_title_view_actions', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_classes_view_actions', 'bx_classes', 'complete-class', '', '_bx_classes_menu_item_title_complete_class', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 1),
('bx_classes_view_actions', 'bx_classes', 'edit-class', '_bx_classes_menu_item_title_system_edit_entry', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 10),
('bx_classes_view_actions', 'bx_classes', 'delete-class', '_bx_classes_menu_item_title_system_delete_entry', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 20),
('bx_classes_view_actions', 'bx_classes', 'approve', '_sys_menu_item_title_system_va_approve', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 30),
('bx_classes_view_actions', 'bx_classes', 'set-badges', '_sys_menu_item_title_system_set_badges', '_sys_menu_item_title_set_badges', 'javascript:void(0)', 'bx_menu_popup(''sys_set_badges'', window, {}, {module: ''bx_classes'', content_id: {content_id}});', '', 'check-circle', '', '', 0, 2147483647, 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 40),
('bx_classes_view_actions', 'bx_classes', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 200),
('bx_classes_view_actions', 'bx_classes', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 210),
('bx_classes_view_actions', 'bx_classes', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 220),
('bx_classes_view_actions', 'bx_classes', 'reaction', '_sys_menu_item_title_system_va_reaction', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 225),
('bx_classes_view_actions', 'bx_classes', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 230),
('bx_classes_view_actions', 'bx_classes', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 240),
('bx_classes_view_actions', 'bx_classes', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 250),
('bx_classes_view_actions', 'bx_classes', 'repost', '_sys_menu_item_title_system_va_repost', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 260),
('bx_classes_view_actions', 'bx_classes', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 270),
('bx_classes_view_actions', 'bx_classes', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', 0, 2147483647, '', 1, 0, 280),
('bx_classes_view_actions', 'bx_classes', 'audit', '_sys_menu_item_title_system_va_audit', '_sys_menu_item_title_va_audit', 'page.php?i=dashboard-audit&module=bx_classes&content_id={content_id}', '', '', 'history', '', '', 0, 192, '', 1, 0, 290),
('bx_classes_view_actions', 'bx_classes', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, '', 1, 0, 300),
('bx_classes_view_actions', 'bx_classes', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, '', 1, 0, 9999);

-- MENU: sub-menu for view entry
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_view_submenu', '_bx_classes_menu_title_view_entry_submenu', 'bx_classes_view_submenu', 'bx_classes', 8, 0, 1, 'BxClssMenuView', 'modules/boonex/classes/classes/BxClssMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_classes_view_submenu', 'bx_classes', '_bx_classes_menu_set_title_view_entry_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_classes_view_submenu', 'bx_classes', 'view-class', '_bx_classes_menu_item_title_system_view_entry', '_bx_classes_menu_item_title_view_entry_submenu_entry', 'page.php?i=view-class&id={content_id}', '', '', '', '', 2147483647, 0, 0, 1),
('bx_classes_view_submenu', 'bx_classes', 'view-class-comments', '_bx_classes_menu_item_title_system_view_entry_comments', '_bx_classes_menu_item_title_view_entry_submenu_comments', 'page.php?i=view-class-comments&id={content_id}', '', '', '', '', 2147483647, 0, 0, 2);

-- MENU: custom menu for snippet meta info
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_snippet_meta', '_sys_menu_title_snippet_meta', 'bx_classes_snippet_meta', 'bx_classes', 15, 0, 1, 'BxClssMenuSnippetMeta', 'modules/boonex/classes/classes/BxClssMenuSnippetMeta.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_classes_snippet_meta', 'bx_classes', '_sys_menu_set_title_snippet_meta', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_classes_snippet_meta', 'bx_classes', 'date', '_sys_menu_item_title_system_sm_date', '_sys_menu_item_title_sm_date', '', '', '', '', '', 2147483647, 1, 0, 1, 1),
('bx_classes_snippet_meta', 'bx_classes', 'author', '_sys_menu_item_title_system_sm_author', '_sys_menu_item_title_sm_author', '', '', '', '', '', 2147483647, 0, 0, 1, 2),
('bx_classes_snippet_meta', 'bx_classes', 'category', '_sys_menu_item_title_system_sm_category', '_sys_menu_item_title_sm_category', '', '', '', '', '', 2147483647, 0, 0, 1, 3),
('bx_classes_snippet_meta', 'bx_classes', 'tags', '_sys_menu_item_title_system_sm_tags', '_sys_menu_item_title_sm_tags', '', '', '', '', '', 2147483647, 0, 0, 1, 4),
('bx_classes_snippet_meta', 'bx_classes', 'views', '_sys_menu_item_title_system_sm_views', '_sys_menu_item_title_sm_views', '', '', '', '', '', 2147483647, 0, 0, 1, 5),
('bx_classes_snippet_meta', 'bx_classes', 'comments', '_sys_menu_item_title_system_sm_comments', '_sys_menu_item_title_sm_comments', '', '', '', '', '', 2147483647, 0, 0, 1, 6),
('bx_classes_snippet_meta', 'bx_classes', 'date-start', '', '_bx_classes_menu_item_title_sm_date_start', '', '', '', '', '', 2147483647, 1, 0, 1, 7),
('bx_classes_snippet_meta', 'bx_classes', 'date-end', '', '_bx_classes_menu_item_title_sm_date_end', '', '', '', '', '', 2147483647, 1, 0, 1, 8);

-- MENU: profile stats
SET @iNotifMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name` = 'sys_profile_stats' AND `active` = 1 LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_profile_stats', 'bx_classes', 'profile-stats-manage-classes', '_bx_classes_menu_item_title_system_manage_my_classes', '_bx_classes_menu_item_title_manage_my_classes', 'page.php?i=classes-manage', '', '_self', 'file-alt col-red3', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:41:"get_menu_addon_manage_tools_profile_stats";}', '', 2147483646, 1, 0, @iNotifMenuOrder + 1);

-- MENU: manage tools submenu
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_menu_manage_tools', '_bx_classes_menu_title_manage_tools', 'bx_classes_menu_manage_tools', 'bx_classes', 6, 0, 1, 'BxClssMenuManageTools', 'modules/boonex/classes/classes/BxClssMenuManageTools.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_classes_menu_manage_tools', 'bx_classes', '_bx_classes_menu_set_title_manage_tools', 0);

--INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
--('bx_classes_menu_manage_tools', 'bx_classes', 'delete-with-content', '_bx_classes_menu_item_title_system_delete_with_content', '_bx_classes_menu_item_title_delete_with_content', 'javascript:void(0)', 'javascript:{js_object}.onClickDeleteWithContent({content_id});', '_self', 'far trash-alt', '', 128, 1, 0, 0);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_classes', 'classes-administration', '_bx_classes_menu_item_title_system_admt_classes', '_bx_classes_menu_item_title_admt_classes', 'page.php?i=classes-administration', '', '_self', 'file-alt', 'a:2:{s:6:"module";s:10:"bx_classes";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);

-- MENU: add menu item to profiles modules (trigger* menu sets are processed separately upon modules enable/disable)

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('trigger_group_view_submenu', 'bx_classes', 'classes-context', '_bx_classes_menu_item_title_system_view_entries_in_context', '_bx_classes_menu_item_title_view_entries_in_context', 'page.php?i=classes-context&profile_id={profile_id}', '', '', 'file-alt col-red3', '', 2147483647, 1, 0, 0);


-- PRIVACY 

INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_classes_allow_view_to', 'bx_classes', 'view', '_bx_classes_form_entry_input_allow_view_to', '3', 'bx_classes_classes', 'id', 'author', '', '');


-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_classes', 'create entry', NULL, '_bx_classes_acl_action_create_entry', '', 1, 3);
SET @iIdActionEntryCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_classes', 'delete entry', NULL, '_bx_classes_acl_action_delete_entry', '', 1, 3);
SET @iIdActionEntryDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_classes', 'view entry', NULL, '_bx_classes_acl_action_view_entry', '', 1, 0);
SET @iIdActionEntryView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_classes', 'set thumb', NULL, '_bx_classes_acl_action_set_thumb', '', 1, 3);
SET @iIdActionSetThumb = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_classes', 'edit any entry', NULL, '_bx_classes_acl_action_edit_any_entry', '', 1, 3);
SET @iIdActionEntryEditAny = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_classes', 'delete any entry', NULL, '_bx_classes_acl_action_delete_any_entry', '', 1, 3);
SET @iIdActionEntryDeleteAny = LAST_INSERT_ID();

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
(@iAccount, @iIdActionEntryView),
(@iStandard, @iIdActionEntryView),
(@iUnconfirmed, @iIdActionEntryView),
(@iPending, @iIdActionEntryView),
(@iModerator, @iIdActionEntryView),
(@iAdministrator, @iIdActionEntryView),
(@iPremium, @iIdActionEntryView),

-- set entry thumb
(@iStandard, @iIdActionSetThumb),
(@iModerator, @iIdActionSetThumb),
(@iAdministrator, @iIdActionSetThumb),
(@iPremium, @iIdActionSetThumb),

-- edit any entry
(@iModerator, @iIdActionEntryEditAny),
(@iAdministrator, @iIdActionEntryEditAny),

-- delete any entry
(@iAdministrator, @iIdActionEntryDeleteAny);


-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
('bx_classes', '_bx_classes', @iSearchOrder + 1, 'BxClssSearchResult', 'modules/boonex/classes/classes/BxClssSearchResult.php'),
('bx_classes_cmts', '_bx_classes_cmts', @iSearchOrder + 2, 'BxClssCmtsSearchResult', 'modules/boonex/classes/classes/BxClssCmtsSearchResult.php');

-- METATAGS
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_classes', 'bx_classes_meta_keywords', 'bx_classes_meta_locations', 'bx_classes_meta_mentions', '', '');

-- STATS
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_classes', 'bx_classes', '_bx_classes', 'page.php?i=classes-home', 'file-alt col-red3', 'SELECT COUNT(*) FROM `bx_classes_classes` WHERE 1 AND `status` = ''active'' AND `status_admin` = ''active''', @iMaxOrderStats + 1);

-- CHARTS
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_classes_growth', '_bx_classes_chart_growth', 'bx_classes_classes', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_classes_growth_speed', '_bx_classes_chart_growth_speed', 'bx_classes_classes', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');

-- GRIDS: moderation tools
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_classes_administration', 'Sql', 'SELECT * FROM `bx_classes_classes` WHERE 1 ', 'bx_classes_classes', 'id', 'added', 'status_admin', '', 20, NULL, 'start', '', 'title,text', '', 'like', 'reports', '', 192, 'BxClssGridAdministration', 'modules/boonex/classes/classes/BxClssGridAdministration.php'),
('bx_classes_common', 'Sql', 'SELECT * FROM `bx_classes_classes` WHERE 1 ', 'bx_classes_classes', 'id', 'added', 'status', '', 20, NULL, 'start', '', 'title,text', '', 'like', '', '', 2147483647, 'BxClssGridCommon', 'modules/boonex/classes/classes/BxClssGridCommon.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_classes_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_classes_administration', 'switcher', '_bx_classes_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_classes_administration', 'reports', '_sys_txt_reports_title', '5%', 0, '', '', 3),
('bx_classes_administration', 'title', '_bx_classes_grid_column_title_adm_title', '25%', 0, '25', '', 4),
('bx_classes_administration', 'added', '_bx_classes_grid_column_title_adm_added', '20%', 1, '25', '', 5),
('bx_classes_administration', 'author', '_bx_classes_grid_column_title_adm_author', '20%', 0, '25', '', 6),
('bx_classes_administration', 'actions', '', '20%', 0, '', '', 7),

('bx_classes_common', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_classes_common', 'switcher', '_bx_classes_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_classes_common', 'title', '_bx_classes_grid_column_title_adm_title', '40%', 0, '35', '', 3),
('bx_classes_common', 'added', '_bx_classes_grid_column_title_adm_added', '15%', 1, '25', '', 4),
('bx_classes_common', 'status_admin', '_bx_classes_grid_column_title_adm_status_admin', '15%', 0, '16', '', 5),
('bx_classes_common', 'actions', '', '20%', 0, '', '', 6);


INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_classes_administration', 'bulk', 'delete', '_bx_classes_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_classes_administration', 'bulk', 'clear_reports', '_bx_classes_grid_action_title_adm_clear_reports', '', 0, 1, 2),
('bx_classes_administration', 'single', 'edit', '_bx_classes_grid_action_title_adm_edit', 'pencil-alt', 1, 0, 1),
('bx_classes_administration', 'single', 'delete', '_bx_classes_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_classes_administration', 'single', 'settings', '_bx_classes_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),
('bx_classes_administration', 'single', 'audit_content', '_bx_classes_grid_action_title_adm_audit_content', 'search', 1, 0, 4),
('bx_classes_administration', 'single', 'clear_reports', '_bx_classes_grid_action_title_adm_clear_reports', 'eraser', 1, 0, 5),

('bx_classes_common', 'bulk', 'delete', '_bx_classes_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_classes_common', 'single', 'edit', '_bx_classes_grid_action_title_adm_edit', 'pencil-alt', 1, 0, 1),
('bx_classes_common', 'single', 'delete', '_bx_classes_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_classes_common', 'single', 'settings', '_bx_classes_grid_action_title_adm_more_actions', 'cog', 1, 0, 3);

-- UPLOADERS

INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_classes_simple', 1, 'BxClssUploaderSimple', 'modules/boonex/classes/classes/BxClssUploaderSimple.php'),
('bx_classes_html5', 1, 'BxClssUploaderHTML5', 'modules/boonex/classes/classes/BxClssUploaderHTML5.php'),
('bx_classes_record_video', 1, 'BxClssUploaderRecordVideo', 'modules/boonex/classes/classes/BxClssUploaderRecordVideo.php'),
('bx_classes_photos_simple', 1, 'BxClssUploaderSimpleAttach', 'modules/boonex/classes/classes/BxClssUploaderSimpleAttach.php'),
('bx_classes_photos_html5', 1, 'BxClssUploaderHTML5Attach', 'modules/boonex/classes/classes/BxClssUploaderHTML5Attach.php'),
('bx_classes_videos_simple', 1, 'BxClssUploaderSimpleAttach', 'modules/boonex/classes/classes/BxClssUploaderSimpleAttach.php'),
('bx_classes_videos_html5', 1, 'BxClssUploaderHTML5Attach', 'modules/boonex/classes/classes/BxClssUploaderHTML5Attach.php'),
('bx_classes_videos_record_video', 1, 'BxClssUploaderRecordVideoAttach', 'modules/boonex/classes/classes/BxClssUploaderRecordVideoAttach.php'),
('bx_classes_sounds_simple', 1, 'BxClssUploaderSimpleAttach', 'modules/boonex/classes/classes/BxClssUploaderSimpleAttach.php'),
('bx_classes_sounds_html5', 1, 'BxClssUploaderHTML5Attach', 'modules/boonex/classes/classes/BxClssUploaderHTML5Attach.php'),
('bx_classes_files_simple', 1, 'BxClssUploaderSimpleAttach', 'modules/boonex/classes/classes/BxClssUploaderSimpleAttach.php'),
('bx_classes_files_html5', 1, 'BxClssUploaderHTML5Attach', 'modules/boonex/classes/classes/BxClssUploaderHTML5Attach.php');

-- ALERTS

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_classes', 'BxClssAlertsResponse', 'modules/boonex/classes/classes/BxClssAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler),
('profile', 'delete', @iHandler),

('bx_classes', 'commentPost', @iHandler),

('bx_classes_videos_mp4', 'transcoded', @iHandler);


-- CRON
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_classes_publishing', '* * * * *', 'BxClssCronPublishing', 'modules/boonex/classes/classes/BxClssCronPublishing.php', '');
