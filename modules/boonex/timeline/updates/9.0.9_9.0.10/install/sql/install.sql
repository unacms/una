SET @sName = 'bx_timeline';


-- TABLES
ALTER TABLE `bx_timeline_hot_track` CHANGE `value` `value` INT(11) NOT NULL DEFAULT '0';


-- FORMS
UPDATE `sys_form_display_inputs` SET `order`='2' WHERE `display_name`='bx_timeline_post_add' AND `input_name`='text';
UPDATE `sys_form_display_inputs` SET `order`='3' WHERE `display_name`='bx_timeline_post_add' AND `input_name`='attachments';

UPDATE `sys_form_display_inputs` SET `order`='3' WHERE `display_name`='bx_timeline_post_add_public' AND `input_name`='text';
UPDATE `sys_form_display_inputs` SET `order`='4' WHERE `display_name`='bx_timeline_post_add_public' AND `input_name`='attachments';

UPDATE `sys_form_display_inputs` SET `order`='3' WHERE `display_name`='bx_timeline_post_add_profile' AND `input_name`='text';
UPDATE `sys_form_display_inputs` SET `order`='4' WHERE `display_name`='bx_timeline_post_add_profile' AND `input_name`='attachments';
