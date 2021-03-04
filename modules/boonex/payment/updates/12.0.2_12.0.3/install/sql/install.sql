SET @sName = 'bx_payment';


-- TABLES
DELETE FROM `bx_payment_providers` WHERE `name`='offline';
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_single`, `for_recurring`, `single_seller`, `time_tracker`, `active`, `order`, `class_name`) VALUES
('offline', '_bx_payment_off_cpt', '_bx_payment_off_dsc', 'off_', 0, 1, 0, 0, 0, 1, 0, 'BxPaymentProviderOffline');
SET @iProviderId = LAST_INSERT_ID();

DELETE FROM `bx_payment_providers_options` WHERE `name` LIKE 'off_%';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'off_active', 'checkbox', '_bx_payment_off_active_cpt', '_bx_payment_off_active_dsc', '', '', '', '', 1),
(@iProviderId, 'off_checkout_email', 'text', '_bx_payment_off_checkout_email_cpt', '_bx_payment_off_checkout_email_dsc', '', 'EmailOrEmpty', '', '_sys_form_account_input_email_error', 2);


UPDATE `bx_payment_providers_options` SET `check_type`='EmailOrEmpty', `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='pp_business';
UPDATE `bx_payment_providers_options` SET `check_type`='EmailOrEmpty', `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='pp_sandbox';

UPDATE `bx_payment_providers_options` SET `check_type`='EmailOrEmpty', `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='pp_api_live_account';
UPDATE `bx_payment_providers_options` SET `check_type`='EmailOrEmpty', `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='pp_api_test_account';

UPDATE `bx_payment_providers_options` SET `check_type`='EmailOrEmpty', `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='bp_notification_email';

UPDATE `bx_payment_providers_options` SET `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='cbee_cancellation_email';
UPDATE `bx_payment_providers_options` SET `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='cbee_expiration_email';

UPDATE `bx_payment_providers_options` SET `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='cbee_v3_cancellation_email';
UPDATE `bx_payment_providers_options` SET `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='cbee_v3_expiration_email';

UPDATE `bx_payment_providers_options` SET `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='strp_cancellation_email';
UPDATE `bx_payment_providers_options` SET `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='strp_expiration_email';

UPDATE `bx_payment_providers_options` SET `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='strp_v3_cancellation_email';
UPDATE `bx_payment_providers_options` SET `check_error`='_bx_payment_form_input_email_err_cor_or_emp' WHERE `name`='strp_v3_expiration_email';
