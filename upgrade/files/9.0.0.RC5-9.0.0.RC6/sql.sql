

UPDATE `sys_form_inputs` SET `db_pass` = 'XssHtml', `html` = 3 WHERE `object` = 'sys_comment' AND `module` = 'system' AND `name` = 'cmt_text';


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC6' WHERE (`version` = '9.0.0.RC5' OR `version` = '9.0.0-RC5') AND `name` = 'system';

