
-- TABLES
DROP TABLE IF EXISTS `bx_credits_bundles`, `bx_credits_orders`, `bx_credits_orders_deleted`, `bx_credits_profiles`, `bx_credits_history`;


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_credits';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_credits';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_credits';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_credits_%';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_credits';
