SET @sName = 'bx_forum';

-- TABLES
ALTER TABLE `bx_forum_discussions` CHANGE `author` `author` int(11) NOT NULL;
ALTER TABLE `bx_forum_discussions` CHANGE `lr_profile_id` `lr_profile_id` int(11) NOT NULL;

ALTER TABLE `bx_forum_cmts` CHANGE `cmt_author_id` `cmt_author_id` int(11) NOT NULL DEFAULT '0';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`=@sName AND `name` IN ('labels', 'anonymous', 'added', 'changed');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'added', '', '', 0, 'datetime', '_bx_forum_form_entry_input_sys_date_added', '_bx_forum_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'changed', '', '', 0, 'datetime', '_bx_forum_form_entry_input_sys_date_changed', '_bx_forum_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


-- REPORTS
DELETE FROM `sys_objects_report` WHERE `name`='bx_forum';
INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_forum', 'bx_forum_reports', 'bx_forum_reports_track', '1', 'page.php?i=view-discussion&id={object_id}', 'bx_forum_discussions', 'id', 'author', 'reports', '', '');
