SET @sName = 'bx_channels';


-- OPTIONS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_channels' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_channels_browse_active_n_posts', 'bx_channels_browse_active_x_hours', 'bx_channels_browse_trending_x_hours');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_channels_browse_active_n_posts', '1', @iCategId, '_bx_channels_option_browse_active_n_posts', 'digit', '', '', '', '', 20),
('bx_channels_browse_active_x_hours', '24', @iCategId, '_bx_channels_option_browse_active_x_hours', 'digit', '', '', '', '', 21),
('bx_channels_browse_trending_x_hours', '24', @iCategId, '_bx_channels_option_browse_trending_x_hours', 'digit', '', '', '', '', 23);


-- PAGES
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_channels' AND `title_system` IN ('_bx_channels_page_block_title_sys_active_entries', '_bx_channels_page_block_title_sys_trending_entries');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_channels', '_bx_channels_page_block_title_sys_active_entries', '_bx_channels_page_block_title_active_entries', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_channels\";s:6:\"method\";s:13:\"browse_active\";}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 1),
('', 0, 'bx_channels', '_bx_channels_page_block_title_sys_trending_entries', '_bx_channels_page_block_title_trending_entries', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_channels\";s:6:\"method\";s:15:\"browse_trending\";}', 0, 1, 1, IFNULL(@iBlockOrder, 0) + 2);
