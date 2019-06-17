-- TABLES
ALTER TABLE `bx_groups_data` CHANGE `allow_view_to` `allow_view_to` varchar(16) NOT NULL DEFAULT '3';


-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_groups_avatar_big';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_groups_avatar_big', 'bx_groups_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_groups_pics";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_groups_avatar_big';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_groups_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_groups_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_groups_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_groups_avatar';

-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_group' AND `name` IN ('allow_post_to', 'labels');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_group', 'bx_groups', 'allow_post_to', 3, '', 0, 'custom', '_bx_groups_form_profile_input_sys_allow_post_to', '_bx_groups_form_profile_input_allow_post_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_group', 'bx_groups', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_group_add' AND `input_name` IN ('picture', 'allow_post_to');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_group_add', 'allow_post_to', 2147483647, 1, 8);


DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_group_edit' AND `input_name` IN ('picture', 'allow_post_to');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_group_edit', 'allow_post_to', 2147483647, 1, 7);
