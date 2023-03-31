-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_stream_streaming' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_stream_aspect_ratio';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_stream_aspect_ratio', '', @iCategId, '_bx_stream_option_aspect_ratio', 'digit', '', '', '', 40);
