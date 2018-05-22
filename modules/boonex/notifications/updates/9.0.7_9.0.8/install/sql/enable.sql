SET @sName = 'bx_notifications';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_notifications_events_per_preview';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_events_per_preview', '20', @iCategId, '_bx_ntfs_option_events_per_preview', 'digit', '', '', '', '', 5);


-- MENUS
DELETE FROM `sys_objects_menu` WHERE `object`='bx_notifications_preview';
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notifications_preview', '_bx_ntfs_menu_title_preview', 'bx_notifications_preview', @sName, 20, 0, 1, 'BxNtfsMenuPreview', 'modules/boonex/notifications/classes/BxNtfsMenuPreview.php');

DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_notifications_preview';
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_notifications_preview', @sName, '_bx_ntfs_menu_set_title_preview', 0);

DELETE FROM `sys_menu_items` WHERE `set_name`='bx_notifications_preview';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_notifications_preview', @sName, 'more', '_bx_ntfs_menu_item_title_system_more', '_bx_ntfs_menu_item_title_more', 'page.php?i=notifications-view', '', '', '', '', 2147483647, 1, 0, 1);

UPDATE `sys_menu_items` SET `link`='javascript:void(0)', `onclick`='bx_menu_slide(''bx_notifications_preview'', this, ''site'', {id:{value:''bx_notifications_preview'', force:1}});', `submenu_object`='bx_notifications_preview', `submenu_popup`='1' WHERE `module`=@sName AND `name`='notifications-notifications';
