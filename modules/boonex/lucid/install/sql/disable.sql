SET @sName = 'bx_lucid';


-- MENU: dropdown menu
DELETE FROM `sys_menu_templates` WHERE `template`='menu_dropdown_site.html' AND `title`='_bx_lucid_menu_template_title_dropdown_site';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_lucid_dropdown_site';

-- MENU: member toolbar
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_toolbar_member' AND `name` IN ('bx_lucid_search');
