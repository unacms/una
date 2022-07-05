SET @sName = 'bx_protean';


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1;
DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action` IN ('save_setting', 'change_logo') AND `handler_id`=@iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxProteanAlertsResponse', 'modules/boonex/protean/classes/BxProteanAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler),
('system', 'change_logo', @iHandler);
