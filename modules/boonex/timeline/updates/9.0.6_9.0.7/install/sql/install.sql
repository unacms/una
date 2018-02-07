SET @sName = 'bx_timeline';


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_timeline_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: bx_timeline_comments

ALTER TABLE `bx_timeline_comments` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_comments` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_comments`;
OPTIMIZE TABLE `bx_timeline_comments`;


-- TABLE: bx_timeline_events

ALTER TABLE `bx_timeline_events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_events` CHANGE `type` `type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_events` CHANGE `action` `action` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_events` CHANGE `object_id` `object_id` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_events` CHANGE `content` `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_events` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_events` CHANGE `description` `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_events`;
OPTIMIZE TABLE `bx_timeline_events`;


-- TABLE: bx_timeline_handlers

ALTER TABLE `bx_timeline_handlers` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_handlers` CHANGE `group` `group` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_handlers` CHANGE `alert_unit` `alert_unit` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_handlers` CHANGE `alert_action` `alert_action` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_handlers` CHANGE `content` `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_handlers` CHANGE `privacy` `privacy` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_handlers`;
OPTIMIZE TABLE `bx_timeline_handlers`;


-- TABLE: bx_timeline_links

ALTER TABLE `bx_timeline_links` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_links` CHANGE `url` `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_links` CHANGE `title` `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_links` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_links`;
OPTIMIZE TABLE `bx_timeline_links`;


-- TABLE: bx_timeline_links2events

ALTER TABLE `bx_timeline_links2events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_timeline_links2events`;
OPTIMIZE TABLE `bx_timeline_links2events`;


-- TABLE: bx_timeline_meta_keywords

ALTER TABLE `bx_timeline_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_meta_keywords`;
OPTIMIZE TABLE `bx_timeline_meta_keywords`;


-- TABLE: bx_timeline_meta_locations

ALTER TABLE `bx_timeline_meta_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_meta_locations` CHANGE `country` `country` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_meta_locations` CHANGE `state` `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_meta_locations` CHANGE `city` `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_meta_locations` CHANGE `zip` `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_meta_locations` CHANGE `street` `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_meta_locations` CHANGE `street_number` `street_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_meta_locations`;
OPTIMIZE TABLE `bx_timeline_meta_locations`;


-- TABLE: bx_timeline_photos

ALTER TABLE `bx_timeline_photos` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_photos` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_photos` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_photos` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_photos` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_photos` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_photos`;
OPTIMIZE TABLE `bx_timeline_photos`;


-- TABLE: bx_timeline_photos2events

ALTER TABLE `bx_timeline_photos2events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_timeline_photos2events`;
OPTIMIZE TABLE `bx_timeline_photos2events`;


-- TABLE: bx_timeline_photos_processed

ALTER TABLE `bx_timeline_photos_processed` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_photos_processed` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_photos_processed` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_photos_processed` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_photos_processed` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_photos_processed` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_photos_processed`;
OPTIMIZE TABLE `bx_timeline_photos_processed`;


-- TABLE: bx_timeline_reports

ALTER TABLE `bx_timeline_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_timeline_reports`;
OPTIMIZE TABLE `bx_timeline_reports`;


-- TABLE: bx_timeline_reports_track

ALTER TABLE `bx_timeline_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_reports_track`;
OPTIMIZE TABLE `bx_timeline_reports_track`;


-- TABLE: bx_timeline_reposts_track

ALTER TABLE `bx_timeline_reposts_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_timeline_reposts_track`;
OPTIMIZE TABLE `bx_timeline_reposts_track`;


-- TABLE: bx_timeline_videos

ALTER TABLE `bx_timeline_videos` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_videos` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_videos` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_videos` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_videos` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_videos` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_videos`;
OPTIMIZE TABLE `bx_timeline_videos`;


-- TABLE: bx_timeline_videos2events

ALTER TABLE `bx_timeline_videos2events` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_timeline_videos2events`;
OPTIMIZE TABLE `bx_timeline_videos2events`;


-- TABLE: bx_timeline_videos_processed

ALTER TABLE `bx_timeline_videos_processed` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_timeline_videos_processed` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_videos_processed` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_videos_processed` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_videos_processed` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_timeline_videos_processed` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_timeline_videos_processed`;
OPTIMIZE TABLE `bx_timeline_videos_processed`;


-- TABLE: bx_timeline_views_track

ALTER TABLE `bx_timeline_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_timeline_views_track`;
OPTIMIZE TABLE `bx_timeline_views_track`;


-- TABLE: bx_timeline_votes

ALTER TABLE `bx_timeline_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_timeline_votes`;
OPTIMIZE TABLE `bx_timeline_votes`;


-- TABLE: bx_timeline_votes_track

ALTER TABLE `bx_timeline_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_timeline_votes_track`;
OPTIMIZE TABLE `bx_timeline_votes_track`;


-- FORMS
UPDATE `sys_form_inputs` SET `html`='3', `db_pass`='XssHtml' WHERE `object`='bx_timeline_post' AND `name`='text';
