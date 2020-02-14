-- TABLES
ALTER TABLE `bx_organizations_data` CHANGE `allow_post_to` `allow_post_to` varchar(16) NOT NULL DEFAULT '5';

ALTER TABLE `bx_organizations_pics` CHANGE `size` `size` bigint(20) NOT NULL;
ALTER TABLE `bx_organizations_pics_resized` CHANGE `size` `size` bigint(20) NOT NULL;


-- FORMS
UPDATE `sys_form_inputs` SET `value`='5' WHERE `object`='bx_organization' AND `name`='allow_post_to';

DELETE FROM `sys_form_inputs` WHERE `object`='bx_organization' AND `name`='allow_contact_to';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_organization', 'bx_organizations', 'allow_contact_to', 3, '', 0, 'custom', '_bx_orgs_form_profile_input_sys_allow_contact_to', '_bx_orgs_form_profile_input_allow_contact_to', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_organization_add', 'bx_organization_edit') AND `input_name`='allow_contact_to';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_organization_add', 'allow_contact_to', 2147483647, 1, 10),
('bx_organization_edit', 'allow_contact_to', 2147483647, 1, 9);
