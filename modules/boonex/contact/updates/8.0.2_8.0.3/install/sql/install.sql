SET @sName = 'bx_contact';

-- FORMS
UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_contact_contact';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_contact_contact' AND `name`='captcha';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_contact_contact', @sName, 'captcha', '', '', 0, 'captcha', '_bx_contact_form_contact_input_sys_captcha', '_bx_contact_form_contact_input_captcha', '', 1, 0, 0, '', '', '', 'Captcha', '', '_bx_contact_form_contact_input_captcha_err', '', '', 1, 0);

UPDATE `sys_form_display_inputs` SET `order`='6' WHERE `display_name`='bx_contact_contact_send' AND `input_name`='do_submit';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_contact_contact_send' AND `input_name`='captcha';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_contact_contact_send', 'captcha', 2147483647, 1, 5);