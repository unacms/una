-- TABLES
CREATE TABLE IF NOT EXISTS `bx_organizations_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: bx_organizations_admins

ALTER TABLE `bx_organizations_admins` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_organizations_admins`;
OPTIMIZE TABLE `bx_organizations_admins`;


-- TABLE: bx_organizations_cmts

ALTER TABLE `bx_organizations_cmts` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_organizations_cmts` CHANGE `cmt_text` `cmt_text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_organizations_cmts`;
OPTIMIZE TABLE `bx_organizations_cmts`;


-- TABLE: bx_organizations_data

ALTER TABLE `bx_organizations_data` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_organizations_data` CHANGE `org_name` `org_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_data` CHANGE `org_desc` `org_desc` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_data` CHANGE `allow_view_to` `allow_view_to` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_organizations_data`;
OPTIMIZE TABLE `bx_organizations_data`;


-- TABLE: bx_organizations_fans

ALTER TABLE `bx_organizations_fans` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_organizations_fans`;
OPTIMIZE TABLE `bx_organizations_fans`;


-- TABLE: bx_organizations_favorites_track

ALTER TABLE `bx_organizations_favorites_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_organizations_favorites_track`;
OPTIMIZE TABLE `bx_organizations_favorites_track`;


-- TABLE: bx_organizations_meta_keywords

ALTER TABLE `bx_organizations_meta_keywords` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_organizations_meta_keywords` CHANGE `keyword` `keyword` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_organizations_meta_keywords`;
OPTIMIZE TABLE `bx_organizations_meta_keywords`;


-- TABLE: bx_organizations_meta_locations

ALTER TABLE `bx_organizations_meta_locations` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_organizations_meta_locations` CHANGE `country` `country` varchar(2) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_meta_locations` CHANGE `state` `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_meta_locations` CHANGE `city` `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_meta_locations` CHANGE `zip` `zip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_meta_locations` CHANGE `street` `street` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_meta_locations` CHANGE `street_number` `street_number` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_organizations_meta_locations`;
OPTIMIZE TABLE `bx_organizations_meta_locations`;


-- TABLE: bx_organizations_pics

ALTER TABLE `bx_organizations_pics` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_organizations_pics` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_pics` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_pics` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_pics` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_pics` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_organizations_pics`;
OPTIMIZE TABLE `bx_organizations_pics`;


-- TABLE: bx_organizations_pics_resized

ALTER TABLE `bx_organizations_pics_resized` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_organizations_pics_resized` CHANGE `remote_id` `remote_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_pics_resized` CHANGE `path` `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_pics_resized` CHANGE `file_name` `file_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_pics_resized` CHANGE `mime_type` `mime_type` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_pics_resized` CHANGE `ext` `ext` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_organizations_pics_resized`;
OPTIMIZE TABLE `bx_organizations_pics_resized`;


-- TABLE: bx_organizations_reports

ALTER TABLE `bx_organizations_reports` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_organizations_reports`;
OPTIMIZE TABLE `bx_organizations_reports`;


-- TABLE: bx_organizations_reports_track

ALTER TABLE `bx_organizations_reports_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

ALTER TABLE `bx_organizations_reports_track` CHANGE `type` `type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `bx_organizations_reports_track` CHANGE `text` `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

REPAIR TABLE `bx_organizations_reports_track`;
OPTIMIZE TABLE `bx_organizations_reports_track`;


-- TABLE: bx_organizations_views_track

ALTER TABLE `bx_organizations_views_track` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;


REPAIR TABLE `bx_organizations_views_track`;
OPTIMIZE TABLE `bx_organizations_views_track`;


-- FORMS
UPDATE `sys_form_inputs` SET `html`='1', `db_pass`='XssHtml' WHERE `object`='bx_organization' AND `name`='org_desc';
