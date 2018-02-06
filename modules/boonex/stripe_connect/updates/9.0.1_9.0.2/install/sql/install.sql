-- TABLE: bx_stripe_connect_accounts

ALTER TABLE `bx_stripe_connect_accounts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_stripe_connect_accounts` CHANGE `user_id` `user_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_stripe_connect_accounts` CHANGE `public_key` `public_key` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_stripe_connect_accounts` CHANGE `access_token` `access_token` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_stripe_connect_accounts` CHANGE `refresh_token` `refresh_token` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_stripe_connect_accounts`;
OPTIMIZE TABLE `bx_stripe_connect_accounts`;
