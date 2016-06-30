-- FORMS
DELETE FROM `sys_form_displays` WHERE `object`='bx_person' AND `display_name`='bx_person_view_full';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_person', 'bx_person_view_full', 'bx_persons', 1, '_bx_persons_form_profile_display_view_full');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name` IN ('cover', 'cover_preview', 'picture', 'picture_preview', 'allow_view_to');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_person', 'bx_persons', 'cover', 'a:1:{i:0;s:21:"bx_persons_cover_crop";}', 'a:1:{s:21:"bx_persons_cover_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_persons_form_profile_input_sys_cover', '_bx_persons_form_profile_input_cover', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'picture', 'a:1:{i:0;s:23:"bx_persons_picture_crop";}', 'a:1:{s:23:"bx_persons_picture_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_persons_form_profile_input_sys_picture', '_bx_persons_form_profile_input_picture', '', 0, 0, 0, '', '', '', '', '', '_bx_persons_form_profile_input_picture_err', '', '', 1, 0),
('bx_person', 'bx_persons', 'allow_view_to', 3, '', 0, 'custom', '_bx_persons_form_profile_input_sys_allow_view_to', '_bx_persons_form_profile_input_allow_view_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

UPDATE `sys_form_inputs` SET `value`='_bx_persons_form_profile_input_submit' WHERE `object`='bx_person' AND `name`='do_submit';
UPDATE `sys_form_inputs` SET `db_pass`='XssMultiline' WHERE `object`='bx_person' AND `name`='description';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_add' AND `input_name` IN ('cover_preview', 'picture_preview', 'allow_view_to', 'do_submit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_add', 'allow_view_to', 2147483647, 1, 9),
('bx_person_add', 'do_submit', 2147483647, 1, 10);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_delete' AND `input_name` IN ('cover_preview', 'picture_preview');

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_edit' AND `input_name` IN ('cover_preview', 'picture_preview', 'allow_view_to', 'do_submit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_edit', 'allow_view_to', 2147483647, 1, 8),
('bx_person_edit', 'do_submit', 2147483647, 1, 9);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_edit_cover' AND `input_name` IN ('cover_preview', 'picture_preview');

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view' AND `input_name` IN ('cover_preview', 'picture_preview');
UPDATE `sys_form_display_inputs` SET `active`='0' WHERE `display_name`='bx_person_view' AND `input_name`='description';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view_full' AND `input_name` IN ('fullname', 'description');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_view_full', 'fullname', 2147483647, 1, 1),
('bx_person_view_full', 'description', 2147483647, 1, 2);


-- Update profile pic and cover
UPDATE `sys_storage_ghosts` AS `g`
INNER JOIN (SELECT `d`.`id`, `d`.`picture` FROM `bx_persons_data` AS `d`) AS `s` ON (`s`.`picture` = `g`.id)
SET `g`.`content_id` = `s`.`id`
WHERE `object` LIKE  'bx_persons_pictures';

UPDATE `sys_storage_ghosts` AS `g`
INNER JOIN (SELECT `d`.`id`, `d`.`cover` FROM `bx_persons_data` AS `d`) AS `s` ON (`s`.`cover` = `g`.id)
SET `g`.`content_id` = `s`.`id`
WHERE `object` LIKE  'bx_persons_pictures';