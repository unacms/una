-- TABLE: bx_groups_admins

ALTER TABLE `bx_groups_admins` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_groups_admins`;
OPTIMIZE TABLE `bx_groups_admins`;


-- TABLE: bx_groups_cmts

ALTER TABLE `bx_groups_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_groups_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_groups_cmts`;
OPTIMIZE TABLE `bx_groups_cmts`;


-- TABLE: bx_groups_data

ALTER TABLE `bx_groups_data` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_groups_data` CHANGE `group_name` `group_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_data` CHANGE `group_desc` `group_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_data` CHANGE `allow_view_to` `allow_view_to` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_groups_data`;
OPTIMIZE TABLE `bx_groups_data`;


-- TABLE: bx_groups_fans

ALTER TABLE `bx_groups_fans` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_groups_fans`;
OPTIMIZE TABLE `bx_groups_fans`;


-- TABLE: bx_groups_favorites_track

ALTER TABLE `bx_groups_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_groups_favorites_track`;
OPTIMIZE TABLE `bx_groups_favorites_track`;


-- TABLE: bx_groups_meta_keywords

ALTER TABLE `bx_groups_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_groups_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_groups_meta_keywords`;
OPTIMIZE TABLE `bx_groups_meta_keywords`;


-- TABLE: bx_groups_pics

ALTER TABLE `bx_groups_pics` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_groups_pics` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_pics` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_pics` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_pics` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_pics` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_groups_pics`;
OPTIMIZE TABLE `bx_groups_pics`;


-- TABLE: bx_groups_pics_resized

ALTER TABLE `bx_groups_pics_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_groups_pics_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_pics_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_pics_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_pics_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_pics_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_groups_pics_resized`;
OPTIMIZE TABLE `bx_groups_pics_resized`;


-- TABLE: bx_groups_reports

ALTER TABLE `bx_groups_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_groups_reports`;
OPTIMIZE TABLE `bx_groups_reports`;


-- TABLE: bx_groups_reports_track

ALTER TABLE `bx_groups_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_groups_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_groups_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_groups_reports_track`;
OPTIMIZE TABLE `bx_groups_reports_track`;


-- TABLE: bx_groups_views_track

ALTER TABLE `bx_groups_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_groups_views_track`;
OPTIMIZE TABLE `bx_groups_views_track`;


-- TABLE: bx_groups_votes

ALTER TABLE `bx_groups_votes` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_groups_votes`;
OPTIMIZE TABLE `bx_groups_votes`;


-- TABLE: bx_groups_votes_track

ALTER TABLE `bx_groups_votes_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_groups_votes_track`;
OPTIMIZE TABLE `bx_groups_votes_track`;


-- TABLES
CREATE TABLE IF NOT EXISTS `bx_groups_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);
