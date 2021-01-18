SET @sName = 'bx_payment';


-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `object`='bx_payment_invoices';
INSERT INTO `sys_objects_page`(`object`, `title_system`, `title`, `module`, `layout_id`, `visible_for_levels`, `visible_for_levels_editable`, `uri`, `url`, `meta_description`, `meta_keywords`, `meta_robots`, `cache_lifetime`, `cache_editable`, `deletable`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_invoices', '_bx_payment_page_title_sys_invoices', '_bx_payment_page_title_invoices', @sName, 5, 2147483647, 1, 'payment-invoices', 'page.php?i=payment-invoices', '', '', '', 0, 1, 0, 'BxPaymentPageInvoices', 'modules/boonex/payment/classes/BxPaymentPageInvoices.php');

DELETE FROM `sys_pages_blocks` WHERE `object`='bx_payment_invoices';
INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `order`) VALUES
('bx_payment_invoices', 1, @sName, '', '_bx_payment_page_block_title_invoices', 11, 2147483647, 'service', 'a:4:{s:6:"module";s:10:"bx_payment";s:6:"method";s:18:"get_block_invoices";s:6:"params";a:0:{}s:5:"class";s:11:"Commissions";}', 0, 0, 1);


-- MENUS
DELETE FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' AND `name`='payment-invoices';
SET @iManageMenuOrder = (SELECT IFNULL(MAX(`order`), 0) FROM `sys_menu_items` WHERE `set_name`='sys_account_dashboard_manage_tools' LIMIT 1);
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `markers`, `submenu_object`, `visible_for_levels`, `visibility_custom`, `active`, `copyable`, `order`) VALUES 
('sys_account_dashboard_manage_tools', 'bx_payment', 'payment-invoices', '_bx_payment_menu_item_title_system_admt_invoices', '_bx_payment_menu_item_title_admt_invoices', 'page.php?i=payment-invoices', '', '_self', 'credit-card col-gray-dark', 'a:2:{s:6:"module";s:10:"bx_payment";s:6:"method";s:27:"get_menu_addon_manage_tools";}', '', '', 192, '', 1, 0, @iManageMenuOrder + 1);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name` IN ('bx_payment_expiring_notification_committent', 'bx_payment_overdue_notification_committent');
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_payment_et_txt_name_expiring_notification_committent', 'bx_payment_expiring_notification_committent', '_bx_payment_et_txt_subject_expiring_notification_committent', '_bx_payment_et_txt_body_expiring_notification_committent'),
(@sName, '_bx_payment_et_txt_name_overdue_notification_committent', 'bx_payment_overdue_notification_committent', '_bx_payment_et_txt_subject_overdue_notification_committent', '_bx_payment_et_txt_body_overdue_notification_committent');


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_payment_commissions';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_payment_commissions', '0 * * * *', 'BxPaymentCronCommissions', 'modules/boonex/payment/classes/BxPaymentCronCommissions.php', '');
