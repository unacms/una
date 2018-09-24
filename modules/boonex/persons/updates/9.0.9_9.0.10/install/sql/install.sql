-- TABLES
ALTER TABLE `bx_persons_cmts` CHANGE `cmt_author_id` `cmt_author_id` INT( 11 ) NOT NULL DEFAULT '0';


-- FORMS
UPDATE `sys_form_inputs` SET `checker_params`='a:3:{s:3:"min";i:18;s:3:"max";i:99;s:8:"required";b:0;}' WHERE `object`='bx_person' AND `name`='birthday';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name` IN ('labels', 'added', 'changed');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_person', 'bx_persons', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'added', '', '', 0, 'datetime', '_bx_persons_form_profile_input_sys_date_added', '_bx_persons_form_profile_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'changed', '', '', 0, 'datetime', '_bx_persons_form_profile_input_sys_date_changed', '_bx_persons_form_profile_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view' AND `input_name` IN ('added', 'changed');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_view', 'added', 192, 1, 7),
('bx_person_view', 'changed', 192, 1, 8);
