SET @sName = 'bx_payment';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name` IN ('bx_payment_expiration_notification_seller', 'bx_payment_expiration_notification_client');
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_payment_et_txt_name_expiration_notification_seller', 'bx_payment_expiration_notification_seller', '_bx_payment_et_txt_subject_expiration_notification_seller', '_bx_payment_et_txt_body_expiration_notification_seller'),
(@sName, '_bx_payment_et_txt_name_expiration_notification_client', 'bx_payment_expiration_notification_client', '_bx_payment_et_txt_subject_expiration_notification_client', '_bx_payment_et_txt_body_expiration_notification_client');
