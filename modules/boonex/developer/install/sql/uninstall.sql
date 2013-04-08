SET @sName = 'bx_developer';

--
-- Studio page and widget.
--
DELETE FROM `tp`, `tw`, `tpw`
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id`=`tw`.`page_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id`=`tpw`.`widget_id`
WHERE `tp`.`name`=@sName;


--
-- Forms Builder
--
DELETE FROM `sys_objects_grid` WHERE `object` IN ('mod_dev_forms', 'mod_dev_forms_displays', 'mod_dev_forms_fields', 'mod_dev_forms_pre_lists', 'mod_dev_forms_pre_values');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('mod_dev_forms', 'mod_dev_forms_displays', 'mod_dev_forms_fields', 'mod_dev_forms_pre_lists', 'mod_dev_forms_pre_values');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('mod_dev_forms', 'mod_dev_forms_displays', 'mod_dev_forms_fields', 'mod_dev_forms_pre_lists', 'mod_dev_forms_pre_values');

--
-- Navigation Builder
--
DELETE FROM `sys_objects_grid` WHERE `object` IN ('mod_dev_nav_menus', 'mod_dev_nav_sets', 'mod_dev_nav_items');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('mod_dev_nav_menus', 'mod_dev_nav_sets', 'mod_dev_nav_items');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('mod_dev_nav_menus', 'mod_dev_nav_sets', 'mod_dev_nav_items');


DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN  (SELECT `display_name` FROM `sys_form_displays` WHERE `module`=@sName);
DELETE FROM `sys_form_inputs` WHERE `module`=@sName;
DELETE FROM `sys_form_displays` WHERE `module`=@sName;
DELETE FROM `sys_objects_form` WHERE `module`=@sName;