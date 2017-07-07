-- SETTINGS
SET @iTypeId = (SELECT `id` FROM `sys_options_types` WHERE `name`='bx_antispam' LIMIT 1);

DELETE FROM `sys_options_categories` WHERE `name`='bx_antispam_disposable_email_domains';
INSERT INTO `sys_options_categories` (`type_id`, `name`, `caption`, `order` )  
VALUES (@iTypeId, 'bx_antispam_disposable_email_domains', '_bx_antispam_adm_stg_cpt_category_disposable_email_domains', 6);
SET @iCategoryId = LAST_INSERT_ID();

DELETE FROM `sys_options` WHERE `name` IN ('bx_antispam_disposable_email_domains_mode', 'bx_antispam_disposable_email_domains_behaviour_join');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_antispam_disposable_email_domains_mode', '_bx_antispam_option_disposable_email_domains_mode', 'blacklist', 'select', 'a:3:{s:6:"module";s:11:"bx_antispam";s:6:"method";s:13:"config_values";s:6:"params";a:1:{i:0;s:29:"disposable_email_domains_mode";}}', '', '', 10),
(@iCategoryId, 'bx_antispam_disposable_email_domains_behaviour_join', '_bx_antispam_option_disposable_email_domains_behaviour_join', 'block', 'select', 'a:3:{s:6:"module";s:11:"bx_antispam";s:6:"method";s:13:"config_values";s:6:"params";a:1:{i:0;s:29:"disposable_email_domains_join";}}', '', '', 20);



-- CRONS
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_antispam_update_disposable_email_domains';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_antispam_update_disposable_email_domains', '0 0 */14 * *', 'BxAntispamCronUpdateDisposableEmailDomains', 'modules/boonex/antispam/classes/BxAntispamCronUpdateDisposableEmailDomains.php', '');