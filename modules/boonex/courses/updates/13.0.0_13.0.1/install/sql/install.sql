-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_course' AND `name`='cf';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_course', 'bx_courses', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_course_add', 'bx_course_edit') AND `input_name`='cf';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_course_add', 'cf', 2147483647, 1, 8),
('bx_course_edit', 'cf', 2147483647, 1, 7);

UPDATE `sys_form_inputs` SET `type`='price' WHERE `object`='bx_courses_price' AND `name`='price';

-- REPORTS
UPDATE `sys_objects_report` SET `object_comment`='bx_courses_notes' WHERE `name`='bx_courses';
