SET @sName = 'bx_massmailer';


-- SETTINGS
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_massmailer_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_massmailer_initial_from_email', 'bx_massmailer_delete_sent_email_in_days');
INSERT INTO `sys_options` (`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'bx_massmailer_delete_sent_email_in_days', '_bx_massmailer_delete_sent_email_in_days', '365', 'digit', '', '', '', '', 1);


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_massmailer_cron';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_massmailer_cron', '0 1 * * *', 'BxMassMailerCron', 'modules/boonex/massmailer/classes/BxMassMailerCron.php', '');
