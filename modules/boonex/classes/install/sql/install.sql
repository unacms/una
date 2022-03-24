-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_classes_classes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,  
  `published` int(11) NOT NULL,
  `module_id` int(10) unsigned NOT NULL,
  `order` int(11) NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `avail` int(11) NOT NULL,
  `cmts` int(11) NOT NULL,
  `completed_when` int(11) NOT NULL,
  `text` mediumtext NOT NULL,
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
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `cf` int(11) NOT NULL default '1',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `status` enum('active','awaiting','failed','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: modules
CREATE TABLE IF NOT EXISTS `bx_classes_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `module_title` varchar(255) NOT NULL,
  `author` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

-- TABLE: status
CREATE TABLE IF NOT EXISTS `bx_classes_statuses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` int(10) unsigned NOT NULL,
  `student_profile_id` int(11) NOT NULL,
  `viewed` int(11) NOT NULL,
  `replied` int(11) NOT NULL,
  `completed` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE `class_student` (`class_id`,`student_profile_id`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_classes_covers` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_files` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_photos` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_photos_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_videos` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_videos_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_sounds` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_sounds_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_links` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_links2content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(11) NOT NULL DEFAULT '0',
  `link_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `link` (`link_id`, `content_id`)
);

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_classes_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_cmts_notes` (
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
CREATE TABLE IF NOT EXISTS `bx_classes_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_classes_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_classes_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_classes_reactions_track` (
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
CREATE TABLE `bx_classes_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE `bx_classes_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE `bx_classes_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

