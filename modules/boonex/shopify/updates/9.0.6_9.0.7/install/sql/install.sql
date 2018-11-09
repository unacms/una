-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_shopify_settings' AND `name` IN ('api_key', 'app_id', 'access_token');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_shopify_settings', 'bx_shopify', 'access_token', '', '', 0, 'text', '_bx_shopify_form_settings_input_sys_access_token', '_bx_shopify_form_settings_input_access_token', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_shopify_form_settings_input_access_token_err', 'Xss', '', 1, 0);


DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_shopify_settings_edit';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_shopify_settings_edit', 'mode', 2147483647, 1, 1),
('bx_shopify_settings_edit', 'domain', 2147483647, 1, 2),
('bx_shopify_settings_edit', 'access_token', 2147483647, 1, 3),
('bx_shopify_settings_edit', 'do_submit', 2147483647, 1, 4);
