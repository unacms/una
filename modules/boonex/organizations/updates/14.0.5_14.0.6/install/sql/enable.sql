SET @sName = 'bx_organizations';


-- MENUS
UPDATE `sys_menu_items` SET `link`='page.php?i=account-settings-delete&id={account_id}&content=0' WHERE `set_name`='bx_organizations_view_actions_more' AND `name`='delete-organization-account';
