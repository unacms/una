-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_googlecon_general' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_googlecon_privacy');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_googlecon_privacy', '3', @iCategId, '_sys_connect_option_privacy', 'select', '', '', 54, 'a:2:{s:6:"module";s:12:"bx_googlecon";s:6:"method";s:18:"get_privacy_groups";}');
