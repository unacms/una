
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

CREATE TABLE IF NOT EXISTS `sys_profiles_track` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL DEFAULT '0',
  `action` varchar(32) NOT NULL DEFAULT '',
  `date` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `profile_track` (`profile_id`,`action`)
);

-- Menu

UPDATE `sys_menu_items` SET `link` = 'page.php?i=logout' WHERE `set_name` = 'sys_account_notifications' AND `name` = 'logout';

-- Grid

ALTER TABLE `sys_objects_grid` CHANGE `show_total_count` `show_total_count` TINYINT(4) NOT NULL DEFAULT '0';

-- Last step is to update current version

UPDATE `sys_modules` SET `version` = '14.0.0-RC4' WHERE (`version` = '14.0.0.RC3' OR `version` = '14.0.0-RC3') AND `name` = 'system';

