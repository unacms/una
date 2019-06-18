-- STORAGES & TRANSCODERS
DELETE FROM `sys_objects_transcoder` WHERE `object`='bx_organizations_avatar_big';
INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_organizations_avatar_big', 'bx_organizations_pics_resized', 'Storage', 'a:1:{s:6:"object";s:21:"bx_organizations_pics";}', 'no', '1', '2592000', '0', '', '');

DELETE FROM `sys_transcoder_filters` WHERE `transcoder_object`='bx_organizations_avatar_big';
INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_organizations_avatar_big', 'Resize', 'a:3:{s:1:"w";s:3:"200";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', '0');

UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"30";s:1:"h";s:2:"30";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_organizations_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"50";s:1:"h";s:2:"50";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_organizations_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_organizations_avatar';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:2:{s:1:"w";s:3:"960";s:1:"h";s:3:"480";}' WHERE `transcoder_object`='bx_organizations_cover';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_organization' AND `name` IN ('allow_post_to', 'friends_count', 'followers_count');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_organization', 'bx_organizations', 'allow_post_to', 3, '', 0, 'custom', '_bx_orgs_form_profile_input_sys_allow_post_to', '_bx_orgs_form_profile_input_allow_post_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'friends_count', '', '', 0, 'text', '_bx_orgs_form_profile_input_sys_friends_count', '_bx_orgs_form_profile_input_friends_count', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'followers_count', '', '', 0, 'text', '_bx_orgs_form_profile_input_sys_followers_count', '_bx_orgs_form_profile_input_followers_count', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_inputs` SET `editable`='0' WHERE `object`='bx_organization' AND `name` IN ('profile_email', 'profile_status', 'profile_ip');

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_organization_add', 'bx_organization_edit') AND `input_name`='allow_post_to';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_organization_add', 'allow_post_to', 2147483647, 1, 9),
('bx_organization_edit', 'allow_post_to', 2147483647, 1, 8);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_view' AND `input_name` IN ('friends_count', 'followers_count');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_organization_view', 'friends_count', 2147483647, 1, 8),
('bx_organization_view', 'followers_count', 2147483647, 1, 9);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_view_full' AND `input_name`='org_desc';
