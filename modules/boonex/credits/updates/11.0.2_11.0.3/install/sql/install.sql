-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_credits_bundle' AND `name`='name';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_credits_bundle', 'bx_credits', 'name', '', '', 0, 'text', '_bx_credits_form_bundle_input_sys_name', '_bx_credits_form_bundle_input_name', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_credits_form_bundle_input_name_err', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_credits_bundle_add', 'bx_credits_bundle_edit') AND `input_name`='name';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_credits_bundle_add', 'name', 2147483647, 1, 1),
('bx_credits_bundle_edit', 'name', 2147483647, 1, 1);
