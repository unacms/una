-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_channels' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_channels_default_author';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `extra`, `check`, `check_params`, `check_error`, `order`) VALUES
('bx_channels_default_author', '', @iCategId, '_bx_channels_option_default_author', 'select', 'a:2:{s:6:"module";s:11:"bx_channels";s:6:"method";s:26:"get_options_default_author";}', '', '', '', 1);
