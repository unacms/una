-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_ads_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `sold` int(11) NOT NULL,
  `shipped` int(11) NOT NULL,
  `received` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` float NOT NULL,
  `auction` tinyint(4) NOT NULL DEFAULT '0',
  `quantity` int(11) NOT NULL default '1',
  `single` tinyint(4) NOT NULL DEFAULT '1',
  `year` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
  `notes_purchased` text NOT NULL,
  `labels` text NOT NULL,
  `location` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `rrate` float NOT NULL default '0',
  `rvotes` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `sc_up` int(11) NOT NULL default '0',
  `sc_down` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reviews` int(11) NOT NULL default '0',
  `reviews_avg` float NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `cf` int(11) NOT NULL default '1',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `status` enum('active','awaiting','offer','sold','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: types of categories
CREATE TABLE IF NOT EXISTS `bx_ads_categories_types` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `display_add` varchar(255) NOT NULL DEFAULT '',
  `display_edit` varchar(255) NOT NULL DEFAULT '',
  `display_view` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
);

INSERT INTO `bx_ads_categories_types` (`id`, `name`, `title`, `display_add`, `display_edit`, `display_view`) VALUES
(1, 'price', '_bx_ads_cat_type_price', 'bx_ads_entry_price_add', 'bx_ads_entry_price_edit', 'bx_ads_entry_price_view'),
(2, 'price_year', '_bx_ads_cat_type_price_year', 'bx_ads_entry_price_year_add', 'bx_ads_entry_price_year_edit', 'bx_ads_entry_price_year_view');

-- TABLE: categories
CREATE TABLE IF NOT EXISTS `bx_ads_categories` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `level` tinyint(11) unsigned NOT NULL DEFAULT '0',
  `type` int(11) NOT NULL DEFAULT '0',
  `name` varchar(64) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `text` text NOT NULL,
  `icon` varchar(255) NOT NULL DEFAULT '',
  `items` int(11) NOT NULL DEFAULT '0',
  `active` tinyint(4) NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(0, 0, 1, 'job', '_bx_ads_cat_title_job', '', 'user-md', 1, 1);
SET @iParentId = LAST_INSERT_ID();

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(@iParentId, 1, 1, 'job_finance', '_bx_ads_cat_title_accounting_finance', '', '', 1, 1),
(@iParentId, 1, 1, 'job_education', '_bx_ads_cat_title_education_nonprofit', '', '', 1, 2),
(@iParentId, 1, 1, 'job_legal', '_bx_ads_cat_title_government_legal', '', '', 1, 3),
(@iParentId, 1, 1, 'job_programming', '_bx_ads_cat_title_programming_web_design', '', '', 1, 4);

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(0, 0, 2, 'music', '_bx_ads_cat_title_music', '', 'music', 1, 2);
SET @iParentId = LAST_INSERT_ID();

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(@iParentId, 1, 2, 'music_isale', '_bx_ads_cat_title_instrument_sale', '', '', 1, 1),
(@iParentId, 1, 2, 'music_iwanted', '_bx_ads_cat_title_instrument_wanted', '', '', 1, 2);

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(0, 0, 1, 'housing', '_bx_ads_cat_title_housing', '', 'home', 1, 3);
SET @iParentId = LAST_INSERT_ID();

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(@iParentId, 1, 1, 'housing_apartments', '_bx_ads_cat_title_apartments_housing', '', '', 1, 1),
(@iParentId, 1, 1, 'housing_office', '_bx_ads_cat_title_office_commercial', '', '', 1, 2),
(@iParentId, 1, 1, 'housing_re_sale', '_bx_ads_cat_title_real_estate_sale', '', '', 1, 3),
(@iParentId, 1, 1, 'housing_roommate', '_bx_ads_cat_title_roommate', '', '', 1, 4),
(@iParentId, 1, 1, 'housing_temp_rental', '_bx_ads_cat_title_temporary_rental', '', '', 1, 5);

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(0, 0, 1, 'service', '_bx_ads_cat_title_service', '', 'wrench', 1, 4);
SET @iParentId = LAST_INSERT_ID();

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(@iParentId, 1, 1, 'service_automotive', '_bx_ads_cat_title_automotive', '', '', 1, 1),
(@iParentId, 1, 1, 'service_educational', '_bx_ads_cat_title_educational', '', '', 1, 2),
(@iParentId, 1, 1, 'service_financial', '_bx_ads_cat_title_financial', '', '', 1, 3),
(@iParentId, 1, 1, 'service_labor', '_bx_ads_cat_title_labor_move', '', '', 1, 4),
(@iParentId, 1, 1, 'service_legal', '_bx_ads_cat_title_legal', '', '', 1, 5);

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(0, 0, 1, 'casting', '_bx_ads_cat_title_casting', '', 'eye', 1, 5);
SET @iParentId = LAST_INSERT_ID();

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(@iParentId, 1, 1, 'casting_acting', '_bx_ads_cat_title_acting', '', '', 1, 1),
(@iParentId, 1, 1, 'casting_dance', '_bx_ads_cat_title_dance', '', '', 1, 2),
(@iParentId, 1, 1, 'casting_modeling', '_bx_ads_cat_title_modeling', '', '', 1, 3),
(@iParentId, 1, 1, 'casting_musician', '_bx_ads_cat_title_musician', '', '', 1, 4),
(@iParentId, 1, 1, 'casting_rshow', '_bx_ads_cat_title_reality_show', '', '', 1, 5);

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(0, 0, 2, 'personal', '_bx_ads_cat_title_personal', '', 'user', 1, 6);
SET @iParentId = LAST_INSERT_ID();

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(@iParentId, 1, 2, 'personal_mw', '_bx_ads_cat_title_men_women', '', '', 1, 1),
(@iParentId, 1, 2, 'personal_wm', '_bx_ads_cat_title_women_men', '', '', 1, 2),
(@iParentId, 1, 2, 'personal_missed', '_bx_ads_cat_title_missed_connection', '', '', 1, 3);

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(0, 0, 2, 'sale', '_bx_ads_cat_title_sale', '', 'shopping-cart', 1, 7);
SET @iParentId = LAST_INSERT_ID();

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(@iParentId, 1, 2, 'sale_barter', '_bx_ads_cat_title_barter', '', '', 1, 1),
(@iParentId, 1, 2, 'sale_clothing', '_bx_ads_cat_title_clothing', '', '', 1, 1),
(@iParentId, 1, 2, 'sale_collectible', '_bx_ads_cat_title_collectible', '', '', 1, 1);

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(0, 0, 2, 'sale_car', '_bx_ads_cat_title_sale_car', '', 'truck', 1, 8);
SET @iParentId = LAST_INSERT_ID();

