SET @sName = 'bx_acl';


-- TABLES
DROP TABLE IF EXISTS `bx_acl_level_prices`, `bx_acl_licenses`;


-- FORMS
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN  (SELECT `display_name` FROM `sys_form_displays` WHERE `module` = @sName);
DELETE FROM `sys_form_inputs` WHERE `module` = @sName;
DELETE FROM `sys_form_displays` WHERE `module` = @sName;
DELETE FROM `sys_objects_form` WHERE `module` = @sName;


-- PRE-VALUES
DELETE FROM `sys_form_pre_lists` WHERE `module` = @sName;
DELETE FROM `sys_form_pre_values` WHERE `Key` IN('bx_acl_period_units');


-- GRIDS
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_acl_administration', 'bx_acl_view');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_acl_administration', 'bx_acl_view');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_acl_administration', 'bx_acl_view');


-- STUDIO PAGE & WIDGET
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = @sName;