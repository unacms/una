-- MENUS
UPDATE `sys_menu_items` SET `active`='1' WHERE `set_name`='bx_persons_snippet_meta' AND `name`='subscribe';

UPDATE `sys_menu_items` SET `visibility_custom`='a:2:{s:6:"module";s:10:"bx_persons";s:6:"method";s:19:"is_enable_relations";}' WHERE `set_name`='sys_account_notifications' AND `module`='bx_persons' AND `name`='notifications-relation-requests';
