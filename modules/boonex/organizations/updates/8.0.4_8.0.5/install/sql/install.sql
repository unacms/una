-- TABLES
ALTER TABLE `bx_organizations_data` ADD `org_desc` text NOT NULL AFTER `org_cat`;


-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"32";s:1:"h";s:2:"32";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_organizations_icon';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_organizations_thumb';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"96";s:1:"h";s:2:"96";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_organizations_avatar';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";}' WHERE `transcoder_object`='bx_organizations_picture';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_organizations_cover';
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}' WHERE `transcoder_object`='bx_organizations_cover_thumb';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_organization' AND `name`='org_desc';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_organization', 'bx_organizations', 'org_desc', '', '', 0, 'textarea', '_bx_orgs_form_profile_input_sys_org_desc', '_bx_orgs_form_profile_input_org_desc', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 1);

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_add' AND `input_name`='org_desc';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_organization_add', 'org_desc', 2147483647, 1, 8);

UPDATE `sys_form_display_inputs` SET `order`='9' WHERE `display_name`='bx_organization_add' AND `input_name`='do_submit';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_edit' AND `input_name`='org_desc';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_organization_edit', 'org_desc', 2147483647, 1, 8);

UPDATE `sys_form_display_inputs` SET `order`='9' WHERE `display_name`='bx_organization_edit' AND `input_name`='do_submit';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_organization_view' AND `input_name`='org_desc';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_organization_view', 'org_desc', 2147483647, 1, 9);