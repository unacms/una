-- SETTINGS
SET @iCategId = (SELECT `id` FROM `sys_options_categories` WHERE `name`='bx_credits' LIMIT 1);

DELETE FROM `sys_options` WHERE `name` IN ('bx_credits_withdraw_clearing', 'bx_credits_withdraw_minimum', 'bx_credits_withdraw_remaining');
INSERT INTO `sys_options` (`name`, `value`, `category_id`, `caption`, `type`, `check`, `check_error`, `extra`, `order`) VALUES
('bx_credits_withdraw_clearing', '30', @iCategId, '_bx_credits_option_withdraw_clearing', 'digit', '', '', '', 3),
('bx_credits_withdraw_minimum', '500', @iCategId, '_bx_credits_option_withdraw_minimum', 'digit', '', '', '', 4),
('bx_credits_withdraw_remaining', '100', @iCategId, '_bx_credits_option_withdraw_remaining', 'digit', '', '', '', 5);


-- GRIDS
UPDATE `sys_grid_fields` SET `width`='15%' WHERE `object`='bx_credits_history_administration' AND `name`='info';
UPDATE `sys_grid_fields` SET `width`='10%' WHERE `object`='bx_credits_history_administration' AND `name`='date';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_credits_history_administration' AND `name`='cleared';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_history_administration', 'cleared', '_bx_credits_grid_column_title_htr_cleared', '10%', 0, '', '', 8);

UPDATE `sys_grid_fields` SET `width`='15%' WHERE `object`='bx_credits_history_common' AND `name`='info';
UPDATE `sys_grid_fields` SET `width`='10%' WHERE `object`='bx_credits_history_common' AND `name`='date';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_credits_history_common' AND `name`='cleared';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_credits_history_common', 'cleared', '_bx_credits_grid_column_title_htr_cleared', '10%', 0, '', '', 7);


-- CRONS
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_credits_clearing';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_credits_clearing', '0 0 * * *', 'BxCreditsCronClearing', 'modules/boonex/credits/classes/BxCreditsCronClearing.php', '');
