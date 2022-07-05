SET @sName = 'bx_artificer';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName; -- OR `object` IN ();


-- MENU: dropdown menu
DELETE FROM `sys_menu_templates` WHERE `template`='menu_sidebar_site.html' AND `title`='_bx_artificer_menu_template_title_sidebar_site';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_artificer_sidebar_site';


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;


-- INJECTIONS
DELETE FROM `sys_injections` WHERE `name` LIKE 'bx_artificer_%';