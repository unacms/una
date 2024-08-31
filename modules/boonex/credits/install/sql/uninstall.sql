
-- TABLES
DROP TABLE IF EXISTS `bx_credits_bundles`, `bx_credits_orders`, `bx_credits_orders_deleted`, `bx_credits_profiles`, `bx_credits_history`, `bx_credits_withdrawals`;


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_credits';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_credits';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_credits';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_credits_%';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_credits';
