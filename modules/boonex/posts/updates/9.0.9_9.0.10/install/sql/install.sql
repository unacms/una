-- TABLES
ALTER TABLE `bx_posts_posts` CHANGE `author` `author` int(11) NOT NULL;
ALTER TABLE `bx_posts_cmts` CHANGE `cmt_author_id` `cmt_author_id` INT( 11 ) NOT NULL DEFAULT '0';

-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_posts' AND `name` IN ('labels', 'anonymous');
INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_posts', 'bx_posts', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_posts', 'bx_posts', 'anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);
