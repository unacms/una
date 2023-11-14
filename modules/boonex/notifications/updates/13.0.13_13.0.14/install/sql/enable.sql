SET @sName = 'bx_notifications';


-- CRON
UPDATE `sys_cron_jobs` SET `time`='0 0 * * *' WHERE `name`='bx_notifications_clean';
