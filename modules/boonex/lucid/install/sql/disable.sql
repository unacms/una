SET @sName = 'bx_lucid';


-- PAGE: explore
DELETE FROM `sys_objects_page` WHERE `object`='bx_lucid_explore';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_lucid_explore';

-- PAGE: updates
DELETE FROM `sys_objects_page` WHERE `object`='bx_lucid_updates';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_lucid_updates';

-- PAGE: trends
DELETE FROM `sys_objects_page` WHERE `object`='bx_lucid_trends';
DELETE FROM `sys_pages_blocks` WHERE `object`='bx_lucid_trends';


-- MENU: dropdown menu
DELETE FROM `sys_menu_templates` WHERE `template`='menu_dropdown_site.html' AND `title`='_bx_lucid_menu_template_title_dropdown_site';

DELETE FROM `sys_objects_menu` WHERE `object`='bx_lucid_dropdown_site';

-- MENU: member toolbar
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_toolbar_member' AND `name` IN ('bx_lucid_search', 'bx_lucid_login', 'bx_lucid_join');

-- MENU: home page submenu
DELETE FROM `sys_objects_menu` WHERE `object`='bx_lucid_homepage_submenu';
DELETE FROM `sys_menu_sets` WHERE `set_name`='bx_lucid_homepage_submenu';
DELETE FROM `sys_menu_items` WHERE `set_name`='bx_lucid_homepage_submenu';
