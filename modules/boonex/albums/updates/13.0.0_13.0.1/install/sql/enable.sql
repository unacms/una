-- PAGES
UPDATE `sys_objects_page` SET `content_info`='bx_albums_media' WHERE `object`='bx_albums_view_media';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions' AND `name` IN ('social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions', 'bx_albums', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, 1, 0, 300);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions_media' AND `name` IN ('report', 'social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions_media', 'bx_albums', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 260),
('bx_albums_view_actions_media', 'bx_albums', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, 1, 0, 300);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions_media_unit' AND `name`='report';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions_media_unit', 'bx_albums', 'report', '_sys_menu_item_title_system_va_report', '', '', '', '', '', '', '', 0, 2147483647, 1, 0, 260);

UPDATE `sys_menu_items` SET `name`='profile-stats-my-albums', `link`='page.php?i=albums-author&profile_id={member_id}' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-albums';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_albums' WHERE `object` IN ('bx_albums', 'bx_albums_media', 'bx_albums_media_camera');
