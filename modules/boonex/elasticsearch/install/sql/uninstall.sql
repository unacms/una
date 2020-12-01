
-- FORMS
DELETE FROM `sys_objects_form` WHERE `module` = 'bx_elasticsearch';
DELETE FROM `sys_form_displays` WHERE `module` = 'bx_elasticsearch';
DELETE FROM `sys_form_inputs` WHERE `module` = 'bx_elasticsearch';
DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN('bx_elasticsearch_manage_index');

-- Logs Objects
DELETE FROM `sys_objects_logs` WHERE `module` = 'bx_elasticsearch';

-- Studio page and widget
DELETE FROM `tp`, `tw`, `twb`, `tpw` 
USING `sys_std_pages` AS `tp` LEFT JOIN `sys_std_widgets` AS `tw` ON `tp`.`id` = `tw`.`page_id` LEFT JOIN `sys_std_widgets_bookmarks` AS `twb` ON `tw`.`id` = `twb`.`widget_id` LEFT JOIN `sys_std_pages_widgets` AS `tpw` ON `tw`.`id` = `tpw`.`widget_id`
WHERE  `tp`.`name` = 'bx_elasticsearch';
