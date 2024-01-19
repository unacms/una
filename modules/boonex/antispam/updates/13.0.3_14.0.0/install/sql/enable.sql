-- SETTINGS
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`='bx_antispam' LIMIT 1);

DELETE FROM `sys_options_categories` WHERE `name`='bx_antispam_lasso_moderation';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )
VALUES (@iTypeId, 'bx_antispam_lasso_moderation', '_bx_antispam_adm_stg_cpt_category_lasso_moderation', 9);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` LIKE 'bx_antispam_lasso_moderation_%';
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_antispam_lasso_moderation_enable', '_bx_antispam_option_lasso_moderation_enable', '', 'checkbox', '', '', '', 10),
(@iCategoryId, 'bx_antispam_lasso_moderation_api_key', '_bx_antispam_option_lasso_moderation_api_key', '', 'digit', '', '', '', 20),
(@iCategoryId, 'bx_antispam_lasso_moderation_webhook_secret', '_bx_antispam_option_lasso_moderation_webhook_secret', '', 'digit', '', '', '', 30),
(@iCategoryId, 'bx_antispam_lasso_moderation_webhook_url', '_bx_antispam_option_lasso_moderation_webhook_url', '{site_url}m/antispam/content_checked', 'value', '', '', '', 40),
(@iCategoryId, 'bx_antispam_lasso_moderation_action', '_bx_antispam_option_lasso_moderation_action', 'none', 'select', 'a:2:{s:6:"module";s:11:"bx_antispam";s:6:"method";s:28:"get_lasso_moderation_actions";}', '', '', 50),
(@iCategoryId, 'bx_antispam_lasso_moderation_report', '_bx_antispam_option_lasso_moderation_report', '', 'checkbox', 'on', '', '', 60),
(@iCategoryId, 'bx_antispam_lasso_moderation_threshold_toxicity', '_bx_antispam_option_lasso_moderation_threshold_toxicity', '50', 'digit', '', '', '', 70),
(@iCategoryId, 'bx_antispam_lasso_moderation_threshold_threat', '_bx_antispam_option_lasso_moderation_threshold_threat', '50', 'digit', '', '', '', 72),
(@iCategoryId, 'bx_antispam_lasso_moderation_threshold_identity_attack', '_bx_antispam_option_lasso_moderation_threshold_identity_attack', '50', 'digit', '', '', '', 74),
(@iCategoryId, 'bx_antispam_lasso_moderation_threshold_profanity', '_bx_antispam_option_lasso_moderation_threshold_profanity', '50', 'digit', '', '', '', 76);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name`='bx_antispam_lasso_moderation_report';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_antispam', '_bx_antispam_et_lasso_moderation_report_name', 'bx_antispam_lasso_moderation_report', '_bx_antispam_et_lasso_moderation_report_subject', '_bx_antispam_et_lasso_moderation_report_body');
