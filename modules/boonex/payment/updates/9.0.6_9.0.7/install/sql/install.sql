SET @sName = 'bx_payment';


-- TABLE: bx_payment_cart

ALTER TABLE `bx_payment_cart` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_cart` CHANGE `items` `items` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_cart` CHANGE `customs` `customs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_cart`;
OPTIMIZE TABLE `bx_payment_cart`;


-- TABLE: bx_payment_modules

ALTER TABLE `bx_payment_modules` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_modules` CHANGE `name` `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_modules`;
OPTIMIZE TABLE `bx_payment_modules`;


-- TABLE: bx_payment_providers

ALTER TABLE `bx_payment_providers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_providers` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers` CHANGE `caption` `caption` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers` CHANGE `description` `description` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers` CHANGE `option_prefix` `option_prefix` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers` CHANGE `class_name` `class_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers` CHANGE `class_file` `class_file` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_providers`;
OPTIMIZE TABLE `bx_payment_providers`;


-- TABLE: bx_payment_providers_options

ALTER TABLE `bx_payment_providers_options` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_providers_options` CHANGE `provider_id` `provider_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers_options` CHANGE `name` `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers_options` CHANGE `type` `type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers_options` CHANGE `caption` `caption` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers_options` CHANGE `description` `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers_options` CHANGE `extra` `extra` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers_options` CHANGE `check_type` `check_type` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers_options` CHANGE `check_params` `check_params` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_providers_options` CHANGE `check_error` `check_error` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_providers_options`;
OPTIMIZE TABLE `bx_payment_providers_options`;


-- TABLE: bx_payment_subscriptions

ALTER TABLE `bx_payment_subscriptions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_subscriptions` CHANGE `customer_id` `customer_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_subscriptions` CHANGE `subscription_id` `subscription_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_subscriptions`;
OPTIMIZE TABLE `bx_payment_subscriptions`;


-- TABLE: bx_payment_subscriptions_deleted

ALTER TABLE `bx_payment_subscriptions_deleted` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_subscriptions_deleted` CHANGE `customer_id` `customer_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_subscriptions_deleted` CHANGE `subscription_id` `subscription_id` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_subscriptions_deleted` CHANGE `reason` `reason` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_subscriptions_deleted`;
OPTIMIZE TABLE `bx_payment_subscriptions_deleted`;


-- TABLE: bx_payment_transactions

ALTER TABLE `bx_payment_transactions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_transactions` CHANGE `license` `license` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_transactions`;
OPTIMIZE TABLE `bx_payment_transactions`;


-- TABLE: bx_payment_transactions_pending

ALTER TABLE `bx_payment_transactions_pending` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_transactions_pending` CHANGE `type` `type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_transactions_pending` CHANGE `provider` `provider` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_transactions_pending` CHANGE `items` `items` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_transactions_pending` CHANGE `customs` `customs` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_transactions_pending` CHANGE `order` `order` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_transactions_pending` CHANGE `error_code` `error_code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_payment_transactions_pending` CHANGE `error_msg` `error_msg` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_transactions_pending`;
OPTIMIZE TABLE `bx_payment_transactions_pending`;


-- TABLE: bx_payment_user_values

ALTER TABLE `bx_payment_user_values` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_payment_user_values` CHANGE `value` `value` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_payment_user_values`;
OPTIMIZE TABLE `bx_payment_user_values`;
