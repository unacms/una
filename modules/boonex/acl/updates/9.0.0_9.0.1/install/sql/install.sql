SET @sName = 'bx_acl';


-- GRIDS
UPDATE `sys_objects_grid` SET `visible_for_levels`='192' WHERE `object`='bx_acl_administration';
UPDATE `sys_objects_grid` SET `visible_for_levels`='2147483647' WHERE `object`='bx_acl_view';