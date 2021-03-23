SET @sName = 'bx_donations';


-- TABLES
DROP TABLE IF EXISTS `bx_donations_entries`, `bx_donations_entries_deleted`;
DROP TABLE IF EXISTS `bx_donations_types`;


-- FORMS
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN (SELECT `display_name` FROM `sys_form_displays` WHERE `module` = @sName);
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_objects_form` WHERE `module` = @sName;


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = @sName;
DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_donations_period_units');


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp`, `sys_std_widgets` AS `tw`, `sys_std_pages_widgets` AS `tpw`
WHERE `tp`.`id` = `tw`.`page_id` AND `tw`.`id` = `tpw`.`widget_id` AND `tp`.`name` = @sName;