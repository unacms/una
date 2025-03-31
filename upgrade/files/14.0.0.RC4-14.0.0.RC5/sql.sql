
-- Forms

DELETE FROM `sys_objects_form` WHERE `object` = 'sys_agents_comment';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('sys_agents_comment', 'system', '_sys_form_agents_comment', 'cmts.php', 'a:3:{s:2:"id";s:20:"cmt-%s-form-%s-%d-%d";s:4:"name";s:20:"cmt-%s-form-%s-%d-%d";s:5:"class";s:14:"cmt-post-reply";}', 'cmt_submit', '', 'cmt_id', '', '', '', 0, 1, 'BxTemplCmtsForm', '');

DELETE FROM `sys_form_displays` WHERE `display_name` = 'sys_agents_comment_post';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('sys_agents_comment_post', 'system', 'sys_agents_comment', '_sys_form_display_agents_comment_post', 0);

DELETE FROM `sys_form_inputs` WHERE `object` = 'sys_agents_comment';
INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('sys_agents_comment', 'system', 'sys', '', '', 0, 'hidden', '_sys_form_agents_comment_input_caption_system_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_agents_comment', 'system', 'id', '', '', 0, 'hidden', '_sys_form_agents_comment_input_caption_system_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_agents_comment', 'system', 'action', '', '', 0, 'hidden', '_sys_form_agents_comment_input_caption_system_action', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('sys_agents_comment', 'system', 'cmt_parent_id', '', '', 0, 'hidden', '_sys_form_agents_comment_input_caption_system_cmt_parent_id', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('sys_agents_comment', 'system', 'cmt_text', '', '', 0, 'textarea', '_sys_form_agents_comment_input_caption_system_cmt_text', '', '', 0, 0, 3, 'a:1:{s:12:"autocomplete";s:3:"off";}', '', '', '', '', '', 'XssHtml', '', 1, 0),
('sys_agents_comment', 'system', 'cmt_image', 'a:1:{i:0;s:14:"sys_cmts_html5";}', 'a:1:{s:14:"sys_cmts_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_sys_form_agents_comment_input_caption_system_cmt_image', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('sys_agents_comment', 'system', 'cmt_submit', '_sys_form_agents_comment_input_caption_cmt_submit', '', 0, 'submit', '_sys_form_agents_comment_input_caption_system_cmt_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

DELETE FROM `sys_form_display_inputs` WHERE `display_name` = 'sys_agents_comment_post';
INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('sys_agents_comment_post', 'sys', 2147483647, 1, 1),
('sys_agents_comment_post', 'id', 2147483647, 1, 2),
('sys_agents_comment_post', 'action', 2147483647, 1, 3),
('sys_agents_comment_post', 'cmt_parent_id', 2147483647, 1, 4),
('sys_agents_comment_post', 'cmt_text', 2147483647, 1, 5),
('sys_agents_comment_post', 'cmt_submit', 2147483647, 1, 6),
('sys_agents_comment_post', 'cmt_image', 2147483647, 1, 7);

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-RC5' WHERE (`version` = '14.0.0.RC4' OR `version` = '14.0.0-RC4') AND `name` = 'system';

