SET @sName = 'bx_payment';

-- MENUS
UPDATE `sys_menu_items` SET `icon`='credit-card' WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='payment-invoices';