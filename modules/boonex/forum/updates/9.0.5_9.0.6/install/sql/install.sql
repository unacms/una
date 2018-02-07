SET @sName = 'bx_forum';


--TABLES
CREATE TABLE IF NOT EXISTS `bx_forum_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: bx_forum_categories

ALTER TABLE `bx_forum_categories` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_forum_categories`;
OPTIMIZE TABLE `bx_forum_categories`;


-- TABLE: bx_forum_cmts

ALTER TABLE `bx_forum_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_forum_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_forum_cmts`;
OPTIMIZE TABLE `bx_forum_cmts`;


-- TABLE: bx_forum_discussions

ALTER TABLE `bx_forum_discussions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_forum_discussions` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_discussions` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_discussions` CHANGE `text_comments` `text_comments` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_forum_discussions`;
OPTIMIZE TABLE `bx_forum_discussions`;


-- TABLE: bx_forum_favorites_track

ALTER TABLE `bx_forum_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_forum_favorites_track`;
OPTIMIZE TABLE `bx_forum_favorites_track`;


-- TABLE: bx_forum_files

ALTER TABLE `bx_forum_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_forum_files` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_files` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_files` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_files` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_files` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_forum_files`;
OPTIMIZE TABLE `bx_forum_files`;


-- TABLE: bx_forum_meta_keywords

ALTER TABLE `bx_forum_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_forum_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_forum_meta_keywords`;
OPTIMIZE TABLE `bx_forum_meta_keywords`;


-- TABLE: bx_forum_photos_resized

ALTER TABLE `bx_forum_photos_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_forum_photos_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_photos_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_photos_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_photos_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_photos_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_forum_photos_resized`;
OPTIMIZE TABLE `bx_forum_photos_resized`;


-- TABLE: bx_forum_reports

ALTER TABLE `bx_forum_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_forum_reports`;
OPTIMIZE TABLE `bx_forum_reports`;


-- TABLE: bx_forum_reports_track

ALTER TABLE `bx_forum_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_forum_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_forum_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_forum_reports_track`;
OPTIMIZE TABLE `bx_forum_reports_track`;


-- TABLE: bx_forum_subscribers

ALTER TABLE `bx_forum_subscribers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_forum_subscribers`;
OPTIMIZE TABLE `bx_forum_subscribers`;


-- TABLE: bx_forum_views_track

ALTER TABLE `bx_forum_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_forum_views_track`;
OPTIMIZE TABLE `bx_forum_views_track`;


-- TABLE: bx_forum_votes

ALTER TABLE `bx_forum_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_forum_votes`;
OPTIMIZE TABLE `bx_forum_votes`;


-- TABLE: bx_forum_votes_track

ALTER TABLE `bx_forum_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_forum_votes_track`;
OPTIMIZE TABLE `bx_forum_votes_track`;
