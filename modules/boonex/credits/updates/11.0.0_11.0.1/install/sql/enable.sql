-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_credits' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_credits_enable_provider';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_credits_enable_provider', '', @iCategId, '_bx_credits_enable_provider', 'checkbox', '', '', '', '', 30);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_credits' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='profile' AND `action`='add' AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('profile', 'add', @iHandler);
