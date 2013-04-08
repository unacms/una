
ALTER TABLE `[db_prefix]_main` CHANGE `EntryUri` `EntryUri` varchar(255) NOT NULL default '';

UPDATE `sys_modules` SET `version` = '1.0.4' WHERE `uri` = 'ads' AND `version` = '1.0.3';

