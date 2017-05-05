
-- SETTINGS
DELETE FROM `top`, `toc`, `to` USING `sys_options_types` AS `top` LEFT JOIN `sys_options_categories` AS `toc` ON `top`.`id`=`toc`.`type_id` LEFT JOIN `sys_options` AS `to` ON `toc`.`id`=`to`.`category_id` WHERE `top`.`name` = 'bx_mlc';


-- GRID
DELETE FROM `sys_objects_grid` WHERE `object` IN ('bx_mlc_keys');
DELETE FROM `sys_grid_fields` WHERE `object` IN ('bx_mlc_keys');
DELETE FROM `sys_grid_actions` WHERE `object` IN ('bx_mlc_keys');


-- FORM
UPDATE `sys_form_inputs` SET `type`='text' WHERE `type`='text_mlc';
UPDATE `sys_form_inputs` SET `type`='textarea' WHERE `type`='textarea_mlc';
UPDATE `sys_form_inputs` SET `checker_func`='Avail' WHERE `checker_func`='AvailMlc';
UPDATE `sys_form_inputs` SET `checker_func`='Length' WHERE `checker_func`='LengthMlc';


-- INJECTIONS
DELETE FROM `sys_injections` WHERE `name`='bx_mlc';


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_mlc_translator';
