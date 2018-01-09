-- TABLES
DROP TABLE IF EXISTS `bx_quoteofday_internal`;

-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_quoteofday';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_quoteofday';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_quoteofday';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_quoteofday_entry_add', 'bx_quoteofday_entry_edit');

-- STUDIO WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_quoteofday';


