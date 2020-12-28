SET @sName = 'bx_payment';

-- TABLES
UPDATE `bx_payment_providers` SET `for_recurring`='1', `single_seller`='1', `time_tracker`='1' WHERE `name`='credits';

SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='stripe_v3' LIMIT 1);

DELETE FROM `bx_payment_providers` WHERE `id`=@iProviderId;
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId;

INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_single`, `for_recurring`, `active`, `order`, `class_name`) VALUES
('stripe_v3', '_bx_payment_strp_v3_cpt', '_bx_payment_strp_v3_dsc', 'strp_v3_', 1, 1, 1, 1, 61, 'BxPaymentProviderStripeV3');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'strp_v3_active', 'checkbox', '_bx_payment_strp_active_cpt', '_bx_payment_strp_active_dsc', '', '', '', '', 1),
(@iProviderId, 'strp_v3_hidden', 'checkbox', '_bx_payment_strp_hidden_cpt', '_bx_payment_strp_hidden_dsc', '', '', '', '', 2),
(@iProviderId, 'strp_v3_mode', 'select', '_bx_payment_strp_mode_cpt', '_bx_payment_strp_mode_dsc', '1|_bx_payment_strp_mode_live,2|_bx_payment_strp_mode_test', '', '', '', 3),
(@iProviderId, 'strp_v3_live_pub_key', 'text', '_bx_payment_strp_live_pub_key_cpt', '_bx_payment_strp_live_pub_key_dsc', '', '', '', '', 4),
(@iProviderId, 'strp_v3_live_sec_key', 'text', '_bx_payment_strp_live_sec_key_cpt', '_bx_payment_strp_live_sec_key_dsc', '', '', '', '', 5),
(@iProviderId, 'strp_v3_test_pub_key', 'text', '_bx_payment_strp_test_pub_key_cpt', '_bx_payment_strp_test_pub_key_dsc', '', '', '', '', 6),
(@iProviderId, 'strp_v3_test_sec_key', 'text', '_bx_payment_strp_test_sec_key_cpt', '_bx_payment_strp_test_sec_key_dsc', '', '', '', '', 7),
(@iProviderId, 'strp_v3_check_amount', 'checkbox', '_bx_payment_strp_check_amount_cpt', '_bx_payment_strp_check_amount_dsc', '', '', '', '', 8),
(@iProviderId, 'strp_v3_ssl', 'checkbox', '_bx_payment_strp_ssl_cpt', '_bx_payment_strp_ssl_dsc', '', '', '', '', 9),
(@iProviderId, 'strp_v3_cancellation_email', 'text', '_bx_payment_strp_cancellation_email_cpt', '', '', 'EmailOrEmpty', '', '_sys_form_account_input_email_error', 10),
(@iProviderId, 'strp_v3_expiration_email', 'text', '_bx_payment_strp_expiration_email_cpt', '', '', 'EmailOrEmpty', '', '_sys_form_account_input_email_error', 11),
(@iProviderId, 'strp_v3_notify_url', 'value', '_bx_payment_strp_notify_url_cpt', '', '', '', '', '', 12);

SET @iProviderId = (SELECT `id` FROM `bx_payment_providers` WHERE `name`='apple_in_app' LIMIT 1);

DELETE FROM `bx_payment_providers` WHERE `id`=@iProviderId;
DELETE FROM `bx_payment_providers_options` WHERE `provider_id`=@iProviderId;

INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_owner_only`, `for_single`, `for_recurring`, `active`, `order`, `class_name`) VALUES
('apple_in_app', '_bx_payment_aina_cpt', '_bx_payment_aina_dsc', 'aina_', 0, 1, 1, 1, 1, 70, 'BxPaymentProviderAppleInApp');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'aina_active', 'checkbox', '_bx_payment_aina_active_cpt', '_bx_payment_aina_active_dsc', '', '', '', '', 1),
(@iProviderId, 'aina_hidden', 'checkbox', '_bx_payment_aina_hidden_cpt', '_bx_payment_aina_hidden_dsc', '', '', '', '', 2),
(@iProviderId, 'aina_secret', 'text', '_bx_payment_aina_secret_cpt', '_bx_payment_aina_secret_dsc', '', '', '', '', 3),
(@iProviderId, 'aina_notify_url', 'value', '_bx_payment_aina_notify_url_cpt', '', '', '', '', '', 4);


