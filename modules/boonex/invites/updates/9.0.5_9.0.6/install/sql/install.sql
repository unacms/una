-- TABLE: bx_inv_invites

ALTER TABLE `bx_inv_invites` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_inv_invites` CHANGE `key` `key` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_inv_invites` CHANGE `email` `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_inv_invites`;
OPTIMIZE TABLE `bx_inv_invites`;


-- TABLE: bx_inv_requests

ALTER TABLE `bx_inv_requests` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_inv_requests` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_inv_requests` CHANGE `email` `email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_inv_requests` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_inv_requests`;
OPTIMIZE TABLE `bx_inv_requests`;
