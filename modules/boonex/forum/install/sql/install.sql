SET @sName = 'bx_forum';

-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_forum_discussions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `multicat` text NOT NULL,
  `text` mediumtext NOT NULL,
  `text_comments` mediumtext NOT NULL,
  `lr_timestamp` int(11) NOT NULL,
  `lr_profile_id` int(11) NOT NULL,
  `lr_comment_id` int(11) NOT NULL,
  `labels` text NOT NULL,
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
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `stick` tinyint(4) NOT NULL DEFAULT '0',
  `lock` tinyint(4) NOT NULL DEFAULT '0',
  `resolvable` tinyint(4) NOT NULL DEFAULT '0',
  `resolved` tinyint(4) NOT NULL DEFAULT '0',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `cf` int(11) NOT NULL default '1',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`,`text_comments`),
  KEY `lr_timestamp` (`lr_timestamp`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_categories` (
  `category` int(11) NOT NULL default '0',
  `visible_for_levels` int(11) NOT NULL default '2147483647',
  `icon` text NOT NULL,
  PRIMARY KEY (`category`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_forum_covers` (
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

CREATE TABLE IF NOT EXISTS `bx_forum_files` (
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

CREATE TABLE IF NOT EXISTS `bx_forum_photos` (
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

CREATE TABLE IF NOT EXISTS `bx_forum_photos_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_forum_videos` (
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

CREATE TABLE IF NOT EXISTS `bx_forum_videos_resized` (
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

-- TABLES: LINKS
CREATE TABLE IF NOT EXISTS `bx_forum_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `media_id` int(11) NOT NULL DEFAULT '0',
  `url` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `added` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `profile_id` (`profile_id`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_links2content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `link_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `link` (`link_id`, `content_id`)
);

-- TABLE: subscribers
CREATE TABLE IF NOT EXISTS `bx_forum_subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
);

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_forum_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_forum_cmts_notes` (
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

-- TABLE: views
CREATE TABLE `bx_forum_views_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: votes
CREATE TABLE IF NOT EXISTS `bx_forum_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_reactions_track` (
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

-- TABLE: metas
CREATE TABLE `bx_forum_meta_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE `bx_forum_meta_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_forum_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_reports_track` (
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
CREATE TABLE `bx_forum_favorites_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `list_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`author_id`)
);

CREATE TABLE `bx_forum_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_forum_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_scores_track` (
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
CREATE TABLE IF NOT EXISTS `bx_forum_polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL default '0',
  `content_id` int(11) NOT NULL default '0',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  FULLTEXT KEY `search_fields` (`text`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_polls_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`),
  FULLTEXT KEY `title` (`title`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_polls_answers_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_forum_polls_answers_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_forum_covers', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_covers', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),

('bx_forum_photos', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_photos', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),
('bx_forum_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_photos_resized', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),

('bx_forum_videos', @sStorageEngine, 'a:1:{s:6:"fields";a:1:{s:10:"dimensions";s:17:"getFileDimensions";}}', 360, 2592000, 3, 'bx_forum_videos', 'allow-deny', '{video}', '', 0, 0, 0, 0, 0, 0),
('bx_forum_videos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_videos_resized', 'allow-deny', '{imagevideo}', '', 0, 0, 0, 0, 0, 0),

('bx_forum_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_files', 'deny-allow', '', '{dangerous}', 0, 0, 0, 0, 0, 0),

-- For Comments
('bx_forum_files_cmts', @sStorageEngine, '', 360, 2592000, 3, 'bx_forum_files', 'deny-allow', '', '{dangerous}', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_forum_preview', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_forum_gallery', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_forum_cover', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_covers";}', 'no', '1', '2592000', '0', '', ''),

('bx_forum_preview_photos', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_photos";}', 'no', '1', '2592000', '0', '', ''),
('bx_forum_gallery_photos', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_photos";}', 'no', '1', '2592000', '0', '', ''),
('bx_forum_view_photos', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_photos";}', 'no', '1', '2592000', '0', '', ''),

('bx_forum_videos_poster', 'bx_forum_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_forum_videos_poster_preview', 'bx_forum_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_forum_videos_mp4', 'bx_forum_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_forum_videos_mp4_hd', 'bx_forum_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_forum_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),

('bx_forum_preview_files', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_forum_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_forum_gallery_files', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_forum_files";}', 'no', '1', '2592000', '0', '', ''),

-- For Comments
('bx_forum_preview_cmts', 'bx_forum_photos_resized', 'Storage', 'a:1:{s:6:"object";s:19:"bx_forum_files_cmts";}', 'no', '1', '2592000', '0', '', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_forum_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_forum_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_forum_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),

('bx_forum_preview_photos', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_forum_gallery_photos', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),
('bx_forum_view_photos', 'Resize',  'a:2:{s:1:"w";s:4:"2000";s:1:"h";s:4:"2000";}', '0'),

('bx_forum_videos_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', 10),
('bx_forum_videos_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_forum_videos_poster', 'Poster', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"jpg";}', 0),
('bx_forum_videos_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"mp4";}', 0),
('bx_forum_videos_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0),

('bx_forum_preview_files', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_forum_gallery_files', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),

-- For Comments
('bx_forum_preview_cmts', 'Resize', 'a:4:{s:1:"w";s:3:"100";s:1:"h";s:3:"100";s:13:"square_resize";s:1:"1";s:10:"force_type";s:3:"jpg";}', '0');


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
(@sName, @sName, '_bx_forum_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_forum_discussions', 'id', '', '', 'do_submit', '', 0, 1, 'BxForumFormEntry', 'modules/boonex/forum/classes/BxForumFormEntry.php'),
('bx_forum_search', @sName, '_bx_forum_form_search', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_forum_discussions', 'id', '', '', 'do_submit', '', 0, 1, 'BxForumFormSearch', 'modules/boonex/forum/classes/BxForumFormSearch.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
(@sName, 'bx_forum_entry_add', @sName, 0, '_bx_forum_form_entry_display_add'),
(@sName, 'bx_forum_entry_delete', @sName, 0, '_bx_forum_form_entry_display_delete'),
(@sName, 'bx_forum_entry_edit', @sName, 0, '_bx_forum_form_entry_display_edit'),
(@sName, 'bx_forum_entry_view', @sName, 1, '_bx_forum_form_entry_display_view'),

('bx_forum_search', 'bx_forum_search_full', @sName, 0, '_bx_forum_form_search_display_full');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
(@sName, @sName, 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
(@sName, @sName, 'allow_view_to', '', '', 0, 'custom', '_bx_forum_form_entry_input_sys_allow_view_to', '_bx_forum_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'delete_confirm', 1, '', 0, 'checkbox', '_bx_forum_form_entry_input_sys_delete_confirm', '_bx_forum_form_entry_input_delete_confirm', '_bx_forum_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_delete_confirm_error', '', '', 1, 0),
(@sName, @sName, 'do_submit', '_bx_forum_form_entry_input_do_submit', '', 0, 'submit', '_bx_forum_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'text', '', '', 0, 'textarea', '_bx_forum_form_entry_input_sys_text', '_bx_forum_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_text_err', 'XssHtml', '', 1, 0),
(@sName, @sName, 'title', '', '', 0, 'text', '_bx_forum_form_entry_input_sys_title', '_bx_forum_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_forum_form_entry_input_title_err', 'Xss', '', 1, 0),
(@sName, @sName, 'cat', '', '#!bx_forum_cats', 0, 'select', '_bx_forum_form_entry_input_sys_cat', '_bx_forum_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_forum_form_entry_input_cat_err', 'Xss', '', 1, 0),
(@sName, @sName, 'multicat', '', '', 0, 'custom', '_bx_forum_form_entry_input_sys_multicat', '_bx_forum_form_entry_input_multicat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_forum_form_entry_input_multicat_err', 'Xss', '', 1, 0),
(@sName, @sName, 'attachments', '', '', 0, 'custom', '_bx_forum_form_entry_input_sys_attachments', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'covers', 'a:1:{i:0;s:14:"bx_forum_html5";}', 'a:2:{s:15:"bx_forum_simple";s:26:"_sys_uploader_simple_title";s:14:"bx_forum_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_forum_form_entry_input_sys_covers', '_bx_forum_form_entry_input_covers', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'pictures', 'a:1:{i:0;s:21:"bx_forum_photos_html5";}', 'a:2:{s:22:"bx_forum_photos_simple";s:26:"_sys_uploader_simple_title";s:21:"bx_forum_photos_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_forum_form_entry_input_sys_pictures', '_bx_forum_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'videos', 'a:2:{i:0;s:21:"bx_forum_videos_html5";i:1;s:28:"bx_forum_videos_record_video";}', 'a:3:{s:22:"bx_forum_videos_simple";s:26:"_sys_uploader_simple_title";s:21:"bx_forum_videos_html5";s:25:"_sys_uploader_html5_title";s:28:"bx_forum_videos_record_video";s:32:"_sys_uploader_record_video_title";}', 0, 'files', '_bx_forum_form_entry_input_sys_videos', '_bx_forum_form_entry_input_videos', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'link', '', '', 0, 'custom', '_bx_forum_form_post_input_sys_link', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),

(@sName, @sName, 'files', 'a:1:{i:0;s:20:"bx_forum_files_html5";}', 'a:2:{s:21:"bx_forum_files_simple";s:26:"_sys_uploader_simple_title";s:20:"bx_forum_files_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_forum_form_entry_input_sys_files', '_bx_forum_form_entry_input_files', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'polls', '', '', 0, 'custom', '_bx_forum_form_entry_input_sys_polls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'added', '', '', 0, 'datetime', '_bx_forum_form_entry_input_sys_date_added', '_bx_forum_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'changed', '', '', 0, 'datetime', '_bx_forum_form_entry_input_sys_date_changed', '_bx_forum_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),

('bx_forum_search', @sName, 'author', '', '', 0, 'custom', '_bx_forum_form_search_input_sys_author', '_bx_forum_form_search_input_author', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_forum_search', @sName, 'category', '', '#!bx_forum_cats', 0, 'select', '_bx_forum_form_search_input_sys_category', '_bx_forum_form_search_input_category', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_forum_search', @sName, 'keyword', '', '', 0, 'text', '_bx_forum_form_search_input_sys_keyword', '_bx_forum_form_search_input_keyword', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
(@sName, @sName, 'resolvable', '1', '', 0, 'switcher', '_bx_forum_form_entry_input_sys_resolvable', '_bx_forum_form_entry_input_resolvable', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_forum_search', @sName, 'do_submit', '_bx_forum_form_search_input_do_submit', '', 0, 'submit', '_bx_forum_form_search_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_forum_entry_add', 'title', 2147483647, 1, 1),
('bx_forum_entry_add', 'cat', 2147483647, 1, 2),
('bx_forum_entry_add', 'text', 2147483647, 1, 3),
('bx_forum_entry_add', 'attachments', 2147483647, 1, 4),
('bx_forum_entry_add', 'link', 2147483647, 1, 5),
('bx_forum_entry_add', 'pictures', 2147483647, 1, 6),
('bx_forum_entry_add', 'videos', 2147483647, 1, 7),
('bx_forum_entry_add', 'files', 2147483647, 1, 8),
('bx_forum_entry_add', 'polls', 2147483647, 1, 9),
('bx_forum_entry_add', 'covers', 2147483647, 1, 10),
('bx_forum_entry_add', 'allow_view_to', 2147483647, 1, 11),
('bx_forum_entry_add', 'cf', 2147483647, 1, 12),
('bx_forum_entry_add', 'resolvable', 2147483647, 1, 13),
('bx_forum_entry_add', 'do_submit', 2147483647, 1, 14),

('bx_forum_entry_edit', 'title', 2147483647, 1, 1),
('bx_forum_entry_edit', 'cat', 2147483647, 1, 2),
('bx_forum_entry_edit', 'text', 2147483647, 1, 3),
('bx_forum_entry_edit', 'attachments', 2147483647, 1, 4),
('bx_forum_entry_edit', 'link', 2147483647, 1, 5),
('bx_forum_entry_edit', 'pictures', 2147483647, 1, 6),
('bx_forum_entry_edit', 'videos', 2147483647, 1, 7),
('bx_forum_entry_edit', 'files', 2147483647, 1, 8),
('bx_forum_entry_edit', 'polls', 2147483647, 1, 9),
('bx_forum_entry_edit', 'covers', 2147483647, 1, 10),
('bx_forum_entry_edit', 'allow_view_to', 2147483647, 1, 11),
('bx_forum_entry_edit', 'cf', 2147483647, 1, 12),
('bx_forum_entry_edit', 'resolvable', 2147483647, 1, 13),
('bx_forum_entry_edit', 'do_submit', 2147483647, 1, 14),

('bx_forum_entry_view', 'title', 2147483647, 1, 1),
('bx_forum_entry_view', 'cat', 2147483647, 1, 2),
('bx_forum_entry_view', 'text', 2147483647, 1, 3),

('bx_forum_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_forum_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_forum_search_full', 'author', 2147483647, 1, 1),
('bx_forum_search_full', 'category', 2147483647, 1, 2),
('bx_forum_search_full', 'keyword', 2147483647, 1, 3),
('bx_forum_search_full', 'do_submit', 2147483647, 1, 4);

-- FORMS: poll
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_poll', 'bx_forum', '_bx_forum_form_poll', '', '', 'do_submit', 'bx_forum_polls', 'id', '', '', 'a:1:{s:14:"checker_helper";s:28:"BxForumFormPollCheckerHelper";}', 0, 1, 'BxForumFormPoll', 'modules/boonex/forum/classes/BxForumFormPoll.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_forum_poll_add', 'bx_forum', 'bx_forum_poll', '_bx_forum_form_poll_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_forum_poll', 'bx_forum', 'text', '', '', 0, 'text', '_bx_forum_form_poll_input_sys_text', '_bx_forum_form_poll_input_text', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_forum_form_poll_input_text_err', 'Xss', '', 1, 0),
('bx_forum_poll', 'bx_forum', 'answers', '', '', 0, 'custom', '_bx_forum_form_poll_input_sys_answers', '_bx_forum_form_poll_input_answers', '', 1, 0, 0, '', '', '', 'AvailAnswers', '', '_bx_forum_form_poll_input_answers_err', '', '', 1, 0),
('bx_forum_poll', 'bx_forum', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_forum_poll', 'bx_forum', 'do_submit', '_bx_forum_form_poll_input_do_submit', '', 0, 'submit', '_bx_forum_form_poll_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_forum_poll', 'bx_forum', 'do_cancel', '_bx_forum_form_poll_input_do_cancel', '', 0, 'button', '_bx_forum_form_poll_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_forum_poll_add', 'text', 2147483647, 1, 1),
('bx_forum_poll_add', 'answers', 2147483647, 1, 2),
('bx_forum_poll_add', 'controls', 2147483647, 1, 3),
('bx_forum_poll_add', 'do_submit', 2147483647, 1, 4),
('bx_forum_poll_add', 'do_cancel', 2147483647, 1, 5);


-- Forms -> Attach link
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_forum_attach_link', 'bx_forum', '_bx_forum_form_attach_link', '', '', 'do_submit', 'bx_forum_links', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_forum_attach_link_add', 'bx_forum', 'bx_forum_attach_link', '_bx_forum_form_attach_link_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_forum_attach_link', 'bx_forum', 'content_id', '0', '', 0, 'hidden', '_bx_forum_form_attach_link_input_sys_content_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_forum_attach_link', 'bx_forum', 'url', '', '', 0, 'text', '_bx_forum_form_attach_link_input_sys_url', '_bx_forum_form_attach_link_input_url', '', 0, 0, 0, '', '', '', 'Preg', 'a:1:{s:4:"preg";s:0:"";}', '_bx_forum_form_attach_link_input_url_err', '', '', 0, 0),
('bx_forum_attach_link', 'bx_forum', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_forum_attach_link', 'bx_forum', 'do_submit', '_bx_forum_form_attach_link_input_do_submit', '', 0, 'submit', '_bx_forum_form_attach_link_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_forum_attach_link', 'bx_forum', 'do_cancel', '_bx_forum_form_attach_link_input_do_cancel', '', 0, 'button', '_bx_forum_form_attach_link_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);


INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_forum_attach_link_add', 'content_id', 2147483647, 1, 1),
('bx_forum_attach_link_add', 'url', 2147483647, 1, 2),
('bx_forum_attach_link_add', 'controls', 2147483647, 1, 3),
('bx_forum_attach_link_add', 'do_submit', 2147483647, 1, 4),
('bx_forum_attach_link_add', 'do_cancel', 2147483647, 1, 5);

-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_forum_cats', '_bx_forum_pre_lists_cats', 'bx_forum', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_forum_cats', '', 0, '_sys_please_select', ''),
('bx_forum_cats', '1', 1, '_bx_forum_cat_General', '');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
(@sName, @sName, 'bx_forum_cmts', 1, 5000, 1000, 2, 50, 3, 'tail', 0, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-discussion&id={object_id}', '', 'bx_forum_discussions', 'id', 'author', 'title', 'comments', 'BxForumCmts', 'modules/boonex/forum/classes/BxForumCmts.php'),
('bx_forum_notes', @sName, 'bx_forum_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-discussion&id={object_id}', '', 'bx_forum_discussions', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');


-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
(@sName, 'bx_forum_views_track', '86400', '1', 'bx_forum_discussions', 'id', 'author', 'views', '', '');


-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
(@sName, 'bx_forum_votes', 'bx_forum_votes_track', '604800', '1', '1', '0', '1', 'bx_forum_discussions', 'id', 'author', 'rate', 'votes', '', ''),
('bx_forum_reactions', 'bx_forum_reactions', 'bx_forum_reactions_track', '604800', '1', '1', '1', '1', 'bx_forum_discussions', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', ''),
('bx_forum_poll_answers', 'bx_forum_polls_answers_votes', 'bx_forum_polls_answers_votes_track', '604800', '1', '1', '0', '1', 'bx_forum_polls_answers', 'id', 'author_id', 'rate', 'votes', 'BxForumVotePollAnswers', 'modules/boonex/forum/classes/BxForumVotePollAnswers.php');


-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
(@sName, @sName, 'bx_forum_scores', 'bx_forum_scores_track', '604800', '1', 'bx_forum_discussions', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- FAVORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `table_lists`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
(@sName, 'bx_forum_favorites_track', 'bx_forum_favorites_lists', '1', '1', '1', 'page.php?i=view-discussion&id={object_id}', 'bx_forum_discussions', 'id', 'author', 'favorites', '', '');


-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
(@sName, @sName, '1', '1', 'page.php?i=view-discussion&id={object_id}', 'bx_forum_discussions', 'id', 'author', 'featured', '', '');


-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
(@sName, '_bx_forum', @sName, 'added', 'edited', 'deleted', '', ''),
('bx_forum_cmts', '_bx_forum_cmts', @sName, 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
(@sName, @sName, 'id', '', 'a:1:{s:4:"sort";a:2:{s:5:"stick";s:4:"desc";s:12:"lr_timestamp";s:4:"desc";}}'),
(@sName, 'bx_forum_administration', 'id', '', ''),
(@sName, 'bx_forum_common', 'id', '', '');


-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_forum', 'bx_forum', 'bx_forum', '_bx_forum_search_extended', 1, '', ''),
('bx_forum_cmts', 'bx_forum_cmts', 'bx_forum', '_bx_forum_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_forum', 'bx_forum', 'bx_forum_reports', 'bx_forum_reports_track', '1', 'page.php?i=view-discussion&id={object_id}', 'bx_forum_notes', 'bx_forum_discussions', 'id', 'author', 'reports', '', '');


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, @sName, '_bx_forum', '_bx_forum', 'bx_forum@modules/boonex/forum/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, @sName, 'content', '{url_studio}module.php?name=bx_forum', '', 'bx_forum@modules/boonex/forum/|std-icon.svg', '_bx_forum', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
