-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_location';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_view_entry', 4, 'bx_albums', '_bx_albums_page_block_title_entry_location', 3, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:13:\"locations_map\";s:6:\"params\";a:2:{i:0;s:9:\"bx_albums\";i:1;s:4:\"{id}\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 0, 0, 1, 2);

UPDATE `sys_objects_page` SET `layout_id`='10' WHERE `object`='bx_albums_view_media';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_view_media' AND `title`='_bx_albums_page_block_title_entry_view_media_exif';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_view_media', 4, 'bx_albums', '', '_bx_albums_page_block_title_entry_view_media_exif', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:10:\"media_exif\";}', 0, 0, 1, 1);

UPDATE `sys_pages_blocks` SET `title_system`='_bx_albums_page_block_title_entry_comments', `title`='_bx_albums_page_block_title_entry_comments_link', `designbox_id`='11' WHERE `object`='bx_albums_view_entry_comments' AND `title`='_bx_albums_page_block_title_entry_comments';

DELETE FROM `sys_objects_page` WHERE `object`='bx_albums_updated';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_updated', '_bx_albums_page_title_sys_entries_updated', '_bx_albums_page_title_entries_updated', 'bx_albums', 5, 2147483647, 1, 'albums-updated', 'page.php?i=albums-updated', '', '', '', 0, 1, 0, 'BxAlbumsPageBrowse', 'modules/boonex/albums/classes/BxAlbumsPageBrowse.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_updated';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES 
('bx_albums_updated', 1, 'bx_albums', '_bx_albums_page_block_title_updated_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:14:"browse_updated";s:6:"params";a:3:{s:9:"unit_view";s:8:"extended";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1);

UPDATE `sys_pages_blocks` SET `designbox_id`='13', `active`='1' WHERE `object`='bx_albums_author' AND `title`='_bx_albums_page_block_title_entries_actions';
UPDATE `sys_pages_blocks` SET `title_system`='_bx_albums_page_block_title_sys_entries_of_author', `active`='1' WHERE `object`='bx_albums_author' AND `title`='_bx_albums_page_block_title_entries_of_author';

UPDATE `sys_objects_page` SET `layout_id`='2' WHERE `object`='bx_albums_home';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_home' AND `title` IN ('_bx_albums_page_block_title_popular_keywords_albums', '_bx_albums_page_block_title_popular_keywords_media', '_bx_albums_page_block_title_popular_keywords_media_camera');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_home', 2, 'bx_albums', '', '_bx_albums_page_block_title_popular_keywords_albums', 11, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:14:\"keywords_cloud\";s:6:\"params\";a:2:{i:0;s:9:\"bx_albums\";i:1;s:9:\"bx_albums\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 1, 0, 1, 0),
('bx_albums_home', 2, 'bx_albums', '', '_bx_albums_page_block_title_popular_keywords_media', 11, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:14:\"keywords_cloud\";s:6:\"params\";a:2:{i:0;s:15:\"bx_albums_media\";i:1;s:15:\"bx_albums_media\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 1, 0, 1, 1),
('bx_albums_home', 2, 'bx_albums', '', '_bx_albums_page_block_title_popular_keywords_media_camera', 11, 2147483647, 'service', 'a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:14:\"keywords_cloud\";s:6:\"params\";a:2:{i:0;s:22:\"bx_albums_media_camera\";i:1;s:22:\"bx_albums_media_camera\";}s:5:\"class\";s:20:\"TemplServiceMetatags\";}', 1, 0, 1, 2);

UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='sys_home' AND `title`='_bx_albums_page_block_title_recent_entries';

DELETE FROM `sys_pages_blocks` WHERE `object`='' AND `title` IN ('_bx_albums_page_block_title_popular_keywords_albums', '_bx_albums_page_block_title_popular_keywords_media', '_bx_albums_page_block_title_popular_keywords_media_camera');
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_albums', '', '_bx_albums_page_block_title_popular_keywords_albums', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"keywords_cloud";s:6:"params";a:2:{i:0;s:9:"bx_albums";i:1;s:9:"bx_albums";}s:5:"class";s:20:"TemplServiceMetatags";}', 0, 1, 1, @iBlockOrder + 2),
('', 0, 'bx_albums', '', '_bx_albums_page_block_title_popular_keywords_media', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"keywords_cloud";s:6:"params";a:2:{i:0;s:15:"bx_albums_media";i:1;s:15:"bx_albums_media";}s:5:"class";s:20:"TemplServiceMetatags";}', 0, 1, 1, @iBlockOrder + 3),
('', 0, 'bx_albums', '', '_bx_albums_page_block_title_popular_keywords_media_camera', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:14:"keywords_cloud";s:6:"params";a:2:{i:0;s:22:"bx_albums_media_camera";i:1;s:22:"bx_albums_media_camera";}s:5:"class";s:20:"TemplServiceMetatags";}', 0, 1, 1, @iBlockOrder + 4);


-- MENU
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_homepage' AND `name`='albums-home';
SET @iHomepageMenuOrder = (SELECT `order` FROM `sys_menu_items` WHERE `set_name` = 'sys_homepage' AND `active` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('sys_homepage', 'bx_albums', 'albums-home', '_bx_albums_menu_item_title_system_entries_home', '_bx_albums_menu_item_title_entries_home', 'page.php?i=albums-home', '', '', 'picture-o col-blue1', 'bx_albums_submenu', 2147483647, 1, 1, IFNULL(@iHomepageMenuOrder, 0) + 1);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_albums_view_popup';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_view_popup', '_bx_albums_menu_title_view_entry_popup', '', 'bx_albums', 16, 0, 1, 'BxAlbumsMenuViewActions', 'modules/boonex/albums/classes/BxAlbumsMenuViewActions.php');

UPDATE `sys_objects_menu` SET `template_id`='6' WHERE `object`='bx_albums_submenu';

UPDATE `sys_menu_items` SET `title_system`='_bx_albums_menu_item_title_system_entries_recent', `title`='_bx_albums_menu_item_title_entries_recent' WHERE `set_name`='bx_albums_submenu' AND `name`='albums-home';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_submenu' AND `name`='albums-updated';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_submenu', 'bx_albums', 'albums-updated', '_bx_albums_menu_item_title_system_entries_updated', '_bx_albums_menu_item_title_entries_updated', 'page.php?i=albums-updated', '', '', '', '', 2147483647, 1, 1, 3);

