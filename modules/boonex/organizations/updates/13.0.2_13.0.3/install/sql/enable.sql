-- MENUS
UPDATE `sys_menu_items` SET `addon_cache`='1' WHERE `set_name`='sys_profile_stats' AND `module`='bx_organizations' AND `name`='profile-stats-subscribed-me';

UPDATE `sys_menu_items` SET `visibility_custom`='a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:19:"is_enable_relations";}' WHERE `set_name`='sys_profile_stats' AND `module`='bx_organizations' AND `name`='profile-stats-relations';
UPDATE `sys_menu_items` SET `visibility_custom`='a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:19:"is_enable_relations";}' WHERE `set_name`='sys_profile_stats' AND `module`='bx_organizations' AND `name`='profile-stats-related-me';
