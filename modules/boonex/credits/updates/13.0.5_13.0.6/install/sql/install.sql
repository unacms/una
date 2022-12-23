-- FORMS
UPDATE `sys_form_inputs` SET `info`='_bx_credits_form_credit_input_cleared_inf' WHERE `object`='bx_credits_credit' AND `name`='cleared';
UPDATE `sys_form_inputs` SET `info`='_bx_credits_form_credit_input_amount_inf' WHERE `object`='bx_credits_credit' AND `name`='amount';

DELETE FROM `sys_objects_form` WHERE `object`='bx_credits_profile';
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_credits_profile', 'bx_credits', '_bx_credits_form_profile', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_credits_profiles', 'id', '', '', 'a:1:{i:0;s:9:"do_submit";}', '', 0, 1, 'BxCreditsFormProfile', 'modules/boonex/credits/classes/BxCreditsFormProfile.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_credits_profile';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_credits_profile', 'bx_credits_profile_edit', 'bx_credits', 0, '_bx_credits_form_profile_display_edit');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_credits_profile';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_credits_profile', 'bx_credits', 'wdw_clearing', '', '', 0, 'text', '_bx_credits_form_profile_input_sys_wdw_clearing', '_bx_credits_form_profile_input_wdw_clearing', '_bx_credits_form_profile_input_wdw_clearing_inf', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_credits_profile', 'bx_credits', 'wdw_minimum', '', '', 0, 'text', '_bx_credits_form_profile_input_sys_wdw_minimum', '_bx_credits_form_profile_input_wdw_minimum', '_bx_credits_form_profile_input_wdw_minimum_inf', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_credits_profile', 'bx_credits', 'wdw_remaining', '', '', 0, 'text', '_bx_credits_form_profile_input_sys_wdw_remaining', '_bx_credits_form_profile_input_wdw_remaining', '_bx_credits_form_profile_input_wdw_remaining_inf', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_credits_profile', 'bx_credits', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_credits_profile', 'bx_credits', 'do_submit', '_bx_credits_form_profile_input_do_submit', '', 0, 'submit', '_bx_credits_form_profile_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_credits_profile', 'bx_credits', 'do_cancel', '_bx_credits_form_profile_input_do_cancel', '', 0, 'button', '_bx_credits_form_bundle_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_credits_profile_edit';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_credits_profile_edit', 'wdw_clearing', 2147483647, 1, 1),
('bx_credits_profile_edit', 'wdw_minimum', 2147483647, 1, 2),
('bx_credits_profile_edit', 'wdw_remaining', 2147483647, 1, 3),
('bx_credits_profile_edit', 'controls', 2147483647, 1, 4),
('bx_credits_profile_edit', 'do_submit', 2147483647, 1, 5),
('bx_credits_profile_edit', 'do_cancel', 2147483647, 1, 6);
