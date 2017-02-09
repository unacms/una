-- TABLE
ALTER TABLE `bx_groups_pics` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;
ALTER TABLE `bx_groups_pics_resized` CHANGE `remote_id` `remote_id` VARCHAR(128) NOT NULL;


-- FORMS
DELETE FROM `sys_form_displays` WHERE `display_name` IN ('bx_group_invite');
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_group', 'bx_group_invite', 'bx_groups', 0, '_bx_groups_form_profile_display_invite');

DELETE FROM `sys_form_display_inputs` WHERE `display_name` IN ('bx_group_invite');
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_group_invite', 'initial_members', 2147483647, 1, 1),
('bx_group_invite', 'do_submit', 2147483647, 1, 2);