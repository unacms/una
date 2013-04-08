
INSERT INTO `sys_sbs_types`(`unit`, `action`, `template`, `params`) VALUES
('bx_wall', '', '', 'return BxDolService::call(\'wall\', \'get_subscription_params\', array($arg1, $arg2, $arg3));'),
('bx_wall', 'update', 't_sbsWallUpdates', 'return BxDolService::call(\'wall\', \'get_subscription_params\', array($arg1, $arg2, $arg3));');

INSERT INTO `sys_email_templates`(`Name`, `Subject`, `Body`, `Desc`, `LangID`) VALUES
('t_sbsWallUpdates', 'New wall event', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p><b>Dear <RealName></b>,</p><br /><p>The wall you subscribed to has new event!</p><br /> <p>Click <a href="<ViewLink>">here</a> to view it.</p><br /> <p><b>Thank you for using our services!</b></p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'New wall events subscription.', '0'),
('t_sbsWallUpdates', 'New wall event', '<html><head></head><body style="font: 12px Verdana; color:#000000"> <p><b>Dear <RealName></b>,</p><br /><p>The wall you subscribed to has new event!</p><br /> <p>Click <a href="<ViewLink>">here</a> to view it.</p><br /> <p><b>Thank you for using our services!</b></p> <p>--</p> <p style="font: bold 10px Verdana; color:red"><SiteName> mail delivery system!!! <br />Auto-generated e-mail, please, do not reply!!!</p></body></html>', 'New wall events subscription.', '1');


UPDATE `sys_modules` SET `version` = '1.0.2' WHERE `uri` = 'wall' AND `version` = '1.0.1';

