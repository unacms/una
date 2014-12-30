SET @sName = 'bx_accounts';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName;


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName;


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_accounts_administration', 'bx_accounts_moderation');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_accounts_administration', 'bx_accounts_moderation');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_accounts_administration', 'bx_accounts_moderation');