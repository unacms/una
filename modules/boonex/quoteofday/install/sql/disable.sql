
-- SETTINGS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = 'bx_quoteofday' LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;

-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_quoteofday_internal');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_quoteofday_internal');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_quoteofday_internal');

-- CRON
DELETE FROM sys_cron_jobs WHERE `name` in('bxquoteofday');

-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = 'bx_quoteofday';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_quoteofday';

-- MENU
DELETE FROM `sys_menu_items` WHERE `module` = 'bx_quoteofday';

-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = 'bx_quoteofday';
DELETE FROM `sys_acl_actions` WHERE `Module` = 'bx_quoteofday';

