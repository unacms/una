-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_posts' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_posts_per_page_profile');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_posts_per_page_profile', '3', @iCategId, '_bx_posts_option_per_page_profile', 'digit', '', '', '', 12);


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_posts_home' AND `title` IN ('_bx_posts_page_block_title_featured_entries_view_extended');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_posts_home', 1, 'bx_posts', '', '_bx_posts_page_block_title_featured_entries_view_extended', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:15:"browse_featured";s:6:"params";a:1:{i:0;s:8:"extended";}}', 0, 1, 1, 0);

UPDATE `sys_pages_blocks` SET `order`='1' WHERE `object`='bx_posts_home' AND `title`='_bx_posts_page_block_title_recent_entries_view_extended';

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"browse_author";s:6:"params";a:2:{i:0;s:12:"{profile_id}";i:1;a:1:{s:8:"per_page";s:25:"bx_posts_per_page_profile";}}}' WHERE `title`='_bx_posts_page_block_title_my_entries' AND `content`='a:3:{s:6:"module";s:8:"bx_posts";s:6:"method";s:13:"browse_author";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}';