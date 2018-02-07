-- TABLES
CREATE TABLE IF NOT EXISTS `bx_snipcart_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: bx_snipcart_cmts

ALTER TABLE `bx_snipcart_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_snipcart_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_snipcart_cmts`;
OPTIMIZE TABLE `bx_snipcart_cmts`;


-- TABLE: bx_snipcart_entries

ALTER TABLE `bx_snipcart_entries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_snipcart_entries` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_entries` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_snipcart_entries`;
OPTIMIZE TABLE `bx_snipcart_entries`;


-- TABLE: bx_snipcart_favorites_track

ALTER TABLE `bx_snipcart_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_snipcart_favorites_track`;
OPTIMIZE TABLE `bx_snipcart_favorites_track`;


-- TABLE: bx_snipcart_files

ALTER TABLE `bx_snipcart_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_snipcart_files` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_files` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_files` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_files` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_files` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_snipcart_files`;
OPTIMIZE TABLE `bx_snipcart_files`;


-- TABLE: bx_snipcart_meta_keywords

ALTER TABLE `bx_snipcart_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_snipcart_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_snipcart_meta_keywords`;
OPTIMIZE TABLE `bx_snipcart_meta_keywords`;


-- TABLE: bx_snipcart_meta_locations

ALTER TABLE `bx_snipcart_meta_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_snipcart_meta_locations` CHANGE `country` `country` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_meta_locations` CHANGE `state` `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_meta_locations` CHANGE `city` `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_meta_locations` CHANGE `zip` `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_meta_locations` CHANGE `street` `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_meta_locations` CHANGE `street_number` `street_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_snipcart_meta_locations`;
OPTIMIZE TABLE `bx_snipcart_meta_locations`;


-- TABLE: bx_snipcart_photos_resized

ALTER TABLE `bx_snipcart_photos_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_snipcart_photos_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_photos_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_photos_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_photos_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_photos_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_snipcart_photos_resized`;
OPTIMIZE TABLE `bx_snipcart_photos_resized`;


-- TABLE: bx_snipcart_reports

ALTER TABLE `bx_snipcart_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_snipcart_reports`;
OPTIMIZE TABLE `bx_snipcart_reports`;


-- TABLE: bx_snipcart_reports_track

ALTER TABLE `bx_snipcart_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_snipcart_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_snipcart_reports_track`;
OPTIMIZE TABLE `bx_snipcart_reports_track`;


-- TABLE: bx_snipcart_settings

ALTER TABLE `bx_snipcart_settings` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_snipcart_settings` CHANGE `mode` `mode` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_settings` CHANGE `api_key_test` `api_key_test` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_settings` CHANGE `api_key_live` `api_key_live` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_snipcart_settings` CHANGE `currency` `currency` varchar(4) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_snipcart_settings`;
OPTIMIZE TABLE `bx_snipcart_settings`;


-- TABLE: bx_snipcart_views_track

ALTER TABLE `bx_snipcart_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_snipcart_views_track`;
OPTIMIZE TABLE `bx_snipcart_views_track`;


-- TABLE: bx_snipcart_votes

ALTER TABLE `bx_snipcart_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_snipcart_votes`;
OPTIMIZE TABLE `bx_snipcart_votes`;


-- TABLE: bx_snipcart_votes_track

ALTER TABLE `bx_snipcart_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_snipcart_votes_track`;
OPTIMIZE TABLE `bx_snipcart_votes_track`;


-- FORMS
UPDATE `sys_objects_form` SET `submit_name`='a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}' WHERE `object`='bx_snipcart';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Nl2br`='0' WHERE `Name`='bx_snipcart';
