-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_antispam' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action`='check_spam_url' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'check_spam_url', @iHandler);
