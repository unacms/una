-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name`='profile_last_active';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`, `rateable`) VALUES 
('bx_person', 'bx_persons', 'profile_last_active', '', '', 0, 'text', '_bx_persons_form_profile_input_sys_profile_last_active', '_bx_persons_form_profile_input_profile_last_active', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0, '');

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_person_view', 'bx_person_view_full') AND `input_name`='profile_last_active';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_view', 'profile_last_active', 192, 1, 7),
('bx_person_view_full', 'profile_last_active', 192, 1, 7);
