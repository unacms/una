
-- settings

DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name`='bx_antispam';

-- pages

DELETE FROM `sys_objects_page` WHERE `module` = 'bx_antispam';
DELETE FROM `sys_pages_blocks` WHERE `module` = 'bx_antispam';

-- grids

DELETE FROM `sys_objects_grid` WHERE `object` LIKE 'bx_antispam%';
DELETE FROM `sys_grid_fields` WHERE `object` LIKE 'bx_antispam%';
DELETE FROM `sys_grid_actions` WHERE `object` LIKE 'bx_antispam%';

-- data list: ip table actions

DELETE FROM `sys_form_pre_lists` WHERE `key` = 'bx_antispam_ip_table_actions';
DELETE FROM `sys_form_pre_values` WHERE `Key` = 'bx_antispam_ip_table_actions';

-- form: IP table add/edit

DELETE FROM `sys_objects_form` WHERE `object`='bx_antispam_ip_table_form';
DELETE FROM `sys_form_displays` WHERE `object`='bx_antispam_ip_table_form';
DELETE FROM `sys_form_inputs` WHERE `object`='bx_antispam_ip_table_form';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_antispam_ip_table_form_add';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_antispam_ip_table_form_edit';

-- alerts

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_antispam' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;

-- email templates

DELETE FROM `sys_email_templates` WHERE `Module` = 'bx_antispam';

