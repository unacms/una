SET @sName = 'bx_reputation';


DROP TABLE IF EXISTS `bx_reputation_handlers`, `bx_reputation_profiles`;


-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_display_inputs` WHERE `display_name` LIKE 'bx_reputation_%';


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;