SET @sName = 'bx_payment';


-- PROVIDERS
SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='chargebee' LIMIT 1);

DELETE FROM `bx_payment_providers_options` WHERE `name` IN ('cbee_cancellation_email', 'cbee_expiration_email');
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cbee_cancellation_email', 'text', '_bx_payment_cbee_cancellation_email_cpt', '', '', 'EmailOrEmpty', '', '_sys_form_account_input_email_error', 10),
(@iProviderId, 'cbee_expiration_email', 'text', '_bx_payment_cbee_expiration_email_cpt', '', '', 'EmailOrEmpty', '', '_sys_form_account_input_email_error', 11);

SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='chargebee_v3' LIMIT 1);

DELETE FROM `bx_payment_providers_options` WHERE `name` IN ('cbee_v3_cancellation_email', 'cbee_v3_expiration_email');
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cbee_v3_cancellation_email', 'text', '_bx_payment_cbee_cancellation_email_cpt', '', '', 'EmailOrEmpty', '', '_sys_form_account_input_email_error', 10),
(@iProviderId, 'cbee_v3_expiration_email', 'text', '_bx_payment_cbee_expiration_email_cpt', '', '', 'EmailOrEmpty', '', '_sys_form_account_input_email_error', 11);

SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='stripe' LIMIT 1);

DELETE FROM `bx_payment_providers_options` WHERE `name` IN ('strp_expiration_email');
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'strp_expiration_email', 'text', '_bx_payment_strp_expiration_email_cpt', '', '', 'EmailOrEmpty', '', '_sys_form_account_input_email_error', 11);
