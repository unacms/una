SET @sName = 'bx_ocean';


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxOceanAlertsResponse', 'modules/boonex/ocean/classes/BxOceanAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'change_logo', @iHandler);