-- GRIDS
UPDATE `sys_objects_grid` SET `source`='SELECT `ttp`.`id` AS `id`, `ttp`.`seller_id` AS `seller_id`, `ts`.`customer_id` AS `customer_id`, `ts`.`subscription_id` AS `subscription_id`, `ttp`.`provider` AS `provider`, `ttp`.`items` AS `items`, `ts`.`date_add` AS `date_add`, `ts`.`date_next` AS `date_next`, `ts`.`status` AS `status` FROM `bx_payment_subscriptions` AS `ts` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `ts`.`pending_id`=`ttp`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ', `field_order`='date_add', `filter_fields`='ts`.`customer_id,ts`.`subscription_id,ts`.`date_add' WHERE `object`='bx_payment_grid_sbs_list_my';
UPDATE `sys_objects_grid` SET `source`='SELECT `ttp`.`id` AS `id`, `ttp`.`client_id` AS `client_id`, `tac`.`email` AS `client_email`, `ttp`.`seller_id` AS `seller_id`, `ts`.`customer_id` AS `customer_id`, `ts`.`subscription_id` AS `subscription_id`, `ttp`.`provider` AS `provider`, `ttp`.`items` AS `items`, `ts`.`date_add` AS `date_add`, `ts`.`date_next` AS `date_next`, `ts`.`status` AS `status` FROM `bx_payment_subscriptions` AS `ts` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `ts`.`pending_id`=`ttp`.`id` LEFT JOIN `sys_profiles` AS `tpc` ON `ttp`.`client_id`=`tpc`.`id` LEFT JOIN `sys_accounts` AS `tac` ON `tpc`.`account_id`=`tac`.`id` WHERE 1 AND `ttp`.`type`=''recurring'' ', `field_order`='date_add' WHERE `object`='bx_payment_grid_sbs_list_all';
UPDATE `sys_objects_grid` SET `source`='SELECT `tt`.`id` AS `id`, `tt`.`client_id` AS `client_id`, `tt`.`seller_id` AS `seller_id`, `tt`.`author_id` AS `author_id`, `tt`.`module_id` AS `module_id`, `tt`.`item_id` AS `item_id`, `tt`.`item_count` AS `item_count`, `ttp`.`order` AS `transaction`, `ttp`.`error_msg` AS `error_msg`, `ttp`.`provider` AS `provider`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 ' WHERE `object`='bx_payment_grid_orders_processed';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_providers';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_providers', 'order', '', '2%', 0, '0', '', 1),
('bx_payment_grid_providers', 'switcher', '', '8%', 0, '0', '', 2),
('bx_payment_grid_providers', 'caption', '_bx_payment_grid_column_title_pdrs_caption', '20%', 1, '16', '', 3),
('bx_payment_grid_providers', 'description', '_bx_payment_grid_column_title_pdrs_description', '30%', 1, '32', '', 4),
('bx_payment_grid_providers', 'for_visitor', '_bx_payment_grid_column_title_pdrs_for_visitor', '8%', 0, '8', '', 5),
('bx_payment_grid_providers', 'for_single', '_bx_payment_grid_column_title_pdrs_for_single', '8%', 0, '8', '', 6),
('bx_payment_grid_providers', 'for_recurring', '_bx_payment_grid_column_title_pdrs_for_recurring', '8%', 0, '8', '', 7),
('bx_payment_grid_providers', 'single_seller', '_bx_payment_grid_column_title_pdrs_single_seller', '8%', 0, '8', '', 8),
('bx_payment_grid_providers', 'time_tracker', '_bx_payment_grid_column_title_pdrs_time_tracker', '8%', 0, '8', '', 9);

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_sbs_list_my';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_list_my', 'checkbox', '', '2%', 0, '0', '', 1),
('bx_payment_grid_sbs_list_my', 'seller_id', '_bx_payment_grid_column_title_sbs_seller_id', '16%', 0, '0', '', 2),
('bx_payment_grid_sbs_list_my', 'customer_id', '_bx_payment_grid_column_title_sbs_customer_id', '10%', 0, '8', '', 3),
('bx_payment_grid_sbs_list_my', 'subscription_id', '_bx_payment_grid_column_title_sbs_subscription_id', '10%', 0, '0', '', 4),
('bx_payment_grid_sbs_list_my', 'provider', '_bx_payment_grid_column_title_sbs_provider', '10%', 0, '16', '', 5),
('bx_payment_grid_sbs_list_my', 'date_add', '_bx_payment_grid_column_title_sbs_date_add', '14%', 0, '10', '', 6),
('bx_payment_grid_sbs_list_my', 'date_next', '_bx_payment_grid_column_title_sbs_date_next', '14%', 0, '10', '', 7),
('bx_payment_grid_sbs_list_my', 'status', '_bx_payment_grid_column_title_sbs_status', '6%', 0, '8', '', 8),
('bx_payment_grid_sbs_list_my', 'actions', '', '18%', 0, '0', '', 9);

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_sbs_list_all';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_sbs_list_all', 'seller_id', '_bx_payment_grid_column_title_sbs_seller_id', '12%', 0, '0', '', 1),
('bx_payment_grid_sbs_list_all', 'client_id', '_bx_payment_grid_column_title_sbs_client_id', '12%', 0, '0', '', 2),
('bx_payment_grid_sbs_list_all', 'client_email', '_bx_payment_grid_column_title_sbs_client_email', '15%', 0, '24', '', 3),
('bx_payment_grid_sbs_list_all', 'customer_id', '_bx_payment_grid_column_title_sbs_customer_id', '5%', 0, '8', '', 4),
('bx_payment_grid_sbs_list_all', 'subscription_id', '_bx_payment_grid_column_title_sbs_subscription_id', '5%', 0, '0', '', 5),
('bx_payment_grid_sbs_list_all', 'provider', '_bx_payment_grid_column_title_sbs_provider', '5%', 0, '16', '', 6),
('bx_payment_grid_sbs_list_all', 'date_add', '_bx_payment_grid_column_title_sbs_date_add', '12%', 0, '10', '', 7),
('bx_payment_grid_sbs_list_all', 'date_next', '_bx_payment_grid_column_title_sbs_date_next', '12%', 0, '10', '', 8),
('bx_payment_grid_sbs_list_all', 'status', '_bx_payment_grid_column_title_sbs_status', '6%', 0, '8', '', 9),
('bx_payment_grid_sbs_list_all', 'actions', '', '16%', 0, '0', '', 10);

