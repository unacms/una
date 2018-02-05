
ALTER DATABASE CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

-- CREATE TABLES

CREATE TABLE IF NOT EXISTS `sys_queue_push` (
  `id` int(11) NOT NULL auto_increment,
  `profile_id` int(11) NOT NULL default '0',
  `message` text NOT NULL default '',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_queue_email` (
  `id` int(11) NOT NULL auto_increment,
  `email` varchar(64) NOT NULL default '',
  `subject` varchar(255) NOT NULL default '',
  `body` text NOT NULL default '',
  `headers` text NOT NULL default '',
  `params` text NOT NULL default '',
  PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `sys_cmts_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- OPTIONS

DELETE FROM `sys_options` WHERE `name` IN ('sys_eq_send_per_start', 'sys_push_queue_send_per_start');

SET @iCategoryIdNotifications = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'notifications');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdNotifications, 'sys_eq_send_per_start', '_adm_stg_cpt_option_sys_eq_send_per_start', '20',  'digit', '', '', '', 10);

SET @iCategoryIdNotificationsPush = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'notifications_push');
INSERT INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryIdNotificationsPush, 'sys_push_queue_send_per_start', '_adm_stg_cpt_option_sys_push_queue_send_per_start', '20',  'digit', '', '', '', 10);

-- INJECTIONS

DELETE FROM `sys_injections` WHERE `name` IN('sys_popup_alert', 'sys_popup_confirm', 'sys_popup_prompt', 'sys_push_init', 'live_updates');
DELETE FROM `sys_injections_admin` WHERE `name` IN('sys_popup_alert', 'sys_popup_confirm', 'sys_popup_prompt');

-- CRON

DELETE FROM `sys_cron_jobs` WHERE `name` IN ('sys_queue_email', 'sys_queue_push');
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_queue_email', '* * * * *', 'BxDolCronQueueEmail', 'inc/classes/BxDolCronQueueEmail.php', ''),
('sys_queue_push', '* * * * *', 'BxDolCronQueuePush', 'inc/classes/BxDolCronQueuePush.php', '');

-- FORMS

UPDATE `sys_form_inputs` SET `db_pass` = 'XssHtml' AND `html` = 3 WHERE `object` = 'sys_comment' AND `module` = 'system' AND `name` = 'cmt_text';

-- META

UPDATE `sys_objects_metatags` SET `table_mentions` = 'sys_cmts_meta_mentions' WHERE `object` = 'sys_cmts';


-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC5' WHERE (`version` = '9.0.0.RC4' OR `version` = '9.0.0-RC4') AND `name` = 'system';

