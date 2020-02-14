SET @sName = 'bx_payment';


-- PROVIDERS
SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='credits' LIMIT 1);
DELETE FROM `bx_payment_providers` WHERE `name`='credits';
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId;

INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_single`, `for_recurring`, `active`, `order`, `class_name`) VALUES
('credits', '_bx_payment_cdt_cpt', '_bx_payment_cdt_dsc', 'cdt_', 0, 1, 0, 1, 0, 'BxPaymentProviderCredits');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cdt_active', 'checkbox', '_bx_payment_cdt_active_cpt', '_bx_payment_cdt_active_dsc', '', '', '', '', 1);
