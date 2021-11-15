SET @sName = 'bx_payment';

-- FORMS
DELETE FROM `sys_objects_form` WHERE `object`='bx_payment_form_details';
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_form_details', @sName, '_bx_payment_form_details_form', '', '', 'submit', '', 'id', '', '', 'a:1:{s:14:"checker_helper";s:33:"BxPaymentDetailsFormCheckerHelper";}', 0, 1, 'BxPaymentFormDetails', 'modules/boonex/payment/classes/BxPaymentFormDetails.php');

DELETE FROM `sys_form_displays` WHERE `object`='bx_payment_form_details';
INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_payment_form_details_edit', @sName, 'bx_payment_form_details', '_bx_payment_form_details_display_edit', 0);


-- PRE-VALUES
UPDATE `sys_form_pre_values` SET `LKey2`='&#8364;' WHERE `Key`='bx_payment_currencies' AND `Value`='EUR';