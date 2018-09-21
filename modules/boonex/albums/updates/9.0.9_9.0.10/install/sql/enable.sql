-- PAGE
UPDATE `sys_objects_page` SET `layout_id`='12' WHERE `object`='bx_albums_view_entry' AND `layout_id`='10';

UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='1' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_text' AND `cell_id`='4' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='2', `designbox_id`='0', `order`='3' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_attachments' AND `cell_id`='4' AND `order`='1' ;
UPDATE `sys_pages_blocks` SET `active`='0', `order`='0' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_social_sharing' AND `active`='1' AND `order`='2';
UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='4' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_comments' AND `cell_id`='4'  AND `order`='3';
UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_location' AND `cell_id`='4';
UPDATE `sys_pages_blocks` SET `cell_id`='3', `order`='2' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_author' AND `cell_id`='2' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='3' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_context' AND `cell_id`='2';
UPDATE `sys_pages_blocks` SET `order`='3' WHERE `object`='bx_albums_view_entry' AND `title`='_bx_albums_page_block_title_entry_info' AND `order`='0';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_view_entry' AND `title` IN ('_bx_albums_page_block_title_entry_actions', '_bx_albums_page_block_title_entry_all_actions');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_view_entry', 3, 'bx_albums', '', '_bx_albums_page_block_title_entry_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:14:\"entity_actions\";}', 0, 0, 0, 0),
('bx_albums_view_entry', 2, 'bx_albums','' , '_bx_albums_page_block_title_entry_all_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:18:\"entity_all_actions\";}', 0, 0, 1, 2);

UPDATE `sys_objects_page` SET `layout_id`='12' WHERE `object`='bx_albums_view_media';

UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='1' WHERE `object`='bx_albums_view_media' AND `title`='_bx_albums_page_block_title_entry_view_media' AND `cell_id`='1' AND `order`='0' ;
UPDATE `sys_pages_blocks` SET `cell_id`='3', `order`='1' WHERE `object`='bx_albums_view_media' AND `title`='_bx_albums_page_block_title_entry_author' AND `cell_id`='2' AND `order`='0';
UPDATE `sys_pages_blocks` SET `active`='0' WHERE `object`='bx_albums_view_media' AND `title`='_bx_albums_page_block_title_entry_social_sharing' AND `active`='1';
UPDATE `sys_pages_blocks` SET `cell_id`='2', `order`='3' WHERE `object`='bx_albums_view_media' AND `title`='_bx_albums_page_block_title_entry_comments' AND `cell_id`='4' AND `order`='0';
UPDATE `sys_pages_blocks` SET `cell_id`='3', `order`='2' WHERE `object`='bx_albums_view_media' AND `title`='_bx_albums_page_block_title_entry_view_media_exif' AND `cell_id`='4' AND `order`='1';

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_albums_view_media' AND `title` IN ('_bx_albums_page_block_title_entry_actions', '_bx_albums_page_block_title_entry_all_actions', '_bx_albums_page_block_title_featured_entries_view_gallery_media');
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES 
('bx_albums_view_media', 3, 'bx_albums', '', '_bx_albums_page_block_title_entry_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:13:\"media_actions\";}', 0, 0, 0, 0),
('bx_albums_view_media', 2, 'bx_albums', '', '_bx_albums_page_block_title_entry_all_actions', 13, 2147483647, 'service', 'a:2:{s:6:\"module\";s:9:\"bx_albums\";s:6:\"method\";s:17:\"media_all_actions\";}', 0, 0, 1, 2),
('bx_albums_view_media', 3, 'bx_albums', '', '_bx_albums_page_block_title_featured_entries_view_gallery_media', 11, 2147483647, 'service', 'a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:21:"browse_featured_media";s:6:"params";a:1:{i:0;s:7:"gallery";}}', 0, 1, 1, 3);

UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:19:"browse_recent_media";s:6:"params";a:3:{s:9:"unit_view";s:7:"gallery";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:1;}}' WHERE `module`='bx_albums' AND `title`='_bx_albums_page_block_title_recent_media';
UPDATE `sys_pages_blocks` SET `content`='a:3:{s:6:"module";s:9:"bx_albums";s:6:"method";s:19:"browse_recent_media";s:6:"params";a:3:{s:9:"unit_view";s:8:"showcase";s:13:"empty_message";b:1;s:13:"ajax_paginate";b:0;}}' WHERE `module`='bx_albums' AND `title`='_bx_albums_page_block_title_recent_media_view_showcase';



