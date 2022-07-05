SET @sName = 'bx_ocean';


-- ALERTS
SET @iHandler = (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1);
DELETE FROM `sys_alerts_handlers` WHERE `name`=@sName LIMIT 1;
DELETE FROM `sys_alerts` WHERE `unit`='system' AND `action` IN ('change_logo') AND `handler_id`=@iHandler;

INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxOceanAlertsResponse', 'modules/boonex/ocean/classes/BxOceanAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'change_logo', @iHandler);
