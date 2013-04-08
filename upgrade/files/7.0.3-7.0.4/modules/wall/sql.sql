
ALTER TABLE `[db_prefix]events` CHANGE `owner_id` `owner_id` int(10) unsigned NOT NULL default '0';
ALTER TABLE `[db_prefix]events` ADD INDEX (`owner_id`);
ALTER TABLE `[db_prefix]events` ADD INDEX (`object_id`);

UPDATE `sys_modules` SET `version` = '1.0.4' WHERE `uri` = 'wall' AND `version` = '1.0.3';

