-- SETTINGS
UPDATE `sys_options` SET `value`='6' WHERE `name`='bx_albums_per_page_profile';

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_albums' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_albums_per_page_browse_showcase';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_albums_per_page_browse_showcase', '32', @iCategId, '_sys_option_per_page_browse_showcase', 'digit', '', '', '', 15);


-- PAGES
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:20:"browse_recent_media";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:1;}}' WHERE `module`='bx_albums' AND `title`='_bx_albums_page_block_title_recent_media';

DELETE FROM `sys_pages_blocks` WHERE `module`='bx_albums' AND `title` IN ('_bx_albums_page_block_title_recent_media_view_showcase', '_bx_albums_page_block_title_popular_media_view_showcase', '_bx_albums_page_block_title_featured_media_view_showcase');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_albums', '_bx_albums_page_block_title_sys_recent_media_view_showcase', '_bx_albums_page_block_title_recent_media_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:20:"browse_recent_media";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1, @iBlockOrder + 6),
('', 0, 'bx_albums', '_bx_albums_page_block_title_sys_popular_media_view_showcase', '_bx_albums_page_block_title_popular_media_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:20:"browse_popular_media";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1,@iBlockOrder + 7),
('', 0, 'bx_albums', '_bx_albums_page_block_title_sys_featured_media_view_showcase', '_bx_albums_page_block_title_featured_media_view_showcase', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:21:"browse_featured_media";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1,@iBlockOrder + 8);


-- METATAGS
UPDATE `sys_objects_metatags` SET `table_mentions`='bx_albums_meta_mentions' WHERE `object`='bx_albums';
