SET @sName = 'bx_lucid';


-- MENU: dropdown menu
DELETE FROM `sys_menu_templates` WHERE `template`='menu_dropdown_site.html' AND `title`='_bx_lucid_menu_template_title_dropdown_site';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_lucid_dropdown_site';

-- MENU: member toolbar
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_toolbar_member' AND `name` IN ('bx_lucid_search');


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;