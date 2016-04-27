SET @sName = 'bx_payment';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_payment_providers` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(64) NOT NULL default '',
  `caption` varchar(128) NOT NULL default '',
  `description` text NOT NULL default '',
  `option_prefix` varchar(32) NOT NULL default '',
  `for_visitor` tinyint(4) NOT NULL default '0',
  `for_subscription` tinyint(4) NOT NULL default '0',
  `class_name` varchar(128) NOT NULL default '',
  `class_file` varchar(255) NOT NULL  default '',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_payment_providers_options` (
  `id` int(11) NOT NULL auto_increment,
  `provider_id` varchar(64) NOT NULL default '',
  `name` varchar(64) NOT NULL default '',
  `type` varchar(64) NOT NULL default 'text',
  `caption` varchar(255) NOT NULL default '',
  `description` text NOT NULL default '',
  `extra` varchar(255) NOT NULL default '',
  `check_type` varchar(64) NOT NULL default '',
  `check_params` varchar(128) NOT NULL default '',
  `check_error` varchar(128) NOT NULL default '',
  `order` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`),
  UNIQUE KEY `name`(`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_payment_user_values` (
  `user_id` int(11) NOT NULL default '0',
  `option_id` int(11) NOT NULL default '0',  
  `value` varchar(255) NOT NULL default '',
  UNIQUE KEY `value`(`user_id`, `option_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_payment_cart` (
  `client_id` int(11) NOT NULL default '0',
  `items` text NOT NULL default '',
  PRIMARY KEY(`client_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_payment_transactions` (
  `id` int(11) NOT NULL auto_increment,
  `pending_id` int(11) NOT NULL default '0',
  `client_id` int(11) NOT NULL default '0',
  `seller_id` int(11) NOT NULL default '0',
  `module_id` int(11) NOT NULL default '0',  
  `item_id` int(11) NOT NULL default '0',
  `item_count` int(11) NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `license` varchar(16) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_payment_transactions_pending` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `client_id` int(11) NOT NULL default '0',
  `seller_id` int(11) NOT NULL default '0',
  `type` varchar(16) NOT NULL default 'single',
  `provider` varchar(32) NOT NULL default '',
  `items` text NOT NULL default '',
  `amount` float NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `error_code` varchar(16) NOT NULL default '',
  `error_msg` varchar(255) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `processed` tinyint(4) NOT NULL default '0',
  PRIMARY KEY(`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_payment_modules` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(32) NOT NULL default '',
  PRIMARY KEY(`id`),
  UNIQUE KEY `uri`(`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


-- PayPal payment provider
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`,`for_subscription`, `class_name`) VALUES
('paypal', '_bx_payment_pp_cpt', '_bx_payment_pp_dsc', 'pp_', 1, 0, 'BxPaymentProviderPayPal');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'pp_active', 'checkbox', '_bx_payment_pp_active_cpt', '_bx_payment_pp_active_dsc', '', '', '', '', 1),
(@iProviderId, 'pp_mode', 'select', '_bx_payment_pp_mode_cpt', '_bx_payment_pp_mode_dsc', '1|_bx_payment_pp_mode_live,2|_bx_payment_pp_mode_test', '', '', '', 2),
(@iProviderId, 'pp_business', 'text', '_bx_payment_pp_business_cpt', '_bx_payment_pp_business_dsc', '', '', '', '', 3),
(@iProviderId, 'pp_prc_type', 'select', '_bx_payment_pp_prc_type_cpt', '_bx_payment_pp_prc_type_dsc', '1|_bx_payment_pp_prc_type_direct,2|_bx_payment_pp_prc_type_pdt,3|_bx_payment_pp_prc_type_ipn', '', '', '', 4),
(@iProviderId, 'pp_token', 'text', '_bx_payment_pp_token_cpt', '_bx_payment_pp_token_dsc', '', '', '', '', 5),
(@iProviderId, 'pp_sandbox', 'text', '_bx_payment_pp_sandbox_cpt', '_bx_payment_pp_sandbox_dsc', '', '', '', '', 6),
(@iProviderId, 'pp_return_data_url', 'value', '_bx_payment_pp_return_data_url_cpt', '', '', '', '', '', 7);


-- 2Checkout payment provider
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_subscription`, `class_name`) VALUES
('2checkout', '_bx_payment_2co_cpt', '_bx_payment_2co_dsc', '2co_', 1, 0, 'BxPaymentProvider2Checkout');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, '2co_active', 'checkbox', '_bx_payment_2co_active_cpt', '_bx_payment_2co_active_dsc', '', '', '', '', 1),
(@iProviderId, '2co_mode', 'select', '_bx_payment_2co_mode_cpt', '_bx_payment_2co_mode_dsc', '1|_bx_payment_2co_mode_live,2|_bx_payment_2co_mode_test', '', '', '', 2),
(@iProviderId, '2co_account_id', 'text', '_bx_payment_2co_account_id_cpt', '_bx_payment_2co_account_id_dsc', '', '', '', '', 3),
(@iProviderId, '2co_payment_method', 'select', '_bx_payment_2co_payment_method_cpt', '_bx_payment_2co_payment_method_dsc', 'CC|_bx_payment_2co_payment_method_cc,PPI|_bx_payment_2co_payment_method_ppi', '', '', '', 4),
(@iProviderId, '2co_secret_word', 'text', '_bx_payment_2co_secret_word_cpt', '_bx_payment_2co_secret_word_dsc', '', '', '', '', 5),
(@iProviderId, '2co_return_data_url', 'value', '_bx_payment_2co_return_data_url_cpt', '', '', '', '', '', 6);


-- BitPay payment provider
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_subscription`, `class_name`) VALUES
('bitpay', '_bx_payment_bp_cpt', '_bx_payment_bp_dsc', 'bp_', 0, 0, 'BxPaymentProviderBitPay');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'bp_active', 'checkbox', '_bx_payment_bp_active_cpt', '_bx_payment_bp_active_dsc', '', 'https', '', '_bx_payment_bp_active_err', 1),
(@iProviderId, 'bp_api_key', 'text', '_bx_payment_bp_api_key_cpt', '_bx_payment_bp_api_key_dsc', '', '', '', '', 2),
(@iProviderId, 'bp_transaction_speed', 'select', '_bx_payment_bp_transaction_speed_cpt', '_bx_payment_bp_transaction_speed_dsc', 'high|_bx_payment_bp_transaction_speed_high,medium|_bx_payment_bp_transaction_speed_medium,low|_bx_payment_bp_transaction_speed_low', '', '', '', 3),
(@iProviderId, 'bp_full_notifications', 'checkbox', '_bx_payment_bp_full_notifications_cpt', '_bx_payment_bp_full_notifications_dsc', '', '', '', '', 4),
(@iProviderId, 'bp_notification_email', 'text', '_bx_payment_bp_notification_email_cpt', '_bx_payment_bp_notification_email_dsc', '', '', '', '', 5);


