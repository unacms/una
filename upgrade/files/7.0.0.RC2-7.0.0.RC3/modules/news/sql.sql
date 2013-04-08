
DELETE FROM `sys_sbs_types` WHERE `unit`='bx_news';

INSERT INTO `sys_sbs_types`(`unit`, `action`, `template`, `params`) VALUES
('bx_news', '', '', 'return BxDolService::call(\'news\', \'get_subscription_params\', array($arg1, $arg2, $arg3));'),
('bx_news', 'commentPost', 't_sbsNewsComments', 'return BxDolService::call(\'news\', \'get_subscription_params\', array($arg1, $arg2, $arg3));'),
('bx_news', 'rate', 't_sbsNewsRates', 'return BxDolService::call(\'news\', \'get_subscription_params\', array($arg1, $arg2, $arg3));');

