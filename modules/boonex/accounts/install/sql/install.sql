SET @sName = 'bx_accounts';


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_accounts_account', @sName, '_sys_form_account', '', '', 'do_submit', 'sys_accounts', 'id', '', '', 'a:1:{s:14:"checker_helper";s:31:"BxAccntFormAccountCheckerHelper";}', 0, 1, 'BxAccntFormAccount', 'modules/boonex/accounts/classes/BxAccntFormAccount.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_accounts_account_settings_email', @sName, 'bx_accounts_account', '_sys_form_display_account_settings_email', 0),
('bx_accounts_account_create', @sName, 'bx_accounts_account', '_sys_form_display_account_create', 0),
('bx_accounts_send_test', @sName, 'bx_accounts_account', '_bx_accnt_form_display_send_message', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_accounts_account', @sName, 'email', '', '', 0, 'text', '_sys_form_login_input_caption_system_email', '_sys_form_account_input_email', '', 1, 0, 0, '', '', '', 'Email', '', '_sys_form_account_input_email_error', 'Xss', '', 0, 0),
('bx_accounts_account', @sName, 'receive_updates', '1', '', 1, 'switcher', '_sys_form_login_input_caption_system_receive_updates', '_sys_form_account_input_receive_updates', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_accounts_account', @sName, 'receive_news', '1', '', 1, 'switcher', '_sys_form_login_input_caption_system_receive_news', '_sys_form_account_input_receive_news', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_accounts_account', @sName, 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_sys_form_login_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_accounts_account', @sName, 'name', '', '', 0, 'text', '_sys_form_login_input_caption_system_name', '_sys_form_account_input_name', '', 1, 0, 0, '', '', '', 'Avail', '', '_sys_form_account_input_name_error', 'Xss', '', 1, 0),
('bx_accounts_account', @sName, 'password', '', '', 0, 'password', '_sys_form_login_input_caption_system_password', '_sys_form_account_input_password', '', 1, 0, 0, '', '', '', 'Preg', 'a:1:{s:4:"preg";s:38:"~^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}~";}', '_sys_form_account_input_password_error', '', '', 0, 0),
('bx_accounts_account', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_accounts_account', @sName, 'do_cancel', '_adm_btn_cancel_submit', '', 0, 'button', '_adm_btn_cancel_submit', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),
('bx_accounts_account', @sName, 'email_confirmed', '1', '', 1, 'switcher', '_bx_accnt_form_login_input_caption_system_email_confirmed', '_bx_accnt_form_account_input_email_confirmed', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_accounts_account', @sName, 'message_subject', '', '', 0, 'text', '_bx_accnt_form_account_input_caption_system_message_subject', '_bx_accnt_form_account_input_caption_system_message_subject', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_accnt_form_account_input_message_subject_error', 'Xss', '', 1, 0),
('bx_accounts_account', @sName, 'message_text', '', '', 0, 'textarea', '_bx_accnt_form_account_input_caption_system_message_text', '_bx_accnt_form_account_input_caption_message_text', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_accnt_form_account_input_message_text_error', 'Xss', '', 1, 0);


INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_accounts_account_settings_email', 'email', 2147483647, 1, 1),
('bx_accounts_account_settings_email', 'receive_updates', 2147483647, 1, 2),
('bx_accounts_account_settings_email', 'receive_news', 2147483647, 1, 3),
('bx_accounts_account_settings_email', 'controls', 2147483647, 1, 4),
('bx_accounts_account_settings_email', 'do_submit', 2147483647, 1, 5),
('bx_accounts_account_settings_email', 'do_cancel', 2147483647, 1, 6),

('bx_accounts_account_create', 'name', 2147483647, 1, 1),
('bx_accounts_account_create', 'email', 2147483647, 1, 2),
('bx_accounts_account_create', 'password', 2147483647, 1, 3),
('bx_accounts_account_create', 'email_confirmed', 2147483647, 1, 4),
('bx_accounts_account_create', 'controls', 2147483647, 1, 5),
('bx_accounts_account_create', 'do_submit', 2147483647, 1, 6),
('bx_accounts_account_create', 'do_cancel', 2147483647, 1, 7),

('bx_accounts_send_test', 'message_subject', 2147483647, 1, 1),
('bx_accounts_send_test', 'message_text', 2147483647, 1, 2),
('bx_accounts_send_test', 'controls', 2147483647, 1, 3),
('bx_accounts_send_test', 'do_submit', 2147483647, 1, 4),
('bx_accounts_send_test', 'do_cancel', 2147483647, 1, 5);



-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_accnt', '_bx_accnt', 'bx_accounts@modules/boonex/accounts/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, 'users', '{url_studio}module.php?name=bx_accounts', '', 'bx_accounts@modules/boonex/accounts/|std-icon.svg', '_bx_accnt', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));