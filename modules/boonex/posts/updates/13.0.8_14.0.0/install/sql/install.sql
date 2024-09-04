-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name`='abstract';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'abstract', '', '', 0, 'textarea', '_bx_posts_form_entry_input_sys_abstract', '_bx_posts_form_entry_input_abstract', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_posts_entry_add', 'bx_posts_entry_edit') AND `input_name`='abstract';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_add', 'abstract', 2147483647, 1, 3),
('bx_posts_entry_edit', 'abstract', 2147483647, 1, 4);
