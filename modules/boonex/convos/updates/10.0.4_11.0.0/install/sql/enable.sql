-- MENUS
UPDATE `sys_menu_items` SET `visibility_custom`='a:3:{s:6:"module";s:9:"bx_convos";s:6:"method";s:18:"is_allowed_contact";s:6:"params";a:1:{i:0;s:12:"{profile_id}";}}' WHERE `module`='bx_convos' AND `name`='convos-compose' AND `title_system`='_bx_cnv_menu_item_title_system_message';


-- ACL
UPDATE `sys_acl_actions` SET `Countable`='0' WHERE `Module`='bx_convos' AND `Name`='view entry';