CREATE TABLE `bx_classes_meta_locations` (
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
CREATE TABLE IF NOT EXISTS `bx_classes_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_classes_reports_track` (
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

-- TABLE: favorites
CREATE TABLE `bx_classes_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_classes_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_classes_scores_track` (
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
CREATE TABLE IF NOT EXISTS `bx_classes_polls` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL default '0',
  `content_id` int(11) NOT NULL default '0',
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`),
  FULLTEXT KEY `search_fields` (`text`)
);

CREATE TABLE IF NOT EXISTS `bx_classes_polls_answers` (
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

CREATE TABLE IF NOT EXISTS `bx_classes_polls_answers_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_classes_polls_answers_votes_track` (
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
('bx_classes_covers', @sStorageEngine, '', 360, 2592000, 3, 'bx_classes_covers', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),

('bx_classes_photos', @sStorageEngine, '', 360, 2592000, 3, 'bx_classes_photos', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_classes_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_classes_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),

('bx_classes_videos', @sStorageEngine, 'a:1:{s:6:"fields";a:1:{s:10:"dimensions";s:17:"getFileDimensions";}}', 360, 2592000, 3, 'bx_classes_videos', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0),
('bx_classes_videos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_classes_videos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0),

('bx_classes_sounds', @sStorageEngine, '', 360, 2592000, 3, 'bx_classes_sounds', 'allow-deny', 'mp3,m4a,m4b,wma,wav,3gp', '', 0, 0, 0, 0, 0, 0),
('bx_classes_sounds_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_classes_sounds_resized', 'allow-deny', 'mp3,m4a,m4b,wma,wav,3gp', '', 0, 0, 0, 0, 0, 0),

('bx_classes_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_classes_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes_preview', 'bx_classes_photos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_classes_gallery', 'bx_classes_photos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_classes_cover', 'bx_classes_photos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_covers";}', 'no', '1', '2592000', '0', '', ''),

('bx_classes_preview_photos', 'bx_classes_photos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_photos";}', 'no', '1', '2592000', '0', '', ''),
('bx_classes_gallery_photos', 'bx_classes_photos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_photos";}', 'no', '1', '2592000', '0', '', ''),

('bx_classes_videos_poster', 'bx_classes_videos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_classes_videos_poster_preview', 'bx_classes_videos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_classes_videos_mp4', 'bx_classes_videos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_classes_videos_mp4_hd', 'bx_classes_videos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),

('bx_classes_sounds_mp3', 'bx_classes_sounds_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_classes_sounds";}', 'no', '0', '0', '0', 'BxDolTranscoderAudio', ''),

('bx_classes_preview_files', 'bx_classes_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_classes_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_classes_gallery_files', 'bx_classes_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_classes_files";}', 'no', '1', '2592000', '0', '', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_classes_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_classes_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_classes_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),

('bx_classes_preview_photos', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_classes_gallery_photos', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),

('bx_classes_videos_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', 10),
('bx_classes_videos_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_classes_videos_poster', 'Poster', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"jpg";}', 0),
('bx_classes_videos_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"mp4";}', 0),
('bx_classes_videos_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0),

('bx_classes_sounds_mp3', 'Mp3', 'a:0:{}', 0),

('bx_classes_preview_files', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_classes_gallery_files', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');


-- FORMS: entry (post)
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_classes', 'bx_classes', '_bx_classes_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_classes_classes', 'id', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}', '', 0, 1, 'BxClssFormEntry', 'modules/boonex/classes/classes/BxClssFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_classes', 'bx_classes_entry_add', 'bx_classes', 0, '_bx_classes_form_entry_display_add'),
('bx_classes', 'bx_classes_entry_delete', 'bx_classes', 0, '_bx_classes_form_entry_display_delete'),
('bx_classes', 'bx_classes_entry_edit', 'bx_classes', 0, '_bx_classes_form_entry_display_edit'),
('bx_classes', 'bx_classes_entry_view', 'bx_classes', 1, '_bx_classes_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_classes', 'bx_classes', 'cf', '', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_classes', 'bx_classes', 'allow_view_to', '', '', 0, 'custom', '_bx_classes_form_entry_input_sys_allow_view_to', '_bx_classes_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_classes_form_entry_input_sys_delete_confirm', '_bx_classes_form_entry_input_delete_confirm', '_bx_classes_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_classes_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_classes', 'bx_classes', 'do_publish', '_bx_classes_form_entry_input_do_publish', '', 0, 'submit', '_bx_classes_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'do_submit', '_bx_classes_form_entry_input_do_submit', '', 0, 'submit', '_bx_classes_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'covers', 'a:1:{i:0;s:16:"bx_classes_html5";}', 'a:2:{s:17:"bx_classes_simple";s:26:"_sys_uploader_simple_title";s:16:"bx_classes_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_classes_form_entry_input_sys_covers', '_bx_classes_form_entry_input_covers', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'pictures', 'a:1:{i:0;s:23:"bx_classes_photos_html5";}', 'a:2:{s:24:"bx_classes_photos_simple";s:26:"_sys_uploader_simple_title";s:23:"bx_classes_photos_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_classes_form_entry_input_sys_pictures', '_bx_classes_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'videos', 'a:2:{i:0;s:23:"bx_classes_videos_html5";i:1;s:30:"bx_classes_videos_record_video";}', 'a:3:{s:24:"bx_classes_videos_simple";s:26:"_sys_uploader_simple_title";s:23:"bx_classes_videos_html5";s:25:"_sys_uploader_html5_title";s:30:"bx_classes_videos_record_video";s:32:"_sys_uploader_record_video_title";}', 0, 'files', '_bx_classes_form_entry_input_sys_videos', '_bx_classes_form_entry_input_videos', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'link', '', '', 0, 'custom', '_bx_classes_form_post_input_sys_link', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'sounds', 'a:1:{i:0;s:23:"bx_classes_sounds_html5";}', 'a:2:{s:24:"bx_classes_sounds_simple";s:26:"_sys_uploader_simple_title";s:23:"bx_classes_sounds_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_classes_form_entry_input_sys_sounds', '_bx_classes_form_entry_input_sounds', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'files', 'a:1:{i:0;s:22:"bx_classes_files_html5";}', 'a:2:{s:23:"bx_classes_files_simple";s:26:"_sys_uploader_simple_title";s:22:"bx_classes_files_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_classes_form_entry_input_sys_files', '_bx_classes_form_entry_input_files', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'polls', '', '', 0, 'custom', '_bx_classes_form_entry_input_sys_polls', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'text', '', '', 0, 'textarea', '_bx_classes_form_entry_input_sys_text', '_bx_classes_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_classes_form_entry_input_text_err', 'XssHtml', '', 1, 0),
('bx_classes', 'bx_classes', 'title', '', '', 0, 'text', '_bx_classes_form_entry_input_sys_title', '_bx_classes_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_classes_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_classes', 'bx_classes', 'avail', '', '#!bx_classes_avail', 0, 'select', '', '_bx_classes_form_entry_input_avail', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_classes', 'bx_classes', 'cmts', '2', '#!bx_classes_cmts', 0, 'select', '', '_bx_classes_form_entry_input_cmts', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_classes', 'bx_classes', 'completed_when', '', '#!bx_classes_completed_when', 0, 'select', '', '_bx_classes_form_entry_input_completed_when', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_classes', 'bx_classes', 'module_id', '', '', 0, 'select', '', '_bx_classes_form_entry_input_module', '', 1, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_classes', 'bx_classes', 'added', '', '', 0, 'datetime', '_bx_classes_form_entry_input_sys_date_added', '_bx_classes_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'changed', '', '', 0, 'datetime', '_bx_classes_form_entry_input_sys_date_changed', '_bx_classes_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'start_date', '', '', 0, 'datetime', '', '_bx_classes_form_entry_input_date_start_date', '', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0),
('bx_classes', 'bx_classes', 'end_date', '', '', 0, 'datetime', '', '_bx_classes_form_entry_input_date_end_date', '', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0),
('bx_classes', 'bx_classes', 'attachments', '', '', 0, 'custom', '_bx_classes_form_entry_input_sys_attachments', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_classes', 'bx_classes', 'anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);



INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_classes_entry_add', 'covers', 2147483647, 1, 1),
('bx_classes_entry_add', 'module_id', 2147483647, 1, 2),
('bx_classes_entry_add', 'title', 2147483647, 1, 3),
('bx_classes_entry_add', 'text', 2147483647, 1, 4),
('bx_classes_entry_add', 'attachments', 2147483647, 1, 5),
('bx_classes_entry_add', 'link', 2147483647, 1, 6),
('bx_classes_entry_add', 'pictures', 2147483647, 1, 7),
('bx_classes_entry_add', 'videos', 2147483647, 1, 8),
('bx_classes_entry_add', 'sounds', 2147483647, 1, 9),
('bx_classes_entry_add', 'files', 2147483647, 1, 10),
('bx_classes_entry_add', 'polls', 2147483647, 1, 11),
('bx_classes_entry_add', 'start_date', 2147483647, 1, 12),
('bx_classes_entry_add', 'end_date', 2147483647, 1, 13),
('bx_classes_entry_add', 'completed_when', 2147483647, 1, 14),
('bx_classes_entry_add', 'avail', 2147483647, 1, 15),
('bx_classes_entry_add', 'cmts', 2147483647, 1, 16),
('bx_classes_entry_add', 'allow_view_to', 2147483647, 1, 17),
('bx_classes_entry_add', 'cf', 2147483647, 1, 18),
('bx_classes_entry_add', 'do_publish', 2147483647, 1, 19),

('bx_classes_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_classes_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_classes_entry_edit', 'covers', 2147483647, 1, 1),
('bx_classes_entry_edit', 'module_id', 2147483647, 1, 2),
('bx_classes_entry_edit', 'title', 2147483647, 1, 3),
('bx_classes_entry_edit', 'text', 2147483647, 1, 4),
('bx_classes_entry_edit', 'attachments', 2147483647, 1, 5),
('bx_classes_entry_edit', 'link', 2147483647, 1, 6),
('bx_classes_entry_edit', 'pictures', 2147483647, 1, 7),
('bx_classes_entry_edit', 'videos', 2147483647, 1, 8),
('bx_classes_entry_edit', 'sounds', 2147483647, 1, 9),
('bx_classes_entry_edit', 'files', 2147483647, 1, 10),
('bx_classes_entry_edit', 'polls', 2147483647, 1, 11),
('bx_classes_entry_edit', 'start_date', 2147483647, 1, 12),
('bx_classes_entry_edit', 'end_date', 2147483647, 1, 13),
('bx_classes_entry_edit', 'completed_when', 2147483647, 1, 14),
('bx_classes_entry_edit', 'avail', 2147483647, 1, 15),
('bx_classes_entry_edit', 'cmts', 2147483647, 1, 16),
('bx_classes_entry_edit', 'allow_view_to', 2147483647, 1, 17),
('bx_classes_entry_edit', 'cf', 2147483647, 1, 18),
('bx_classes_entry_edit', 'do_submit', 2147483647, 1, 19),

('bx_classes_entry_view', 'module_id', 2147483647, 1, 1),
('bx_classes_entry_view', 'completed_when', 2147483647, 1, 2),
('bx_classes_entry_view', 'added', 2147483647, 1, 3),
('bx_classes_entry_view', 'changed', 2147483647, 1, 4),
('bx_classes_entry_view', 'start_date', 2147483647, 1, 5),
('bx_classes_entry_view', 'end_date', 2147483647, 1, 6);

-- FORMS: poll
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_classes_poll', 'bx_classes', '_bx_classes_form_poll', '', '', 'do_submit', 'bx_classes_polls', 'id', '', '', 'a:1:{s:14:"checker_helper";s:27:"BxClssFormPollCheckerHelper";}', 0, 1, 'BxClssFormPoll', 'modules/boonex/classes/classes/BxClssFormPoll.php');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_classes_poll_add', 'bx_classes', 'bx_classes_poll', '_bx_classes_form_poll_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_classes_poll', 'bx_classes', 'text', '', '', 0, 'text', '_bx_classes_form_poll_input_sys_text', '_bx_classes_form_poll_input_text', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_classes_form_poll_input_text_err', 'Xss', '', 1, 0),
('bx_classes_poll', 'bx_classes', 'answers', '', '', 0, 'custom', '_bx_classes_form_poll_input_sys_answers', '_bx_classes_form_poll_input_answers', '', 1, 0, 0, '', '', '', 'AvailAnswers', '', '_bx_classes_form_poll_input_answers_err', '', '', 1, 0),
('bx_classes_poll', 'bx_classes', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_classes_poll', 'bx_classes', 'do_submit', '_bx_classes_form_poll_input_do_submit', '', 0, 'submit', '_bx_classes_form_poll_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_classes_poll', 'bx_classes', 'do_cancel', '_bx_classes_form_poll_input_do_cancel', '', 0, 'button', '_bx_classes_form_poll_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_classes_poll_add', 'text', 2147483647, 1, 1),
('bx_classes_poll_add', 'answers', 2147483647, 1, 2),
('bx_classes_poll_add', 'controls', 2147483647, 1, 3),
('bx_classes_poll_add', 'do_submit', 2147483647, 1, 4),
('bx_classes_poll_add', 'do_cancel', 2147483647, 1, 5);


-- Forms -> Attach link
INSERT INTO `sys_objects_form` (`object`, `module`, `title`, `action`, `form_attrs`, `submit_name`, `table`, `key`, `uri`, `uri_title`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES
('bx_classes_attach_link', 'bx_classes', '_bx_classes_form_attach_link', '', '', 'do_submit', 'bx_classes_links', 'id', '', '', '', 0, 1, '', '');

INSERT INTO `sys_form_displays` (`display_name`, `module`, `object`, `title`, `view_mode`) VALUES
('bx_classes_attach_link_add', 'bx_classes', 'bx_classes_attach_link', '_bx_classes_form_attach_link_display_add', 0);

INSERT INTO `sys_form_inputs` (`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES
('bx_classes_attach_link', 'bx_classes', 'content_id', '0', '', 0, 'hidden', '_bx_classes_form_attach_link_input_sys_content_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_classes_attach_link', 'bx_classes', 'url', '', '', 0, 'text', '_bx_classes_form_attach_link_input_sys_url', '_bx_classes_form_attach_link_input_url', '', 0, 0, 0, '', '', '', 'Preg', 'a:1:{s:4:"preg";s:0:"";}', '_bx_classes_form_attach_link_input_url_err', '', '', 0, 0),
('bx_classes_attach_link', 'bx_classes', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_classes_attach_link', 'bx_classes', 'do_submit', '_bx_classes_form_attach_link_input_do_submit', '', 0, 'submit', '_bx_classes_form_attach_link_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_classes_attach_link', 'bx_classes', 'do_cancel', '_bx_classes_form_attach_link_input_do_cancel', '', 0, 'button', '_bx_classes_form_attach_link_input_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_classes_attach_link_add', 'content_id', 2147483647, 1, 1),
('bx_classes_attach_link_add', 'url', 2147483647, 1, 2),
('bx_classes_attach_link_add', 'controls', 2147483647, 1, 3),
('bx_classes_attach_link_add', 'do_submit', 2147483647, 1, 4),
('bx_classes_attach_link_add', 'do_cancel', 2147483647, 1, 5);

-- PRE-VALUES

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_classes_avail', '_bx_classes_pre_lists_availability', 'bx_classes', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_classes_avail', '1', 1, '_bx_classes_avail_always', ''),
('bx_classes_avail', '2', 2, '_bx_classes_avail_prev_class_completed', ''),
('bx_classes_avail', '3', 3, '_bx_classes_avail_after_start_date', ''),
('bx_classes_avail', '4', 4, '_bx_classes_avail_after_start_date_prev_class_completed', ''),
('bx_classes_avail', '5', 5, '_bx_classes_avail_between_start_end_dates', ''),
('bx_classes_avail', '6', 6, '_bx_classes_avail_between_start_end_dates_prev_completed', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_classes_cmts', '_bx_classes_pre_lists_cmts', 'bx_classes', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_classes_cmts', '1', 1, '_bx_classes_cmts_disabled', ''),
('bx_classes_cmts', '2', 2, '_bx_classes_cmts_all_shown', '');


INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_classes_completed_when', '_bx_classes_pre_lists_completed_when', 'bx_classes', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_classes_completed_when', '1', 1, '_bx_classes_completed_when_viewed', ''),
('bx_classes_completed_when', '2', 2, '_bx_classes_completed_when_replied', '');

-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_classes', 'bx_classes', 'bx_classes_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-class&id={object_id}', '', 'bx_classes_classes', 'id', 'author', 'title', 'comments', '', ''),
('bx_classes_notes', 'bx_classes', 'bx_classes_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-class&id={object_id}', '', 'bx_classes_classes', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_classes', 'bx_classes_votes', 'bx_classes_votes_track', '604800', '1', '1', '0', '1', 'bx_classes_classes', 'id', 'author', 'rate', 'votes', '', ''),
('bx_classes_reactions', 'bx_classes_reactions', 'bx_classes_reactions_track', '604800', '1', '1', '1', '1', 'bx_classes_classes', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', ''),
('bx_classes_poll_answers', 'bx_classes_polls_answers_votes', 'bx_classes_polls_answers_votes_track', '604800', '1', '1', '0', '1', 'bx_classes_polls_answers', 'id', 'author_id', 'rate', 'votes', 'BxClssVotePollAnswers', 'modules/boonex/classes/classes/BxClssVotePollAnswers.php');

-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_classes', 'bx_classes', 'bx_classes_scores', 'bx_classes_scores_track', '604800', '0', 'bx_classes_classes', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_classes', 'bx_classes', 'bx_classes_reports', 'bx_classes_reports_track', '1', 'page.php?i=view-class&id={object_id}', 'bx_classes_notes', 'bx_classes_classes', 'id', 'author', 'reports', '', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_classes', 'bx_classes_views_track', '86400', '1', 'bx_classes_classes', 'id', 'author', 'views', '', '');

-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_classes', 'bx_classes_favorites_track', '1', '1', '1', 'page.php?i=view-class&id={object_id}', 'bx_classes_classes', 'id', 'author', 'favorites', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_classes', 'bx_classes', '1', '1', 'page.php?i=view-class&id={object_id}', 'bx_classes_classes', 'id', 'author', 'featured', '', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_classes', '_bx_classes', 'bx_classes', 'added', 'edited', 'deleted', '', ''),
('bx_classes_cmts', '_bx_classes_cmts', 'bx_classes', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_classes', 'bx_classes_administration', 'id', '', ''),
('bx_classes', 'bx_classes_common', 'id', '', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_classes', 'bx_classes', 'bx_classes', '_bx_classes_search_extended', 1, '', ''),
('bx_classes_cmts', 'bx_classes_cmts', 'bx_classes', '_bx_classes_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_classes', '_bx_classes', '_bx_classes', 'bx_classes@modules/boonex/classes/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_classes', 'content', '{url_studio}module.php?name=bx_classes', '', 'bx_classes@modules/boonex/classes/|std-icon.svg', '_bx_classes', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

