-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_spaces' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_spaces_enable_multilevel_hierarchy';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_spaces_enable_multilevel_hierarchy', '', @iCategId, '_bx_spaces_option_enable_multilevel_hierarchy', 'checkbox', '', '', '', 0);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_spaces_home' AND `title`='_bx_spaces_page_block_title_top_level_spaces';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`, `active`) VALUES
('bx_spaces_home', 1, 'bx_spaces', '_bx_spaces_page_block_title_top_level_spaces', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_spaces\";s:6:\"method\";s:16:\"browse_top_level\";s:6:"params";a:1:{i:0;b:1;}}', 0, 1, 2, 0);
