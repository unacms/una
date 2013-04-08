
ALTER TABLE `[db_prefix]entries` ADD `snippet` text NOT NULL AFTER `caption`;

UPDATE `sys_modules` SET `version` = '1.0.3' WHERE `uri` = 'news' AND `version` = '1.0.2';

