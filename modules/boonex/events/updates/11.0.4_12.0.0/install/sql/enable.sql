-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_events' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_events_per_page_for_favorites_lists', 'bx_events_datetime_format', 'bx_events_summary_chars', 'bx_events_members_mode', 'bx_events_internal_notifications');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_events_per_page_for_favorites_lists', '5', @iCategId, '_bx_events_option_per_page_for_favorites_lists', 'digit', '', '', '', '', 17),
('bx_events_datetime_format', 'j M Y H:i', @iCategId, '_bx_events_option_datetime_format', 'digit', '', '', '', '', 23),
('bx_events_summary_chars', '700', @iCategId, '_bx_events_option_summary_chars', 'digit', '', '', '', '', 25),
('bx_events_members_mode', '', @iCategId, '_bx_events_option_members_mode', 'select', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:24:"get_options_members_mode";}', '', '', '', 40),
('bx_events_internal_notifications', '', @iCategId, '_bx_events_option_internal_notifications', 'checkbox', '', '', '', '', 60);


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object`='bx_events_upcoming';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_upcoming', '_bx_events_page_title_sys_upcoming', '_bx_events_page_title_upcoming', 'bx_events', 5, 2147483647, 1, 'events-upcoming', 'page.php?i=events-upcoming', '', '', '', 0, 1, 0, 'BxEventsPageBrowse', 'modules/boonex/events/classes/BxEventsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_upcoming';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_upcoming', 1, 'bx_events', '', '_bx_events_page_block_title_upcoming_profiles', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:24:"browse_upcoming_profiles";s:6:"params";a:1:{i:0;a:1:{s:13:"empty_message";b:1;}}}', 0, 1, 0);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_events_joined' AND `title`='_bx_events_page_block_title_favorites_of_author';

DELETE FROM `sys_objects_page` WHERE `object`='bx_events_favorites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_events_favorites', '_bx_events_page_title_sys_entries_favorites', '_bx_events_page_title_entries_favorites', 'bx_events', 12, 2147483647, 1, 'events-favorites', 'page.php?i=events-favorites', '', '', '', 0, 1, 0, 'BxEventsPageListEntry', 'modules/boonex/events/classes/BxEventsPageListEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_events_favorites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_events_favorites', 2, 'bx_events', '', '_bx_events_page_block_title_favorites_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_events";s:6:"method";s:15:"browse_favorite";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', 0, 1, 1),
('bx_events_favorites', 3, 'bx_events', '', '_bx_events_page_block_title_favorites_entries_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:19:"favorites_list_info";}', 0, 0, 0),
('bx_events_favorites', 3, 'bx_events', '', '_bx_events_page_block_title_favorites_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_events";s:6:"method";s:22:"favorites_list_actions";}', 0, 0, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_view_actions_all' AND `name`='notes';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_view_actions_all', 'bx_events', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_submenu' AND `name`='events-upcoming';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_submenu', 'bx_events', 'events-upcoming', '_bx_events_menu_item_title_system_entries_upcoming', '_bx_events_menu_item_title_entries_upcoming', 'page.php?i=events-upcoming', '', '', '', '', '', '', 2147483647, '', 1, 1, 3);

UPDATE `sys_objects_menu` SET `template_id`='18' WHERE `object`='bx_events_view_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_menu_manage_tools' AND `name`='clear-reports';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_events_menu_manage_tools', 'bx_events', 'clear-reports', '_bx_cevents_menu_item_title_system_clear_reports', '_bx_events_menu_item_title_clear_reports', 'javascript:void(0)', 'javascript:{js_object}.onClickClearReports({content_id});', '_self', 'eraser', '', '', '', 2147483647, '', 1, 0, 3);


-- GRID
DELETE FROM `sys_grid_fields` WHERE `object`='bx_events_fans' AND `name`='role';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `params`, `order`) VALUES
('bx_events_fans', 'role', '_bx_events_txt_role', '10%', '', 15);

UPDATE `sys_grid_fields` SET `width`='40%' WHERE `object`='bx_events_fans' AND `name`='actions';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_events_fans' AND `name` IN ('to_admins', 'from_admins', 'set_role', 'set_role_submit');
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_events_fans', 'single', 'set_role', '_bx_events_txt_set_role', '', 0, 20),
('bx_events_fans', 'single', 'set_role_submit', '', '', 0, 21);

DELETE FROM `sys_grid_actions` WHERE `object`='bx_events_administration' AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_events_administration', 'bulk', 'clear_reports', '_bx_events_grid_action_title_adm_clear_reports', '', 0, 1, 3);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_events_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `spaces`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_events_allow_view_favorite_list', 'bx_events', 'view_favorite_list', '_bx_events_form_entry_input_allow_view_favorite_list', '3', '', 'bx_events_favorites_lists', 'id', 'author_id', 'BxEventsPrivacy', 'modules/boonex/events/classes/BxEventsPrivacy.php');

-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_events_set_role';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_events', '_bx_events_email_set_role', 'bx_events_set_role', '_bx_events_email_set_role_subject', '_bx_events_email_set_role_body');
