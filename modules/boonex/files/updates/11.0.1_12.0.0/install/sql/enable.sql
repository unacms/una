-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_files' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_files_show_link_to_preview', 'bx_files_max_nesting_level', 'bx_files_max_bulk_download_size', 'bx_files_per_page_for_favorites_lists');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_files_show_link_to_preview', '', @iCategId, '_bx_files_option_link_to_preview', 'checkbox', '', '', '', '', 5),
('bx_files_max_nesting_level', '3', @iCategId, '_bx_files_option_max_nesting_level', 'digit', '', '', '', '', 6),
('bx_files_max_bulk_download_size', '100', @iCategId, '_bx_files_option_max_bulk_download_size', 'digit', '', '', '', '', 7),
('bx_files_per_page_for_favorites_lists', '5', @iCategId, '_bx_files_option_per_page_for_favorites_lists', 'digit', '', '', '', '', 17);


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_files_favorites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_files_favorites', '_bx_files_page_title_sys_entries_favorites', '_bx_files_page_title_entries_favorites', 'bx_files', 12, 2147483647, 1, 'files-favorites', 'page.php?i=files-favorites', '', '', '', 0, 1, 0, 'BxFilesPageListEntry', 'modules/boonex/files/classes/BxFilesPageListEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_files_favorites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_files_favorites', 2, 'bx_files', '', '_bx_files_page_block_title_favorites_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_files";s:6:"method";s:15:"browse_favorite";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', 0, 1, 1),
('bx_files_favorites', 3, 'bx_files', '', '_bx_files_page_block_title_favorites_entries_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_files";s:6:"method";s:19:"favorites_list_info";}', 0, 0, 0),
('bx_files_favorites', 3, 'bx_files', '', '_bx_files_page_block_title_favorites_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_files";s:6:"method";s:22:"favorites_list_actions";}', 0, 0, 1);


UPDATE `sys_pages_blocks` SET `title_system`='_bx_files_page_block_title_sys_my_entries', `content`='a:3:{s:6:"module";s:8:"bx_files";s:6:"method";s:13:"browse_author";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:3:{s:8:"per_page";s:25:"bx_files_per_page_profile";s:13:"empty_message";b:0;s:10:"no_toolbar";b:1;}}}' WHERE `module`='bx_files' AND `title`='_bx_files_page_block_title_my_entries';


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_files_view_inline';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_files_view_inline', '_bx_files_menu_title_view_inline', 'bx_files_view_inline', 'bx_files', 9, 0, 1, 'BxFilesMenuViewActionsInline', 'modules/boonex/files/classes/BxFilesMenuViewActions.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_files_view_inline';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES
('bx_files_view_inline', 'bx_files', '_bx_files_menu_set_title_view_inline', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_files_view_inline';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES
('bx_files_view_inline', 'bx_files', 'favorite', '_sys_menu_item_title_system_va_favorite', '_sys_menu_item_title_va_favorite', '', '', '', '', '', '', '', 2147483647, '', 0, 0, 1),
('bx_files_view_inline', 'bx_files', 'bookmark', '_bx_files_menu_item_title_system_bookmark', '_bx_files_menu_item_title_bookmark', '', '{js_object}.bookmark({content_id}, this); return false;', '', 'far star', '', '', '', 2147483647, '', 1, 0, 2),
('bx_files_view_inline', 'bx_files', 'download-file', '_bx_files_menu_item_title_system_download_file', '_bx_files_menu_item_title_download_file', 'modules/?r=files/download/{file_download_token}/{content_id}.{file_ext}', '', '_blank', 'download', '', '', '', 2147483647, '', 1, 0, 3),
('bx_files_view_inline', 'bx_files', 'delete-file-quick', '_bx_files_menu_item_title_system_delete_entry', '_bx_files_menu_item_title_delete_entry', 'page.php?i=delete-file&id={content_id}', '{js_object}.delete({content_id}); return false;', '', 'remove', '', '', '', 2147483647, '', 1, 0, 4),
('bx_files_view_inline', 'bx_files', 'move-to', '_bx_files_menu_item_title_system_move_to_entry', '_bx_files_menu_item_title_move_to_entry', '', '{js_object}.moveTo({content_id}); return false;', '', 'file-export', '', '', '', 2147483647, '', 1, 0, 5),
('bx_files_view_inline', 'bx_files', 'edit-title', '_bx_files_menu_item_title_system_edit_entry', '_bx_files_menu_item_title_edit_entry', 'page.php?i=edit-file&id={content_id}', '{js_object}.edit({content_id}); return false;', '', 'pencil-alt', '', '', '', 2147483647, '', 1, 0, 6),
('bx_files_view_inline', 'bx_files', 'preview', '_bx_files_menu_item_title_system_info_entry', '_bx_files_menu_item_title_info_entry', '', '{js_object}.info({content_id}); return false;', '', 'info', '', '', '', 2147483647, '', 1, 0, 7),
('bx_files_view_inline', 'bx_files', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', '', 2147483647, '', 1, 0, 8),
('bx_files_view_inline', 'bx_files', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', '', 2147483647, '', 1, 0, 9999);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_files_snippet_meta' AND `name`='size';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_files_snippet_meta', 'bx_files', 'size', '_bx_files_menu_item_title_system_sm_size', '_bx_files_menu_item_title_sm_size', '', '', '', '', '', '', '', 2147483647, '', 1, 0, 1, 2);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_files_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_files_allow_view_favorite_list', 'bx_files', 'view_favorite_list', '_bx_files_form_entry_input_allow_view_favorite_list', '3', 'bx_files_favorites_lists', 'id', 'author_id', 'BxFilesPrivacy', 'modules/boonex/files/classes/BxFilesPrivacy.php');


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_files_administration' AND `type` IN ('bulk', 'single') AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_files_administration', 'bulk', 'clear_reports', '_bx_files_grid_action_title_adm_clear_reports', '', 0, 1, 2),
('bx_files_administration', 'single', 'clear_reports', '_bx_files_grid_action_title_adm_clear_reports', 'eraser', 1, 0, 5);
