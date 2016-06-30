-- PAGES:
DELETE FROM `sys_objects_page` WHERE `object`='bx_albums_add_images';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_add_images', '_bx_albums_page_title_sys_add_images', '_bx_albums_page_title_add_images', 'bx_albums', 5, 2147483647, 1, 'add-images', '', '', '', '', 0, 1, 0, 'BxAlbumsPageEntry', 'modules/boonex/albums/classes/BxAlbumsPageEntry.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_add_images' AND `title`='_bx_albums_page_block_title_add_images';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_albums_add_images', 1, 'bx_albums', '_bx_albums_page_block_title_add_images', 11, 2147483647, 'service', 'a:2:{s:6:"module";s:9:"bx_albums";s:6:"method";s:16:"entity_add_files";}', 0, 0, 0);

UPDATE `sys_pages_blocks` SET `order`='4' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_location';
UPDATE `sys_pages_blocks` SET `cell_id`='4', `order`='2' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_social_sharing';
UPDATE `sys_pages_blocks` SET `designbox_id`='11' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_author';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_view_entry' AND `title` IN ('_bx_albums_page_block_title_entry_actions', '_bx_albums_page_block_title_entry_info');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_view_entry', 3, 'bx_albums', '_bx_albums_page_block_title_entry_info', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:11:\"entity_info\";}', 0, 0, 1, 0);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:14:"browse_popular";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}' WHERE `object`='bx_albums_popular' AND `title` IN ('_bx_albums_page_block_title_popular_entries');

DELETE FROM `sys_objects_page` WHERE `object`='bx_albums_updated';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_updated' AND `title` IN ('_bx_albums_page_block_title_updated_entries');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_home' AND `title` IN ('_bx_albums_page_block_title_recent_entries', '_bx_albums_page_block_title_updated_entries');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_albums_home', 1, 'bx_albums', '', '_bx_albums_page_block_title_updated_entries', 11, 2147483647, 'service', 'a:3:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:14:\"browse_updated\";s:6:\"params\";a:3:{s:9:\"unit_view\";s:8:\"extended\";s:13:\"empty_message\";b:1;s:13:\"ajax_paginate\";b:0;}}', 0, 1, 1, 1);

UPDATE `sys_pages_blocks` SET `content`='a:4:{s:6:\"module\";s:6:\"system\";s:6:\"method\";s:14:\"keywords_cloud\";s:6:\"params\";a:3:{i:0;s:9:\"bx_albums\";i:1;s:9:\"bx_albums\";i:2;a:1:{s:10:\"show_empty\";b:1;}}s:5:\"class\";s:20:\"TemplServiceMetatags\";}' WHERE `object`='bx_albums_home' AND `title`='_bx_albums_page_block_title_popular_keywords_albums';

DELETE FROM `sys_pages_blocks` WHERE `object`='sys_home' AND `title`='_bx_albums_page_block_title_updated_entries';
SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `cell_id` = 1 ORDER BY `order` DESC LIMIT 1);
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('sys_home', 1, 'bx_albums', '_bx_albums_page_block_title_updated_entries', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:14:\"browse_updated\";}', 1, 0, 0, IFNULL(@iBlockOrder, 0) + 1);

SET @iPBCellProfile = 3;
DELETE FROM `sys_pages_blocks` WHERE `object`='trigger_page_profile_view_entry' AND `title` IN ('_bx_albums_page_block_title_my_entries');
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('trigger_page_profile_view_entry', @iPBCellProfile, 'bx_albums', '_bx_albums_page_block_title_my_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:13:"browse_author";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}', 0, 0, 0);

SET @iBlockOrder = (SELECT `order` FROM `sys_pages_blocks` WHERE `object` = '' AND `cell_id` = 0 ORDER BY `order` DESC LIMIT 1);
DELETE FROM `sys_pages_blocks` WHERE `module`='bx_albums' AND `title`='_bx_albums_page_block_title_recent_entries';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('', 0, 'bx_albums', '', '_bx_albums_page_block_title_recent_entries', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:13:"browse_public";s:6:"params";a:3:{s:9:"unit_view";s:8:"extended";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}', 0, 1, 1, @iBlockOrder + 1);


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_albums_view_popup';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view' AND `name`='add-images-to-album';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view', 'bx_albums', 'add-images-to-album', '_bx_albums_menu_item_title_system_add_images', '_bx_albums_menu_item_title_add_images', 'page.php?i=add-images&id={content_id}', '', '', 'plus', '', 2147483647, 1, 0, 10);

UPDATE `sys_menu_items` SET `order`='20' WHERE `set_name`='bx_albums_view' AND `name`='edit-album';
UPDATE `sys_menu_items` SET `order`='30' WHERE `set_name`='bx_albums_view' AND `name`='delete-album';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_albums_view_media';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_view_media', '_bx_albums_menu_title_view_media', 'bx_albums_view_media', 'bx_albums', 9, 0, 1, 'BxAlbumsMenuView', 'modules/boonex/albums/classes/BxAlbumsMenuView.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_albums_view_media';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_albums_view_media', 'bx_albums', '_bx_albums_menu_set_title_view_media', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_media' AND `name` IN ('add-images-to-album', 'edit-album');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_media', 'bx_albums', 'add-images-to-album', '_bx_albums_menu_item_title_system_add_images', '_bx_albums_menu_item_title_add_images', 'page.php?i=add-images&id={content_id}', '', '', 'plus', '', 2147483647, 1, 0, 10),
('bx_albums_view_media', 'bx_albums', 'edit-album', '_bx_albums_menu_item_title_system_edit_album', '_bx_albums_menu_item_title_edit_album', 'page.php?i=edit-album&id={content_id}', '', '', 'pencil', '', 2147483647, 1, 0, 20);

UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object`='bx_albums_submenu';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_submenu' AND `name` IN ('albums-updated');

UPDATE `sys_objects_menu` SET `template_id`='8' WHERE `object`='bx_albums_view_submenu';


-- UPLOADERS
DELETE FROM `sys_objects_uploader` WHERE `object`='bx_albums_crop';
INSERT INTO `sys_objects_uploader` (`object`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_albums_crop', 1, 'BxAlbumsUploaderCrop', 'modules/boonex/albums/classes/BxAlbumsUploaderCrop.php');