SET @sName = 'bx_donations';


-- SETTINGS
DELETE FROM `tot`, `toc`, `to` USING `sys_options_types` AS `tot` LEFT JOIN `sys_options_categories` AS `toc` ON `tot`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `tot`.`name`=@sName;


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName OR `object` IN ('bx_donations_make', 'bx_donations_list', 'bx_donations_list_all');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName OR `set_name` IN ('bx_donations_list_submenu');


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_donations_%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_donations_%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_donations_%';


-- INJECTIONS
DELETE FROM `sys_injections` WHERE `name`=@sName;


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = @sName;