INSERT INTO `bx_ads_categories` (`parent_id`, `level`, `type`, `name`, `title`, `text`, `icon`, `active`, `order`) VALUES 
(@iParentId, 1, 2, 'sale_car_part', '_bx_ads_cat_title_auto_part', '', '', 1, 1),
(@iParentId, 1, 2, 'sale_car_auto', '_bx_ads_cat_title_auto_truck', '', '', 1, 2),
(@iParentId, 1, 2, 'sale_car_motorcycle', '_bx_ads_cat_title_motorcycle', '', '', 1, 3);

CREATE TABLE IF NOT EXISTS `bx_ads_interested_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `interested` (`entry_id`, `profile_id`)
);

-- TABLE: licenses
CREATE TABLE IF NOT EXISTS `bx_ads_licenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `entry_id` int(11) unsigned NOT NULL default '0',
  `count` int(11) unsigned NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `new` tinyint(1) NOT NULL default '1',
  PRIMARY KEY (`id`),
  KEY `product_id` (`entry_id`, `profile_id`),
  KEY `license` (`license`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_licenses_deleted` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(11) unsigned NOT NULL default '0',
  `entry_id` int(11) unsigned NOT NULL default '0',
  `count` int(11) unsigned NOT NULL default '0',
  `order` varchar(32) NOT NULL default '',
  `license` varchar(32) NOT NULL default '',
  `added` int(11) unsigned NOT NULL default '0',
  `new` tinyint(1) NOT NULL default '1',
  `reason` varchar(16) NOT NULL default '',
  `deleted` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`entry_id`,`profile_id`),
  KEY `license` (`license`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_ads_covers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_photos_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_videos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `dimensions` varchar(12) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_videos_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` bigint(20) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_ads_cmts` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  `cmt_cf` int(11) NOT NULL default '1',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_cmts_notes` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

-- TABLE: reviews
CREATE TABLE IF NOT EXISTS `bx_ads_reviews` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(11) NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  `cmt_pinned` int(11) NOT NULL default '0',
  `cmt_cf` int(11) NOT NULL default '1',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

-- TABLE: votes
CREATE TABLE IF NOT EXISTS `bx_ads_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),    
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_reactions_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

-- TABLE: views
CREATE TABLE IF NOT EXISTS `bx_ads_views_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`), 
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE IF NOT EXISTS `bx_ads_meta_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,  
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),  
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_meta_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),  
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_meta_locations` (
  `object_id` int(10) unsigned NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `street` varchar(255) NOT NULL,
  `street_number` varchar(255) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `country_state_city` (`country`,`state`(8),`city`(8))
);

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_ads_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_reports_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `text` text NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  `checked_by` int(11) NOT NULL default '0',
  `status` tinyint(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `report` (`object_id`, `author_nip`)
);

-- TABLES: favorites
CREATE TABLE IF NOT EXISTS `bx_ads_favorites_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `list_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
   PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`author_id`)
);

CREATE TABLE `bx_ads_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_ads_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