UPDATE `sys_grid_fields` SET `chars_limit`='0' WHERE `object`='bx_payment_grid_sbs_history' AND `name`='seller_id';
UPDATE `sys_grid_fields` SET `chars_limit`='0' WHERE `object`='bx_payment_grid_orders_history' AND `name`='seller_id';

DELETE FROM `sys_grid_fields` WHERE `object`='bx_payment_grid_orders_processed';
INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_orders_processed', 'checkbox', '', '2%', 0, 0, '', 1),
('bx_payment_grid_orders_processed', 'client_id', '_bx_payment_grid_column_title_ods_client_id', '16%', 0, 0, '', 2),
('bx_payment_grid_orders_processed', 'author_id', '_bx_payment_grid_column_title_ods_author_id', '16%', 0, 0, '', 3),
('bx_payment_grid_orders_processed', 'transaction', '_bx_payment_grid_column_title_ods_transaction', '10%', 0, 8, '', 4),
('bx_payment_grid_orders_processed', 'item', '_bx_payment_grid_column_title_ods_item', '20%', 0, 0, '', 5),
('bx_payment_grid_orders_processed', 'amount', '_bx_payment_grid_column_title_ods_amount', '10%', 1, 10, '', 6),
('bx_payment_grid_orders_processed', 'date', '_bx_payment_grid_column_title_ods_date', '10%', 0, 10, '', 7),
('bx_payment_grid_orders_processed', 'actions', '', '16%', 0, 0, '', 8);

UPDATE `sys_grid_fields` SET `chars_limit`='0' WHERE `object`='bx_payment_grid_orders_pending' AND `name`='client_id';

UPDATE `sys_grid_actions` SET `icon`='ban' WHERE `object`='bx_payment_grid_sbs_list_all' AND `name`='cancel';

DELETE FROM `sys_grid_actions` WHERE `object`='bx_payment_grid_sbs_list_all' AND `name`='delete';
INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_payment_grid_sbs_list_all', 'single', 'delete', '_bx_payment_grid_action_title_sbs_delete', 'times', 1, 1, 3);


-- STUDIO PAGE & WIDGET
SET @iPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name`=@sName LIMIT 1);
UPDATE `sys_std_widgets` SET `type`='extensions' WHERE `page_id`=@iPageId;
