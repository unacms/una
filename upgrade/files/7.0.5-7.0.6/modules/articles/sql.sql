
ALTER TABLE `bx_arl_entries` CHANGE `caption` `caption` VARCHAR(100);
ALTER TABLE `bx_arl_entries` CHANGE `uri` `uri` VARCHAR(100);
ALTER TABLE `bx_arl_entries` CHANGE `content` `content` MEDIUMTEXT;

UPDATE `sys_modules` SET `version` = '1.0.6' WHERE `uri` = 'articles' AND `version` = '1.0.5';