-- TABLE: polls
CREATE TABLE IF NOT EXISTS `bx_ads_polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL default '0',
  `content_id` int(11) NOT NULL default '0',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search_fields` (`text`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_polls_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_polls_answers_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_ads_polls_answers_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

-- TABLE: offers
CREATE TABLE IF NOT EXISTS `bx_ads_offers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  `changed` int(11) NOT NULL default '0',
  `amount` float NOT NULL default '0',
  `quantity` int(11) NOT NULL default '0',
  `message` text NOT NULL,
  `status` enum('accepted','awaiting','declined','canceled','paid') NOT NULL DEFAULT 'awaiting',
  PRIMARY KEY (`id`)
);


-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_ads_covers', @sStorageEngine, '', 360, 2592000, 3, 'bx_ads_covers', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),

('bx_ads_photos', @sStorageEngine, '', 360, 2592000, 3, 'bx_ads_photos', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),
('bx_ads_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_ads_photos_resized', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),

('bx_ads_videos', @sStorageEngine, '', 360, 2592000, 3, 'bx_ads_videos', 'allow-deny', '{video}', '', 0, 0, 0, 0, 0, 0),
('bx_ads_videos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_ads_videos_resized', 'allow-deny', '{imagevideo}', '', 0, 0, 0, 0, 0, 0),

('bx_ads_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_ads_files', 'deny-allow', '', '{dangerous}', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_preview', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_ads_gallery', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_ads_cover', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_covers";}', 'no', '1', '2592000', '0', '', ''),

('bx_ads_preview_photos', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_photos";}', 'no', '1', '2592000', '0', '', ''),
('bx_ads_gallery_photos', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_photos";}', 'no', '1', '2592000', '0', '', ''),
('bx_ads_view_photos', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_photos";}', 'no', '1', '2592000', '0', '', ''),

('bx_ads_videos_poster', 'bx_ads_videos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_ads_videos_poster_preview', 'bx_ads_videos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_ads_videos_mp4', 'bx_ads_videos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_ads_videos_mp4_hd', 'bx_ads_videos_resized', 'Storage', 'a:1:{s:6:"object";s:13:"bx_ads_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),

('bx_ads_preview_files', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:12:"bx_ads_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_ads_gallery_files', 'bx_ads_photos_resized', 'Storage', 'a:1:{s:6:"object";s:12:"bx_ads_files";}', 'no', '1', '2592000', '0', '', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_ads_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_ads_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_ads_cover', 'Resize', 'a:1:{s:1:"w";s:4:"1200";}', '0'),

('bx_ads_preview_photos', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_ads_gallery_photos', 'Resize', 'a:4:{s:1:"w";s:3:"600";s:1:"h";s:3:"600";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0'),
('bx_ads_view_photos', 'Resize',  'a:2:{s:1:"w";s:4:"1200";s:1:"h";s:4:"1200";}', '0'),

('bx_ads_videos_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', 10),
('bx_ads_videos_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_ads_videos_poster', 'Poster', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"jpg";}', 0),
('bx_ads_videos_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"mp4";}', 0),
('bx_ads_videos_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0),

('bx_ads_preview_files', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_ads_gallery_files', 'Resize', 'a:4:{s:1:"w";s:3:"600";s:1:"h";s:3:"600";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- FORMS: category
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads_category', 'bx_ads', '_bx_ads_form_category', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_ads_categories', 'id', '', '', 'a:1:{i:0;s:9:"do_submit";}', '', 0, 1, 'BxAdsFormCategory', 'modules/boonex/ads/classes/BxAdsFormCategory.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_ads_category', 'bx_ads_category_add', 'bx_ads', 0, '_bx_ads_form_category_display_add'),
('bx_ads_category', 'bx_ads_category_delete', 'bx_ads', 0, '_bx_ads_form_category_display_delete'),
('bx_ads_category', 'bx_ads_category_edit', 'bx_ads', 0, '_bx_ads_form_category_display_edit');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_ads_category', 'bx_ads', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_ads_form_category_input_sys_delete_confirm', '_bx_ads_form_category_input_delete_confirm', '_bx_ads_form_category_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_category_input_delete_confirm_error', '', '', 1, 0),
('bx_ads_category', 'bx_ads', 'parent_id', '', '', 0, 'select', '_bx_ads_form_category_input_sys_parent_id', '_bx_ads_form_category_input_parent_id', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_ads_category', 'bx_ads', 'title', '', '', 0, 'text_translatable', '_bx_ads_form_category_input_sys_title', '_bx_ads_form_category_input_title', '', 1, 0, 0, '', '', '', 'AvailTranslatable', 'a:1:{i:0;s:5:"title";}', '_bx_ads_form_category_input_title_err', 'Xss', '', 1, 0),
('bx_ads_category', 'bx_ads', 'text', '', '', 0, 'textarea_translatable', '_bx_ads_form_category_input_sys_text', '_bx_ads_form_category_input_text', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_ads_category', 'bx_ads', 'type', '', '', 0, 'select', '_bx_ads_form_category_input_sys_type', '_bx_ads_form_category_input_type', '_bx_ads_form_category_input_type_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_category_input_type_err', 'Int', '', 0, 0),
('bx_ads_category', 'bx_ads', 'type_clone', 'on', '', 0, 'switcher', '_bx_ads_form_category_input_sys_type_clone', '_bx_ads_form_category_input_type_clone', '', 0, 0, 0, 'a:1:{s:8:"onchange";s:33:"oBxAdsStudio.onChangeClone(this);";}', '', '', '', '', '', '', '', 0, 0),
('bx_ads_category', 'bx_ads', 'type_title', '', '', 0, 'text_translatable', '_bx_ads_form_category_input_sys_type_title', '_bx_ads_form_category_input_type_title', '', 0, 0, 0, 'a:1:{s:8:"disabled";s:8:"disabled";}', 'a:1:{s:5:"style";s:13:"display:none;";}', '', '', '', '', '', '', 0, 0),
('bx_ads_category', 'bx_ads', 'icon', '', '', 0, 'text', '_bx_ads_form_category_input_sys_icon', '_bx_ads_form_category_input_icon', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_ads_category', 'bx_ads', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_ads_category', 'bx_ads', 'do_submit', '_bx_ads_form_category_input_do_submit', '', 0, 'submit', '_bx_ads_form_category_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads_category', 'bx_ads', 'do_cancel', '_bx_ads_form_category_input_do_cancel', '', 0, 'button', '_bx_ads_form_category_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_category_add', 'parent_id', 2147483647, 1, 1),
('bx_ads_category_add', 'type', 2147483647, 1, 2),
('bx_ads_category_add', 'type_clone', 2147483647, 1, 3),
('bx_ads_category_add', 'type_title', 2147483647, 1, 4),
('bx_ads_category_add', 'title', 2147483647, 1, 5),
('bx_ads_category_add', 'text', 2147483647, 1, 6),
('bx_ads_category_add', 'icon', 2147483647, 1, 7),
('bx_ads_category_add', 'controls', 2147483647, 1, 8),
('bx_ads_category_add', 'do_submit', 2147483647, 1, 9),
('bx_ads_category_add', 'do_cancel', 2147483647, 1, 10),

('bx_ads_category_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_ads_category_delete', 'do_submit', 2147483647, 1, 2),

('bx_ads_category_edit', 'parent_id', 2147483647, 1, 1),
('bx_ads_category_edit', 'title', 2147483647, 1, 2),
('bx_ads_category_edit', 'text', 2147483647, 1, 3),
('bx_ads_category_edit', 'icon', 2147483647, 1, 4),
('bx_ads_category_edit', 'controls', 2147483647, 1, 5),
('bx_ads_category_edit', 'do_submit', 2147483647, 1, 6),
('bx_ads_category_edit', 'do_cancel', 2147483647, 1, 7);


-- FORMS: entry (ad)
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_ads', 'bx_ads', '_bx_ads_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_ads_entries', 'id', '', '', 'a:1:{i:0;s:9:"do_submit";}', '', 0, 1, 'BxAdsFormEntry', 'modules/boonex/ads/classes/BxAdsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_ads', 'bx_ads_entry_add', 'bx_ads', 0, '_bx_ads_form_entry_display_add'),
('bx_ads', 'bx_ads_entry_delete', 'bx_ads', 0, '_bx_ads_form_entry_display_delete'),

('bx_ads', 'bx_ads_entry_price_add', 'bx_ads', 0, '_bx_ads_form_entry_price_display_add'),
('bx_ads', 'bx_ads_entry_price_edit', 'bx_ads', 0, '_bx_ads_form_entry_price_display_edit'),
('bx_ads', 'bx_ads_entry_price_view', 'bx_ads', 1, '_bx_ads_form_entry_price_display_view'),

('bx_ads', 'bx_ads_entry_price_year_add', 'bx_ads', 0, '_bx_ads_form_entry_price_year_display_add'),
('bx_ads', 'bx_ads_entry_price_year_edit', 'bx_ads', 0, '_bx_ads_form_entry_price_year_display_edit'),
('bx_ads', 'bx_ads_entry_price_year_view', 'bx_ads', 1, '_bx_ads_form_entry_price_year_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_ads', 'bx_ads', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_ads', 'bx_ads', 'allow_view_to', '', '', 0, 'custom', '_bx_ads_form_entry_input_sys_allow_view_to', '_bx_ads_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_ads_form_entry_input_sys_delete_confirm', '_bx_ads_form_entry_input_delete_confirm', '_bx_ads_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_ads', 'bx_ads', 'do_submit', '_bx_ads_form_entry_input_do_submit', '', 0, 'submit', '_bx_ads_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'covers', 'a:1:{i:0;s:12:"bx_ads_html5";}', 'a:1:{s:12:"bx_ads_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_ads_form_entry_input_sys_covers', '_bx_ads_form_entry_input_covers', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'pictures', 'a:1:{i:0;s:19:"bx_ads_photos_html5";}', 'a:1:{s:19:"bx_ads_photos_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_ads_form_entry_input_sys_pictures', '_bx_ads_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'videos', 'a:2:{i:0;s:19:"bx_ads_videos_html5";i:1;s:26:"bx_ads_videos_record_video";}', 'a:2:{s:19:"bx_ads_videos_html5";s:25:"_sys_uploader_html5_title";s:26:"bx_ads_videos_record_video";s:32:"_sys_uploader_record_video_title";}', 0, 'files', '_bx_ads_form_entry_input_sys_videos', '_bx_ads_form_entry_input_videos', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'files', 'a:1:{i:0;s:18:"bx_ads_files_html5";}', 'a:1:{s:18:"bx_ads_files_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_ads_form_entry_input_sys_files', '_bx_ads_form_entry_input_files', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'polls', '', '', 0, 'custom', '_bx_ads_form_entry_input_sys_polls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'text', '', '', 0, 'textarea', '_bx_ads_form_entry_input_sys_text', '_bx_ads_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_ads_form_entry_input_text_err', 'XssHtml', '', 1, 0),
('bx_ads', 'bx_ads', 'title', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_title', '_bx_ads_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'name', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_name', '_bx_ads_form_entry_input_name', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_entry_input_name_err', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'price', '', '', 0, 'price', '_bx_ads_form_entry_input_sys_price', '_bx_ads_form_entry_input_price', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'auction', 1, '', 0, 'checkbox', '_bx_ads_form_entry_input_sys_auction', '_bx_ads_form_entry_input_auction', '_bx_ads_form_entry_input_auction_info', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_ads', 'bx_ads', 'quantity', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_quantity', '_bx_ads_form_entry_input_quantity', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_entry_input_quantity_err', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'year', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_year', '_bx_ads_form_entry_input_year', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_ads', 'bx_ads', 'category', '', '', 0, 'hidden', '_bx_ads_form_entry_input_sys_category', '', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 0, 0),
('bx_ads', 'bx_ads', 'category_view', '', '', 0, 'text', '_bx_ads_form_entry_input_sys_category_view', '_bx_ads_form_entry_input_category_view', '', 0, 0, 0, 'a:1:{s:8:"disabled";s:8:"disabled";}', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'category_select', '', '', 0, 'select', '_bx_ads_form_entry_input_sys_category_select', '_bx_ads_form_entry_input_category_select', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_entry_input_category_select_err', 'Int', '', 0, 0),
('bx_ads', 'bx_ads', 'notes_purchased', '', '', 0, 'textarea', '_bx_ads_form_entry_input_sys_notes_purchased', '_bx_ads_form_entry_input_notes_purchased', '_bx_ads_form_entry_input_notes_purchased_inf', 0, 0, 3, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_ads', 'bx_ads', 'added', '', '', 0, 'datetime', '_bx_ads_form_entry_input_sys_date_added', '_bx_ads_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'changed', '', '', 0, 'datetime', '_bx_ads_form_entry_input_sys_date_changed', '_bx_ads_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'attachments', '', '', 0, 'custom', '_bx_ads_form_entry_input_sys_attachments', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_ads', 'bx_ads', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_ads_entry_add', 'category_select', 2147483647, 1, 1),
('bx_ads_entry_add', 'do_submit', 2147483647, 1, 2),

('bx_ads_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_ads_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_ads_entry_price_add', 'category', 2147483647, 1, 1),
('bx_ads_entry_price_add', 'category_view', 2147483647, 1, 2),
('bx_ads_entry_price_add', 'title', 2147483647, 1, 3),
('bx_ads_entry_price_add', 'name', 2147483647, 1, 4),
('bx_ads_entry_price_add', 'price', 2147483647, 1, 5),
('bx_ads_entry_price_add', 'auction', 2147483647, 1, 6),
('bx_ads_entry_price_add', 'quantity', 2147483647, 1, 7),
('bx_ads_entry_price_add', 'text', 2147483647, 1, 8),
('bx_ads_entry_price_add', 'attachments', 2147483647, 1, 9),
('bx_ads_entry_price_add', 'pictures', 2147483647, 1, 10),
('bx_ads_entry_price_add', 'videos', 2147483647, 1, 11),
('bx_ads_entry_price_add', 'files', 2147483647, 1, 12),
('bx_ads_entry_price_add', 'polls', 2147483647, 1, 13),
('bx_ads_entry_price_add', 'covers', 2147483647, 1, 14),
('bx_ads_entry_price_add', 'allow_view_to', 2147483647, 1, 15),
('bx_ads_entry_price_add', 'cf', 2147483647, 1, 16),
('bx_ads_entry_price_add', 'notes_purchased', 2147483647, 1, 17),
('bx_ads_entry_price_add', 'location', 2147483647, 1, 18),
('bx_ads_entry_price_add', 'do_submit', 2147483647, 1, 19),

('bx_ads_entry_price_edit', 'category_view', 2147483647, 1, 1),
('bx_ads_entry_price_edit', 'title', 2147483647, 1, 2),
('bx_ads_entry_price_edit', 'name', 2147483647, 1, 3),
('bx_ads_entry_price_edit', 'price', 2147483647, 1, 4),
('bx_ads_entry_price_edit', 'auction', 2147483647, 1, 5),
('bx_ads_entry_price_edit', 'quantity', 2147483647, 1, 6),
('bx_ads_entry_price_edit', 'text', 2147483647, 1, 7),
('bx_ads_entry_price_edit', 'attachments', 2147483647, 1, 8),
('bx_ads_entry_price_edit', 'pictures', 2147483647, 1, 9),
('bx_ads_entry_price_edit', 'videos', 2147483647, 1, 10),
('bx_ads_entry_price_edit', 'files', 2147483647, 1, 11),
('bx_ads_entry_price_edit', 'polls', 2147483647, 1, 12),
('bx_ads_entry_price_edit', 'covers', 2147483647, 1, 13),
('bx_ads_entry_price_edit', 'allow_view_to', 2147483647, 1, 14),
('bx_ads_entry_price_edit', 'cf', 2147483647, 1, 15),
('bx_ads_entry_price_edit', 'notes_purchased', 2147483647, 1, 16),
('bx_ads_entry_price_edit', 'location', 2147483647, 1, 17),
('bx_ads_entry_price_edit', 'do_submit', 2147483647, 1, 18),

('bx_ads_entry_price_view', 'category_view', 2147483647, 1, 1),
('bx_ads_entry_price_view', 'price', 2147483647, 1, 2),
('bx_ads_entry_price_view', 'added', 2147483647, 1, 3),
('bx_ads_entry_price_view', 'changed', 2147483647, 1, 4),

('bx_ads_entry_price_year_add', 'category', 2147483647, 1, 1),
('bx_ads_entry_price_year_add', 'category_view', 2147483647, 1, 2),
('bx_ads_entry_price_year_add', 'title', 2147483647, 1, 3),
('bx_ads_entry_price_year_add', 'name', 2147483647, 1, 4),
('bx_ads_entry_price_year_add', 'price', 2147483647, 1, 5),
('bx_ads_entry_price_year_add', 'auction', 2147483647, 1, 6),
('bx_ads_entry_price_year_add', 'quantity', 2147483647, 1, 7),
('bx_ads_entry_price_year_add', 'year', 2147483647, 1, 8),
('bx_ads_entry_price_year_add', 'text', 2147483647, 1, 9),
('bx_ads_entry_price_year_add', 'attachments', 2147483647, 1, 10),
('bx_ads_entry_price_year_add', 'pictures', 2147483647, 1, 11),
('bx_ads_entry_price_year_add', 'videos', 2147483647, 1, 12),
('bx_ads_entry_price_year_add', 'files', 2147483647, 1, 13),
('bx_ads_entry_price_year_add', 'polls', 2147483647, 1, 14),
('bx_ads_entry_price_year_add', 'covers', 2147483647, 1, 15),
('bx_ads_entry_price_year_add', 'allow_view_to', 2147483647, 1, 16),
('bx_ads_entry_price_year_add', 'cf', 2147483647, 1, 17),
('bx_ads_entry_price_year_add', 'notes_purchased', 2147483647, 1, 18),
('bx_ads_entry_price_year_add', 'location', 2147483647, 1, 19),
('bx_ads_entry_price_year_add', 'do_submit', 2147483647, 1, 20),

('bx_ads_entry_price_year_edit', 'category_view', 2147483647, 1, 1),
('bx_ads_entry_price_year_edit', 'title', 2147483647, 1, 2),
('bx_ads_entry_price_year_edit', 'name', 2147483647, 1, 3),
('bx_ads_entry_price_year_edit', 'price', 2147483647, 1, 4),
('bx_ads_entry_price_year_edit', 'auction', 2147483647, 1, 5),
('bx_ads_entry_price_year_edit', 'quantity', 2147483647, 1, 6),
('bx_ads_entry_price_year_edit', 'year', 2147483647, 1, 7),
('bx_ads_entry_price_year_edit', 'text', 2147483647, 1, 8),
('bx_ads_entry_price_year_edit', 'attachments', 2147483647, 1, 9),
('bx_ads_entry_price_year_edit', 'pictures', 2147483647, 1, 10),
('bx_ads_entry_price_year_edit', 'videos', 2147483647, 1, 11),
('bx_ads_entry_price_year_edit', 'files', 2147483647, 1, 12),
('bx_ads_entry_price_year_edit', 'polls', 2147483647, 1, 13),
('bx_ads_entry_price_year_edit', 'covers', 2147483647, 1, 14),
('bx_ads_entry_price_year_edit', 'allow_view_to', 2147483647, 1, 15),
('bx_ads_entry_price_year_edit', 'cf', 2147483647, 1, 16),
('bx_ads_entry_price_year_edit', 'notes_purchased', 2147483647, 1, 17),
('bx_ads_entry_price_year_edit', 'location', 2147483647, 1, 18),
('bx_ads_entry_price_year_edit', 'do_submit', 2147483647, 1, 19),

('bx_ads_entry_price_year_view', 'category_view', 2147483647, 1, 1),
('bx_ads_entry_price_year_view', 'price', 2147483647, 1, 2),
('bx_ads_entry_price_year_view', 'quantity', 2147483647, 1, 3),
('bx_ads_entry_price_year_view', 'year', 2147483647, 1, 4),
('bx_ads_entry_price_year_view', 'added', 2147483647, 1, 5),
('bx_ads_entry_price_year_view', 'changed', 2147483647, 1, 6);

-- FORMS: poll
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_ads_poll', 'bx_ads', '_bx_ads_form_poll', '', '', 'do_submit', 'bx_ads_polls', 'id', '', '', 'a:1:{s:14:"checker_helper";s:26:"BxAdsFormPollCheckerHelper";}', 0, 1, 'BxAdsFormPoll', 'modules/boonex/ads/classes/BxAdsFormPoll.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_ads_poll_add', 'bx_ads', 'bx_ads_poll', '_bx_ads_form_poll_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_ads_poll', 'bx_ads', 'text', '', '', 0, 'text', '_bx_ads_form_poll_input_sys_text', '_bx_ads_form_poll_input_text', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_poll_input_text_err', 'Xss', '', 1, 0),
('bx_ads_poll', 'bx_ads', 'answers', '', '', 0, 'custom', '_bx_ads_form_poll_input_sys_answers', '_bx_ads_form_poll_input_answers', '', 1, 0, 0, '', '', '', 'AvailAnswers', '', '_bx_ads_form_poll_input_answers_err', '', '', 1, 0),
('bx_ads_poll', 'bx_ads', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_ads_poll', 'bx_ads', 'do_submit', '_bx_ads_form_poll_input_do_submit', '', 0, 'submit', '_bx_ads_form_poll_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_ads_poll', 'bx_ads', 'do_cancel', '_bx_ads_form_poll_input_do_cancel', '', 0, 'button', '_bx_ads_form_poll_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_ads_poll_add', 'text', 2147483647, 1, 1),
('bx_ads_poll_add', 'answers', 2147483647, 1, 2),
('bx_ads_poll_add', 'controls', 2147483647, 1, 3),
('bx_ads_poll_add', 'do_submit', 2147483647, 1, 4),
('bx_ads_poll_add', 'do_cancel', 2147483647, 1, 5);

-- FORMS: offer
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_ads_offer', 'bx_ads', '_bx_ads_form_offer', '', '', 'do_submit', 'bx_ads_offers', 'id', '', '', '', 0, 1, 'BxAdsFormOffer', 'modules/boonex/ads/classes/BxAdsFormOffer.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_ads_offer_add', 'bx_ads', 'bx_ads_offer', '_bx_ads_form_offer_display_add', 0),
('bx_ads_offer_view', 'bx_ads', 'bx_ads_offer', '_bx_ads_form_offer_display_view', 1);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_ads_offer', 'bx_ads', 'amount', '', '', 0, 'text', '_bx_ads_form_offer_input_sys_amount', '_bx_ads_form_offer_input_amount', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_offer_input_amount_err', 'Xss', '', 1, 0),
('bx_ads_offer', 'bx_ads', 'quantity', '1', '', 0, 'text', '_bx_ads_form_offer_input_sys_quantity', '_bx_ads_form_offer_input_quantity', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_ads_form_offer_input_quantity_err', 'Xss', '', 1, 0),
('bx_ads_offer', 'bx_ads', 'total', '', '', 0, 'text', '_bx_ads_form_offer_input_sys_total', '_bx_ads_form_offer_input_total', '', 0, 0, 0, 'a:1:{s:8:"readonly";s:8:"readonly";}', '', '', '', '', '', '', '', 1, 0),
('bx_ads_offer', 'bx_ads', 'message', '', '', 0, 'textarea', '_bx_ads_form_offer_input_sys_message', '_bx_ads_form_offer_input_message', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_ads_offer', 'bx_ads', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_ads_offer', 'bx_ads', 'do_submit', '_bx_ads_form_offer_input_do_submit', '', 0, 'submit', '_bx_ads_form_offer_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_ads_offer', 'bx_ads', 'do_cancel', '_bx_ads_form_offer_input_do_cancel', '', 0, 'button', '_bx_ads_form_offer_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_ads_offer_add', 'amount', 2147483647, 1, 1),
('bx_ads_offer_add', 'quantity', 2147483647, 1, 2),
('bx_ads_offer_add', 'total', 2147483647, 1, 3),
('bx_ads_offer_add', 'message', 2147483647, 1, 4),
('bx_ads_offer_add', 'controls', 2147483647, 1, 5),
('bx_ads_offer_add', 'do_submit', 2147483647, 1, 6),
('bx_ads_offer_add', 'do_cancel', 2147483647, 1, 7),

('bx_ads_offer_view', 'amount', 2147483647, 1, 1),
('bx_ads_offer_view', 'quantity', 2147483647, 1, 2),
('bx_ads_offer_view', 'total', 2147483647, 1, 3),
('bx_ads_offer_view', 'message', 2147483647, 1, 4);


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_ads', 'bx_ads', 'bx_ads_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-ad&id={object_id}', '', 'bx_ads_entries', 'id', 'author', 'title', 'comments', '', ''),
('bx_ads_notes', 'bx_ads', 'bx_ads_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-ad&id={object_id}', '', 'bx_ads_entries', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', ''),
('bx_ads_reviews', 'bx_ads', 'bx_ads_reviews', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 0, 1, -3, 1, 'cmt', 'page.php?i=view-ad&id={object_id}', '', 'bx_ads_entries', 'id', 'author', 'title', 'reviews', 'BxTemplCmtsReviews', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `Module`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_ads', 'bx_ads', 'bx_ads_votes', 'bx_ads_votes_track', '604800', '1', '1', '0', '1', 'bx_ads_entries', 'id', 'author', 'rate', 'votes', '', ''),
('bx_ads_reactions', 'bx_ads', 'bx_ads_reactions', 'bx_ads_reactions_track', '604800', '1', '1', '1', '1', 'bx_ads_entries', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', ''),
('bx_ads_poll_answers', 'bx_ads', 'bx_ads_polls_answers_votes', 'bx_ads_polls_answers_votes_track', '604800', '1', '1', '0', '1', 'bx_ads_polls_answers', 'id', 'author_id', 'rate', 'votes', 'BxAdsVotePollAnswers', 'modules/boonex/ads/classes/BxAdsVotePollAnswers.php');

-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_ads', 'bx_ads', 'bx_ads_scores', 'bx_ads_scores_track', '604800', '0', 'bx_ads_entries', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_ads', 'bx_ads', 'bx_ads_reports', 'bx_ads_reports_track', '1', 'page.php?i=view-ad&id={object_id}', 'bx_ads_notes', 'bx_ads_entries', 'id', 'author', 'reports', '', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `module`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_ads', 'bx_ads', 'bx_ads_views_track', '86400', '1', 'bx_ads_entries', 'id', 'author', 'views', '', '');

-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `table_lists`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_ads', 'bx_ads_favorites_track', 'bx_ads_favorites_lists', '1', '1', '1', 'page.php?i=view-ad&id={object_id}', 'bx_ads_entries', 'id', 'author', 'favorites', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_ads', 'bx_ads', '1', '1', 'page.php?i=view-ad&id={object_id}', 'bx_ads_entries', 'id', 'author', 'featured', '', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_ads', '_bx_ads', 'bx_ads', 'added', 'edited', 'deleted', '', ''),
('bx_ads_cmts', '_bx_ads_cmts', 'bx_ads', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', ''),
('bx_ads_reviews', '_bx_ads_reviews', 'bx_ads_reviews', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_ads', 'bx_ads_administration', 'id', '', ''),
('bx_ads', 'bx_ads_common', 'id', '', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_ads', 'bx_ads', 'bx_ads', '_bx_ads_search_extended', 1, '', ''),
('bx_ads_cmts', 'bx_ads_cmts', 'bx_ads', '_bx_ads_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', ''),
('bx_ads_reviews', 'bx_ads_reviews', 'bx_ads', '_bx_ads_search_extended_reviews', 1, 'BxTemplSearchExtendedCmts', '');

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_ads', '_bx_ads', '_bx_ads', 'bx_ads@modules/boonex/ads/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_ads', 'content', '{url_studio}module.php?name=bx_ads', '', 'bx_ads@modules/boonex/ads/|std-icon.svg', '_bx_ads', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
