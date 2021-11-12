SET @sName = 'bx_accounts';


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name`='bx_accounts_send_test';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_accounts_send_test', @sName, 'bx_accounts_account', '_bx_accnt_form_display_send_message', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_accounts_account' AND `name` IN ('message_subject', 'message_text');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `help`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_accounts_account', @sName, 'message_subject', '', '', 0, 'text', '_bx_accnt_form_account_input_caption_system_message_subject', '_bx_accnt_form_account_input_caption_system_message_subject', '', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_accnt_form_account_input_message_subject_error', 'Xss', '', 1, 0),
('bx_accounts_account', @sName, 'message_text', '', '', 0, 'textarea', '_bx_accnt_form_account_input_caption_system_message_text', '_bx_accnt_form_account_input_caption_message_text', '', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_accnt_form_account_input_message_text_error', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_accounts_send_test';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_accounts_send_test', 'message_subject', 2147483647, 1, 1),
('bx_accounts_send_test', 'message_text', 2147483647, 1, 2),
('bx_accounts_send_test', 'controls', 2147483647, 1, 3),
('bx_accounts_send_test', 'do_submit', 2147483647, 1, 4),
('bx_accounts_send_test', 'do_cancel', 2147483647, 1, 5);