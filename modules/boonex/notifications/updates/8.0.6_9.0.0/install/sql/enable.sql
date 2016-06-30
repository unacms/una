SET @sName = 'bx_notifications';

-- ALERTS
SET @iHandlerId = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = @sName LIMIT 1);
DELETE FROM `sys_alerts` WHERE `unit`='sys_profiles_subscriptions' AND `action` IN ('connection_added', 'connection_removed') AND `handler_id` = @iHandlerId;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('sys_profiles_subscriptions', 'connection_added', @iHandlerId),
('sys_profiles_subscriptions', 'connection_removed', @iHandlerId);