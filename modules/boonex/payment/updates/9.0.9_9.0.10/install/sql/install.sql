SET @sName = 'bx_payment';

-- TABLES
SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='chargebee' LIMIT 1);
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId AND `name`='cbee_hidden';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cbee_hidden', 'checkbox', '_bx_payment_cbee_hidden_cpt', '_bx_payment_cbee_hidden_dsc', '', '', '', '', 2);

SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='chargebee_v3' LIMIT 1);
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId AND `name`='cbee_v3_hidden';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cbee_v3_hidden', 'checkbox', '_bx_payment_cbee_hidden_cpt', '_bx_payment_cbee_hidden_dsc', '', '', '', '', 2);

SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='recurly' LIMIT 1);
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId AND `name`='rcrl_hidden';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'rcrl_hidden', 'checkbox', '_bx_payment_rcrl_hidden_cpt', '_bx_payment_rcrl_hidden_dsc', '', '', '', '', 2);

SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='stripe' LIMIT 1);
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId AND `name`='strp_hidden';
INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'strp_hidden', 'checkbox', '_bx_payment_strp_hidden_cpt', '_bx_payment_strp_hidden_dsc', '', '', '', '', 2);


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `ttp`.`id` AS `id`, `ttp`.`seller_id` AS `seller_id`, `ts`.`customer_id` AS `customer_id`, `ts`.`subscription_id` AS `subscription_id`, `ttp`.`provider` AS `provider`, `ttp`.`items` AS `items`, `ts`.`date` AS `date` FROM `bx_payment_subscriptions` AS `ts` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `ts`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ' WHERE `object`='bx_payment_grid_sbs_list_my';
UPDATE `sys_objects_grid` SET `source`='SELECT `ttp`.`id` AS `id`, `ttp`.`client_id` AS `client_id`, `tac`.`email` AS `client_email`, `ttp`.`seller_id` AS `seller_id`, `ts`.`customer_id` AS `customer_id`, `ts`.`subscription_id` AS `subscription_id`, `ttp`.`provider` AS `provider`, `ttp`.`items` AS `items`, `ts`.`paid` AS `paid`, `ts`.`date` AS `date` FROM `bx_payment_subscriptions` AS `ts` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `ts`.`pending_id`=`ttp`.`id` LEFT JOIN `sys_profiles` AS `tpc` ON `ttp`.`client_id`=`tpc`.`id` LEFT JOIN `sys_accounts` AS `tac` ON `tpc`.`account_id`=`tac`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ', `filter_fields`='tac`.`email,ts`.`customer_id,ts`.`subscription_id,ts`.`date' WHERE `object`='bx_payment_grid_sbs_list_all';

UPDATE `sys_grid_fields` SET `chars_limit`='32' WHERE `object`='bx_payment_grid_sbs_list_my' AND `name`='subscription_id';
UPDATE `sys_grid_fields` SET `title`='_bx_payment_grid_column_title_sbs_date_created' WHERE `object`='bx_payment_grid_sbs_list_my' AND `name`='date';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_sbs_list_all';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_list_all', 'seller_id', '_bx_payment_grid_column_title_sbs_seller_id', '10%', 0, '24', '', 1),
('bx_payment_grid_sbs_list_all', 'client_id', '_bx_payment_grid_column_title_sbs_client_id', '10%', 0, '24', '', 2),
('bx_payment_grid_sbs_list_all', 'client_email', '_bx_payment_grid_column_title_sbs_client_email', '15%', 0, '24', '', 3),
('bx_payment_grid_sbs_list_all', 'customer_id', '_bx_payment_grid_column_title_sbs_customer_id', '15%', 0, '18', '', 4),
('bx_payment_grid_sbs_list_all', 'subscription_id', '_bx_payment_grid_column_title_sbs_subscription_id', '15%', 0, '32', '', 5),
('bx_payment_grid_sbs_list_all', 'provider', '_bx_payment_grid_column_title_sbs_provider', '5%', 0, '16', '', 6),
('bx_payment_grid_sbs_list_all', 'paid', '_bx_payment_grid_column_title_sbs_paid', '4%', 0, '4', '', 7),
('bx_payment_grid_sbs_list_all', 'date', '_bx_payment_grid_column_title_sbs_date_created', '10%', 0, '10', '', 8),
('bx_payment_grid_sbs_list_all', 'actions', '', '16%', 0, '', '', 9);

UPDATE `sys_grid_actions` SET `icon`='sync' WHERE `object`='bx_payment_grid_orders_pending' AND `name`='process' AND `icon`='refresh';


-- FORMS
DELETE FROM `sys_form_inputs` WHERE `object`='bx_payment_form_processed' AND `name`='id';
DELETE FROM `sys_form_display_inputs` WHERE `display_name`='bx_payment_form_processed_add' AND `input_name`='id';