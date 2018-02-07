-- TABLES
CREATE TABLE IF NOT EXISTS `bx_shopify_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: bx_shopify_cmts

ALTER TABLE `bx_shopify_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_shopify_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_shopify_cmts`;
OPTIMIZE TABLE `bx_shopify_cmts`;


-- TABLE: bx_shopify_entries

ALTER TABLE `bx_shopify_entries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_shopify_entries` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_shopify_entries` CHANGE `code` `code` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_shopify_entries`;
OPTIMIZE TABLE `bx_shopify_entries`;


-- TABLE: bx_shopify_favorites_track

ALTER TABLE `bx_shopify_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_shopify_favorites_track`;
OPTIMIZE TABLE `bx_shopify_favorites_track`;


-- TABLE: bx_shopify_meta_keywords

ALTER TABLE `bx_shopify_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_shopify_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_shopify_meta_keywords`;
OPTIMIZE TABLE `bx_shopify_meta_keywords`;


-- TABLE: bx_shopify_meta_locations

ALTER TABLE `bx_shopify_meta_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_shopify_meta_locations` CHANGE `country` `country` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_shopify_meta_locations` CHANGE `state` `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_shopify_meta_locations` CHANGE `city` `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_shopify_meta_locations` CHANGE `zip` `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_shopify_meta_locations` CHANGE `street` `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_shopify_meta_locations` CHANGE `street_number` `street_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_shopify_meta_locations`;
OPTIMIZE TABLE `bx_shopify_meta_locations`;


-- TABLE: bx_shopify_reports

ALTER TABLE `bx_shopify_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_shopify_reports`;
OPTIMIZE TABLE `bx_shopify_reports`;


-- TABLE: bx_shopify_reports_track

ALTER TABLE `bx_shopify_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_shopify_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_shopify_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_shopify_reports_track`;
OPTIMIZE TABLE `bx_shopify_reports_track`;


-- TABLE: bx_shopify_settings

ALTER TABLE `bx_shopify_settings` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_shopify_settings` CHANGE `api_key` `api_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_shopify_settings` CHANGE `domain` `domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_shopify_settings`;
OPTIMIZE TABLE `bx_shopify_settings`;


-- TABLE: bx_shopify_views_track

ALTER TABLE `bx_shopify_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_shopify_views_track`;
OPTIMIZE TABLE `bx_shopify_views_track`;


-- TABLE: bx_shopify_votes

ALTER TABLE `bx_shopify_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_shopify_votes`;
OPTIMIZE TABLE `bx_shopify_votes`;


-- TABLE: bx_shopify_votes_track

ALTER TABLE `bx_shopify_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_shopify_votes_track`;
OPTIMIZE TABLE `bx_shopify_votes_track`;


-- FORMS
UPDATE `sys_objects_form` SET `submit_name`='a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}' WHERE `object`='bx_shopify';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Nl2br`='0' WHERE `Name`='bx_shopify';
