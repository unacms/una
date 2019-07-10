-- MENUS
UPDATE `sys_menu_items` SET `active`='1' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='friends';
UPDATE `sys_menu_items` SET `active`='1' WHERE `set_name`='bx_organizations_snippet_meta' AND `name`='befriend';

UPDATE `sys_menu_items` SET `visibility_custom`='a:2:{s:6:"module";s:16:"bx_organizations";s:6:"method";s:19:"is_enable_relations";}' WHERE `set_name`='sys_account_notifications' AND `module`='bx_organizations' AND `name`='notifications-relation-requests';
