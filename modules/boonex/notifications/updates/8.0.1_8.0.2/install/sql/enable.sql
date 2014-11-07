DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard' AND `module`='bx_notifications' AND `name`='account-dashboard-notifications';


DELETE FROM `sys_objects_menu` WHERE `module`='bx_notifications' AND `object`='bx_notifications_submenu' LIMIT 1;
INSERT INTO `sys_objects_menu`(`object`, `title`, `set_name`, `module`, `template_id`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_notifications_submenu', '_bx_ntfs_menu_title_submenu', 'bx_notifications_submenu', 'bx_notifications', 8, 0, 1, '', '');

DELETE FROM `sys_menu_sets` WHERE `module`='bx_notifications' AND `set_name`='bx_notifications_submenu' LIMIT 1;
INSERT INTO `sys_menu_sets`(`set_name`, `module`, `title`, `deletable`) VALUES 
('bx_notifications_submenu', 'bx_notifications', '_bx_ntfs_menu_set_title_submenu', 0);

DELETE FROM `sys_menu_items` WHERE `module`='bx_notifications' AND `set_name`='bx_notifications_submenu' LIMIT 1;
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_notifications_submenu', 'bx_notifications', 'notifications-all', '_bx_ntfs_menu_item_title_system_notifications_all', '_bx_ntfs_menu_item_title_notifications_all', 'page.php?i=notifications-view', '', '', '', '', 2147483647, 1, 0, 1);