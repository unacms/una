-- TABLE: bx_albums_albums

ALTER TABLE `bx_albums_albums` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_albums` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_albums` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_albums`;
OPTIMIZE TABLE `bx_albums_albums`;


-- TABLE: bx_albums_cmts

ALTER TABLE `bx_albums_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_cmts`;
OPTIMIZE TABLE `bx_albums_cmts`;


-- TABLE: bx_albums_cmts_media

ALTER TABLE `bx_albums_cmts_media` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_cmts_media` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_cmts_media`;
OPTIMIZE TABLE `bx_albums_cmts_media`;


-- TABLE: bx_albums_favorites_media_track

ALTER TABLE `bx_albums_favorites_media_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_favorites_media_track`;
OPTIMIZE TABLE `bx_albums_favorites_media_track`;


-- TABLE: bx_albums_favorites_track

ALTER TABLE `bx_albums_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_favorites_track`;
OPTIMIZE TABLE `bx_albums_favorites_track`;


-- TABLE: bx_albums_files

ALTER TABLE `bx_albums_files` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_files` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_files` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_files` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_files` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_files` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_files`;
OPTIMIZE TABLE `bx_albums_files`;


-- TABLE: bx_albums_files2albums

ALTER TABLE `bx_albums_files2albums` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_files2albums` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_files2albums` CHANGE `data` `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_files2albums` CHANGE `exif` `exif` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_files2albums`;
OPTIMIZE TABLE `bx_albums_files2albums`;


-- TABLE: bx_albums_meta_keywords

ALTER TABLE `bx_albums_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_meta_keywords`;
OPTIMIZE TABLE `bx_albums_meta_keywords`;


-- TABLE: bx_albums_meta_keywords_media

ALTER TABLE `bx_albums_meta_keywords_media` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_meta_keywords_media` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_meta_keywords_media`;
OPTIMIZE TABLE `bx_albums_meta_keywords_media`;


-- TABLE: bx_albums_meta_keywords_media_camera

ALTER TABLE `bx_albums_meta_keywords_media_camera` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_meta_keywords_media_camera` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_meta_keywords_media_camera`;
OPTIMIZE TABLE `bx_albums_meta_keywords_media_camera`;


-- TABLE: bx_albums_meta_locations

ALTER TABLE `bx_albums_meta_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_meta_locations` CHANGE `country` `country` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_meta_locations` CHANGE `state` `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_meta_locations` CHANGE `city` `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_meta_locations` CHANGE `zip` `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_meta_locations` CHANGE `street` `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_meta_locations` CHANGE `street_number` `street_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_meta_locations`;
OPTIMIZE TABLE `bx_albums_meta_locations`;


-- TABLE: bx_albums_meta_mentions

ALTER TABLE `bx_albums_meta_mentions` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_meta_mentions`;
OPTIMIZE TABLE `bx_albums_meta_mentions`;


-- TABLE: bx_albums_photos_resized

ALTER TABLE `bx_albums_photos_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_photos_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_photos_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_photos_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_photos_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_photos_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_photos_resized`;
OPTIMIZE TABLE `bx_albums_photos_resized`;


-- TABLE: bx_albums_reports

ALTER TABLE `bx_albums_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_reports`;
OPTIMIZE TABLE `bx_albums_reports`;


-- TABLE: bx_albums_reports_track

ALTER TABLE `bx_albums_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_albums_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_albums_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_albums_reports_track`;
OPTIMIZE TABLE `bx_albums_reports_track`;


-- TABLE: bx_albums_views_media_track

ALTER TABLE `bx_albums_views_media_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_views_media_track`;
OPTIMIZE TABLE `bx_albums_views_media_track`;


-- TABLE: bx_albums_views_track

ALTER TABLE `bx_albums_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_views_track`;
OPTIMIZE TABLE `bx_albums_views_track`;


-- TABLE: bx_albums_votes

ALTER TABLE `bx_albums_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_votes`;
OPTIMIZE TABLE `bx_albums_votes`;


-- TABLE: bx_albums_votes_media

ALTER TABLE `bx_albums_votes_media` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_votes_media`;
OPTIMIZE TABLE `bx_albums_votes_media`;


-- TABLE: bx_albums_votes_media_track

ALTER TABLE `bx_albums_votes_media_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_votes_media_track`;
OPTIMIZE TABLE `bx_albums_votes_media_track`;


-- TABLE: bx_albums_votes_track

ALTER TABLE `bx_albums_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_albums_votes_track`;
OPTIMIZE TABLE `bx_albums_votes_track`;


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_albums_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);


-- COMMENTS
UPDATE `sys_objects_cmts` SET `Nl2br`='0' WHERE `Name` IN ('bx_albums', 'bx_albums_media');
