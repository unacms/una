SET @sName = 'bx_lucid';


-- MENU
UPDATE `sys_menu_items` SET `hidden_on`='7' WHERE `set_name`='sys_toolbar_member' AND `name` IN ('bx_lucid_search', 'bx_lucid_login', 'bx_lucid_join');

