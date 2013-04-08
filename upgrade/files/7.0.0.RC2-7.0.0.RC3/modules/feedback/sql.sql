
DELETE FROM `sys_sbs_types` WHERE `unit`='bx_feedback';

INSERT INTO `sys_sbs_types`(`unit`, `action`, `template`, `params`) VALUES
('bx_feedback', '', '', 'return BxDolService::call(\'feedback\', \'get_subscription_params\', array($arg1, $arg2, $arg3));'),
('bx_feedback', 'commentPost', 't_sbsFeedbackComments', 'return BxDolService::call(\'feedback\', \'get_subscription_params\', array($arg1, $arg2, $arg3));'),
('bx_feedback', 'rate', 't_sbsFeedbackRates', 'return BxDolService::call(\'feedback\', \'get_subscription_params\', array($arg1, $arg2, $arg3));');

