SET @sName = 'bx_invites';


-- MENU
UPDATE `sys_menu_items` SET `title_system`='_bx_invites_menu_item_title_system_admt_requests', `title`='_bx_invites_menu_item_title_admt_requests' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='invites-requests';