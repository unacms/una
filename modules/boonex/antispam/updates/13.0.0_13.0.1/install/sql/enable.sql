-- SETTINGS
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`='bx_antispam' LIMIT 1);

DELETE FROM `sys_options_categories` WHERE `name`='bx_antispam_toxicity_filter';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )
VALUES (@iTypeId, 'bx_antispam_toxicity_filter', '_bx_antispam_adm_stg_cpt_category_toxicity_filter', 8);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` LIKE 'bx_antispam_toxicity_filter_%';
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_antispam_toxicity_filter_enable', '_bx_antispam_option_toxicity_filter_enable', '', 'checkbox', '', '', '', 10),
(@iCategoryId, 'bx_antispam_toxicity_filter_api_key', '_bx_antispam_option_toxicity_filter_api_key', '', 'digit', '', '', '', 20),
(@iCategoryId, 'bx_antispam_toxicity_filter_action', '_bx_antispam_option_toxicity_filter_action', 'none', 'select', 'a:2:{s:6:"module";s:11:"bx_antispam";s:6:"method";s:27:"get_toxicity_filter_actions";}', '', '', 30),
(@iCategoryId, 'bx_antispam_toxicity_filter_report', '_bx_antispam_option_toxicity_report', '', 'checkbox', 'on', '', '', 40),
(@iCategoryId, 'bx_antispam_toxicity_filter_threshold', '_bx_antispam_option_toxicity_filter_threshold', '60', 'digit', '', '', '', 50);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_antispam' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action` IN ('form_check', 'form_submitted') AND `handler_id`=@iHandler;
DELETE FROM `sys_alerts` WHERE `unit`='comment' AND `action` IN ('added', 'edited') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'form_check', @iHandler),
('system', 'form_submitted', @iHandler),
('comment', 'added', @iHandler),
('comment', 'edited', @iHandler);


-- EMAIL TEMPLATES
DELETE FROM `sys_email_templates` WHERE `Name` IN ('bx_antispam_toxicity_blocked_report', 'bx_antispam_toxicity_posted_report');
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('bx_antispam', '_bx_antispam_toxicity_blocked_report_name', 'bx_antispam_toxicity_blocked_report', '_bx_antispam_toxicity_blocked_report_subject', '_bx_antispam_toxicity_blocked_report_body'),
('bx_antispam', '_bx_antispam_toxicity_posted_report_name', 'bx_antispam_toxicity_posted_report', '_bx_antispam_toxicity_posted_report_subject', '_bx_antispam_toxicity_posted_report_body');
