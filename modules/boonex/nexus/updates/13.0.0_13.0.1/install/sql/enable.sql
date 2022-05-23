-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_nexus_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_nexus_option_push_notifications_count';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_error`, `order`) VALUES
('bx_nexus_option_push_notifications_count', '', @iCategId, '_bx_nexus_option_push_notifications_count', 'checkbox', '', '', '', 50);
