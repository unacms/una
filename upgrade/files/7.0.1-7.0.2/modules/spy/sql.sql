
UPDATE `sys_cron_jobs` SET `time` = '0 0 * * *' WHERE `name` = 'bx_spy' AND `time` = '0 1 * * *';

UPDATE `sys_modules` SET `version` = '1.0.2' WHERE `uri` = 'spy' AND `version` = '1.0.1';

