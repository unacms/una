
-- Forms

UPDATE `sys_form_inputs` SET `checker_func` = '', `checker_params` = '', `checker_error` = ''  WHERE `object` = 'sys_comment' AND `name` = 'cmt_text';

-- Menu 

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_notifications' AND `name` = 'invoices';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_notifications', 'system', 'invoices', '_sys_menu_item_title_system_invoices', '_sys_menu_item_title_invoices', 'invoices.php', '', '', 'file-invoice col-green3', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:18:"get_invoices_count";s:6:"params";a:1:{i:0;s:6:"unpaid";}s:5:"class";s:21:"TemplPaymentsServices";}', '', 2147483646, 0, 1, 8);

UPDATE `sys_menu_items` SET `icon` = 'credit-card col-blue3' WHERE `set_name` = 'sys_account_dashboard' AND `name` = 'dashboard-subscriptions';

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_account_dashboard' AND `name` = 'dashboard-invoices';
INSERT INTO `sys_menu_items` (`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `visible_for_levels`, `active`, `copyable`, `order`) VALUES
('sys_account_dashboard', 'system', 'dashboard-invoices', '_sys_menu_item_title_system_invoices', '_sys_menu_item_title_invoices', 'invoices.php', '', '', 'file-invoice col-green3', '', '', 2147483646, 1, 1, 4);

DELETE FROM `sys_menu_items` WHERE `set_name` = 'sys_cmts_item_meta' AND `name` = 'in-reply-to';
INSERT INTO `sys_menu_items`(`set_name`, `module`, `name`, `title_system`, `title`, `link`, `onclick`, `target`, `icon`, `addon`, `submenu_object`, `submenu_popup`, `visible_for_levels`, `active`, `copyable`, `editable`, `order`) VALUES 
('sys_cmts_item_meta', 'system', 'in-reply-to', '_sys_menu_item_title_system_sm_in_reply_to', '_sys_menu_item_title_sm_in_reply_to', '', '', '', '', '', '', 0, 2147483647, 1, 0, 1, 1);

UPDATE `sys_menu_items` SET `order` = 0 WHERE `set_name` = 'sys_cmts_item_meta' AND `name` = 'author';

-- Live updates

DELETE FROM `sys_objects_live_updates` WHERE `name` = 'sys_payments_invoices';
INSERT INTO `sys_objects_live_updates`(`name`, `init`, `frequency`, `service_call`, `active`) VALUES
('sys_payments_invoices', 0, 1, 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:25:"get_live_updates_invoices";s:6:"params";a:3:{i:0;a:2:{s:11:"menu_object";s:18:"sys_toolbar_member";s:9:"menu_item";s:7:"account";}i:1;a:2:{s:11:"menu_object";s:25:"sys_account_notifications";s:9:"menu_item";s:8:"invoices";}i:2;s:7:"{count}";}s:5:"class";s:21:"TemplPaymentsServices";}', 1);

-- Preloader

DELETE FROM `sys_preloader` WHERE `module` = 'system' AND `type` = 'js_system' AND `content` = 'jquery.ba-resize.min.js';
INSERT INTO `sys_preloader`(`module`, `type`, `content`, `active`, `order`) VALUES
('system', 'js_system', 'jquery.ba-resize.min.js', 1, 25);


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '12.0.0-B2' WHERE (`version` = '12.0.0.B1' OR `version` = '12.0.0-B1') AND `name` = 'system';

