SET @sName = 'bx_timeline';


-- TABLES
ALTER TABLE `bx_timeline_events` CHANGE `owner_id` `owner_id` int(11) NOT NULL default '0';
ALTER TABLE `bx_timeline_comments` CHANGE `cmt_author_id` `cmt_author_id` INT( 11 ) NOT NULL DEFAULT '0';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_timeline_post' AND `name`='anonymous';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_timeline_post', @sName, 'anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

UPDATE `sys_objects_form` SET `submit_name`='tlb_do_submit' WHERE `object`='bx_timeline_post';

UPDATE `sys_form_inputs` SET `name`='tlb_do_submit' WHERE `object`='bx_timeline_post' AND `name`='do_submit';
UPDATE `sys_form_inputs` SET `name`='tlb_do_cancel' WHERE `object`='bx_timeline_post' AND `name`='do_cancel';
UPDATE `sys_form_inputs` SET `values`='tlb_do_submit,tlb_do_cancel' WHERE `object`='bx_timeline_post' AND `name`='controls';

UPDATE `sys_form_display_inputs` SET `input_name`='tlb_do_submit' WHERE `display_name` IN ('bx_timeline_post_add', 'bx_timeline_post_add_public', 'bx_timeline_post_add_profile', 'bx_timeline_post_edit') AND `input_name`='do_submit';
UPDATE `sys_form_display_inputs` SET `input_name`='tlb_do_cancel' WHERE `display_name` IN ('bx_timeline_post_edit') AND `input_name`='do_cancel';
