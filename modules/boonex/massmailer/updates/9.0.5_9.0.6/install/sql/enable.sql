SET @sName = 'bx_massmailer';


-- MENUS
UPDATE `sys_menu_items` SET `icon`='mail-bulk' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='massmailer-campaigns' AND `icon`='';

UPDATE `sys_menu_items` SET `icon`='mail-bulk col-red' WHERE `set_name`='sys_account_dashboard' AND `name`='dashboard-massmailer' AND `icon`='envelope col-red';
