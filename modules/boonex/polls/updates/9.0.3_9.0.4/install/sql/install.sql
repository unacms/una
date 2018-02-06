-- TABLE: bx_polls_cmts

ALTER TABLE `bx_polls_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_polls_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_polls_cmts`;
OPTIMIZE TABLE `bx_polls_cmts`;


-- TABLE: bx_polls_entries

ALTER TABLE `bx_polls_entries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_polls_entries` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_polls_entries`;
OPTIMIZE TABLE `bx_polls_entries`;


-- TABLE: bx_polls_favorites_track

ALTER TABLE `bx_polls_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_polls_favorites_track`;
OPTIMIZE TABLE `bx_polls_favorites_track`;


-- TABLE: bx_polls_files

ALTER TABLE `bx_polls_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_polls_files` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_files` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_files` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_files` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_files` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_polls_files`;
OPTIMIZE TABLE `bx_polls_files`;


-- TABLE: bx_polls_meta_keywords

ALTER TABLE `bx_polls_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_polls_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_polls_meta_keywords`;
OPTIMIZE TABLE `bx_polls_meta_keywords`;


-- TABLE: bx_polls_meta_locations

ALTER TABLE `bx_polls_meta_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_polls_meta_locations` CHANGE `country` `country` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_meta_locations` CHANGE `state` `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_meta_locations` CHANGE `city` `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_meta_locations` CHANGE `zip` `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_meta_locations` CHANGE `street` `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_meta_locations` CHANGE `street_number` `street_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_polls_meta_locations`;
OPTIMIZE TABLE `bx_polls_meta_locations`;


-- TABLE: bx_polls_photos_resized

ALTER TABLE `bx_polls_photos_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_polls_photos_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_photos_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_photos_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_photos_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_photos_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_polls_photos_resized`;
OPTIMIZE TABLE `bx_polls_photos_resized`;


-- TABLE: bx_polls_reports

ALTER TABLE `bx_polls_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_polls_reports`;
OPTIMIZE TABLE `bx_polls_reports`;


-- TABLE: bx_polls_reports_track

ALTER TABLE `bx_polls_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_polls_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_polls_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_polls_reports_track`;
OPTIMIZE TABLE `bx_polls_reports_track`;


-- TABLE: bx_polls_subentries

ALTER TABLE `bx_polls_subentries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_polls_subentries` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_polls_subentries`;
OPTIMIZE TABLE `bx_polls_subentries`;


-- TABLE: bx_polls_views_track

ALTER TABLE `bx_polls_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_polls_views_track`;
OPTIMIZE TABLE `bx_polls_views_track`;


-- TABLE: bx_polls_votes

ALTER TABLE `bx_polls_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_polls_votes`;
OPTIMIZE TABLE `bx_polls_votes`;


-- TABLE: bx_polls_votes_subentries

ALTER TABLE `bx_polls_votes_subentries` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_polls_votes_subentries`;
OPTIMIZE TABLE `bx_polls_votes_subentries`;


-- TABLE: bx_polls_votes_subentries_track

ALTER TABLE `bx_polls_votes_subentries_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_polls_votes_subentries_track`;
OPTIMIZE TABLE `bx_polls_votes_subentries_track`;


-- TABLE: bx_polls_votes_track

ALTER TABLE `bx_polls_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_polls_votes_track`;
OPTIMIZE TABLE `bx_polls_votes_track`;


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_polls_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);


-- FORMS
UPDATE `sys_objects_form` SET `submit_name`='a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}' WHERE `object`='bx_polls';


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Nl2br`='0' WHERE `Name`='bx_polls';
