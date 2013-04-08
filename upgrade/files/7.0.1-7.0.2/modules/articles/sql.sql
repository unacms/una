
ALTER TABLE `[db_prefix]entries` ADD `when` int(11) NOT NULL default '0' AFTER `content`;
UPDATE `[db_prefix]entries` SET `when` = `date`;

INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `eval`) VALUES
('bx_articles', '*/5 * * * *', 'BxArlCron', 'modules/boonex/articles/classes/BxArlCron.php', '');

UPDATE `sys_modules` SET `version` = '1.0.2' WHERE `uri` = 'articles' AND `version` = '1.0.1';

