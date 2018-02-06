-- TABLE: bx_market_cmts

ALTER TABLE `bx_market_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_cmts`;
OPTIMIZE TABLE `bx_market_cmts`;


-- TABLE: bx_market_downloads_track

ALTER TABLE `bx_market_downloads_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_market_downloads_track`;
OPTIMIZE TABLE `bx_market_downloads_track`;


-- TABLE: bx_market_favorites_track

ALTER TABLE `bx_market_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_market_favorites_track`;
OPTIMIZE TABLE `bx_market_favorites_track`;


-- TABLE: bx_market_files

ALTER TABLE `bx_market_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_files` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_files` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_files` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_files` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_files` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_files`;
OPTIMIZE TABLE `bx_market_files`;


-- TABLE: bx_market_files2products

ALTER TABLE `bx_market_files2products` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_files2products` CHANGE `version` `version` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_files2products` CHANGE `version_to` `version_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_files2products`;
OPTIMIZE TABLE `bx_market_files2products`;


-- TABLE: bx_market_licenses

ALTER TABLE `bx_market_licenses` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_licenses` CHANGE `order` `order` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_licenses` CHANGE `license` `license` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_licenses` CHANGE `type` `type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_licenses` CHANGE `domain` `domain` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_licenses`;
OPTIMIZE TABLE `bx_market_licenses`;


-- TABLE: bx_market_licenses_deleted

ALTER TABLE `bx_market_licenses_deleted` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_licenses_deleted` CHANGE `order` `order` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_licenses_deleted` CHANGE `license` `license` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_licenses_deleted` CHANGE `type` `type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_licenses_deleted` CHANGE `domain` `domain` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_licenses_deleted` CHANGE `reason` `reason` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_licenses_deleted`;
OPTIMIZE TABLE `bx_market_licenses_deleted`;


-- TABLE: bx_market_meta_keywords

ALTER TABLE `bx_market_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_meta_keywords`;
OPTIMIZE TABLE `bx_market_meta_keywords`;


-- TABLE: bx_market_meta_locations

ALTER TABLE `bx_market_meta_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_meta_locations` CHANGE `country` `country` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_meta_locations` CHANGE `state` `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_meta_locations` CHANGE `city` `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_meta_locations` CHANGE `zip` `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_meta_locations`;
OPTIMIZE TABLE `bx_market_meta_locations`;


-- TABLE: bx_market_meta_mentions

ALTER TABLE `bx_market_meta_mentions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_market_meta_mentions`;
OPTIMIZE TABLE `bx_market_meta_mentions`;


-- TABLE: bx_market_photos

ALTER TABLE `bx_market_photos` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_photos` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_photos` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_photos` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_photos` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_photos` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_photos`;
OPTIMIZE TABLE `bx_market_photos`;


-- TABLE: bx_market_photos2products

ALTER TABLE `bx_market_photos2products` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_photos2products` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_photos2products`;
OPTIMIZE TABLE `bx_market_photos2products`;


-- TABLE: bx_market_photos_resized

ALTER TABLE `bx_market_photos_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_photos_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_photos_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_photos_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_photos_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_photos_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_photos_resized`;
OPTIMIZE TABLE `bx_market_photos_resized`;


-- TABLE: bx_market_products

ALTER TABLE `bx_market_products` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_products` CHANGE `name` `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `notes` `notes` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `duration_recurring` `duration_recurring` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `allow_view_to` `allow_view_to` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `allow_purchase_to` `allow_purchase_to` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `allow_comment_to` `allow_comment_to` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `allow_vote_to` `allow_vote_to` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_products` CHANGE `sex` `sex` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_products`;
OPTIMIZE TABLE `bx_market_products`;


-- TABLE: bx_market_reports

ALTER TABLE `bx_market_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_market_reports`;
OPTIMIZE TABLE `bx_market_reports`;


-- TABLE: bx_market_reports_track

ALTER TABLE `bx_market_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_market_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_market_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_market_reports_track`;
OPTIMIZE TABLE `bx_market_reports_track`;


-- TABLE: bx_market_subproducts

ALTER TABLE `bx_market_subproducts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_market_subproducts`;
OPTIMIZE TABLE `bx_market_subproducts`;


-- TABLE: bx_market_views_track

ALTER TABLE `bx_market_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_market_views_track`;
OPTIMIZE TABLE `bx_market_views_track`;


-- TABLE: bx_market_votes

ALTER TABLE `bx_market_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_market_votes`;
OPTIMIZE TABLE `bx_market_votes`;


-- TABLE: bx_market_votes_track

ALTER TABLE `bx_market_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_market_votes_track`;
OPTIMIZE TABLE `bx_market_votes_track`;


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_market_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Nl2br`='0' WHERE `Name`='bx_market';
