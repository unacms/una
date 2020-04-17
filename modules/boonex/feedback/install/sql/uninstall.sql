
-- TABLES
DROP TABLE IF EXISTS `bx_fdb_questions`, `bx_fdb_answers`, `bx_fdb_answers2users`;


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_feedback';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_feedback';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_feedback';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_feedback_%';


-- STUDIO: page & widget
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = 'bx_feedback';