-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_albums_view_actions';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_view_actions', '_sys_menu_title_view_actions', 'bx_albums_view_actions', 'bx_albums', 15, 0, 1, 'BxAlbumsMenuViewActions', 'modules/boonex/albums/classes/BxAlbumsMenuViewActions.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_albums_view_actions';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_albums_view_actions', 'bx_albums', '_sys_menu_set_title_view_actions', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions', 'bx_albums', 'add-images-to-album', '_bx_albums_menu_item_title_system_add_images', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 10),
('bx_albums_view_actions', 'bx_albums', 'edit-album', '_bx_albums_menu_item_title_system_edit_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 20),
('bx_albums_view_actions', 'bx_albums', 'delete-album', '_bx_albums_menu_item_title_system_delete_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 30),
('bx_albums_view_actions', 'bx_albums', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, 0, 0, 200),
('bx_albums_view_actions', 'bx_albums', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 210),
('bx_albums_view_actions', 'bx_albums', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 220),
('bx_albums_view_actions', 'bx_albums', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 230),
('bx_albums_view_actions', 'bx_albums', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 240),
('bx_albums_view_actions', 'bx_albums', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 250),
('bx_albums_view_actions', 'bx_albums', 'repost', '_sys_menu_item_title_system_va_repost', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 260),
('bx_albums_view_actions', 'bx_albums', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 270),
('bx_albums_view_actions', 'bx_albums', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 300),
('bx_albums_view_actions', 'bx_albums', 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 310),
('bx_albums_view_actions', 'bx_albums', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 320),
('bx_albums_view_actions', 'bx_albums', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 330),
('bx_albums_view_actions', 'bx_albums', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, 1, 0, 9999);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_albums_view_actions_media';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_view_actions_media', '_bx_albums_menu_title_view_actions_media', 'bx_albums_view_actions_media', 'bx_albums', 15, 0, 1, 'BxAlbumsMenuViewActionsMedia', 'modules/boonex/albums/classes/BxAlbumsMenuViewActionsMedia.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_albums_view_actions_media';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_albums_view_actions_media', 'bx_albums', '_bx_albums_menu_set_title_view_actions_media', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions_media';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions_media', 'bx_albums', 'add-images-to-album', '_bx_albums_menu_item_title_system_add_images', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 10),
('bx_albums_view_actions_media', 'bx_albums', 'edit-album', '_bx_albums_menu_item_title_system_edit_entry', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 20),
('bx_albums_view_actions_media', 'bx_albums', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, 0, 0, 200),
('bx_albums_view_actions_media', 'bx_albums', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 210),
('bx_albums_view_actions_media', 'bx_albums', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 220),
('bx_albums_view_actions_media', 'bx_albums', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 230),
('bx_albums_view_actions_media', 'bx_albums', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 240),
('bx_albums_view_actions_media', 'bx_albums', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 250),
('bx_albums_view_actions_media', 'bx_albums', 'social-sharing-facebook', '_sys_menu_item_title_system_social_sharing_facebook', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 300),
('bx_albums_view_actions_media', 'bx_albums', 'social-sharing-googleplus', '_sys_menu_item_title_system_social_sharing_googleplus', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 310),
('bx_albums_view_actions_media', 'bx_albums', 'social-sharing-twitter', '_sys_menu_item_title_system_social_sharing_twitter', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 320),
('bx_albums_view_actions_media', 'bx_albums', 'social-sharing-pinterest', '_sys_menu_item_title_system_social_sharing_pinterest', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 330),
('bx_albums_view_actions_media', 'bx_albums', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, 1, 0, 9999);

DELETE FROM `sys_objects_menu` WHERE `object`='bx_albums_view_actions_media_unit';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums_view_actions_media_unit', '_bx_albums_menu_title_view_actions_media_unit', 'bx_albums_view_actions_media_unit', 'bx_albums', 15, 0, 1, 'BxAlbumsMenuViewActionsMedia', 'modules/boonex/albums/classes/BxAlbumsMenuViewActionsMedia.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_albums_view_actions_media_unit';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_albums_view_actions_media_unit', 'bx_albums', '_bx_albums_menu_set_title_view_actions_media_unit', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions_media_unit';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions_media_unit', 'bx_albums', 'comment', '_sys_menu_item_title_system_va_comment', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 200),
('bx_albums_view_actions_media_unit', 'bx_albums', 'view', '_sys_menu_item_title_system_va_view', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 210),
('bx_albums_view_actions_media_unit', 'bx_albums', 'vote', '_sys_menu_item_title_system_va_vote', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 220),
('bx_albums_view_actions_media_unit', 'bx_albums', 'score', '_sys_menu_item_title_system_va_score', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 230),
('bx_albums_view_actions_media_unit', 'bx_albums', 'favorite', '_sys_menu_item_title_system_va_favorite', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 240),
('bx_albums_view_actions_media_unit', 'bx_albums', 'feature', '_sys_menu_item_title_system_va_feature', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 250),
('bx_albums_view_actions_media_unit', 'bx_albums', 'more-auto', '_sys_menu_item_title_system_va_more_auto', '_sys_menu_item_title_va_more_auto', 'javascript:void(0)', '', '', 'ellipsis-v', '', '', 0, 2147483647, 1, 0, 9999);
