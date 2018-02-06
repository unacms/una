UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = 'bx_oauth' LIMIT 1;


-- TABLE: bx_oauth_access_tokens

ALTER TABLE `bx_oauth_access_tokens` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_oauth_access_tokens` CHANGE `access_token` `access_token` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_access_tokens` CHANGE `client_id` `client_id` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_access_tokens` CHANGE `scope` `scope` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_oauth_access_tokens`;
OPTIMIZE TABLE `bx_oauth_access_tokens`;


-- TABLE: bx_oauth_authorization_codes

ALTER TABLE `bx_oauth_authorization_codes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_oauth_authorization_codes` CHANGE `authorization_code` `authorization_code` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_authorization_codes` CHANGE `client_id` `client_id` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_authorization_codes` CHANGE `redirect_uri` `redirect_uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_authorization_codes` CHANGE `scope` `scope` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_oauth_authorization_codes`;
OPTIMIZE TABLE `bx_oauth_authorization_codes`;


-- TABLE: bx_oauth_clients

ALTER TABLE `bx_oauth_clients` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_oauth_clients` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_clients` CHANGE `client_id` `client_id` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_clients` CHANGE `client_secret` `client_secret` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_clients` CHANGE `redirect_uri` `redirect_uri` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_clients` CHANGE `grant_types` `grant_types` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_clients` CHANGE `scope` `scope` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_oauth_clients`;
OPTIMIZE TABLE `bx_oauth_clients`;


-- TABLE: bx_oauth_refresh_tokens

ALTER TABLE `bx_oauth_refresh_tokens` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_oauth_refresh_tokens` CHANGE `refresh_token` `refresh_token` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_refresh_tokens` CHANGE `client_id` `client_id` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_oauth_refresh_tokens` CHANGE `scope` `scope` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_oauth_refresh_tokens`;
OPTIMIZE TABLE `bx_oauth_refresh_tokens`;


-- TABLE: bx_oauth_scopes

ALTER TABLE `bx_oauth_scopes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_oauth_scopes` CHANGE `scope` `scope` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_oauth_scopes`;
OPTIMIZE TABLE `bx_oauth_scopes`;
