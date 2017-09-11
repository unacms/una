-- MENUS
UPDATE `sys_menu_items` SET `icon`='shopping-cart col-green2' WHERE `set_name`='sys_site' AND `name`='snipcart-home';

UPDATE `sys_menu_items` SET `icon`='shopping-cart col-green2' WHERE `set_name`='sys_homepage' AND `name`='snipcart-home';

UPDATE `sys_menu_items` SET `icon`='shopping-cart col-green2' WHERE `set_name`='sys_add_content_links' AND `name`='create-snipcart-entry';

UPDATE `sys_menu_items` SET `icon`='shopping-cart col-green2' WHERE `set_name`='sys_profile_stats' AND `name`='profile-stats-manage-snipcart';

UPDATE `sys_menu_items` SET `icon`='shopping-cart col-green2' WHERE `set_name`='trigger_profile_view_submenu' AND `name`='snipcart-author';
UPDATE `sys_menu_items` SET `icon`='shopping-cart col-green2' WHERE `set_name` LIKE '%view_submenu' AND `name`='snipcart-author';


-- STATS
UPDATE `sys_statistics` SET `icon`='shopping-cart col-green2' WHERE `module`='bx_snipcart';
