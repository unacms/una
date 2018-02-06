UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = 'bx_googlecon' LIMIT 1;


-- TABLE: bx_googlecon_accounts

ALTER TABLE `bx_googlecon_accounts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_googlecon_accounts` CHANGE `remote_profile` `remote_profile` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL;

REPAIR TABLE `bx_googlecon_accounts`;
OPTIMIZE TABLE `bx_googlecon_accounts`;
