
SET @iLastHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name` = 'bx_spy_profiles_activity');
DELETE FROM `sys_alerts` WHERE `unit` = 'profile' AND `action` = 'delete' AND `handler_id` = @iLastHandler;

UPDATE `sys_modules` SET `version` = '1.0.4' WHERE `uri` = 'spy' AND `version` = '1.0.3';

