SET @sName = 'bx_artificer';


-- alerts
INSERT INTO `sys_alerts_handlers` (`name`, `class`, `file`, `service_call`) VALUES 
(@sName, 'BxArtificerAlertsResponse', 'modules/boonex/artificer/classes/BxArtificerAlertsResponse.php', '');
SET @iHandler := LAST_INSERT_ID();

INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'unit', @iHandler);


-- injections
INSERT INTO `sys_injections` (`name`, `page_index`, `key`, `type`, `data`, `replace`, `active`) VALUES
(@sName, 0, 'injection_head', 'service', 'a:2:{s:6:"module";s:12:"bx_artificer";s:6:"method";s:14:"include_css_js";}', 0, 1);