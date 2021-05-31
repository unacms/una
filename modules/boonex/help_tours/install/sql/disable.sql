-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_help_tours_%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_help_tours_%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_help_tours_%';