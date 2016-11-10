-- SETTINGS
UPDATE `sys_options_types` SET `icon`='bx_albums@modules/boonex/albums/|std-icon.svg' WHERE `name`='bx_albums';


-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_author' AND `title` IN ('_bx_albums_page_block_title_favorites_of_author', '_bx_albums_page_block_title_favorites_of_author_media');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_author', 1, 'bx_albums', '_bx_albums_page_block_title_sys_favorites_of_author', '_bx_albums_page_block_title_favorites_of_author', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:15:\"browse_favorite\";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 0, 2),
('bx_albums_author', 1, 'bx_albums', '_bx_albums_page_block_title_sys_favorites_of_author_media', '_bx_albums_page_block_title_favorites_of_author_media', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:21:\"browse_favorite_media\";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 1, 1, 3);