-- FORMS
UPDATE `sys_objects_form` SET `override_class_name`='BxCreditsFormCredit', `override_class_file`='modules/boonex/credits/classes/BxCreditsFormCredit.php' WHERE `object`='bx_credits_credit';

DELETE FROM `sys_form_displays` WHERE `display_name`='bx_credits_credit_send';
INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_credits_credit', 'bx_credits_credit_send', 'bx_credits', 0, '_bx_credits_form_credit_display_send');

DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_credits_credit_send';
INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_credits_credit_send', 'profile', 2147483647, 1, 1),
('bx_credits_credit_send', 'amount', 2147483647, 1, 2),
('bx_credits_credit_send', 'message', 2147483647, 1, 3),
('bx_credits_credit_send', 'controls', 2147483647, 1, 4),
('bx_credits_credit_send', 'do_submit', 2147483647, 1, 5),
('bx_credits_credit_send', 'do_cancel', 2147483647, 1, 6);

UPDATE `sys_form_inputs` SET `type`='price' WHERE `object`='bx_credits_bundle' AND `name`='price';
