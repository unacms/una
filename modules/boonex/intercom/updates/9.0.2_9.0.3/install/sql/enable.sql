-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_intercom_general' LIMIT 1);
DELETE FROM `sys_options` WHERE `name` IN ('bx_intercom_option_hide_launcher', 'bx_intercom_option_alignment', 'bx_intercom_option_horizontal_padding', 'bx_intercom_option_vertical_padding');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_intercom_option_hide_launcher', '', @iCategId, '_bx_intercom_option_hide_launcher', 'checkbox', '', '', 22, ''),
('bx_intercom_option_alignment', 'right', @iCategId, '_bx_intercom_option_alignment', 'select', '', '', 30, 'right,left'),
('bx_intercom_option_horizontal_padding', '20', @iCategId, '_bx_intercom_option_horizontal_padding', 'digit', '', '', 32, ''),
('bx_intercom_option_vertical_padding', '20', @iCategId, '_bx_intercom_option_vertical_padding', 'digit', '', '', 34, '');
