-- TABLE: bx_persons_cmts

ALTER TABLE `bx_persons_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_persons_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_persons_cmts`;
OPTIMIZE TABLE `bx_persons_cmts`;


-- TABLE: bx_persons_data

ALTER TABLE `bx_persons_data` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_persons_data` CHANGE `fullname` `fullname` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_data` CHANGE `description` `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_data` CHANGE `gender` `gender` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_data` CHANGE `long_text` `long_text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_persons_data`;
OPTIMIZE TABLE `bx_persons_data`;


-- TABLE: bx_persons_favorites_track

ALTER TABLE `bx_persons_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_persons_favorites_track`;
OPTIMIZE TABLE `bx_persons_favorites_track`;


-- TABLE: bx_persons_meta_keywords

ALTER TABLE `bx_persons_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_persons_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_persons_meta_keywords`;
OPTIMIZE TABLE `bx_persons_meta_keywords`;


-- TABLE: bx_persons_meta_locations

ALTER TABLE `bx_persons_meta_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_persons_meta_locations` CHANGE `country` `country` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_meta_locations` CHANGE `state` `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_meta_locations` CHANGE `city` `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_meta_locations` CHANGE `zip` `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_meta_locations` CHANGE `street` `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_meta_locations` CHANGE `street_number` `street_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_persons_meta_locations`;
OPTIMIZE TABLE `bx_persons_meta_locations`;


-- TABLE: bx_persons_meta_mentions

ALTER TABLE `bx_persons_meta_mentions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_persons_meta_mentions`;
OPTIMIZE TABLE `bx_persons_meta_mentions`;


-- TABLE: bx_persons_pictures

ALTER TABLE `bx_persons_pictures` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_persons_pictures` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_pictures` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_pictures` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_pictures` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_pictures` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_persons_pictures`;
OPTIMIZE TABLE `bx_persons_pictures`;


-- TABLE: bx_persons_pictures_resized

ALTER TABLE `bx_persons_pictures_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_persons_pictures_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_pictures_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_pictures_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_pictures_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_pictures_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_persons_pictures_resized`;
OPTIMIZE TABLE `bx_persons_pictures_resized`;


-- TABLE: bx_persons_reports

ALTER TABLE `bx_persons_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_persons_reports`;
OPTIMIZE TABLE `bx_persons_reports`;


-- TABLE: bx_persons_reports_track

ALTER TABLE `bx_persons_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_persons_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_persons_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_persons_reports_track`;
OPTIMIZE TABLE `bx_persons_reports_track`;


-- TABLE: bx_persons_views_track

ALTER TABLE `bx_persons_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_persons_views_track`;
OPTIMIZE TABLE `bx_persons_views_track`;


--TABLES
CREATE TABLE IF NOT EXISTS `bx_persons_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);


-- FORMS
UPDATE `sys_form_inputs` SET `html`='1', `db_pass`='XssHtml' WHERE `object`='bx_person' AND `name`='description';
