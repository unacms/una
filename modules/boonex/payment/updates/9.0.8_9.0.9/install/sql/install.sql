SET @sName = 'bx_payment';


-- PROVIDERS
SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='chargebee' LIMIT 1);

DELETE FROM `bx_payment_providers_options` WHERE `name`='cbee_check_amount';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cbee_check_amount', 'checkbox', '_bx_payment_cbee_check_amount_cpt', '_bx_payment_cbee_check_amount_dsc', '', '', '', '', 7);

SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='chargebee_v3' LIMIT 1);

DELETE FROM `bx_payment_providers_options` WHERE `name`='cbee_v3_check_amount';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cbee_v3_check_amount', 'checkbox', '_bx_payment_cbee_check_amount_cpt', '_bx_payment_cbee_check_amount_dsc', '', '', '', '', 7);
