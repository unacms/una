


RENAME TABLE `sys_transcoder_images_filters` TO `sys_transcoder_filters`;

RENAME TABLE `sys_objects_transcoder_images` TO `sys_objects_transcoder`;



ALTER TABLE `sys_objects_transcoder` ADD `override_class_name` varchar(255) NOT NULL;
ALTER TABLE `sys_objects_transcoder` ADD `override_class_file` varchar(255) NOT NULL;

ALTER TABLE `sys_grid_actions` ADD `icon_only` tinyint(4) NOT NULL DEFAULT '0' AFTER `icon`;



-- can be safely applied multiple times


DELETE FROM `sys_email_templates` WHERE `Name` = 't_UpgradeModulesFailed';
INSERT INTO `sys_email_templates` (`Module`, `NameSystem`, `Name`, `Subject`, `Body`) VALUES
('system', '_sys_et_txt_name_upgrade_modules_failed', 't_UpgradeModulesFailed', '_sys_et_txt_subject_upgrade_modules_failed', '_sys_et_txt_body_upgrade_modules_failed');



SET @iCategoryId = (SELECT `id` FROM `sys_options_categories` WHERE `name` = 'general');
INSERT IGNORE INTO `sys_options`(`category_id`, `name`, `caption`, `value`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
(@iCategoryId, 'sys_transcoder_queue_storage', '_adm_stg_cpt_option_sys_transcoder_queue_storage', '', 'checkbox', '', '', '', 50);



CREATE TABLE IF NOT EXISTS `sys_cmts_ids` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `system_id` int(11) NOT NULL DEFAULT '0',
  `cmt_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `system_cmt_id` (`system_id`,`cmt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_cmts_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



DELETE FROM `sys_cron_jobs` WHERE `name` = 'sys_transcoder';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('sys_transcoder', '* * * * *', 'BxDolCronTranscoder', 'inc/classes/BxDolCronTranscoder.php', '');

UPDATE `sys_cron_jobs` SET `time` = '30 2,3 * * *' WHERE `name` = 'sys_upgrade_modules';



INSERT IGNORE INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('sys_transcoder_queue_files', 'Local', '', 3600, 2592000, 0, 'sys_transcoder_queue_files', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,divx,xvid,3gp,webm,jpg', '', 0, 0, 0, 0, 0, 0);



CREATE TABLE IF NOT EXISTS `sys_transcoder_videos_files` (
  `transcoder_object` varchar(64) NOT NULL,
  `file_id` int(11) NOT NULL,
  `handler` varchar(255) NOT NULL,
  `atime` int(11) NOT NULL,
  UNIQUE KEY `transcoder_object` (`transcoder_object`,`handler`),
  KEY `atime` (`atime`),
  KEY `file_id` (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `sys_transcoder_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `transcoder_object` varchar(64) NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  `file_url_source` varchar(255) NOT NULL,
  `file_id_source` varchar(255) NOT NULL,
  `file_url_result` varchar(255) NOT NULL,
  `file_ext_result` varchar(255) NOT NULL,
  `file_id_result` int(11) NOT NULL,
  `server` varchar(255) NOT NULL,
  `status` enum('pending','processing','complete','failed','delete') NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `log` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transcoder_object` (`transcoder_object`,`file_id_source`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE `sys_transcoder_queue_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;



INSERT IGNORE INTO `sys_objects_metatags` (`object`, `table_keywords`, `table_locations`, `table_mentions`, `override_class_name`, `override_class_file`) VALUES
('sys_cmts', 'sys_cmts_meta_keywords', '', '', '', '');



-- last step is to update current version

UPDATE `sys_modules` SET `version` = '8.0.0-A9' WHERE `version` = '8.0.0-A8' AND `name` = 'system';

