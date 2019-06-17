-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_albums' LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_albums_album_browsing_unit';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`,`check_params`, `check_error`, `extra`, `order`) VALUES
('bx_albums_album_browsing_unit', '10', @iCategId, '_bx_albums_option_album_browsing_unit', 'digit', '', '', '', '', 20);


-- MENUS
UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_albums_view_actions' AND `name`='vote';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions' AND `name` IN ('reaction', 'social-sharing-googleplus');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions', 'bx_albums', 'reaction', '_sys_menu_item_title_system_va_reaction', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 225);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_media' AND `name`='edit-image';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_media', 'bx_albums', 'edit-image', '_bx_albums_menu_item_title_system_edit_image', '_bx_albums_menu_item_title_edit_image', 'javascript:void(0)', 'javascript:{js_object}.editMedia(this, {media_id});', '', 'pencil-alt', '', '', 2147483647, '', 1, 0, 30);

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_albums_view_actions_media' AND `name`='add-images-to-album';
UPDATE `sys_menu_items` SET `title_system`='_bx_albums_menu_item_title_system_edit_album', `active`='0' WHERE `set_name`='bx_albums_view_actions_media' AND `name`='edit-album';

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_view_actions_media' AND `name` IN ('edit-image', 'social-sharing-googleplus');
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('bx_albums_view_actions_media', 'bx_albums', 'edit-image', '_bx_albums_menu_item_title_system_edit_image', '', '', '', '', '', '', '', 0, 2147483647, '', 1, 0, 30);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_albums_snippet_meta' AND `name`='items';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `editable`, `order`) VALUES 
('bx_albums_snippet_meta', 'bx_albums', 'items', '_bx_albums_menu_item_title_system_sm_items', '_bx_albums_menu_item_title_sm_items', '', '', '', '', '', '', 2147483647, '', 0, 0, 1, 6);

UPDATE `sys_menu_items` SET `icon`='far image' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='albums-administration' AND `icon`='';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_albums' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_albums_video_mp4' AND `action`='transcoded';
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('bx_albums_video_mp4', 'transcoded', @iHandler);
