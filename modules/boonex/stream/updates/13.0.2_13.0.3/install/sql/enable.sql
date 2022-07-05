-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_stream_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_stream_always_accessible', 'bx_stream_mute');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_stream_always_accessible', '', @iCategId, '_bx_stream_option_always_accessible', 'checkbox', '', '', '', 170),
('bx_stream_mute', '', @iCategId, '_bx_stream_option_mute', 'checkbox', '', '', '', 180);
