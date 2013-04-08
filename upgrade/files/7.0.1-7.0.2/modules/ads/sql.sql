
ALTER TABLE `[db_prefix]_main` DROP INDEX `ftMain`;
ALTER TABLE `[db_prefix]_main` ADD FULLTEXT KEY `ftMain` (`Subject`, `Tags`, `Message`, `City`);

UPDATE `sys_modules` SET `version` = '1.0.2' WHERE `uri` = 'ads' AND `version` = '1.0.1';

