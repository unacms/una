SET @sName = 'bx_accounts';


-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_accounts_account';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_accounts_account', @sName, '_sys_form_account', '', '', 'do_submit', 'sys_accounts', 'id', '', '', 'a:1:{s:14:"checker_helper";s:31:"BxAccntFormAccountCheckerHelper";}', 0, 1, 'BxAccntFormAccount', 'modules/boonex/accounts/classes/BxAccntFormAccount.php');

DELETE FROM `sys_form_displays` WHERE `display_name`='bx_accounts_account_settings_email';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_accounts_account_settings_email', @sName, 'bx_accounts_account', '_sys_form_display_account_settings_email', 0);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_accounts_account';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_accounts_account', @sName, 'email', '', '', 0, 'text', '_sys_form_login_input_caption_system_email', '_sys_form_account_input_email', '', 1, 0, 0, '', '', '', 'Email', '', '_sys_form_account_input_email_error', 'Xss', '', 0, 0),
('bx_accounts_account', @sName, 'receive_updates', '1', '', 1, 'switcher', '_sys_form_login_input_caption_system_receive_updates', '_sys_form_account_input_receive_updates', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_accounts_account', @sName, 'receive_news', '1', '', 1, 'switcher', '_sys_form_login_input_caption_system_receive_news', '_sys_form_account_input_receive_news', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_accounts_account', @sName, 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_sys_form_login_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_accounts_account_settings_email';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_accounts_account_settings_email', 'email', 2147483647, 1, 1),
('bx_accounts_account_settings_email', 'receive_updates', 2147483647, 1, 2),
('bx_accounts_account_settings_email', 'receive_news', 2147483647, 1, 3),
('bx_accounts_account_settings_email', 'do_submit', 2147483647, 1, 4);
