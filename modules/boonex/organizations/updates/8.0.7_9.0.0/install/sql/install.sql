ALTER TABLE `bx_organizations_data` ADD `allow_view_to` int(11) NOT NULL DEFAULT '3' AFTER `views`;


-- FORMS
DELETE FROM `sys_form_displays` WHERE `object`='bx_organization' AND `display_name`='bx_organization_view_full';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_organization', 'bx_organization_view_full', 'bx_organizations', 1, '_bx_orgs_form_profile_display_view_full');

DELETE FROM `sys_form_inputs` WHERE `object`='bx_organization' AND `name` IN ('cover', 'cover_preview', 'picture', 'picture_preview', 'allow_view_to');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_organization', 'bx_organizations', 'cover', 'a:1:{i:0;s:27:"bx_organizations_cover_crop";}', 'a:1:{s:27:"bx_organizations_cover_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_orgs_form_profile_input_sys_cover', '_bx_orgs_form_profile_input_cover', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'picture', 'a:1:{i:0;s:29:"bx_organizations_picture_crop";}', 'a:1:{s:29:"bx_organizations_picture_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_orgs_form_profile_input_sys_picture', '_bx_orgs_form_profile_input_picture', '', 0, 0, 0, '', '', '', '', '', '_bx_orgs_form_profile_input_picture_err', '', '', 1, 0),
('bx_organization', 'bx_organizations', 'allow_view_to', 3, '', 0, 'custom', '_bx_orgs_form_profile_input_sys_allow_view_to', '_bx_orgs_form_profile_input_allow_view_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

UPDATE `sys_form_inputs` SET `db_pass`='XssMultiline' WHERE `object`='bx_organization' AND `name`='org_desc';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_add' AND `input_name` IN ('cover_preview', 'picture_preview', 'allow_view_to', 'do_submit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_organization_add', 'allow_view_to', 2147483647, 1, 9),
('bx_organization_add', 'do_submit', 2147483647, 1, 10);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_delete' AND `input_name` IN ('cover_preview', 'picture_preview');

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_edit' AND `input_name` IN ('cover_preview', 'picture_preview', 'allow_view_to', 'do_submit');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_organization_edit', 'allow_view_to', 2147483647, 1, 9),
('bx_organization_edit', 'do_submit', 2147483647, 1, 10);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_view' AND `input_name` IN ('cover_preview', 'picture_preview');
UPDATE `sys_form_display_inputs` SET `active`='0' WHERE `display_name`='bx_organization_view' AND `input_name`='org_desc';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_view_full' AND `input_name` IN ('org_name', 'org_cat', 'org_desc');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_organization_view_full', 'org_name', 2147483647, 1, 1),
('bx_organization_view_full', 'org_cat', 2147483647, 1, 2),
('bx_organization_view_full', 'org_desc', 2147483647, 1, 3);