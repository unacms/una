SET @sName = 'bx_payment';


-- OPTIONS
DELETE FROM `tot`, `toc`, `to` USING `sys_options_types` AS `tot` LEFT JOIN `sys_options_categories` AS `toc` ON `tot`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `tot`.`name` = @sName;


-- PAGES & BLOCKS
DELETE FROM `sys_objects_page` WHERE `module` = @sName;
DELETE FROM `sys_pages_blocks` WHERE `module` = @sName OR `object` IN ('bx_payment_join', 'bx_payment_carts', 'bx_payment_cart', 'bx_payment_cart_thank_you', 'bx_payment_history', 'bx_payment_sbs_list_my', 'bx_payment_sbs_list_all', 'bx_payment_sbs_history', 'bx_payment_orders', 'bx_payment_details', 'bx_payment_invoices', 'bx_payment_checkout_offline');


-- MENU
DELETE FROM `sys_objects_menu` WHERE `module` = @sName;
DELETE FROM `sys_menu_sets` WHERE `module` = @sName;
DELETE FROM `sys_menu_items` WHERE `module` = @sName OR `set_name` IN('bx_payment_menu_cart_submenu', 'bx_payment_menu_sbs_submenu', 'bx_payment_menu_sbs_actions', 'bx_payment_menu_orders_submenu');


-- ALERTS
SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandlerId;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandlerId LIMIT 1;


-- ACL
DELETE `sys_acl_actions`, `sys_acl_matrix` FROM `sys_acl_actions`, `sys_acl_matrix` WHERE `sys_acl_matrix`.`IDAction` = `sys_acl_actions`.`ID` AND `sys_acl_actions`.`Module` = @sName;
DELETE FROM `sys_acl_actions` WHERE `Module` = @sName;


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Module` = @sName;


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name` IN ('bx_payment_commissions', 'bx_payment_time_tracker');


-- PAYMENTS
DELETE FROM `sys_objects_payments` WHERE `object` = @sName;
