
DROP TABLE IF EXISTS `sys_acl_level_prices`;

DELETE FROM `sys_objects_grid` WHERE `object`='sys_studio_acl_prices';
DELETE FROM `sys_grid_fields` WHERE `object`='sys_studio_acl_prices';
DELETE FROM `sys_grid_actions` WHERE `object`='sys_studio_acl_prices';

