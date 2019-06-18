SET @sName = 'bx_notifications';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_options` WHERE `name`='bx_notifications_delivery_timeout';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_notifications_delivery_timeout', '120', @iCategId, '_bx_ntfs_option_delivery_timeout', 'digit', '', '', '', '', 20);


-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_notifications_queue';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_notifications_queue', '* * * * *', 'BxNtfsCronQueue', 'modules/boonex/notifications/classes/BxNtfsCronQueue.php', '');


-- CUSTOM
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_notifications' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='bx_albums' AND `action`='media_added' AND `handler_id`=@iHandler;
