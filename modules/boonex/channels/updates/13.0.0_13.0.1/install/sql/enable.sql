SET @sName = 'bx_channels';

-- PAGES
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_channels_view_profile' AND `title`='_bx_channels_page_block_title_entry_reports';
INSERT INTO `sys_pages_blocks`(`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `order`) VALUES
('bx_channels_view_profile', 4, 'bx_channels', '', '_bx_channels_page_block_title_entry_reports', 11, 2147483647, 'service', 'a:2:{s:6:\"module\";s:11:\"bx_channels\";s:6:\"method\";s:14:\"entity_reports\";}', 0, 0, 1, 6);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_view_actions_all' AND `name` IN ('notes', 'social-sharing', 'social-sharing-facebook', 'social-sharing-twitter', 'social-sharing-pinterest');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_channels_view_actions_all', 'bx_channels', 'notes', '_sys_menu_item_title_system_va_notes', '_sys_menu_item_title_va_notes', 'javascript:void(0)', 'javascript:bx_get_notes(this,  ''{module_uri}'', {content_id});', '', 'exclamation-triangle', '', '', 0, 2147483647, 1, 0, 280),
('bx_channels_view_actions_all', 'bx_channels', 'social-sharing', '_sys_menu_item_title_system_social_sharing', '_sys_menu_item_title_social_sharing', 'javascript:void(0)', 'oBxDolPage.share(this, \'{url_encoded}\')', '', 'share', '', '', 0, 2147483647, 1, 0, 300);

UPDATE `sys_menu_items` SET `collapsed`='0' WHERE `set_name`='sys_profile_followings' AND `name`='channels';


-- METATAGS
UPDATE `sys_objects_metatags` SET `module`='bx_channels' WHERE `object`='bx_channels';
