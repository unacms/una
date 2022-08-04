-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_stream_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` = 'bx_stream_mute_embed';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_stream_mute_embed', '', @iCategId, '_bx_stream_option_mute_embed', 'checkbox', '', '', '', 182);
