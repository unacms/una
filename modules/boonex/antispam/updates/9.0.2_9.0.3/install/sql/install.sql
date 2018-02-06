UPDATE sys_modules SET help_url = 'http://feed.una.io/?section={module_name}' WHERE name = 'bx_antispam' LIMIT 1;


-- TABLE: bx_antispam_block_log

ALTER TABLE `bx_antispam_block_log` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_antispam_block_log` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_antispam_block_log` CHANGE `extra` `extra` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_antispam_block_log`;
OPTIMIZE TABLE `bx_antispam_block_log`;


-- TABLE: bx_antispam_disposable_email_domains

ALTER TABLE `bx_antispam_disposable_email_domains` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_antispam_disposable_email_domains` CHANGE `domain` `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_antispam_disposable_email_domains`;
OPTIMIZE TABLE `bx_antispam_disposable_email_domains`;


-- TABLE: bx_antispam_dnsbl_rules

ALTER TABLE `bx_antispam_dnsbl_rules` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_antispam_dnsbl_rules` CHANGE `zonedomain` `zonedomain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_antispam_dnsbl_rules` CHANGE `postvresp` `postvresp` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_antispam_dnsbl_rules` CHANGE `url` `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_antispam_dnsbl_rules` CHANGE `recheck` `recheck` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_antispam_dnsbl_rules` CHANGE `comment` `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_antispam_dnsbl_rules`;
OPTIMIZE TABLE `bx_antispam_dnsbl_rules`;


-- TABLE: bx_antispam_dnsbluri_zones

ALTER TABLE `bx_antispam_dnsbluri_zones` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_antispam_dnsbluri_zones` CHANGE `zone` `zone` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_antispam_dnsbluri_zones`;
OPTIMIZE TABLE `bx_antispam_dnsbluri_zones`;


-- TABLE: bx_antispam_ip_table

ALTER TABLE `bx_antispam_ip_table` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_antispam_ip_table` CHANGE `Desc` `Desc` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_antispam_ip_table`;
OPTIMIZE TABLE `bx_antispam_ip_table`;
