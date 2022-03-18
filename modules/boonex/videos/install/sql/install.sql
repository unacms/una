-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_videos_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL default '0',
  `added` int(11) NOT NULL default '0',
  `changed` int(11) NOT NULL default '0',
  `thumb` int(11) NOT NULL default '0',
  `poster` int(11) NOT NULL default '0',
  `video_source` enum('upload', 'embed') NOT NULL DEFAULT 'upload',
  `video` int(11) NOT NULL default '0',
  `video_embed` TEXT,
  `video_embed_data` TEXT,
  `title` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `multicat` text NOT NULL,
  `text` text NOT NULL,
  `duration` int(11) NOT NULL,
  `labels` text NOT NULL,
  `location` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `srate` float NOT NULL default '0',
  `svotes` int(11) NOT NULL default '0',
  `rrate` float NOT NULL default '0',
  `rvotes` int(11) NOT NULL default '0',
  `score` int(11) NOT NULL default '0',
  `sc_up` int(11) NOT NULL default '0',
  `sc_down` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `cf` int(11) NOT NULL default '1',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `status` enum('active','awaiting','failed','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_videos_photos` (
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

CREATE TABLE IF NOT EXISTS `bx_videos_videos` (
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

CREATE TABLE IF NOT EXISTS `bx_videos_media_resized` (
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
CREATE TABLE IF NOT EXISTS `bx_videos_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_videos_cmts_notes` (
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

-- TABLE: votes
CREATE TABLE IF NOT EXISTS `bx_videos_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_svotes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_svotes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_reactions_track` (
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
CREATE TABLE `bx_videos_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE `bx_videos_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE `bx_videos_meta_locations` (
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

CREATE TABLE `bx_videos_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_videos_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_reports_track` (
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
CREATE TABLE `bx_videos_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `list_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
);

CREATE TABLE `bx_videos_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
   PRIMARY KEY (`id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_videos_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_videos_embeds_providers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object` varchar(64) NOT NULL,
  `module` varchar(64) NOT NULL,
  `params` text NOT NULL,
  `class_name` varchar(255) NOT NULL,
  `class_file` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

INSERT INTO `bx_videos_embeds_providers` (`object`, `module`, `params`, `class_name`, `class_file`) VALUES
('oembed', 'bx_videos', '', 'BxVideosEmbedProviderOEmbed', 'modules/boonex/videos/classes/BxVideosEmbedProviderOEmbed.php');


-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_videos_photos', @sStorageEngine, '', 360, 2592000, 3, 'bx_videos_photos', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_videos_videos', @sStorageEngine, 'a:1:{s:6:"fields";a:1:{s:10:"dimensions";s:17:"getFileDimensions";}}', 360, 2592000, 3, 'bx_videos_videos', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc,ts', '', 0, 0, 0, 0, 0, 0),
('bx_videos_media_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_videos_media_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc,ts', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_videos_preview', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_photos";}', 'no', 1, 2592000, 0, '', ''),
('bx_videos_gallery', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_photos";}', 'no', 1, 2592000, 0, '', ''),
('bx_videos_cover', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_photos";}', 'no', 1, 2592000, 0, '', ''),
('bx_videos_poster', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_photos";}', 'no', 1, 2592000, 0, '', ''),

('bx_videos_video_poster_preview', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_videos";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_videos_video_poster_gallery', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_videos";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_videos_video_poster_cover', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_videos";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_videos_video_mp4', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_videos";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_videos_video_mp4_hd', 'bx_videos_media_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_videos_videos";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_videos_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_videos_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_videos_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),
('bx_videos_poster', 'Resize', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),

('bx_videos_video_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', 10),
('bx_videos_video_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_videos_video_poster_gallery', 'Poster', 'a:2:{s:1:"w";s:3:"500";s:10:"force_type";s:3:"jpg";}', 0),
('bx_videos_video_poster_cover', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_videos_video_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"mp4";}', 0),
('bx_videos_video_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0);


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_videos', 'bx_videos', '_bx_videos_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_videos_entries', 'id', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}', 'a:1:{s:14:"checker_helper";s:25:"BxVideosFormCheckerHelper";}', 0, 1, 'BxVideosFormEntry', 'modules/boonex/videos/classes/BxVideosFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_videos', 'bx_videos_entry_add', 'bx_videos', 0, '_bx_videos_form_entry_display_add'),
('bx_videos', 'bx_videos_entry_delete', 'bx_videos', 0, '_bx_videos_form_entry_display_delete'),
('bx_videos', 'bx_videos_entry_edit', 'bx_videos', 0, '_bx_videos_form_entry_display_edit'),
('bx_videos', 'bx_videos_entry_view', 'bx_videos', 1, '_bx_videos_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_videos', 'bx_videos', 'cf', '', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_videos', 'bx_videos', 'allow_view_to', '', '', 0, 'custom', '_bx_videos_form_entry_input_sys_allow_view_to', '_bx_videos_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_videos', 'bx_videos', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_videos_form_entry_input_sys_delete_confirm', '_bx_videos_form_entry_input_delete_confirm', '_bx_videos_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_videos_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_videos', 'bx_videos', 'do_publish', '_bx_videos_form_entry_input_do_publish', '', 0, 'submit', '_bx_videos_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_videos', 'bx_videos', 'do_submit', '_bx_videos_form_entry_input_do_submit', '', 0, 'submit', '_bx_videos_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_videos', 'bx_videos', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_videos', 'bx_videos', 'video_source', 'upload', '#!bx_videos_source', 0, 'radio_set', '_bx_videos_form_entry_input_sys_video_source', '_bx_videos_form_entry_input_video_source', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_videos', 'bx_videos', 'video_embed', '', '', 0, 'custom', '_bx_videos_form_entry_input_sys_video_embed', '_bx_videos_form_entry_input_video_embed', '', 1, 0, 0, '', '', '', 'EmbedVideoAvail', '', '_bx_videos_form_entry_input_video_embed_error', 'Xss', '', 1, 0),
('bx_videos', 'bx_videos', 'videos', 'a:2:{i:0;s:15:"bx_videos_html5";i:1;s:22:"bx_videos_record_video";}', 'a:3:{s:16:"bx_videos_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_videos_html5";s:25:"_sys_uploader_html5_title";s:22:"bx_videos_record_video";s:32:"_sys_uploader_record_video_title";}', 0, 'files', '_bx_videos_form_entry_input_sys_videos', '_bx_videos_form_entry_input_videos', '', 1, 0, 0, '', '', '', 'UploadVideoAvail', '', '_bx_videos_form_entry_input_videos_error', '', '', 1, 0),
('bx_videos', 'bx_videos', 'pictures', 'a:1:{i:0;s:15:"bx_videos_html5";}', 'a:2:{s:16:"bx_videos_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_videos_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_videos_form_entry_input_sys_pictures', '_bx_videos_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_videos', 'bx_videos', 'text', '', '', 0, 'textarea', '_bx_videos_form_entry_input_sys_text', '_bx_videos_form_entry_input_text', '', 0, 0, 3, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_videos', 'bx_videos', 'title', '', '', 0, 'text', '_bx_videos_form_entry_input_sys_title', '_bx_videos_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_videos_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_videos', 'bx_videos', 'cat', '', '#!bx_videos_cats', 0, 'select', '_bx_videos_form_entry_input_sys_cat', '_bx_videos_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_videos_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_videos', 'bx_videos', 'multicat', '', '', 0, 'custom', '_bx_videos_form_entry_input_sys_multicat', '_bx_videos_form_entry_input_multicat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_videos_form_entry_input_multicat_err', 'Xss', '', 1, 0),
('bx_videos', 'bx_videos', 'duration', '', '', 0, 'text', '_bx_videos_form_entry_input_sys_duration', '_bx_videos_form_entry_input_duration', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_videos', 'bx_videos', 'added', '', '', 0, 'datetime', '_bx_videos_form_entry_input_sys_date_added', '_bx_videos_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_videos', 'bx_videos', 'changed', '', '', 0, 'datetime', '_bx_videos_form_entry_input_sys_date_changed', '_bx_videos_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_videos', 'bx_videos', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_videos_entry_add', 'title', 2147483647, 1, 1),
('bx_videos_entry_add', 'cat', 2147483647, 1, 2),
('bx_videos_entry_add', 'text', 2147483647, 1, 3),
('bx_videos_entry_add', 'video_source', 2147483647, 1, 4),
('bx_videos_entry_add', 'video_embed', 2147483647, 1, 5),
('bx_videos_entry_add', 'videos', 2147483647, 1, 6),
('bx_videos_entry_add', 'pictures', 2147483647, 1, 7),
('bx_videos_entry_add', 'allow_view_to', 2147483647, 1, 8),
('bx_videos_entry_add', 'cf', 2147483647, 1, 9),
('bx_videos_entry_add', 'location', 2147483647, 1, 10),
('bx_videos_entry_add', 'do_publish', 2147483647, 1, 11),

('bx_videos_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_videos_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_videos_entry_edit', 'title', 2147483647, 1, 1),
('bx_videos_entry_edit', 'cat', 2147483647, 1, 2),
('bx_videos_entry_edit', 'text', 2147483647, 1, 3),
('bx_videos_entry_edit', 'video_source', 2147483647, 1, 4),
('bx_videos_entry_edit', 'video_embed', 2147483647, 1, 5),
('bx_videos_entry_edit', 'videos', 2147483647, 1, 6),
('bx_videos_entry_edit', 'pictures', 2147483647, 1, 7),
('bx_videos_entry_edit', 'allow_view_to', 2147483647, 1, 8),
('bx_videos_entry_edit', 'cf', 2147483647, 1, 9),
('bx_videos_entry_edit', 'location', 2147483647, 1, 10),
('bx_videos_entry_edit', 'do_submit', 2147483647, 1, 11),

('bx_videos_entry_view', 'duration', 2147483647, 1, 1),
('bx_videos_entry_view', 'cat', 2147483647, 1, 2),
('bx_videos_entry_view', 'added', 2147483647, 1, 3),
('bx_videos_entry_view', 'changed', 2147483647, 1, 4);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_videos_cats', '_bx_videos_pre_lists_cats', 'bx_videos', '0'),
('bx_videos_source', '_bx_videos_pre_lists_source', 'bx_videos', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_videos_cats', '', 0, '_sys_please_select', ''),
('bx_videos_cats', '1', 1, '_bx_videos_cat_animals_and_pets', ''),
('bx_videos_cats', '2', 2, '_bx_videos_cat_education', ''),
('bx_videos_cats', '3', 3, '_bx_videos_cat_entertainment', ''),
('bx_videos_cats', '4', 4, '_bx_videos_cat_film_and_animation', ''),
('bx_videos_cats', '5', 5, '_bx_videos_cat_music', ''),
('bx_videos_cats', '6', 6, '_bx_videos_cat_news', ''),
('bx_videos_cats', '7', 7, '_bx_videos_cat_people_and_blogs', ''),
('bx_videos_cats', '8', 8, '_bx_videos_cat_sports', ''),
('bx_videos_cats', '9', 9, '_bx_videos_cat_travel', ''),

('bx_videos_source', 'upload', 0, '_bx_videos_source_upload', ''),
('bx_videos_source', 'embed', 1, '_bx_videos_source_embed', '');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_videos', 'bx_videos', 'bx_videos_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-video&id={object_id}', '', 'bx_videos_entries', 'id', 'author', 'title', 'comments', '', ''),
('bx_videos_notes', 'bx_videos', 'bx_videos_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-video&id={object_id}', '', 'bx_videos_entries', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');


-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_videos', 'bx_videos_votes', 'bx_videos_votes_track', '604800', '1', '1', '0', '1', 'bx_videos_entries', 'id', 'author', 'rate', 'votes', '', ''),
('bx_videos_stars', 'bx_videos_svotes', 'bx_videos_svotes_track', '604800', '1', '5', '0', '1', 'bx_videos_entries', 'id', 'author', 'srate', 'svotes', '', ''),
('bx_videos_reactions', 'bx_videos_reactions', 'bx_videos_reactions_track', '604800', '1', '1', '1', '1', 'bx_videos_entries', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');


-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_videos', 'bx_videos', 'bx_videos_scores', 'bx_videos_scores_track', '604800', '0', 'bx_videos_entries', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_videos', 'bx_videos', 'bx_videos_reports', 'bx_videos_reports_track', '1', 'page.php?i=view-video&id={object_id}', 'bx_videos_notes', 'bx_videos_entries', 'id', 'author', 'reports', '', '');


-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_videos', 'bx_videos_views_track', '86400', '1', 'bx_videos_entries', 'id', 'author', 'views', '', '');


-- FAVORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `table_lists`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_videos', 'bx_videos_favorites_track', 'bx_videos_favorites_lists', '1', '1', '1', 'page.php?i=view-video&id={object_id}', 'bx_videos_entries', 'id', 'author', 'favorites', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_videos', 'bx_videos', '1', '1', 'page.php?i=view-video&id={object_id}', 'bx_videos_entries', 'id', 'author', 'featured', '', '');


-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_videos', '_bx_videos', 'bx_videos', 'added', 'edited', 'deleted', '', ''),
('bx_videos_cmts', '_bx_videos_cmts', 'bx_videos', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_videos', 'bx_videos_administration', 'id', '', ''),
('bx_videos', 'bx_videos_common', 'id', '', '');


-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_videos', 'bx_videos', 'bx_videos', '_bx_videos_search_extended', 1, '', ''),
('bx_videos_cmts', 'bx_videos_cmts', 'bx_videos', '_bx_videos_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_videos', '_bx_videos', '_bx_videos', 'bx_videos@modules/boonex/videos/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_videos', 'content', '{url_studio}module.php?name=bx_videos', '', 'bx_videos@modules/boonex/videos/|std-icon.svg', '_bx_videos', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
