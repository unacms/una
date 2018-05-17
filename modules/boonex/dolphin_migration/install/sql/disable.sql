SET @sName = 'bx_dolphin_migration';

-- SETTINGS
DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` 
LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` 
LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name`=@sName;

DELETE FROM `sys_options` WHERE `name` = 'bx_dolphin_migration_salt';

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_dolphin_migration_transfers');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_dolphin_migration_transfers', 'bx_dolphin_migration_transfers_path');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_dolphin_migration_transfers');