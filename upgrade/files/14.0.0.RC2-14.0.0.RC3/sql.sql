
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- PUSH objects

CREATE TABLE IF NOT EXISTS `sys_objects_push` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
);

INSERT IGNORE INTO `sys_objects_push` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_onesignal', 'OneSignal', 'BxTemplPushOneSignal', '');

-- SMS objects

CREATE TABLE IF NOT EXISTS `sys_objects_sms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `title` varchar(255) NOT NULL,
  `override_class_name` varchar(255) NOT NULL,
  `override_class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`object`)
);

INSERT IGNORE INTO `sys_objects_sms` (`object`, `title`, `override_class_name`, `override_class_file`) VALUES
('sys_twilio', 'Twilio', 'BxDolSmsTwilio', '');


-- Options

UPDATE `sys_options_types` SET `icon` = 'mi-cog.svg' WHERE `name` = 'system';


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'hidden');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_x_frame_options', '_adm_stg_cpt_option_sys_x_frame_options', 'SAMEORIGIN', 'select', 'Off,SAMEORIGIN,DENY', '', '', '', 151);

UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_std_show_header_left';
UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_std_show_header_left_search';
UPDATE `sys_options` SET `value` = 'on' WHERE `name` = 'sys_std_show_header_right_search';
UPDATE `sys_options` SET `value` = '' WHERE `name` = 'sys_std_show_header_right_site';


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'site_settings');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `info`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_enable_post_to_context_for_privacy', '_adm_stg_cpt_option_sys_enable_post_to_context_for_privacy', '', '', 'checkbox', '', '', '', 50),
(@iCategoryId, 'sys_check_fan_in_parent_context', '_adm_stg_cpt_option_sys_check_fan_in_parent_context', '', 'on', 'checkbox', '', '', '', 55);


SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'notifications_push');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_push_default', '_adm_stg_cpt_option_sys_push_default', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:24:"get_options_push_default";s:5:"class";s:13:"TemplServices";}', '', '', 1);

UPDATE `sys_options` SET `name` = 'sys_push_onesignal_app_id', `caption` = '_adm_stg_cpt_option_sys_push_onesignal_app_id', `order` = 21 WHERE `name` = 'sys_push_app_id';
UPDATE `sys_options` SET `name` = 'sys_push_onesignal_rest_api', `caption` = '_adm_stg_cpt_option_sys_push_onesignal_rest_api', `order` = 22 WHERE `name` = 'sys_push_rest_api';
UPDATE `sys_options` SET `name` = 'sys_push_onesignal_short_name', `caption` = '_adm_stg_cpt_option_sys_push_onesignal_short_name', `order` = 23 WHERE `name` = 'sys_push_short_name';
UPDATE `sys_options` SET `name` = 'sys_push_onesignal_safari_id', `caption` = '_adm_stg_cpt_option_sys_push_onesignal_safari_id', `order` = 24 WHERE `name` = 'sys_push_safari_id';


UPDATE `sys_options_categories` SET `name` = 'sms', `caption` = '_adm_stg_cpt_category_sms' WHERE `name` = 'twilio_gate';
SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'sms');

INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_sms_default', '_adm_stg_cpt_option_sys_sms_default', '', 'select', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:23:"get_options_sms_default";s:5:"class";s:13:"TemplServices";}', '', '', 1);

UPDATE `sys_options` SET `name` = 'sys_sms_twilio_sid', `caption` = '_adm_stg_cpt_option_sys_sms_twilio_sid', `order` = 11 WHERE `name` = 'sys_twilio_gate_sid';
UPDATE `sys_options` SET `name` = 'sys_sms_twilio_token', `caption` = '_adm_stg_cpt_option_sys_sms_twilio_token', `order` = 12 WHERE `name` = 'sys_twilio_gate_token';
UPDATE `sys_options` SET `name` = 'sys_sms_twilio_from_number', `caption` = '_adm_stg_cpt_option_sys_sms_twilio_from_number', `order` = 13 WHERE `name` = 'sys_twilio_gate_from_number';


-- BG jobs

CREATE TABLE IF NOT EXISTS `sys_background_jobs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `priority` tinyint(4) unsigned NOT NULL default '0',
  `service_call` text NOT NULL default '', 
  `status` varchar(16) NOT NULL default 'awaiting',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

-- Cron

DELETE FROM `sys_cron_jobs` WHERE `name` = 'sys_background_jobs';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_background_jobs', '* * * * *', 'BxDolCronBackgroundJobs', 'inc/classes/BxDolCronBackgroundJobs.php', '');

-- Menu

UPDATE `sys_menu_items` SET `active` = 1 WHERE `set_name` = 'sys_studio_account_popup' AND `name` = 'site';

-- Pages

ALTER TABLE `sys_pages_blocks` CHANGE `content` `content` MEDIUMTEXT NOT NULL;

DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_create_account' AND `active_api` = 1 AND `content` = 'a:4:{s:6:"module";s:6:"system";s:6:"method"s:19:"create_account_form";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_login' AND `active_api` = 1 AND `content` = 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:10:"login_form";s:5:"class";s:17:"TemplServiceLogin";}';
DELETE FROM `sys_pages_blocks` WHERE `object` = 'sys_home' AND `title` = '_sys_page_block_title_forgot_password' AND `active_api` = 1 AND `content` = 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"forgot_password";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}';

INSERT INTO `sys_pages_blocks` (`object`, `cell_id`, `module`, `title_system`, `title`, `designbox_id`, `tabs`, `async`, `visible_for_levels`, `type`, `content`, `deletable`, `copyable`, `active`, `active_api`, `order`) VALUES
('sys_home', 1, 'system', '', '_sys_page_block_title_create_account', 11, 0, 0, 1, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method"s:19:"create_account_form";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 0, 0, 1, 0),
('sys_home', 1, 'system', '_sys_page_block_system_title_login', '_sys_page_block_title_login', 11, 0, 0, 1, 'service', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:10:"login_form";s:5:"class";s:17:"TemplServiceLogin";}', 0, 0, 0, 1, 0),
('sys_home', 1, 'system', '', '_sys_page_block_title_forgot_password', 13, 0, 0, 1, 'service', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:15:"forgot_password";s:6:"params";a:0:{}s:5:"class";s:19:"TemplServiceAccount";}', 0, 0, 0, 1, 0);

-- Logs

UPDATE `sys_objects_logs` SET `object` = 'sys_sms', `title` = '_sys_log_sms' WHERE `object` = 'sys_twilio';

DELETE FROM `sys_objects_logs` WHERE `object` = 'sys_background_jobs';
INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('sys_background_jobs', 'system', 'Auto', '_sys_log_background_jobs', 1, '', '');

-- Preloader 

UPDATE `sys_preloader` SET `content` = 'moment-with-locales.min.js' WHERE `content` = 'moment-with-locales.js';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-RC3' WHERE (`version` = '14.0.0.RC2' OR `version` = '14.0.0-RC2') AND `name` = 'system';

