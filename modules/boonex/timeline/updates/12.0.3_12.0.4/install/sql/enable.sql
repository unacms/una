SET @sName = 'bx_timeline';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_enable_repost_own_actions';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_repost_own_actions', 'on', @iCategId, '_bx_timeline_option_enable_repost_own_actions', 'checkbox', '', '', '', '', 4);

SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline_post' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_limit_attach_links';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_limit_attach_links', '0', @iCategId, '_bx_timeline_option_limit_attach_links', 'digit', '', '', '', '', 2);


-- ALERTS
SET @iHandler := (SELECT `id` FROM `sys_alerts_handlers` WHERE `name`='bx_timeline' LIMIT 1);

DELETE FROM `sys_alerts` WHERE `unit`='comment' AND `action` IN ('added', 'edited', 'deleted') AND `handler_id`=@iHandler;
INSERT INTO `sys_alerts` (`unit`, `action`, `handler_id`) VALUES
('comment', 'added', @iHandler),
('comment', 'edited', @iHandler),
('comment', 'deleted', @iHandler);
