SET @sName = 'bx_acl';


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_acl_subscription_cancel_required';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES 
(@sName, '_bx_acl_et_txt_name_subscription_cancel_required', 'bx_acl_subscription_cancel_required', '_bx_acl_et_txt_subject_subscription_cancel_required', '_bx_acl_et_txt_body_subscription_cancel_required');
