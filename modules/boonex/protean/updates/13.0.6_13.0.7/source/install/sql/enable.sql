SET @sName = 'bx_protean';


-- ALERTS
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxProteanAlertsResponse', 'modules/boonex/protean/classes/BxProteanAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('system', 'save_setting', @iHandler),
('system', 'change_logo', @iHandler);


-- INJECTIONS
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_head', 'service', 'a:3:{s:6:"module";s:10:"bx_protean";s:6:"method";s:14:"include_css_js";s:6:"params";a:1:{i:0;s:4:"head";}}', 0, 1),
('bx_protean_footer', 0, 'injection_footer', 'service', 'a:3:{s:6:"module";s:10:"bx_protean";s:6:"method";s:14:"include_css_js";s:6:"params";a:1:{i:0;s:6:"footer";}}', 0, 1);