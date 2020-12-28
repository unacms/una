SET @sName = 'bx_notifications';


-- GRIDS
UPDATE `sys_grid_fields` SET `title`='_bx_ntfs_grid_column_title_active' WHERE `object`='bx_notifications_settings_administration' AND `name`='switcher';
UPDATE `sys_grid_fields` SET `width`='79%' WHERE `object`='bx_notifications_settings_administration' AND `name`='title';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_notifications_settings_administration' AND `name`='value';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_notifications_settings_administration', 'value', '_bx_ntfs_grid_column_title_value', '10%', 0, 0, '', 20);

DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_notifications_settings_administration', 'bx_notifications_settings_common') AND `name`='activate';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `confirm`, `order`) VALUES
('bx_notifications_settings_administration', 'bulk', 'activate', '_bx_ntfs_grid_action_title_activate', '', 1, 1),
('bx_notifications_settings_common', 'bulk', 'activate', '_bx_ntfs_grid_action_title_activate', '', 1, 1);
