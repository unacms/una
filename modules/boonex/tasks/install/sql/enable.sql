
-- SETTINGS

SET @iTypeOrder = (SELECT MAX(`order`) FROM `sys_options_types` WHERE `group` = 'modules');
INSERT INTO `sys_options_types`(`group`, `name`, `caption`, `icon`, `order`) VALUES 
('modules', 'bx_tasks', '_bx_tasks', 'bx_tasks@modules/boonex/tasks/|std-icon.svg', IF(ISNULL(@iTypeOrder), 1, @iTypeOrder + 1));
SET @iTypeId = LAST_INSERT_ID();

INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order`)
VALUES (@iTypeId, 'bx_tasks', '_bx_tasks', 1);
SET @iCategId = LAST_INSERT_ID();

INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_tasks_summary_chars', '700', @iCategId, '_bx_tasks_option_summary_chars', 'digit', '', '', '', 1),
('bx_tasks_plain_summary_chars', '240', @iCategId, '_bx_tasks_option_plain_summary_chars', 'digit', '', '', '', 2),
('bx_tasks_per_page_browse', '12', @iCategId, '_bx_tasks_option_per_page_browse', 'digit', '', '', '', 10),
('bx_tasks_per_page_profile', '6', @iCategId, '_bx_tasks_option_per_page_profile', 'digit', '', '', '', 12),
('bx_tasks_per_page_browse_showcase', '32', @iCategId, '_sys_option_per_page_browse_showcase', 'digit', '', '', '', 15),
('bx_tasks_rss_num', '10', @iCategId, '_bx_tasks_option_rss_num', 'digit', '', '', '', 20),
('bx_tasks_searchable_fields', 'title,text', @iCategId, '_bx_tasks_option_searchable_fields', 'list', '', '', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:21:"get_searchable_fields";}', 30),
('bx_tasks_auto_activation_for_categories', 'on', @iCategId, '_bx_tasks_option_auto_activation_for_categories', 'checkbox', '', '', '', 35);

-- PAGE: create entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_create_entry', '_bx_tasks_page_title_sys_create_entry', '_bx_tasks_page_title_create_entry', 'bx_tasks', 5, 2147483647, 1, 'create-task', 'page.php?i=create-task', '', '', '', 0, 1, 0, 'BxTasksPageBrowse', 'modules/boonex/tasks/classes/BxTasksPageBrowse.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_tasks_create_entry', 1, 'bx_tasks', '_bx_tasks_page_block_title_create_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:13:"entity_create";}', 0, 1, 1);


-- PAGE: edit entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_edit_entry', '_bx_tasks_page_title_sys_edit_entry', '_bx_tasks_page_title_edit_entry', 'bx_tasks', 5, 2147483647, 1, 'edit-task', '', '', '', '', 0, 1, 0, 'BxTasksPageEntry', 'modules/boonex/tasks/classes/BxTasksPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_tasks_edit_entry', 1, 'bx_tasks', '_bx_tasks_page_block_title_edit_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:11:"entity_edit";}', 0, 0, 0);


-- PAGE: delete entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_delete_entry', '_bx_tasks_page_title_sys_delete_entry', '_bx_tasks_page_title_delete_entry', 'bx_tasks', 5, 2147483647, 1, 'delete-task', '', '', '', '', 0, 1, 0, 'BxTasksPageEntry', 'modules/boonex/tasks/classes/BxTasksPageEntry.php');

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_tasks_delete_entry', 1, 'bx_tasks', '_bx_tasks_page_block_title_delete_entry', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:13:"entity_delete";}', 0, 0, 0);


-- PAGE: view entry

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_view_entry', '_bx_tasks_page_title_sys_view_entry', '_bx_tasks_page_title_view_entry', 'bx_tasks', 12, 2147483647, 1, 'view-task', '', '', '', '', 0, 1, 0, 'BxTasksPageEntry', 'modules/boonex/tasks/classes/BxTasksPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_tasks_view_entry', 2, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_text', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:17:\"entity_text_block\";}', 0, 0, 1, 2),
('bx_tasks_view_entry', 2, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_author', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:13:\"entity_author\";}', 0, 0, 1, 1),
('bx_tasks_view_entry', 2, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_assignments', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:18:\"entity_assignments\";}', 0, 0, 1, 2),
('bx_tasks_view_entry', 3, 'bx_tasks', '_bx_tasks_page_block_title_sys_entry_context', '_bx_tasks_page_block_title_entry_context', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:14:\"entity_context\";}', 0, 0, 1, 1),
('bx_tasks_view_entry', 3, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 2),
('bx_tasks_view_entry', 2, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_all_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:18:\"entity_all_actions\";}', 0, 0, 1, 3),
('bx_tasks_view_entry', 4, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 0, 0),
('bx_tasks_view_entry', 4, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_social_sharing', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:21:\"entity_social_sharing\";}', 0, 0, 0, 0),
('bx_tasks_view_entry', 2, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_attachments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:18:\"entity_attachments\";}', 0, 0, 1, 4),
('bx_tasks_view_entry', 2, 'bx_tasks', '', '_bx_tasks_page_block_title_entry_comments', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1, 6),
('bx_tasks_view_entry', 3, 'bx_tasks', '', '_bx_tasks_page_block_title_featured_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 0, 1, 5);


-- PAGE: view entry comments

INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_view_entry_comments', '_bx_tasks_page_title_sys_view_entry_comments', '_bx_tasks_page_title_view_entry_comments', 'bx_tasks', 5, 2147483647, 1, 'view-task-comments', '', '', '', '', 0, 1, 0, 'BxTasksPageEntry', 'modules/boonex/tasks/classes/BxTasksPageEntry.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_view_entry_comments', 1, 'bx_tasks', '_bx_tasks_page_block_title_entry_comments', '_bx_tasks_page_block_title_entry_comments_link', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:15:\"entity_comments\";}', 0, 0, 1);

-- PAGE: entries in context

INSERT INTO `sys_objects_page`(`object`, `uri`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_context', 'tasks-context', '_bx_tasks_page_title_sys_entries_in_context', '_bx_tasks_page_title_entries_in_context', 'bx_tasks', 5, 2147483647, 1, '', '', '', '', 0, 1, 0, 'BxTasksPageAuthor', 'modules/boonex/tasks/classes/BxTasksPageAuthor.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_tasks_context', 1, 'bx_tasks', '_bx_tasks_page_block_title_sys_entries_in_context', '_bx_tasks_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:14:\"browse_context\";}', 0, 0, 1, 1),
('bx_tasks_context', 1, 'bx_tasks', '_bx_tasks_page_block_title_sys_calendar_in_context', '_bx_tasks_page_block_title_calendar_in_context', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:8:"calendar";s:12:"ignore_cache";b:1;s:6:"params";a:1:{i:0;a:1:{s:10:"context_id";s:12:"{profile_id}";}}}', 0, 0, 1, 2);

-- PAGE: module manage all
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_administration', '_bx_tasks_page_title_sys_manage_administration', '_bx_tasks_page_title_manage', 'bx_tasks', 5, 192, 1, 'tasks-administration', 'page.php?i=tasks-administration', '', '', '', 0, 1, 0, 'BxTasksPageBrowse', 'modules/boonex/tasks/classes/BxTasksPageBrowse.php');

INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_tasks_administration', 1, 'bx_tasks', '_bx_tasks_page_block_title_system_manage_administration', '_bx_tasks_page_block_title_manage', 11, 192, 'service', 'a:3:{s:6:\"module\";s:8:\"bx_tasks\";s:6:\"method\";s:12:\"manage_tools\";s:6:\"params\";a:1:{i:0;s:14:\"administration\";}}', 0, 1, 0);


-- MENU: create task form attachments (link, photo, video, etc)

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_entry_attachments', '_bx_tasks_menu_title_entry_attachments', 'bx_tasks_entry_attachments', 'bx_tasks', 23, 0, 1, 'BxTasksMenuAttachments', 'modules/boonex/tasks/classes/BxTasksMenuAttachments.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_entry_attachments', 'bx_tasks', '_bx_tasks_menu_set_title_entry_attachments', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_tasks_entry_attachments', 'bx_tasks', 'photo_simple', '_bx_tasks_menu_item_title_system_cpa_photo_simple', '_bx_tasks_menu_item_title_cpa_photo_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_photos_simple}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, '', 0, 0, 1, 1),
('bx_tasks_entry_attachments', 'bx_tasks', 'photo_html5', '_bx_tasks_menu_item_title_system_cpa_photo_html5', '_bx_tasks_menu_item_title_cpa_photo_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_photos_html5}.showUploaderForm();', '_self', 'camera', '', '', 2147483647, '', 1, 0, 1, 2),
('bx_tasks_entry_attachments', 'bx_tasks', 'video_simple', '_bx_tasks_menu_item_title_system_cpa_video_simple', '_bx_tasks_menu_item_title_cpa_video_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_simple}.showUploaderForm();', '_self', 'video', '', '', 2147483647, '', 0, 0, 1, 3),
('bx_tasks_entry_attachments', 'bx_tasks', 'video_html5', '_bx_tasks_menu_item_title_system_cpa_video_html5', '_bx_tasks_menu_item_title_cpa_video_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_videos_html5}.showUploaderForm();', '_self', 'video', '', '', 2147483647, '', 1, 0, 1, 4),
('bx_tasks_entry_attachments', 'bx_tasks', 'file_simple', '_bx_tasks_menu_item_title_system_cpa_file_simple', '_bx_tasks_menu_item_title_cpa_file_simple', 'javascript:void(0)', 'javascript:{js_object_uploader_files_simple}.showUploaderForm();', '_self', 'file', '', '', 2147483647, '', 0, 0, 1, 5),
('bx_tasks_entry_attachments', 'bx_tasks', 'file_html5', '_bx_tasks_menu_item_title_system_cpa_file_html5', '_bx_tasks_menu_item_title_cpa_file_html5', 'javascript:void(0)', 'javascript:{js_object_uploader_files_html5}.showUploaderForm();', '_self', 'file', '', '', 2147483647, '', 1, 0, 1, 6);

-- MENU: actions menu for view entry 

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_view', '_bx_tasks_menu_title_view_entry', 'bx_tasks_view', 'bx_tasks', 9, 0, 1, 'BxTasksMenuView', 'modules/boonex/tasks/classes/BxTasksMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_view', 'bx_tasks', '_bx_tasks_menu_set_title_view_entry', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_view', 'bx_tasks', 'edit-task', '_bx_tasks_menu_item_title_system_edit_entry', '_bx_tasks_menu_item_title_edit_entry', 'page.php?i=edit-task&id={content_id}', '', '', 'pencil-alt', '', 2147483647, 1, 0, 1),
('bx_tasks_view', 'bx_tasks', 'delete-task', '_bx_tasks_menu_item_title_system_delete_entry', '_bx_tasks_menu_item_title_delete_entry', 'page.php?i=delete-task&id={content_id}', '', '', 'remove', '', 2147483647, 1, 0, 2);


-- MENU: all actions menu for view entry 

INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_view_actions', '_sys_menu_title_view_actions', 'bx_tasks_view_actions', 'bx_tasks', 15, 0, 1, 'BxTasksMenuViewActions', 'modules/boonex/tasks/classes/BxTasksMenuViewActions.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_view_actions', 'bx_tasks', '_sys_menu_set_title_view_actions', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_view_actions', 'bx_tasks', 'edit-task', '_bx_tasks_menu_item_title_system_edit_entry', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 10),
('bx_tasks_view_actions', 'bx_tasks', 'delete-task', '_bx_tasks_menu_item_title_system_delete_entry', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 20),
('bx_tasks_view_actions', 'bx_tasks', 'set-completed', '_bx_tasks_menu_item_title_system_set_completed', '_bx_tasks_menu_item_title_set_completed', 'javascript:void(0)', 'javascript:{js_object}.setCompletedByMenu({content_id}, 1, this);', '', 'check', '', '', 0, 2147483647, 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:12:"is_completed";s:6:"params";a:1:{i:0;s:12:"{content_id}";}}', 1, 0, 30),
('bx_tasks_view_actions', 'bx_tasks', 'set-uncompleted', '_bx_tasks_menu_item_title_system_set_uncompleted', '_bx_tasks_menu_item_title_set_uncompleted', 'javascript:void(0)', 'javascript:{js_object}.setCompletedByMenu({content_id}, 0, this);', '', 'circle', '', '', 0, 2147483647, 'a:3:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:14:"is_uncompleted";s:6:"params";a:1:{i:0;s:12:"{content_id}";}}', 1, 0, 35),
('bx_tasks_view_actions', 'bx_tasks', 'set-badges', '_sys_menu_item_title_system_set_badges', '_sys_menu_item_title_set_badges', 'javascript:void(0)', 'bx_menu_popup(''sys_set_badges'', window, {}, {module: ''bx_tasks'', content_id: {content_id}});', '', 'check-circle', '', '', 0, 2147483647, 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:19:"is_badges_avaliable";}', 1, 0, 40),
('bx_tasks_view_actions', 'bx_tasks', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 200),
('bx_tasks_view_actions', 'bx_tasks', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 210),
('bx_tasks_view_actions', 'bx_tasks', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, '', 0, 0, 220),
('bx_tasks_view_actions', 'bx_tasks', 'reaction', '_sys_menu_item_title_system_va_reaction', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 225),
('bx_tasks_view_actions', 'bx_tasks', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 230),
('bx_tasks_view_actions', 'bx_tasks', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 240),
('bx_tasks_view_actions', 'bx_tasks', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 250),
('bx_tasks_view_actions', 'bx_tasks', 'repost', '_sys_menu_item_title_system_va_repost', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 260),
('bx_tasks_view_actions', 'bx_tasks', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 270),
('bx_tasks_view_actions', 'bx_tasks', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', 0, 2147483647, '', 1, 0, 280),
('bx_tasks_view_actions', 'bx_tasks', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 300),
('bx_tasks_view_actions', 'bx_tasks', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 320),
('bx_tasks_view_actions', 'bx_tasks', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 330),
('bx_tasks_view_actions', 'bx_tasks', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, '', 1, 0, 9999);


-- MENU: sub-menu for view entry
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_view_submenu', '_bx_tasks_menu_title_view_entry_submenu', 'bx_tasks_view_submenu', 'bx_tasks', 8, 0, 1, 'BxTasksMenuView', 'modules/boonex/tasks/classes/BxTasksMenuView.php');

INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_tasks_view_submenu', 'bx_tasks', '_bx_tasks_menu_set_title_view_entry_submenu', 0);

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_tasks_view_submenu', 'bx_tasks', 'view-task', '_bx_tasks_menu_item_title_system_view_entry', '_bx_tasks_menu_item_title_view_entry_submenu_entry', 'page.php?i=view-task&id={content_id}', '', '', '', '', 2147483647, 0, 0, 1),
('bx_tasks_view_submenu', 'bx_tasks', 'view-task-comments', '_bx_tasks_menu_item_title_system_view_entry_comments', '_bx_tasks_menu_item_title_view_entry_submenu_comments', 'page.php?i=view-task-comments&id={content_id}', '', '', '', '', 2147483647, 0, 0, 2);

-- MENU: dashboard manage tools
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_tasks', 'tasks-administration', '_bx_tasks_menu_item_title_system_admt_tasks', '_bx_tasks_menu_item_title_admt_tasks', 'page.php?i=tasks-administration', '', '_self', 'tasks', 'a:2:{s:6:"module";s:8:"bx_tasks";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', 192, 1, 0, @iManageMenuOrder + 1);

-- MENU: add menu item to profiles modules (trigger* menu sets are processed separately upon modules enable/disable)

INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('trigger_group_view_submenu', 'bx_tasks', 'tasks-context', '_bx_tasks_menu_item_title_system_view_entries_in_context', '_bx_tasks_menu_item_title_view_entries_in_context', 'page.php?i=tasks-context&profile_id={profile_id}', '', '', 'tasks col-red3', '', 2147483647, 1, 0, 0);


-- PRIVACY 

INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_allow_view_to', 'bx_tasks', 'view', '_bx_tasks_form_entry_input_allow_view_to', '3', 'bx_tasks_tasks', 'id', 'author', '', '');


-- ACL

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_tasks', 'create entry', NULL, '_bx_tasks_acl_action_create_entry', '', 1, 3);
SET @iIdActionEntryCreate = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_tasks', 'delete entry', NULL, '_bx_tasks_acl_action_delete_entry', '', 1, 3);
SET @iIdActionEntryDelete = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_tasks', 'view entry', NULL, '_bx_tasks_acl_action_view_entry', '', 1, 0);
SET @iIdActionEntryView = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_tasks', 'set thumb', NULL, '_bx_tasks_acl_action_set_thumb', '', 1, 3);
SET @iIdActionSetThumb = LAST_INSERT_ID();

INSERT INTO `sys_acl_actions` (`Module`, `Name`, `AdditionalParamName`, `Title`, `Desc`, `Countable`, `DisabledForLevels`) VALUES
('bx_tasks', 'edit any entry', NULL, '_bx_tasks_acl_action_edit_any_entry', '', 1, 3);
SET @iIdActionEntryEditAny = LAST_INSERT_ID();

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
(@iAdministrator, @iIdActionEntryEditAny);


-- SEARCH
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `ClassName`, `ClassPath`) VALUES
('bx_tasks', '_bx_tasks', @iSearchOrder + 1, 'BxTasksSearchResult', 'modules/boonex/tasks/classes/BxTasksSearchResult.php'),
('bx_tasks_cmts', '_bx_tasks_cmts', @iSearchOrder + 2, 'BxTasksCmtsSearchResult', 'modules/boonex/tasks/classes/BxTasksCmtsSearchResult.php');

-- CONNECTIONS
INSERT INTO `sys_objects_connection` (`object`, `table`, `type`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_assignments', 'bx_tasks_assignments', 'one-way', '', '');

-- CATEGORY
INSERT INTO `sys_objects_category` (`object`, `search_object`, `form_object`, `list_name`, `table`, `field`, `join`, `where`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_cats', 'bx_tasks', 'bx_tasks', 'bx_tasks_cats', 'bx_tasks_tasks', 'cat', 'INNER JOIN `sys_profiles` ON (`sys_profiles`.`id` = ABS(`bx_tasks_tasks`.`author`))', 'AND `sys_profiles`.`status` = ''active''', '', '');

-- STATS
SET @iMaxOrderStats = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_statistics`);
INSERT INTO `sys_statistics` (`module`, `name`, `title`, `link`, `icon`, `query`, `order`) VALUES 
('bx_tasks', 'bx_tasks', '_bx_tasks', 'page.php?i=tasks-home', 'tasks col-red3', 'SELECT COUNT(*) FROM `bx_tasks_tasks` WHERE 1 AND `status` = ''active'' AND `status_admin` = ''active''', @iMaxOrderStats + 1);

