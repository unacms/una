SET @sName = 'bx_developer';


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `tdi`.`id` AS `id`, `ti`.`caption_system` AS `caption_system`, `ti`.`caption` AS `caption`, `ti`.`type` AS `type`, `ti`.`module` AS `module`, `tdi`.`visible_for_levels` AS `visible_for_levels`, `tdi`.`active` AS `active`, `ti`.`editable` AS `editable`, `ti`.`deletable` AS `deletable`, `tdi`.`order` AS `order` FROM `sys_form_display_inputs` AS `tdi` LEFT JOIN `sys_form_inputs` AS `ti` ON `tdi`.`input_name`=`ti`.`name` AND `ti`.`object`=? WHERE 1 AND `tdi`.`display_name`=?' WHERE `object`='bx_developer_forms_fields';

-- FORMS
UPDATE `sys_form_inputs` SET `required`='0', `checker_func`='', `checker_error`='' WHERE `object`='bx_developer_nav_menu' AND `name`='set_name';

UPDATE `sys_form_inputs` SET `required`='0', `checker_func`='', `checker_error`='' WHERE `object`='bx_developer_nav_item' AND `name`='title_system';
UPDATE `sys_form_inputs` SET `required`='1', `checker_func`='Avail', `checker_error`='_bx_dev_nav_err_items_title' WHERE `object`='bx_developer_nav_item' AND `name`='title';

UPDATE `sys_form_inputs` SET `required`='0', `checker_func`='', `checker_params`='', `checker_error`='' WHERE `object`='bx_developer_bp_page' AND `name`='title_system';

UPDATE `sys_form_inputs` SET `required`='0', `checker_func`='', `checker_params`='', `checker_error`='' WHERE `object`='bx_developer_bp_block' AND `name`='title_system';
