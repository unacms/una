-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_polls' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_polls_per_page_for_favorites_lists';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_polls_per_page_for_favorites_lists', '5', @iCategId, '_bx_polls_option_per_page_for_favorites_lists', 'digit', '',  '', '', '', 17);


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_polls";s:6:"method";s:21:"browse_favorite_lists";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}' WHERE `object`='bx_polls_author' AND `title`='_bx_polls_page_block_title_favorites_of_author';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_polls_author' AND `title`='_bx_polls_page_block_title_entries_in_context';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_polls_author', 1, 'bx_polls', '_bx_polls_page_block_title_sys_entries_in_context', '_bx_polls_page_block_title_entries_in_context', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_polls";s:6:"method";s:14:"browse_context";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";i:0;a:1:{s:13:"empty_message";b:0;}}}', 0, 0, 1, 4);

DELETE FROM `sys_objects_page` WHERE `object`='bx_polls_favorites';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_polls_favorites', '_bx_polls_page_title_sys_entries_favorites', '_bx_polls_page_title_entries_favorites', 'bx_polls', 12, 2147483647, 1, 'polls-favorites', 'page.php?i=polls-favorites', '', '', '', 0, 1, 0, 'BxPollsPageListEntry', 'modules/boonex/polls/classes/BxPollsPageListEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_polls_favorites';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_polls_favorites', 2, 'bx_polls', '', '_bx_polls_page_block_title_favorites_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_polls";s:6:"method";s:15:"browse_favorite";s:6:"params";a:2:{s:10:"profile_id";s:12:"{profile_id}";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:0;s:13:"ajax_paginate";b:0;}}}', 0, 1, 1),
('bx_polls_favorites', 3, 'bx_polls', '', '_bx_polls_page_block_title_favorites_entries_info', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_polls";s:6:"method";s:19:"favorites_list_info";}', 0, 0, 0),
('bx_polls_favorites', 3, 'bx_polls', '', '_bx_polls_page_block_title_favorites_entries_actions', 13, 2147483647, 'service', 'a:2:{s:6:"module";s:8:"bx_polls";s:6:"method";s:22:"favorites_list_actions";}', 0, 0, 1);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_polls_page_block_title_sys_my_entries' WHERE `module`='bx_polls' AND `title`='_bx_polls_page_block_title_my_entries';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_polls_view_actions' AND `name`='_sys_menu_item_title_va_notes';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_polls_view_actions', 'bx_polls', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', '', 0, 2147483647, '', 1, 0, 280);


-- PRIVACY 
DELETE FROM `sys_objects_privacy` WHERE `object`='bx_polls_allow_view_favorite_list';
INSERT INTO `sys_objects_privacy` (`object`, `module`, `action`, `title`, `default_group`, `table`, `table_field_id`, `table_field_author`, `override_class_name`, `override_class_file`) VALUES
('bx_polls_allow_view_favorite_list', 'bx_polls', 'view_favorite_list', '_bx_polls_form_entry_input_allow_view_favorite_list', '3', 'bx_polls_favorites_lists', 'id', 'author_id', '', '');


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_polls_administration' AND `type` IN ('bulk', 'single') AND `name`='clear_reports';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_polls_administration', 'bulk', 'clear_reports', '_bx_polls_grid_action_title_adm_clear_reports', '', 0, 1, 2),
('bx_polls_administration', 'single', 'clear_reports', '_bx_polls_grid_action_title_adm_clear_reports', 'eraser', 1, 0, 5);
