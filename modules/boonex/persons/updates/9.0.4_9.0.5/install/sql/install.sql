-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name` IN ('birthday', 'gender', 'profile_email', 'profile_status');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_person', 'bx_persons', 'birthday', 0, '', 0, 'datepicker', '_bx_persons_form_profile_input_sys_birthday', '_bx_persons_form_profile_input_birthday', '', 0, 0, 0, '', '', '', '', '', '', 'Date', '', 1, 0),
('bx_person', 'bx_persons', 'gender', '', '#!Sex', 0, 'select', '_bx_persons_form_profile_input_sys_gender', '_bx_persons_form_profile_input_gender', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_person', 'bx_persons', 'profile_email', '', '', 0, 'text', '_bx_persons_form_profile_input_sys_profile_email', '_bx_persons_form_profile_input_profile_email', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_person', 'bx_persons', 'profile_status', '', '', 0, 'text', '_bx_persons_form_profile_input_sys_profile_status', '_bx_persons_form_profile_input_profile_status', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_person_add', 'bx_person_edit', 'bx_person_view', 'bx_person_view_full');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_add', 'picture', 2147483647, 1, 1),
('bx_person_add', 'gender', 2147483647, 1, 2),
('bx_person_add', 'birthday', 2147483647, 1, 3),
('bx_person_add', 'fullname', 2147483647, 1, 4),
('bx_person_add', 'description', 2147483647, 1, 5),
('bx_person_add', 'location', 2147483647, 1, 6),
('bx_person_add', 'allow_view_to', 2147483647, 1, 7),
('bx_person_add', 'do_submit', 2147483647, 1, 8),

('bx_person_edit', 'picture', 2147483647, 1, 1),
('bx_person_edit', 'gender', 2147483647, 1, 2),
('bx_person_edit', 'birthday', 2147483647, 1, 3),
('bx_person_edit', 'fullname', 2147483647, 1, 4),
('bx_person_edit', 'description', 2147483647, 1, 5),
('bx_person_edit', 'location', 2147483647, 1, 6),
('bx_person_edit', 'allow_view_to', 2147483647, 1, 7),
('bx_person_edit', 'do_submit', 2147483647, 1, 8),

('bx_person_view', 'gender', 2147483647, 1, 1),
('bx_person_view', 'birthday', 2147483647, 1, 2),
('bx_person_view', 'fullname', 2147483647, 1, 3),
('bx_person_view', 'profile_email', 192, 1, 4),
('bx_person_view', 'profile_status', 192, 1, 5),

('bx_person_view_full', 'gender', 2147483647, 1, 1),
('bx_person_view_full', 'birthday', 2147483647, 1, 2),
('bx_person_view_full', 'fullname', 2147483647, 1, 3),
('bx_person_view_full', 'description', 2147483647, 1, 4),
('bx_person_view_full', 'profile_email', 192, 1, 5),
('bx_person_view_full', 'profile_status', 192, 1, 6);


-- CONTENT INFO
DELETE FROM `sys_objects_content_info` WHERE `name` IN ('bx_persons', 'bx_persons_cmts');
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_persons', '_bx_persons', 'bx_persons', 'added', 'edited', 'deleted', '', ''),
('bx_persons_cmts', '_bx_persons_cmts', 'bx_persons', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

DELETE FROM `sys_content_info_grids` WHERE `object`='bx_persons';
INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_persons', 'bx_persons_administration', 'td`.`id', '', ''),
('bx_persons', 'bx_persons_common', 'td`.`id', '', '');


-- SEARCH EXTENDED
DELETE FROM `sys_objects_search_extended` WHERE `module`='bx_persons';
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_persons', 'bx_persons', 'bx_persons', '_bx_persons_search_extended', 1, '', ''),
('bx_persons_cmts', 'bx_persons_cmts', 'bx_persons', '_bx_persons_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- REASSIGN PROFILES
UPDATE `sys_accounts` AS `a` INNER JOIN `sys_profiles` AS `p` ON `a`.`id`=`p`.`account_id` AND `p`.`type`<>'system' AND `a`.`profile_id`='0' SET `a`.`profile_id`=`p`.`id`;
UPDATE `sys_accounts` AS `a` INNER JOIN `sys_profiles` AS `p` ON `a`.`id`=`p`.`account_id` AND `p`.`type`='system' AND `a`.`profile_id`='0' SET `a`.`profile_id`=`p`.`id`;