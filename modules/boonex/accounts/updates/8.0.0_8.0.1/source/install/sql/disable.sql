SET @sName = 'bx_accounts';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName;


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName;


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = @sName;
DELETE FROM `sys_acl_actions` WHERE `Module` = @sName;


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_accounts_administration');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_accounts_administration');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_accounts_administration');