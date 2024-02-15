-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_market_license';
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_market_license', 'bx_market', '_bx_market_form_license', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_market_licenses', 'id', '', '', 'do_submit', '', 0, 1, 'BxMarketFormLicense', 'modules/boonex/market/classes/BxMarketFormLicense.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_market_license';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_market_license', 'bx_market_license_edit', 'bx_market', 0, '_bx_market_form_license_display_edit');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_market_license';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_market_license', 'bx_market', 'order', '', '', 0, 'text', '_bx_market_form_license_input_sys_order', '_bx_market_form_license_input_order', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_market_form_license_input_order_err', 'Xss', '', 1, 0),
('bx_market_license', 'bx_market', 'license', '', '', 0, 'text', '_bx_market_form_license_input_sys_license', '_bx_market_form_license_input_license', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_market_form_license_input_license_err', 'Xss', '', 1, 0),
('bx_market_license', 'bx_market', 'domain', '', '', 0, 'text', '_bx_market_form_license_input_sys_domain', '_bx_market_form_license_input_domain', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_market_license', 'bx_market', 'added', '', '', 0, 'datetime', '_bx_market_form_license_input_sys_added', '_bx_market_form_license_input_added', '', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0),
('bx_market_license', 'bx_market', 'expired', '', '', 0, 'datetime', '_bx_market_form_license_input_sys_expired', '_bx_market_form_license_input_expired', '', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0),
('bx_market_license', 'bx_market', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '_bx_market_form_license_input_sys_controls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_market_license', 'bx_market', 'do_submit', '_bx_market_form_license_input_do_submit', '', 0, 'submit', '_bx_market_form_license_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_market_license', 'bx_market', 'do_cancel', '_bx_market_form_license_input_do_cancel', '', 0, 'button', '_bx_market_form_license_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_market_license_edit';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_market_license_edit', 'order', 2147483647, 1, 1),
('bx_market_license_edit', 'license', 2147483647, 1, 2),
('bx_market_license_edit', 'domain', 2147483647, 1, 3),
('bx_market_license_edit', 'added', 2147483647, 1, 4),
('bx_market_license_edit', 'expired', 2147483647, 1, 5),
('bx_market_license_edit', 'controls', 2147483647, 1, 6),
('bx_market_license_edit', 'do_submit', 2147483647, 1, 7),
('bx_market_license_edit', 'do_cancel', 2147483647, 1, 8);
