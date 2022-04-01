-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_tasks_tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `published` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `multicat` text NOT NULL,
  `text` mediumtext NOT NULL,
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
  `due_date` int(11) NOT NULL,
  `tasks_list` int(11) NOT NULL,	
  `completed` tinyint(4) NOT NULL DEFAULT '0', 
  `expired` tinyint(4) NOT NULL DEFAULT '0', 
  `cf` int(11) NOT NULL default '1',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `allow_comments` tinyint(4) NOT NULL DEFAULT '1',
  `status` enum('active','awaiting','failed','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: task-lists
CREATE TABLE IF NOT EXISTS `bx_tasks_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `context_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

-- TABLE: assignment
CREATE TABLE IF NOT EXISTS `bx_tasks_assignments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_tasks_covers` (
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

CREATE TABLE IF NOT EXISTS `bx_tasks_files` (
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

CREATE TABLE IF NOT EXISTS `bx_tasks_photos` (
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

CREATE TABLE IF NOT EXISTS `bx_tasks_photos_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_tasks_videos` (
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

CREATE TABLE IF NOT EXISTS `bx_tasks_videos_resized` (
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
CREATE TABLE IF NOT EXISTS `bx_tasks_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_tasks_cmts_notes` (
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
CREATE TABLE IF NOT EXISTS `bx_tasks_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_tasks_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_tasks_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_tasks_reactions_track` (
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
CREATE TABLE `bx_tasks_views_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE `bx_tasks_meta_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE `bx_tasks_meta_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_tasks_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_tasks_reports_track` (
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
CREATE TABLE `bx_tasks_favorites_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`author_id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_tasks_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_tasks_scores_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(8) NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);


-- STORAGES & TRANSCODERS
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_tasks_covers', @sStorageEngine, '', 360, 2592000, 3, 'bx_tasks_covers', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),

('bx_tasks_photos', @sStorageEngine, '', 360, 2592000, 3, 'bx_tasks_photos', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_tasks_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_tasks_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),

('bx_tasks_videos', @sStorageEngine, 'a:1:{s:6:"fields";a:1:{s:10:"dimensions";s:17:"getFileDimensions";}}', 360, 2592000, 3, 'bx_tasks_videos', 'allow-deny', 'avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0),
('bx_tasks_videos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_tasks_videos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0),

('bx_tasks_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_tasks_files', 'deny-allow', '', 'action,apk,app,bat,bin,cmd,com,command,cpl,csh,exe,gadget,inf,ins,inx,ipa,isu,job,jse,ksh,lnk,msc,msi,msp,mst,osx,out,paf,pif,prg,ps1,reg,rgs,run,sct,shb,shs,u3p,vb,vbe,vbs,vbscript,workflow,ws,wsf', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_preview', 'bx_tasks_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_tasks_gallery', 'bx_tasks_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_tasks_cover', 'bx_tasks_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_covers";}', 'no', '1', '2592000', '0', '', ''),

('bx_tasks_preview_photos', 'bx_tasks_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_photos";}', 'no', '1', '2592000', '0', '', ''),
('bx_tasks_gallery_photos', 'bx_tasks_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_photos";}', 'no', '1', '2592000', '0', '', ''),

('bx_tasks_videos_poster', 'bx_tasks_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_tasks_videos_poster_preview', 'bx_tasks_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_tasks_videos_mp4', 'bx_tasks_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),
('bx_tasks_videos_mp4_hd', 'bx_tasks_videos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_tasks_videos";}', 'no', '0', '0', '0', 'BxDolTranscoderVideo', ''),

('bx_tasks_preview_files', 'bx_tasks_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_tasks_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_tasks_gallery_files', 'bx_tasks_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_tasks_files";}', 'no', '1', '2592000', '0', '', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_tasks_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_tasks_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_tasks_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),

('bx_tasks_preview_photos', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_tasks_gallery_photos', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),

('bx_tasks_videos_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:13:"square_resize";s:1:"1";}', 10),
('bx_tasks_videos_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_tasks_videos_poster', 'Poster', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"jpg";}', 0),
('bx_tasks_videos_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"318";s:10:"force_type";s:3:"mp4";}', 0),
('bx_tasks_videos_mp4_hd', 'Mp4', 'a:3:{s:1:"h";s:3:"720";s:13:"video_bitrate";s:4:"1536";s:10:"force_type";s:3:"mp4";}', 0),

('bx_tasks_preview_files', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_tasks_gallery_files', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');


-- FORMS: entry (task)
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks', 'bx_tasks', '_bx_tasks_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_tasks_tasks', 'id', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}', '', 0, 1, 'BxTasksFormEntry', 'modules/boonex/tasks/classes/BxTasksFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_tasks', 'bx_tasks_entry_add', 'bx_tasks', 0, '_bx_tasks_form_entry_display_add'),
('bx_tasks', 'bx_tasks_entry_delete', 'bx_tasks', 0, '_bx_tasks_form_entry_display_delete'),
('bx_tasks', 'bx_tasks_entry_edit', 'bx_tasks', 0, '_bx_tasks_form_entry_display_edit'),
('bx_tasks', 'bx_tasks_entry_view', 'bx_tasks', 1, '_bx_tasks_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_tasks', 'bx_tasks', 'cf', '', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_tasks', 'bx_tasks', 'allow_view_to', '', '', 0, 'custom', '_bx_tasks_form_entry_input_sys_allow_view_to', '_bx_tasks_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_tasks_form_entry_input_sys_delete_confirm', '_bx_tasks_form_entry_input_delete_confirm', '_bx_tasks_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_tasks_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'do_publish', '_bx_tasks_form_entry_input_do_publish', '', 0, 'submit', '_bx_tasks_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'do_submit', '_bx_tasks_form_entry_input_do_submit', '', 0, 'submit', '_bx_tasks_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'do_cancel', '_bx_tasks_form_entry_input_do_cancel', '', 0, 'button', '_bx_tasks_form_entry_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'controls', '', 'do_publish,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'covers', 'a:1:{i:0;s:14:"bx_tasks_html5";}', 'a:2:{s:15:"bx_tasks_simple";s:26:"_sys_uploader_simple_title";s:14:"bx_tasks_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_tasks_form_entry_input_sys_covers', '_bx_tasks_form_entry_input_covers', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'pictures', 'a:1:{i:0;s:21:"bx_tasks_photos_html5";}', 'a:2:{s:22:"bx_tasks_photos_simple";s:26:"_sys_uploader_simple_title";s:21:"bx_tasks_photos_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_tasks_form_entry_input_sys_pictures', '_bx_tasks_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'videos', 'a:2:{i:0;s:21:"bx_tasks_videos_html5";i:1;s:28:"bx_tasks_videos_record_video";}', 'a:3:{s:22:"bx_tasks_videos_simple";s:26:"_sys_uploader_simple_title";s:21:"bx_tasks_videos_html5";s:25:"_sys_uploader_html5_title";s:28:"bx_tasks_videos_record_video";s:32:"_sys_uploader_record_video_title";}', 0, 'files', '_bx_tasks_form_entry_input_sys_videos', '_bx_tasks_form_entry_input_videos', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'files', 'a:1:{i:0;s:20:"bx_tasks_files_html5";}', 'a:2:{s:21:"bx_tasks_files_simple";s:26:"_sys_uploader_simple_title";s:20:"bx_tasks_files_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_tasks_form_entry_input_sys_files', '_bx_tasks_form_entry_input_files', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'text', '', '', 0, 'textarea', '_bx_tasks_form_entry_input_sys_text', '_bx_tasks_form_entry_input_text', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_tasks', 'bx_tasks', 'title', '', '', 0, 'text', '_bx_tasks_form_entry_input_sys_title', '_bx_tasks_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_tasks_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_tasks', 'bx_tasks', 'cat', '', '#!bx_tasks_cats', 0, 'select', '_bx_tasks_form_entry_input_sys_cat', '_bx_tasks_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_tasks_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_tasks', 'bx_tasks', 'multicat', '', '', 0, 'custom', '_bx_tasks_form_entry_input_sys_multicat', '_bx_tasks_form_entry_input_multicat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_tasks_form_entry_input_multicat_err', 'Xss', '', 1, 0),
('bx_tasks', 'bx_tasks', 'added', '', '', 0, 'datetime', '_bx_tasks_form_entry_input_sys_date_added', '_bx_tasks_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'changed', '', '', 0, 'datetime', '_bx_tasks_form_entry_input_sys_date_changed', '_bx_tasks_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'published', '', '', 0, 'datetime', '_bx_tasks_form_entry_input_sys_date_published', '_bx_tasks_form_entry_input_date_published', '_bx_tasks_form_entry_input_date_published_info', 0, 0, 0, '', '', '', '', '', '', 'DateTimeUtc', '', 1, 0),
('bx_tasks', 'bx_tasks', 'initial_members', '', '', 0, 'custom', '_bx_tasks_form_profile_input_sys_initial_members', '_bx_tasks_form_profile_input_initial_members', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('bx_tasks', 'bx_tasks', 'due_date', '', '', 0, 'datetime', '_bx_tasks_form_entry_input_sys_date_due_date', '_bx_tasks_form_entry_input_date_due_date', '', 0, 0, 0, '', '', '', '', '', '', 'DateTimeTs', '', 1, 0),
('bx_tasks', 'bx_tasks', 'attachments', '', '', 0, 'custom', '_bx_tasks_form_entry_input_sys_attachments', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks', 'bx_tasks', 'allow_comments', '1', '', 1, 'switcher', '_bx_tasks_form_entry_input_sys_allow_comments', '_bx_tasks_form_entry_input_allow_comments', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_tasks_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_tasks_entry_add', 'title', 2147483647, 1, 2),
('bx_tasks_entry_add', 'text', 2147483647, 1, 3),
('bx_tasks_entry_add', 'initial_members', 192, 1, 4),
('bx_tasks_entry_add', 'due_date', 192, 1, 5),
('bx_tasks_entry_add', 'controls', 2147483647, 1, 6),
('bx_tasks_entry_add', 'do_publish', 2147483647, 1, 7),
('bx_tasks_entry_add', 'do_cancel', 2147483647, 1, 8),

('bx_tasks_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_tasks_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_tasks_entry_edit', 'do_publish', 2147483647, 0, 1),
('bx_tasks_entry_edit', 'delete_confirm', 2147483647, 0, 2),
('bx_tasks_entry_edit', 'title', 2147483647, 1, 3),
('bx_tasks_entry_edit', 'text', 2147483647, 1, 4),
('bx_tasks_entry_edit', 'attachments', 2147483647, 1, 5),
('bx_tasks_entry_edit', 'pictures', 2147483647, 1, 6),
('bx_tasks_entry_edit', 'videos', 2147483647, 1, 7),
('bx_tasks_entry_edit', 'files', 2147483647, 1, 8),
('bx_tasks_entry_edit', 'allow_view_to', 2147483647, 1, 9),
('bx_tasks_entry_edit', 'cf', 2147483647, 1, 10),
('bx_tasks_entry_edit', 'tasks_list', 192, 1, 11),
('bx_tasks_entry_edit', 'initial_members', 192, 1, 12),
('bx_tasks_entry_edit', 'due_date', 192, 1, 13),
('bx_tasks_entry_edit', 'do_submit', 2147483647, 1, 14),

('bx_tasks_entry_view', 'cat', 2147483647, 1, 1),
('bx_tasks_entry_view', 'added', 2147483647, 1, 2),
('bx_tasks_entry_view', 'changed', 2147483647, 1, 3),
('bx_tasks_entry_view', 'due_date', 192, 1, 4);


-- FORMS: entry (tasklist)
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_tasks_list', 'bx_tasks', '_bx_tasks_form_list_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_tasks_lists', 'id', '', '', 'do_submit', '', 0, 1, 'BxTasksFormListEntry', 'modules/boonex/tasks/classes/BxTasksFormListEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_tasks_list', 'bx_tasks_list_entry_add', 'bx_tasks', 0, '_bx_tasks_form_list_entry_display_add'),
('bx_tasks_list', 'bx_tasks_list_entry_edit', 'bx_tasks', 0, '_bx_tasks_form_list_entry_display_edit');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_tasks_list', 'bx_tasks', 'do_submit', '_bx_tasks_form_list_entry_input_do_submit', '', 0, 'submit', '_bx_tasks_form_list_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_tasks_list', 'bx_tasks', 'title', '', '', 0, 'text', '_bx_tasks_form_list_entry_input_sys_title', '_bx_tasks_form_list_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_tasks_form_list_entry_input_title_err', 'Xss', '', 1, 0),
('bx_tasks_list', 'bx_tasks', 'do_cancel', '_bx_tasks_form_entry_input_do_cancel', '', 0, 'button', '_bx_tasks_form_entry_input_sys_do_cancel', '', '', 0, 0, 0, 'a:2:{s:7:"onclick";s:45:"$(''.bx-popup-applied:visible'').dolPopupHide()";s:5:"class";s:22:"bx-def-margin-sec-left";}', '', '', '', '', '', '', '', 1, 0),
('bx_tasks_list', 'bx_tasks', 'controls', '', 'do_submit,do_cancel', 0, 'input_set', '', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_tasks_list_entry_add', 'title', 2147483647, 1, 1),
('bx_tasks_list_entry_add', 'controls', 2147483647, 1, 2),
('bx_tasks_list_entry_add', 'do_submit', 2147483647, 1, 3),
('bx_tasks_list_entry_add', 'do_cancel', 2147483647, 1, 4),

('bx_tasks_list_entry_edit', 'title', 2147483647, 1, 1),
('bx_tasks_list_entry_edit', 'controls', 2147483647, 1, 2),
('bx_tasks_list_entry_edit', 'do_submit', 2147483647, 1, 3),
('bx_tasks_list_entry_edit', 'do_cancel', 2147483647, 1, 4);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_tasks_cats', '_bx_tasks_pre_lists_cats', 'bx_tasks', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_tasks_cats', '', 0, '_sys_please_select', ''),
('bx_tasks_cats', '1', 1, '_bx_tasks_cat_Animals_Pets', ''),
('bx_tasks_cats', '2', 2, '_bx_tasks_cat_Architecture', ''),
('bx_tasks_cats', '3', 3, '_bx_tasks_cat_Art', ''),
('bx_tasks_cats', '4', 4, '_bx_tasks_cat_Cars_Motorcycles', ''),
('bx_tasks_cats', '5', 5, '_bx_tasks_cat_Celebrities', ''),
('bx_tasks_cats', '6', 6, '_bx_tasks_cat_Design', ''),
('bx_tasks_cats', '7', 7, '_bx_tasks_cat_DIY_Crafts', ''),
('bx_tasks_cats', '8', 8, '_bx_tasks_cat_Education', ''),
('bx_tasks_cats', '9', 9, '_bx_tasks_cat_Film_Music_Books', ''),
('bx_tasks_cats', '10', 10, '_bx_tasks_cat_Food_Drink', ''),
('bx_tasks_cats', '11', 11, '_bx_tasks_cat_Gardening', ''),
('bx_tasks_cats', '12', 12, '_bx_tasks_cat_Geek', ''),
('bx_tasks_cats', '13', 13, '_bx_tasks_cat_Hair_Beauty', ''),
('bx_tasks_cats', '14', 14, '_bx_tasks_cat_Health_Fitness', ''),
('bx_tasks_cats', '15', 15, '_bx_tasks_cat_History', ''),
('bx_tasks_cats', '16', 16, '_bx_tasks_cat_Holidays_Events', ''),
('bx_tasks_cats', '17', 17, '_bx_tasks_cat_Home_Decor', ''),
('bx_tasks_cats', '18', 18, '_bx_tasks_cat_Humor', ''),
('bx_tasks_cats', '19', 19, '_bx_tasks_cat_Illustrations_Posters', ''),
('bx_tasks_cats', '20', 20, '_bx_tasks_cat_Kids_Parenting', ''),
('bx_tasks_cats', '21', 21, '_bx_tasks_cat_Mens_Fashion', ''),
('bx_tasks_cats', '22', 22, '_bx_tasks_cat_Outdoors', ''),
('bx_tasks_cats', '23', 23, '_bx_tasks_cat_Photography', ''),
('bx_tasks_cats', '24', 24, '_bx_tasks_cat_Products', ''),
('bx_tasks_cats', '25', 25, '_bx_tasks_cat_Quotes', ''),
('bx_tasks_cats', '26', 26, '_bx_tasks_cat_Science_Nature', ''),
('bx_tasks_cats', '27', 27, '_bx_tasks_cat_Sports', ''),
('bx_tasks_cats', '28', 28, '_bx_tasks_cat_Tattoos', ''),
('bx_tasks_cats', '29', 29, '_bx_tasks_cat_Technology', ''),
('bx_tasks_cats', '30', 30, '_bx_tasks_cat_Travel', ''),
('bx_tasks_cats', '31', 31, '_bx_tasks_cat_Weddings', ''),
('bx_tasks_cats', '32', 32, '_bx_tasks_cat_Womens_Fashion', '');

-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_tasks', 'bx_tasks', 'bx_tasks_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-task&id={object_id}', '', 'bx_tasks_tasks', 'id', 'author', 'title', 'comments', '', ''),
('bx_tasks_notes', 'bx_tasks', 'bx_tasks_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-task&id={object_id}', '', 'bx_tasks_tasks', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_tasks', 'bx_tasks_votes', 'bx_tasks_votes_track', '604800', '1', '1', '0', '1', 'bx_tasks_tasks', 'id', 'author', 'rate', 'votes', '', ''),
('bx_tasks_reactions', 'bx_tasks_reactions', 'bx_tasks_reactions_track', '604800', '1', '1', '1', '1', 'bx_tasks_tasks', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');

-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_tasks', 'bx_tasks', 'bx_tasks_scores', 'bx_tasks_scores_track', '604800', '0', 'bx_tasks_tasks', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_tasks', 'bx_tasks', 'bx_tasks_reports', 'bx_tasks_reports_track', '1', 'page.php?i=view-task&id={object_id}', 'bx_tasks_notes', 'bx_tasks_tasks', 'id', 'author', 'reports', '', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_tasks', 'bx_tasks_views_track', '86400', '1', 'bx_tasks_tasks', 'id', 'author', 'views', '', '');

-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_tasks', 'bx_tasks_favorites_track', '1', '1', '1', 'page.php?i=view-task&id={object_id}', 'bx_tasks_tasks', 'id', 'author', 'favorites', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_tasks', 'bx_tasks', '1', '1', 'page.php?i=view-task&id={object_id}', 'bx_tasks_tasks', 'id', 'author', 'featured', '', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_tasks', '_bx_tasks', 'bx_tasks', 'added', 'edited', 'deleted', '', ''),
('bx_tasks_cmts', '_bx_tasks_cmts', 'bx_tasks', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_tasks', 'bx_tasks', 'bx_tasks', '_bx_tasks_search_extended', 1, '', ''),
('bx_tasks_cmts', 'bx_tasks_cmts', 'bx_tasks', '_bx_tasks_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_tasks', '_bx_tasks', '_bx_tasks', 'bx_tasks@modules/boonex/tasks/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_tasks', 'content', '{url_studio}module.php?name=bx_tasks', '', 'bx_tasks@modules/boonex/tasks/|std-icon.svg', '_bx_tasks', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

