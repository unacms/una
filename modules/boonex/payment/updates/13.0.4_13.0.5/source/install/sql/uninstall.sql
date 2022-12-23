SET @sName = 'bx_payment';


-- TABLES
DROP TABLE IF EXISTS `bx_payment_currencies`, `bx_payment_providers`, `bx_payment_providers_options`, `bx_payment_user_values`;
DROP TABLE IF EXISTS `bx_payment_cart`;
DROP TABLE IF EXISTS `bx_payment_transactions`, `bx_payment_transactions_pending`;
DROP TABLE IF EXISTS `bx_payment_subscriptions`, `bx_payment_subscriptions_deleted`;
DROP TABLE IF EXISTS `bx_payment_modules`;
DROP TABLE IF EXISTS `bx_payment_commissions`, `bx_payment_invoices`;


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_payment_grid%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_payment_grid%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_payment_grid%';


-- FORMS
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN (SELECT `display_name` FROM `sys_form_displays` WHERE `module`=@sName);
DELETE FROM `sys_form_inputs` WHERE `module`=@sName;
DELETE FROM `sys_form_displays` WHERE `module`=@sName;
DELETE FROM `sys_objects_form` WHERE `module`=@sName;


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = 'bx_payment';
DELETE FROM `sys_form_pre_values` WHERE `Key` IN ('bx_payment_currencies');


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = @sName;
