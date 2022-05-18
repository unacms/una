-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_credits_credit' AND `name`='cleared';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_credits_credit', 'bx_credits', 'cleared', '', '', 0, 'text', '_bx_credits_form_credit_input_sys_cleared', '_bx_credits_form_credit_input_cleared', '', 0, 0, 0, 'a:1:{s:8:"disabled";s:8:"disabled";}', '', '', '', '', '', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_credits_credit_withdraw_request' AND `input_name`='cleared';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_credits_credit_withdraw_request', 'cleared', 2147483647, 1, 1);