UPDATE `sys_objects_menu` SET `template_id`='6' WHERE `object`='bx_albums_view_submenu';

UPDATE `sys_menu_items` SET `icon`='picture-o col-blue1' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='albums-author';


-- SEARCH
UPDATE `sys_objects_search` SET `GlobalSearch`='1' WHERE `ObjectName` IN ('bx_albums', 'bx_albums_cmts', 'bx_albums_media', 'bx_albums_media_cmts');

DELETE FROM `sys_objects_search` WHERE `ObjectName`='bx_albums_media_camera';
SET @iSearchOrder = (SELECT IFNULL(MAX(`Order`), 0) FROM `sys_objects_search`);
INSERT INTO `sys_objects_search` (`ObjectName`, `Title`, `Order`, `GlobalSearch`, `ClassName`, `ClassPath`) VALUES
('bx_albums_media_camera', '_bx_albums_media', @iSearchOrder + 1, 0, 'BxAlbumsSearchResultMediaCamera', 'modules/boonex/albums/classes/BxAlbumsSearchResultMediaCamera.php');


-- METATAGS
DELETE FROM `sys_objects_metatags` WHERE `object`='bx_albums_media_camera';
INSERT INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('bx_albums_media_camera', 'bx_albums_meta_keywords_media_camera', '', '', '', '');