-- Chargebee payment provider
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_subscription`, `class_name`) VALUES
('chargebee', '_bx_payment_cbee_cpt', '_bx_payment_cbee_dsc', 'cbee_', 1, 1, 'BxPaymentProviderChargebee');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'cbee_active', 'checkbox', '_bx_payment_cbee_active_cpt', '_bx_payment_cbee_active_dsc', '', '', '', '', 1),
(@iProviderId, 'cbee_mode', 'select', '_bx_payment_cbee_mode_cpt', '_bx_payment_cbee_mode_dsc', '1|_bx_payment_cbee_mode_live,2|_bx_payment_cbee_mode_test', '', '', '', 2),
(@iProviderId, 'cbee_live_site', 'text', '_bx_payment_cbee_live_site_cpt', '_bx_payment_cbee_live_site_dsc', '', '', '', '', 3),
(@iProviderId, 'cbee_live_api_key', 'text', '_bx_payment_cbee_live_api_key_cpt', '_bx_payment_cbee_live_api_key_dsc', '', '', '', '', 4),
(@iProviderId, 'cbee_test_site', 'text', '_bx_payment_cbee_test_site_cpt', '_bx_payment_cbee_test_site_dsc', '', '', '', '', 5),
(@iProviderId, 'cbee_test_api_key', 'text', '_bx_payment_cbee_test_api_key_cpt', '_bx_payment_cbee_test_api_key_dsc', '', '', '', '', 6),
(@iProviderId, 'cbee_ssl', 'checkbox', '_bx_payment_cbee_ssl_cpt', '_bx_payment_cbee_ssl_dsc', '', '', '', '', 7),
(@iProviderId, 'cbee_return_data_url', 'value', '_bx_payment_cbee_return_data_url_cpt', '', '', '', '', '', 8),
(@iProviderId, 'cbee_notify_url', 'value', '_bx_payment_cbee_notify_url_cpt', '', '', '', '', '', 9);

-- Recurly payment provider
INSERT INTO `bx_payment_providers`(`name`, `caption`, `description`, `option_prefix`, `for_visitor`, `for_subscription`, `class_name`) VALUES
('recurly', '_bx_payment_rcrl_cpt', '_bx_payment_rcrl_dsc', 'rcrl_', 1, 1, 'BxPaymentProviderRecurly');
SET @iProviderId = LAST_INSERT_ID();

INSERT INTO `bx_payment_providers_options`(`provider_id`, `name`, `type`, `caption`, `description`, `extra`, `check_type`, `check_params`, `check_error`, `order`) VALUES
(@iProviderId, 'rcrl_active', 'checkbox', '_bx_payment_rcrl_active_cpt', '_bx_payment_rcrl_active_dsc', '', 'https', '', '_bx_payment_rcrl_active_err', 1),
(@iProviderId, 'rcrl_mode', 'select', '_bx_payment_rcrl_mode_cpt', '_bx_payment_rcrl_mode_dsc', '1|_bx_payment_rcrl_mode_live,2|_bx_payment_rcrl_mode_test', '', '', '', 2),
(@iProviderId, 'rcrl_site', 'text', '_bx_payment_rcrl_site_cpt', '_bx_payment_rcrl_site_dsc', '', '', '', '', 3),
(@iProviderId, 'rcrl_api_key_private', 'text', '_bx_payment_rcrl_api_key_private_cpt', '_bx_payment_rcrl_api_key_private_dsc', '', '', '', '', 4),
(@iProviderId, 'rcrl_api_key_public', 'text', '_bx_payment_rcrl_api_key_public_cpt', '_bx_payment_rcrl_api_key_public_dsc', '', '', '', '', 5),
(@iProviderId, 'rcrl_return_data_url', 'value', '_bx_payment_rcrl_return_data_url_cpt', '', '', '', '', '', 6),
(@iProviderId, 'rcrl_notify_url', 'value', '_bx_payment_rcrl_notify_url_cpt', '', '', '', '', '', 7);

-- GRIDS
INSERT INTO `sys_objects_grid` (`object`, `source_type`, `source`, `table`, `field_id`, `field_order`, `field_active`, `paginate_url`, `paginate_per_page`, `paginate_simple`, `paginate_get_start`, `paginate_get_per_page`, `filter_fields`, `filter_fields_translatable`, `filter_mode`, `sorting_fields`, `sorting_fields_translatable`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_grid_carts', 'Array', '', '', 'vendor_id', '', '', '', 20, NULL, 'start', '', '', '', 'like', '', '', 'BxPaymentGridCarts', 'modules/boonex/payment/classes/BxPaymentGridCarts.php'),
('bx_payment_grid_cart', 'Array', '', '', 'descriptor', '', '', '', 20, NULL, 'start', '', 'title,description', '', 'like', '', '', 'BxPaymentGridCart', 'modules/boonex/payment/classes/BxPaymentGridCart.php'),

('bx_payment_grid_orders_history', 'Sql', 'SELECT `tt`.`id` AS `id`, `tt`.`seller_id` AS `seller_id`, `ttp`.`order` AS `transaction`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 ', 'bx_payment_transactions', 'id', 'date', '', '', 100, NULL, 'start', '', 'ttp`.`order,tt`.`license,tt`.`amount,tt`.`date', '', 'auto', '', '', 'BxPaymentGridHistory', 'modules/boonex/payment/classes/BxPaymentGridHistory.php'),
('bx_payment_grid_orders_processed', 'Sql', 'SELECT `tt`.`id` AS `id`, `tt`.`client_id` AS `client_id`, `tt`.`seller_id` AS `seller_id`, `tt`.`module_id` AS `module_id`, `tt`.`item_id` AS `item_id`, `tt`.`item_count` AS `item_count`, `ttp`.`order` AS `transaction`, `ttp`.`error_msg` AS `error_msg`, `ttp`.`provider` AS `provider`, `tt`.`license` AS `license`, `tt`.`amount` AS `amount`, `tt`.`date` AS `date` FROM `bx_payment_transactions` AS `tt` LEFT JOIN `bx_payment_transactions_pending` AS `ttp` ON `tt`.`pending_id`=`ttp`.`id` WHERE 1 ', 'bx_payment_transactions', 'id', 'date', '', '', 100, NULL, 'start', '', 'ttp`.`order,tt`.`license,tt`.`amount,tt`.`date', '', 'auto', '', '', 'BxPaymentGridProcessed', 'modules/boonex/payment/classes/BxPaymentGridProcessed.php'),
('bx_payment_grid_orders_pending', 'Sql', 'SELECT `tt`.`id` AS `id`, `tt`.`client_id` AS `client_id`, `tt`.`seller_id` AS `seller_id`, `tt`.`items` AS `items`, `tt`.`amount` AS `amount`, `tt`.`order` AS `transaction`, `tt`.`error_msg` AS `error_msg`, `tt`.`provider` AS `provider`, `tt`.`date` AS `date` FROM `bx_payment_transactions_pending` AS `tt` WHERE 1 AND (ISNULL(`tt`.`order`) OR (NOT ISNULL(`tt`.`order`) AND `tt`.`error_code`<>''0'')) ', 'bx_payment_transactions_pending', 'id', 'date', '', '', 100, NULL, 'start', '', 'tt`.`order,tt`.`amount,tt`.`date', '', 'auto', '', '', 'BxPaymentGridPending', 'modules/boonex/payment/classes/BxPaymentGridPending.php');

INSERT INTO `sys_grid_fields` (`object`, `name`, `title`, `width`, `translatable`, `chars_limit`, `params`, `order`) VALUES
('bx_payment_grid_carts', 'checkbox', '', '2%', 0, '', '', 1),
('bx_payment_grid_carts', 'vendor_id', '_bx_payment_grid_column_title_crts_vendor_id', '40%', 0, '36', '', 2),
('bx_payment_grid_carts', 'items_count', '_bx_payment_grid_column_title_crts_items_count', '20%', 0, '16', '', 3),
('bx_payment_grid_carts', 'items_price', '_bx_payment_grid_column_title_crts_items_price', '20%', 0, '16', '', 4),
('bx_payment_grid_carts', 'actions', '', '18%', 0, '', '', 5),

('bx_payment_grid_cart', 'checkbox', '', '2%', 0, '', '', 1),
('bx_payment_grid_cart', 'title', '_bx_payment_grid_column_title_crt_title', '25%', 0, '24', '', 2),
('bx_payment_grid_cart', 'description', '_bx_payment_grid_column_title_crt_description', '35%', 0, '48', '', 3),
('bx_payment_grid_cart', 'quantity', '_bx_payment_grid_column_title_crt_quantity', '10%', 0, '16', '', 4),
('bx_payment_grid_cart', 'price_single', '_bx_payment_grid_column_title_crt_price', '10%', 0, '16', '', 5),
('bx_payment_grid_cart', 'actions', '', '18%', 0, '', '', 6),

('bx_payment_grid_orders_history', 'seller_id', '_bx_payment_grid_column_title_ods_seller_id', '24%', 0, '20', '', 1),
('bx_payment_grid_orders_history', 'transaction', '_bx_payment_grid_column_title_ods_transaction', '22%', 0, '18', '', 2),
('bx_payment_grid_orders_history', 'license', '_bx_payment_grid_column_title_ods_license', '22%', 0, '18', '', 3),
('bx_payment_grid_orders_history', 'amount', '_bx_payment_grid_column_title_ods_amount', '10%', 1, '10', '', 4),
('bx_payment_grid_orders_history', 'date', '_bx_payment_grid_column_title_ods_date', '10%', 0, '10', '', 5),
('bx_payment_grid_orders_history', 'actions', '', '12%', 0, '', '', 6),

('bx_payment_grid_orders_processed', 'checkbox', '', '2%', 0, '', '', 1),
('bx_payment_grid_orders_processed', 'client_id', '_bx_payment_grid_column_title_ods_client_id', '22%', 0, '18', '', 2),
('bx_payment_grid_orders_processed', 'transaction', '_bx_payment_grid_column_title_ods_transaction', '20%', 0, '18', '', 3),
('bx_payment_grid_orders_processed', 'license', '_bx_payment_grid_column_title_ods_license', '20%', 0, '18', '', 4),
('bx_payment_grid_orders_processed', 'amount', '_bx_payment_grid_column_title_ods_amount', '10%', 1, '10', '', 5),
('bx_payment_grid_orders_processed', 'date', '_bx_payment_grid_column_title_ods_date', '10%', 0, '10', '', 6),
('bx_payment_grid_orders_processed', 'actions', '', '16%', 0, '', '', 7),

('bx_payment_grid_orders_pending', 'checkbox', '', '2%', 0, '', '', 1),
('bx_payment_grid_orders_pending', 'client_id', '_bx_payment_grid_column_title_ods_client_id', '25%', 0, '25', '', 2),
('bx_payment_grid_orders_pending', 'transaction', '_bx_payment_grid_column_title_ods_transaction', '25%', 0, '25', '', 3),
('bx_payment_grid_orders_pending', 'amount', '_bx_payment_grid_column_title_ods_amount', '14%', 1, '14', '', 4),
('bx_payment_grid_orders_pending', 'date', '_bx_payment_grid_column_title_ods_date', '14%', 0, '14', '', 5),
('bx_payment_grid_orders_pending', 'actions', '', '20%', 0, '', '', 6);

INSERT INTO `sys_grid_actions` (`object`, `type`, `name`, `title`, `icon`, `icon_only`, `confirm`, `order`) VALUES
('bx_payment_grid_carts', 'bulk', 'delete', '_bx_payment_grid_action_title_crts_delete', '', 0, 1, 1),
('bx_payment_grid_carts', 'single', 'continue', '_bx_payment_grid_action_title_crts_continue', 'money', 1, 0, 1),
('bx_payment_grid_carts', 'single', 'delete', '_bx_payment_grid_action_title_crts_delete', 'remove', 1, 1, 2),

('bx_payment_grid_cart', 'bulk', 'delete', '_bx_payment_grid_action_title_crt_delete', '', 0, 1, 1),
('bx_payment_grid_cart', 'single', 'delete', '_bx_payment_grid_action_title_crt_delete', 'remove', 1, 1, 1),

('bx_payment_grid_orders_history', 'single', 'view_order', '_bx_payment_grid_action_title_ods_view_order', 'ellipsis-h', 1, 0, 1),

('bx_payment_grid_orders_processed', 'independent', 'add', '_bx_payment_grid_action_title_ods_add', '', 0, 0, 1),
('bx_payment_grid_orders_processed', 'bulk', 'cancel', '_bx_payment_grid_action_title_ods_cancel', '', 0, 1, 1),
('bx_payment_grid_orders_processed', 'single', 'view_order', '_bx_payment_grid_action_title_ods_view_order', 'ellipsis-h', 1, 0, 1),
('bx_payment_grid_orders_processed', 'single', 'cancel', '_bx_payment_grid_action_title_ods_cancel', 'times', 1, 1, 2),

('bx_payment_grid_orders_pending', 'bulk', 'cancel', '_bx_payment_grid_action_title_ods_cancel', '', 0, 1, 1),
('bx_payment_grid_orders_pending', 'single', 'view_order', '_bx_payment_grid_action_title_ods_view_order', 'ellipsis-h', 1, 0, 1),
('bx_payment_grid_orders_pending', 'single', 'process', '_bx_payment_grid_action_title_ods_process', 'refresh', 1, 0, 2),
('bx_payment_grid_orders_pending', 'single', 'cancel', '_bx_payment_grid_action_title_ods_cancel', 'times', 1, 1, 3);


-- FORMS
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_payment_form_pendings', @sName, '_bx_payment_form_pendings_form', '', '', 'do_submit', 'bx_payment_transactions_pending', 'id', '', '', '', 0, 1, 'BxPaymentFormView', 'modules/boonex/payment/classes/BxPaymentFormView.php'),
('bx_payment_form_processed', @sName, '_bx_payment_form_processed_form', '', '', 'do_submit', 'bx_payment_transactions', 'id', '', '', '', 0, 1, 'BxPaymentFormView', 'modules/boonex/payment/classes/BxPaymentFormView.php'),
-- FORMS: Recurly
('bx_payment_form_rcrl_card', @sName, '_bx_payment_form_rcrl_card', '', '', 'do_submit', '', '', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_payment_form_pendings_process', @sName, 'bx_payment_form_pendings', '_bx_payment_form_pendings_display_process', 0),
('bx_payment_form_processed_add', @sName, 'bx_payment_form_processed', '_bx_payment_form_processed_display_add', 0),
-- FORMS: Recurly
('bx_payment_form_rcrl_card_add', @sName, 'bx_payment_form_rcrl_card', '_bx_payment_form_rcrl_card_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_payment_form_pendings', @sName, 'id', '0', '', 0, 'hidden', '_bx_payment_form_pendings_input_id_sys', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_payment_form_pendings', @sName, 'seller_id', '0', '', 0, 'hidden', '_bx_payment_form_pendings_input_seller_id_sys', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_payment_form_pendings', @sName, 'order', '', '', 0, 'text', '_bx_payment_form_pendings_input_order_sys', '_bx_payment_form_pendings_input_order', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_payment_form_pendings_input_order_err', 'Xss', '', 0, 0),
('bx_payment_form_pendings', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_pendings', @sName, 'do_submit', '_bx_payment_form_pendings_input_process', '', 0, 'submit', '_bx_payment_form_pendings_input_process_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_pendings', @sName, 'do_cancel', '_bx_payment_form_pendings_input_cancel', '', 0, 'button', '_bx_payment_form_pendings_input_cancel_sys', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),

('bx_payment_form_processed', @sName, 'id', '0', '', 0, 'hidden', '_bx_payment_form_processed_input_id_sys', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_payment_form_processed', @sName, 'seller_id', '0', '', 0, 'hidden', '_bx_payment_form_processed_input_seller_id_sys', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_payment_form_processed', @sName, 'client_id', '0', '', 0, 'hidden', '_bx_payment_form_processed_input_client_id_sys', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_payment_form_processed', @sName, 'client', '0', '', 0, 'text', '_bx_payment_form_processed_input_client_sys', '_bx_payment_form_processed_input_client', '', 0, 0, 0, '', '', '', 'Avail', '', '_bx_payment_form_processed_input_client_err', 'Xss', '', 0, 0),
('bx_payment_form_processed', @sName, 'order', '0', '', 0, 'text', '_bx_payment_form_processed_input_order_sys', '_bx_payment_form_processed_input_order', '', 0, 0, 0, '', '', '', 'Avail', '', '_bx_payment_form_processed_input_order_err', 'Xss', '', 0, 0),
('bx_payment_form_processed', @sName, 'module_id', '0', '', 0, 'select', '_bx_payment_form_processed_input_module_id_sys', '_bx_payment_form_processed_input_module_id', '', 0, 0, 0, '', '', '', 'Avail', '', '_bx_payment_form_processed_input_module_id_err', 'Int', '', 0, 0),
('bx_payment_form_processed', @sName, 'items', '0', '', 0, 'custom', '_bx_payment_form_processed_input_items_sys', '_bx_payment_form_processed_input_items', '', 0, 0, 0, '', '', '', '', '', '_bx_payment_form_processed_input_items_err', '', '', 0, 0),
('bx_payment_form_processed', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_processed', @sName, 'do_submit', '_bx_payment_form_processed_input_add', '', 0, 'submit', '_bx_payment_form_processed_input_add_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_processed', @sName, 'do_cancel', '_bx_payment_form_processed_input_cancel', '', 0, 'button', '_bx_payment_form_processed_input_cancel_sys', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0),

-- FORMS: Recurly
('bx_payment_form_rcrl_card', @sName, 'pending_id', '0', '', 0, 'hidden', '_bx_payment_form_rcrl_card_input_pending_id_sys', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'item', '', '', 0, 'hidden', '_bx_payment_form_rcrl_card_input_item_sys', '', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'token', '', '', 0, 'hidden', '_bx_payment_form_rcrl_card_input_token_sys', '', '', 0, 0, 0, 'a:1:{s:12:"data-recurly";s:5:"token";}', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'first_name', '', '', 0, 'text', '_bx_payment_form_rcrl_card_input_first_name_sys', '_bx_payment_form_rcrl_card_input_first_name', '', 1, 0, 0, 'a:1:{s:12:"data-recurly";s:10:"first_name";}', '', '', 'Avail', '', '_bx_payment_form_rcrl_card_input_first_name_err', 'Xss', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'last_name', '', '', 0, 'text', '_bx_payment_form_rcrl_card_input_last_name_sys', '_bx_payment_form_rcrl_card_input_last_name', '', 1, 0, 0, 'a:1:{s:12:"data-recurly";s:9:"last_name";}', '', '', 'Avail', '', '_bx_payment_form_rcrl_card_input_last_name_err', 'Xss', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'email', '', '', 0, 'text', '_bx_payment_form_rcrl_card_input_email_sys', '_bx_payment_form_rcrl_card_input_email', '', 1, 0, 0, '', '', '', 'Email', '', '', 'Xss', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'card_number', '', '', 0, 'custom', '_bx_payment_form_rcrl_card_input_card_number_sys', '_bx_payment_form_rcrl_card_input_card_number', '_bx_payment_form_rcrl_card_input_card_number_inf', 1, 0, 0, 'a:1:{s:12:"data-recurly";s:6:"number";}', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'card_expire', '', '', 0, 'custom', '_bx_payment_form_rcrl_card_input_card_expire_sys', '_bx_payment_form_rcrl_card_input_card_expire', '_bx_payment_form_rcrl_card_input_card_expire_inf', 1, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'card_cvv', '', '', 0, 'custom', '_bx_payment_form_rcrl_card_input_card_cvv_sys', '_bx_payment_form_rcrl_card_input_card_cvv', '_bx_payment_form_rcrl_card_input_card_cvv_inf', 1, 0, 0, 'a:1:{s:12:"data-recurly";s:3:"cvv";}', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'do_submit', '_bx_payment_form_rcrl_card_input_submit', '', 0, 'submit', '_bx_payment_form_rcrl_card_input_submit_sys', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_payment_form_rcrl_card', @sName, 'do_cancel', '_bx_payment_form_rcrl_card_input_cancel', '', 0, 'button', '_bx_payment_form_rcrl_card_input_cancel_sys', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_payment_form_pendings_process', 'id', 2147483647, 1, 1),
('bx_payment_form_pendings_process', 'seller_id', 2147483647, 1, 2),
('bx_payment_form_pendings_process', 'order', 2147483647, 1, 3),
('bx_payment_form_pendings_process', 'controls', 2147483647, 1, 4),
('bx_payment_form_pendings_process', 'do_submit', 2147483647, 1, 5),
('bx_payment_form_pendings_process', 'do_cancel', 2147483647, 1, 6),

('bx_payment_form_processed_add', 'id', 2147483647, 0, 1),
('bx_payment_form_processed_add', 'seller_id', 2147483647, 1, 2),
('bx_payment_form_processed_add', 'client_id', 2147483647, 1, 3),
('bx_payment_form_processed_add', 'client', 2147483647, 1, 4),
('bx_payment_form_processed_add', 'order', 2147483647, 1, 5),
('bx_payment_form_processed_add', 'module_id', 2147483647, 1, 6),
('bx_payment_form_processed_add', 'items', 2147483647, 1, 7),
('bx_payment_form_processed_add', 'controls', 2147483647, 1, 8),
('bx_payment_form_processed_add', 'do_submit', 2147483647, 1, 9),
('bx_payment_form_processed_add', 'do_cancel', 2147483647, 1, 10),

-- FORMS: Recurly
('bx_payment_form_rcrl_card_add', 'pending_id', 2147483647, 1, 1),
('bx_payment_form_rcrl_card_add', 'item', 2147483647, 1, 2),
('bx_payment_form_rcrl_card_add', 'token', 2147483647, 1, 3),
('bx_payment_form_rcrl_card_add', 'first_name', 2147483647, 1, 4),
('bx_payment_form_rcrl_card_add', 'last_name', 2147483647, 1, 5),
('bx_payment_form_rcrl_card_add', 'email', 2147483647, 1, 6),
('bx_payment_form_rcrl_card_add', 'card_number', 2147483647, 1, 7),
('bx_payment_form_rcrl_card_add', 'card_expire', 2147483647, 1, 8),
('bx_payment_form_rcrl_card_add', 'card_cvv', 2147483647, 1, 9),
('bx_payment_form_rcrl_card_add', 'controls', 2147483647, 1, 10),
('bx_payment_form_rcrl_card_add', 'do_submit', 2147483647, 1, 11),
('bx_payment_form_rcrl_card_add', 'do_cancel', 2147483647, 1, 12);


-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_payment', '_bx_payment', 'bx_payment@modules/boonex/payment/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, '{url_studio}module.php?name=bx_payment', '', 'bx_payment@modules/boonex/payment/|std-wi.png', '_bx_payment', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
