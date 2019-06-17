SET @sName = 'bx_lucid';


-- PAGES
DELETE FROM `sys_objects_page` WHERE `object` IN ('bx_lucid_explore', 'bx_lucid_updates', 'bx_lucid_trends');


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_toolbar_member' AND `name` IN ('bx_lucid_login', 'bx_lucid_join');

DELETE FROM `sys_objects_menu` WHERE `object`='bx_lucid_homepage_submenu';
DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_lucid_homepage_submenu';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_lucid_homepage_submenu';