-- CHARTS
SET @iMaxOrderCharts = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_objects_chart`);
INSERT INTO `sys_objects_chart` (`object`, `title`, `table`, `field_date_ts`, `field_date_dt`, `field_status`, `query`, `active`, `order`, `class_name`, `class_file`) VALUES
('bx_tasks_growth', '_bx_tasks_chart_growth', 'bx_tasks_tasks', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 1, 'BxDolChartGrowth', ''),
('bx_tasks_growth_speed', '_bx_tasks_chart_growth_speed', 'bx_tasks_tasks', 'added', '', 'status,status_admin', '', 1, @iMaxOrderCharts + 2, 'BxDolChartGrowthSpeed', '');

-- GRIDS: moderation tools
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `visible_for_levels`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_administration', 'Sql', 'SELECT * FROM `bx_tasks_tasks` WHERE 1 ', 'bx_tasks_tasks', 'id', 'added', 'status_admin', '', 20, NULL, 'start', '', 'title,text', '', 'like', 'reports', '', 192, 'BxTasksGridAdministration', 'modules/boonex/tasks/classes/BxTasksGridAdministration.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_tasks_administration', 'checkbox', '_sys_select', '2%', 0, '', '', 1),
('bx_tasks_administration', 'switcher', '_bx_tasks_grid_column_title_adm_active', '8%', 0, '', '', 2),
('bx_tasks_administration', 'reports', '_sys_txt_reports_title', '5%', 0, '', '', 3),
('bx_tasks_administration', 'title', '_bx_tasks_grid_column_title_adm_title', '25%', 0, '25', '', 4),
('bx_tasks_administration', 'added', '_bx_tasks_grid_column_title_adm_added', '20%', 1, '25', '', 5),
('bx_tasks_administration', 'author', '_bx_tasks_grid_column_title_adm_author', '20%', 0, '25', '', 6),
('bx_tasks_administration', 'actions', '', '20%', 0, '', '', 7);


INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_tasks_administration', 'bulk', 'delete', '_bx_tasks_grid_action_title_adm_delete', '', 0, 1, 1),
('bx_tasks_administration', 'single', 'edit', '_bx_tasks_grid_action_title_adm_edit', 'pencil-alt', 1, 0, 1),
('bx_tasks_administration', 'single', 'delete', '_bx_tasks_grid_action_title_adm_delete', 'remove', 1, 1, 2),
('bx_tasks_administration', 'single', 'settings', '_bx_tasks_grid_action_title_adm_more_actions', 'cog', 1, 0, 3),
('bx_tasks_administration', 'single', 'audit_content', '_bx_tasks_grid_action_title_adm_audit_content', 'search', 1, 0, 4);

-- UPLOADERS

INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_tasks_simple', 1, 'BxTasksUploaderSimple', 'modules/boonex/tasks/classes/BxTasksUploaderSimple.php'),
('bx_tasks_html5', 1, 'BxTasksUploaderHTML5', 'modules/boonex/tasks/classes/BxTasksUploaderHTML5.php'),
('bx_tasks_photos_simple', 1, 'BxTasksUploaderSimpleAttach', 'modules/boonex/tasks/classes/BxTasksUploaderSimpleAttach.php'),
('bx_tasks_photos_html5', 1, 'BxTasksUploaderHTML5Attach', 'modules/boonex/tasks/classes/BxTasksUploaderHTML5Attach.php'),
('bx_tasks_videos_simple', 1, 'BxTasksUploaderSimpleAttach', 'modules/boonex/tasks/classes/BxTasksUploaderSimpleAttach.php'),
('bx_tasks_videos_html5', 1, 'BxTasksUploaderHTML5Attach', 'modules/boonex/tasks/classes/BxTasksUploaderHTML5Attach.php'),
('bx_tasks_files_simple', 1, 'BxTasksUploaderSimpleAttach', 'modules/boonex/tasks/classes/BxTasksUploaderSimpleAttach.php'),
('bx_tasks_files_html5', 1, 'BxTasksUploaderHTML5Attach', 'modules/boonex/tasks/classes/BxTasksUploaderHTML5Attach.php');

-- ALERTS

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
('bx_tasks', 'BxTasksAlertsResponse', 'modules/boonex/tasks/classes/BxTasksAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler),
('profile', 'delete', @iHandler),

('bx_tasks_videos_mp4', 'transcoded', @iHandler);


-- CRON
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_tasks_publishing', '* * * * *', 'BxTasksCronPublishing', 'modules/boonex/tasks/classes/BxTasksCronPublishing.php', '');
