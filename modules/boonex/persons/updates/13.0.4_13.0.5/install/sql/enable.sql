-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_persons_menu_manage_tools' AND `name`='delete';


-- GRIDS
DELETE FROM `sys_grid_actions` WHERE `object`='bx_persons_administration' AND `type`='bulk' AND `name`='delete';
DELETE FROM `sys_grid_actions` WHERE `object`='bx_persons_common' AND `type`='bulk' AND `name`='delete';
