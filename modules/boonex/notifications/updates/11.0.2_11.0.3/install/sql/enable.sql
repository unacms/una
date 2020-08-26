SET @sName = 'bx_notifications';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name` = 'bx_notifications_clear_interval';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_clear_interval', '0', @iCategId, '_bx_ntfs_option_clear_interval', 'digit', '', '', '', '', 30);

-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_notifications_clean';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_notifications_clean', '* * * * *', 'BxNtfsCronClean', 'modules/boonex/notifications/classes/BxNtfsCronClean.php', '');
