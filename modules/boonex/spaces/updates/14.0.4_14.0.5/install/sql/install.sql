-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name`='bx_space_edit_settings';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_space', 'bx_space_edit_settings', 'bx_spaces', 0, '_bx_spaces_form_profile_display_edit_settings');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_space' AND `name`='stg_tabs';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_space', 'bx_spaces', 'stg_tabs', 1, '', 0, 'checkbox_set', '_bx_spaces_form_profile_input_sys_stg_tabs', '_bx_spaces_form_profile_input_stg_tabs', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_space_edit_settings';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_space_edit_settings', 'stg_tabs', 2147483647, 1, 1),
('bx_space_edit_settings', 'do_submit', 2147483647, 1, 2);
