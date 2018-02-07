UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = 'bx_dolcon' LIMIT 1;


-- TABLE: bx_dolcon_accounts

ALTER TABLE `bx_dolcon_accounts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_dolcon_accounts`;
OPTIMIZE TABLE `bx_dolcon_accounts`;
