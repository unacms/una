-- CRON
DELETE FROM `sys_cron_jobs` WHERE `name`='bx_market_pruning';
INSERT INTO `sys_cron_jobs` (`name`, `time`, `class`, `file`, `service_call`) VALUES
('bx_market_pruning', '0 0 * * *', 'BxMarketCronPruning', 'modules/boonex/market/classes/BxMarketCronPruning.php', '');