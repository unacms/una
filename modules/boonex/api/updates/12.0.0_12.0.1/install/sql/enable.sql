
-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_api_password_reset';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
('bx_api', '_bx_api_et_txt_name_password_reset', 'bx_api_password_reset', '_bx_api_et_txt_subject_password_reset', '_bx_api_et_txt_body_password_reset');
