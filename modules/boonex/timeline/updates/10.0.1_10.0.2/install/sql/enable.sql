-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_timeline' LIMIT 1);

DELETE FROM `sys_options` WHERE `name`='bx_timeline_enable_jump_to_switcher';
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_params`, `check_error`, `extra`, `order`) VALUES
('bx_timeline_enable_jump_to_switcher', 'on', @iCategId, '_bx_timeline_option_enable_jump_to_switcher', 'checkbox', '', '', '', '', 52);
