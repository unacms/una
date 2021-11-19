
UPDATE `sys_options` SET `type` = 'digit' WHERE `name` = 'sys_editor_default';
UPDATE `sys_options` SET `value` = 'sys_quill' WHERE `name` = 'sys_editor_default' AND `value` IN('sys_recaptcha_new', 'sys_recaptcha_invisible', 'sys_hcaptcha', 'sys_recaptcha', '');

-- Forms

UPDATE `sys_form_inputs` SET `value` = '', `values` = '' WHERE `object` IN('sys_comment','sys_review') AND `name` IN('sys', 'id', 'action', 'cmt_id', 'cmt_parent_id', 'cmt_text', 'cmt_anonymous', 'cmt_mood');
UPDATE `sys_form_inputs` SET `value` = '', `values` = 'cmt_submit,cmt_cancel' WHERE `object` IN('sys_comment') AND `name` IN('cmt_controls');
UPDATE `sys_form_inputs` SET `value` = '_sys_form_comment_input_cancel', `values` = '' WHERE `object` IN('sys_comment') AND `name` IN('cmt_cancel');
UPDATE `sys_form_inputs` SET `value` = '_sys_form_comment_input_submit', `values` = '' WHERE `object` IN('sys_comment') AND `name` IN('cmt_submit');
UPDATE `sys_form_inputs` SET `value` = '_sys_form_review_input_submit', `values` = '' WHERE `object` IN('sys_review') AND `name` IN('cmt_submit');

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '13.0.0-A2' WHERE (`version` = '13.0.0.A1' OR `version` = '13.0.0-A1') AND `name` = 'system';

