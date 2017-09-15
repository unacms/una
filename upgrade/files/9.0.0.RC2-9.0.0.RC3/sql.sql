

-- Alerts

SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'sys_installed' LIMIT 1);
DELETE FROM `sys_alerts` WHERE `handler_id` = @iHandler;
DELETE FROM `sys_alerts_handlers` WHERE `id` = @iHandler;


INSERT INTO `sys_alerts_handlers` (`name`, `service_call`) VALUES 
('sys_installed', 'a:3:{s:6:"module";s:6:"system";s:6:"method";s:32:"alert_response_process_installed";s:5:"class";s:13:"TemplServices";}');
SET @iHandler = LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'installed', @iHandler);


-- last step is to update current version

UPDATE `sys_modules` SET `version` = '9.0.0-RC3' WHERE (`version` = '9.0.0.RC2' OR `version` = '9.0.0-RC2') AND `name` = 'system';

