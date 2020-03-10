SET @sName = 'bx_accounts';


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name`='bx_accounts_account_create';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_accounts_account_create', @sName, 'bx_accounts_account', '_sys_form_display_account_create', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_accounts_account' AND `name` IN ('name', 'password', 'controls', 'do_cancel', 'email_confirmed');
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_accounts_account', @sName, 'name', '', '', 0, 'text', '_sys_form_login_input_caption_system_name', '_sys_form_account_input_name', '', 1, 0, 0, '', '', '', 'Avail', '', '_sys_form_account_input_name_error', 'Xss', '', 1, 0),
('bx_accounts_account', @sName, 'password', '', '', 0, 'password', '_sys_form_login_input_caption_system_password', '_sys_form_account_input_password', '', 1, 0, 0, '', '', '', 'Preg', 'a:1:{s:4:"preg";s:38:"~^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}~";}', '_sys_form_account_input_password_error', '', '', 0, 0),
('bx_accounts_account', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_accounts_account', @sName, 'do_cancel', '_adm_btn_cancel_submit', '', 0, 'button', '_adm_btn_cancel_submit', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),
('bx_accounts_account', @sName, 'email_confirmed', '1', '', 1, 'switcher', '_bx_accnt_form_login_input_caption_system_email_confirmed', '_bx_accnt_form_account_input_email_confirmed', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0);

UPDATE `sys_form_display_inputs` SET `order`='5' WHERE `display_name`='bx_accounts_account_settings_email' AND `input_name`='do_submit';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_accounts_account_settings_email' AND `input_name` IN ('controls', 'do_cancel');
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_accounts_account_settings_email', 'controls', 2147483647, 1, 4),
('bx_accounts_account_settings_email', 'do_cancel', 2147483647, 1, 6);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_accounts_account_create';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_accounts_account_create', 'name', 2147483647, 1, 1),
('bx_accounts_account_create', 'email', 2147483647, 1, 2),
('bx_accounts_account_create', 'password', 2147483647, 1, 3),
('bx_accounts_account_create', 'email_confirmed', 2147483647, 1, 4),
('bx_accounts_account_create', 'controls', 2147483647, 1, 5),
('bx_accounts_account_create', 'do_submit', 2147483647, 1, 6),
('bx_accounts_account_create', 'do_cancel', 2147483647, 1, 7);
