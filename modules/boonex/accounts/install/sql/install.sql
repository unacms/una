SET @sName = 'bx_accounts';


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_accounts_account', @sName, '_sys_form_account', '', '', 'do_submit', 'sys_accounts', 'id', '', '', 'a:1:{s:14:"checker_helper";s:31:"BxAccntFormAccountCheckerHelper";}', 0, 1, 'BxAccntFormAccount', 'modules/boonex/accounts/classes/BxAccntFormAccount.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_accounts_account_settings_email', @sName, 'bx_accounts_account', '_sys_form_display_account_settings_email', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_accounts_account', @sName, 'email', '', '', 0, 'text', '_sys_form_login_input_caption_system_email', '_sys_form_account_input_email', '', 1, 0, 0, '', '', '', 'Email', '', '_sys_form_account_input_email_error', 'Xss', '', 0, 0),
('bx_accounts_account', @sName, 'receive_updates', '1', '', 1, 'switcher', '_sys_form_login_input_caption_system_receive_updates', '_sys_form_account_input_receive_updates', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_accounts_account', @sName, 'receive_news', '1', '', 1, 'switcher', '_sys_form_login_input_caption_system_receive_news', '_sys_form_account_input_receive_news', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_accounts_account', @sName, 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_sys_form_login_input_caption_system_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_accounts_account_settings_email', 'email', 2147483647, 1, 1),
('bx_accounts_account_settings_email', 'receive_updates', 2147483647, 1, 2),
('bx_accounts_account_settings_email', 'receive_news', 2147483647, 1, 3),
('bx_accounts_account_settings_email', 'do_submit', 2147483647, 1, 4);


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_accnt', '_bx_accnt', 'bx_accounts@modules/boonex/accounts/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_accounts', '', 'bx_accounts@modules/boonex/accounts/|std-icon.svg', '_bx_accnt', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));