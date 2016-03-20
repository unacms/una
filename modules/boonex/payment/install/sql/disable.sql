SET @sName = 'bx_payment';


-- OPTIONS
SET @iTypeId = (SELECT `ID` FROM `sys_options_types` WHERE `name` = @sName LIMIT 1);
SET @iCategId = (SELECT `ID` FROM `sys_options_categories` WHERE `type_id` = @iTypeId LIMIT 1);
DELETE FROM `sys_options` WHERE `category_id` = @iCategId;
DELETE FROM `sys_options_categories` WHERE `type_id` = @iTypeId;
DELETE FROM `sys_options_types` WHERE `id` = @iTypeId;


-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName;


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName;


-- ALERTS
SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandlerId;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandlerId LIMIT 1;


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = @sName;
DELETE FROM `sys_acl_actions` WHERE `Module` = @sName;


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = @sName;


-- PAYMENTS
DELETE FROM `sys_objects_payments` WHERE `object` = @sName;
