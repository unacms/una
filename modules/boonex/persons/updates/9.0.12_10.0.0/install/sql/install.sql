-- TABLES
ALTER TABLE `bx_persons_data` CHANGE `allow_view_to` `allow_view_to` VARCHAR(16) NOT NULL DEFAULT '3';


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_persons_avatar_big';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_persons_avatar_big', 'bx_persons_pictures_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_persons_pictures";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_persons_avatar_big';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_persons_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_persons_avatar';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:3:"960";s:1:"h";s:3:"480";}' WHERE `transcoder_object`='bx_persons_cover';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_person' AND `name` IN ('allow_post_to', 'friends_count', 'followers_count');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_person', 'bx_persons', 'allow_post_to', 3, '', 0, 'custom', '_bx_persons_form_profile_input_sys_allow_post_to', '_bx_persons_form_profile_input_allow_post_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'friends_count', '', '', 0, 'text', '_bx_persons_form_profile_input_sys_friends_count', '_bx_persons_form_profile_input_friends_count', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_person', 'bx_persons', 'followers_count', '', '', 0, 'text', '_bx_persons_form_profile_input_sys_followers_count', '_bx_persons_form_profile_input_followers_count', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_inputs` SET `editable`='0' WHERE `object`='bx_person' AND `name` IN ('profile_email', 'profile_status', 'profile_ip');

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_person_add', 'bx_person_edit') AND `input_name`='allow_post_to';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_add', 'allow_post_to', 2147483647, 1, 8),
('bx_person_edit', 'allow_post_to', 2147483647, 1, 8);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view' AND `input_name` IN ('friends_count', 'followers_count');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_person_view', 'friends_count', 2147483647, 1, 9),
('bx_person_view', 'followers_count', 2147483647, 1, 10);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_person_view_full' AND `input_name`='description';
