SET @sName = 'bx_accounts';


-- FORMS
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN  (SELECT `display_name` FROM `sys_form_displays` WHERE `module` = @sName);
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_objects_form` WHERE `module` = @sName;


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;