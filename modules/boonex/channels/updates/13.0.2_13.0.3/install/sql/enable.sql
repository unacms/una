SET @sName = 'bx_channels';


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_channels_submenu' AND `name`='channels-administration';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES 
('bx_channels_submenu', 'bx_channels', 'channels-administration', '_bx_channels_menu_item_title_system_entries_manage', '_bx_courses_menu_item_title_entries_manage', 'page.php?i=channels-administration', '', '', '', '', 192, 1, 1, 5);

UPDATE `sys_menu_items` SET `active`='0' WHERE `set_name`='bx_channels_menu_manage_tools' AND `name`='delete-with-content';


-- GRIDS
UPDATE `sys_grid_actions` SET `active`='0' WHERE `object`='bx_channels_administration' AND `type`='bulk' AND `name`='delete_with_content';
