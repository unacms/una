-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_files' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_files_default_layout_mode', 'bx_files_allowed_ext');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_files_default_layout_mode', 'gallery', @iCategId, '_bx_files_option_default_layout_mode', 'select', '', '', '', 'gallery,table', 4),
('bx_files_allowed_ext', '', @iCategId, '_bx_files_option_allowed_ext', 'digit', '', '', '', '', 8);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_files_author' AND `title`='_bx_files_page_block_title_entries_actions';


-- MENUS
UPDATE `sys_menu_items` SET `title_system`='_bx_files_menu_item_title_system_edit_title', `title`='_bx_files_menu_item_title_edit_title' WHERE `set_name`='bx_files_view_inline' AND `name`='edit-title';
