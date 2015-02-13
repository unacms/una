-- STORAGES & TRANSCODERS
UPDATE `sys_transcoder_filters` SET `filter_params`='a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}' WHERE `transcoder_object`='bx_posts_preview';

-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name`='do_publish';
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'do_publish', '_bx_posts_form_entry_input_do_publish', '', 0, 'submit', '_bx_posts_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_form_inputs` SET `editable`='1' WHERE `object`='bx_posts' AND `name`='location';

UPDATE `sys_form_display_inputs` SET `active`='0' WHERE `display_name`='bx_posts_entry_add' AND `input_name`='do_submit';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_add' AND `input_name`='do_publish';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_add', 'do_publish', 2147483647, 1, 8);

UPDATE `sys_form_display_inputs` SET `active`='1' WHERE `display_name`='bx_posts_entry_view' AND `input_name`='text';

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_posts_entry_view' AND `input_name`='location';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_posts_entry_view', 'location', 2147483647, 0, 0);