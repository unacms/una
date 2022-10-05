-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_smtp_general' LIMIT  1);

INSERT IGNORE INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `order`, `extra`) VALUES
('bx_smtp_test_mode', '', @iCategId, '_bx_smtp_option_test_mode', 'checkbox', '', '', 100, ''),
('bx_smtp_test_email', '', @iCategId, '_bx_smtp_option_test_email', 'digit', '', '', 102, ''),
('bx_smtp_test_subj', '[TEST] ', @iCategId, '_bx_smtp_option_test_subj', 'digit', '', '', 104, '');