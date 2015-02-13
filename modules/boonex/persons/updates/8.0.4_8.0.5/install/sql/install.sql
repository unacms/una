-- TABLES
ALTER TABLE `bx_persons_data` DROP `sex`;
ALTER TABLE `bx_persons_data` ADD `description` text NOT NULL AFTER `fullname`;


-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"32";s:1:"h";s:2:"32";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"96";s:1:"h";s:2:"96";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_avatar';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";}' WHERE `transcoder_object`='bx_persons_picture';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_cover';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_cover_thumb';


-- FORMS
UPDATE `sys_form_inputs` SET `checker_func`='Avail' WHERE `object`='bx_person' AND `name` IN ('delete_confirm', 'fullname', 'picture');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name`='description';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_person', 'bx_persons', 'description', '', '', 0, 'textarea', '_bx_persons_form_profile_input_sys_desc', '_bx_persons_form_profile_input_desc', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 1);

DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name`='sex';

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_person_add', 'bx_person_delete', 'bx_person_edit', 'bx_person_edit_cover', 'bx_person_view') AND `input_name`='sex';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_add' AND `input_name`='description';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_add', 'description', 2147483647, 1, 7);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_edit' AND `input_name`='description';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_edit', 'description', 2147483647, 1, 7);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view' AND `input_name`='description';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_view', 'description', 2147483647, 1, 8);