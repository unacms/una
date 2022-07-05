SET @sName = 'bx_decorous';


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxDecorousAlertsResponse', 'modules/boonex/decorous/classes/BxDecorousAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler),
('system', 'change_logo', @iHandler);


-- INJECTIONS
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:11:"bx_decorous";s:6:"method";s:14:"include_css_js";}', 0, 1);