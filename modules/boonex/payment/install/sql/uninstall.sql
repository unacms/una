SET @sName = 'bx_payment';


-- TABLES
DROP TABLE IF EXISTS `bx_payment_providers`;
DROP TABLE IF EXISTS `bx_payment_providers_options`;
DROP TABLE IF EXISTS `bx_payment_user_values`;
DROP TABLE IF EXISTS `bx_payment_cart`;
DROP TABLE IF EXISTS `bx_payment_transactions`;
DROP TABLE IF EXISTS `bx_payment_transactions_pending`;
DROP TABLE IF EXISTS `bx_payment_modules`;


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_payment_grid%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_payment_grid%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_payment_grid%';


-- FORMS
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN  (SELECT `display_name` FROM `sys_form_displays` WHERE `module`=@sName);
DELETE FROM `sys_form_inputs` WHERE `module`=@sName;
DELETE FROM `sys_form_displays` WHERE `module`=@sName;
DELETE FROM `sys_objects_form` WHERE `module`=@sName;


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;
