SET @sName = 'bx_events';


-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`=@sName LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_events_rm_from_timeline_after';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_events_rm_from_timeline_after', '0', @iCategId, '_bx_events_option_rm_from_timeline_after', 'digit', '', '', '', 80);
