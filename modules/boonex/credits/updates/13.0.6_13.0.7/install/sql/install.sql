-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_credits_bundle' AND `name`='description';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_credits_bundle', 'bx_credits', 'description', '', '', 0, 'textarea_translatable', '_bx_credits_form_bundle_input_sys_description', '_bx_credits_form_bundle_input_description', '', 0, 0, 3, '', '', '', '', '', '', 'XssHtml', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_credits_bundle_add', 'bx_credits_bundle_edit') AND `input_name`='description';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_credits_bundle_add', 'description', 2147483647, 1, 2),
('bx_credits_bundle_edit', 'description', 2147483647, 1, 2);
