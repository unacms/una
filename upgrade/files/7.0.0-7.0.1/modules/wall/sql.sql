

SET @iHandlerId := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_wall');
INSERT IGNORE INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES('profile', 'delete', @iHandlerId);

UPDATE `sys_modules` SET `version` = '1.0.1' WHERE `uri` = 'wall' AND `version` = '1.0.0';

