-- TABLE: bx_convos_cmts

ALTER TABLE `bx_convos_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_convos_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_convos_cmts`;
OPTIMIZE TABLE `bx_convos_cmts`;


-- TABLE: bx_convos_conv2folder

ALTER TABLE `bx_convos_conv2folder` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_convos_conv2folder`;
OPTIMIZE TABLE `bx_convos_conv2folder`;


-- TABLE: bx_convos_conversations

ALTER TABLE `bx_convos_conversations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_convos_conversations` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_convos_conversations`;
OPTIMIZE TABLE `bx_convos_conversations`;


-- TABLE: bx_convos_files

ALTER TABLE `bx_convos_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_convos_files` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_convos_files` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_convos_files` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_convos_files` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_convos_files` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_convos_files`;
OPTIMIZE TABLE `bx_convos_files`;


-- TABLE: bx_convos_folders

ALTER TABLE `bx_convos_folders` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_convos_folders` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_convos_folders`;
OPTIMIZE TABLE `bx_convos_folders`;


-- TABLE: bx_convos_photos_resized

ALTER TABLE `bx_convos_photos_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_convos_photos_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_convos_photos_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_convos_photos_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_convos_photos_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_convos_photos_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_convos_photos_resized`;
OPTIMIZE TABLE `bx_convos_photos_resized`;


-- TABLE: bx_convos_views_track

ALTER TABLE `bx_convos_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_convos_views_track`;
OPTIMIZE TABLE `bx_convos_views_track`;
