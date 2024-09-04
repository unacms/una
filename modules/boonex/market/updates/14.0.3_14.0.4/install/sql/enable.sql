-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_market' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_market_enable_no_payments', 'bx_market_enable_icon');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_market_enable_no_payments', '', @iCategId, '_bx_market_option_enable_no_payments', 'checkbox', '', '', '', 1),
('bx_market_enable_icon', '', @iCategId, '_bx_market_option_enable_icon', 'checkbox', '', '', '', 40);
