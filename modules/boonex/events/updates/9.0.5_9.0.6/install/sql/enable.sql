-- SETTINGS
UPDATE `sys_options` SET `value`='6' WHERE `name`='bx_events_num_connections_quick';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_events' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_events_per_page_browse_showcase';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_events_per_page_browse_showcase', '32', @iCategId, '_sys_option_per_page_browse_showcase', 'digit', '', '', '', 15);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_events' AND `title` IN ('_bx_events_page_block_title_featured_entries_view_showcase');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('', 0, 'bx_events', '_bx_events_page_block_title_sys_featured_entries_view_showcase', '_bx_events_page_block_title_featured_entries_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_events\";s:6:\"method\";s:15:\"browse_featured\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"showcase\";s:13:\"empty_message\";b:0;s:13:\"ajax_paginate\";b:0;}}', 0, 1, IFNULL(@iBlockOrder, 0) + 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_events_snippet_meta' AND `name` IN ('country', 'country-city');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES
('bx_events_snippet_meta', 'bx_events', 'country', '_sys_menu_item_title_system_sm_country', '_sys_menu_item_title_sm_country', '', '', '', '', '', 2147483647, 0, 0, 1, 11),
('bx_events_snippet_meta', 'bx_events', 'country-city', '_sys_menu_item_title_system_sm_country_city', '_sys_menu_item_title_sm_country_city', '', '', '', '', '', 2147483647, 0, 0, 1, 12);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Nl2br`='0' WHERE `Name`='bx_events';


-- METATAGS
UPDATE `sys_objects_metatags` SET `table_mentions`='bx_events_meta_mentions' WHERE `object`='bx_events';
