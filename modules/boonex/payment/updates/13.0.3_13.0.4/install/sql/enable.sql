SET @sName = 'bx_payment';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_payment_wrong_balance';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_payment_et_txt_name_wrong_balance', 'bx_payment_wrong_balance', '_bx_payment_et_txt_subject_wrong_balance', '_bx_payment_et_txt_body_wrong_balance');
