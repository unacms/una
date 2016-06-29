
-- TABLE: entries

CREATE TABLE IF NOT EXISTS `bx_albums_albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `allow_view_to` int(11) NOT NULL DEFAULT '3',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: storages & transcoders

CREATE TABLE IF NOT EXISTS `bx_albums_files` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_albums_photos_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `mime_type` varchar(128) NOT NULL,
  `ext` varchar(32) NOT NULL,
  `size` int(11) NOT NULL,
  `added` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `private` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `remote_id` (`remote_id`)
);

CREATE TABLE IF NOT EXISTS `bx_albums_files2albums` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `file_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `views` int(11) NOT NULL,
  `rate` float NOT NULL,
  `votes` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `data` text NOT NULL,
  `exif` text NOT NULL,
  `order` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `file_content` (`file_id`,`content_id`),
  KEY `content_id` (`content_id`)
);

-- TABLE: comments

CREATE TABLE IF NOT EXISTS `bx_albums_cmts` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
);

CREATE TABLE IF NOT EXISTS `bx_albums_cmts_media` (
  `cmt_id` int(11) NOT NULL AUTO_INCREMENT,
  `cmt_parent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_vparent_id` int(11) NOT NULL DEFAULT '0',
  `cmt_object_id` int(11) NOT NULL DEFAULT '0',
  `cmt_author_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cmt_level` int(11) NOT NULL DEFAULT '0',
  `cmt_text` text NOT NULL,
  `cmt_mood` tinyint(4) NOT NULL DEFAULT '0',
  `cmt_rate` int(11) NOT NULL DEFAULT '0',
  `cmt_rate_count` int(11) NOT NULL DEFAULT '0',
  `cmt_time` int(11) unsigned NOT NULL DEFAULT '0',
  `cmt_replies` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cmt_id`),
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`)
);

-- TABLE: votes

CREATE TABLE IF NOT EXISTS `bx_albums_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_albums_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_albums_votes_media` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_albums_votes_media_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- TABLE: views

CREATE TABLE `bx_albums_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bx_albums_views_media_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: metas

CREATE TABLE `bx_albums_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bx_albums_meta_keywords_media` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bx_albums_meta_keywords_media_camera` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `bx_albums_meta_locations` (
  `object_id` int(10) unsigned NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `country` varchar(2) NOT NULL,
  `state` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  PRIMARY KEY (`object_id`),
  KEY `country_state_city` (`country`,`state`(8),`city`(8))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: reports

CREATE TABLE IF NOT EXISTS `bx_albums_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_albums_reports_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `type` varchar(32) NOT NULL default '',
  `text` text NOT NULL default '',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `report` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- STORAGES & TRANSCODERS

INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_albums_files', 'Local', '', 360, 2592000, 3, 'bx_albums_files', 'allow-deny', 'jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0),
('bx_albums_photos_resized', 'Local', '', 360, 2592000, 3, 'bx_albums_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png,avi,flv,mpg,mpeg,wmv,mp4,m4v,mov,qt,divx,xvid,3gp,3g2,webm,mkv,ogv,ogg,rm,rmvb,asf,drc', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES
('bx_albums_preview', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_albums_browse', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_albums_big', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', '1', '2592000', '0', '', ''),
('bx_albums_video_poster_browse', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_albums_video_poster_preview', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_albums_video_poster_big', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_albums_video_mp4', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_albums_video_webm', 'bx_albums_photos_resized', 'Storage', 'a:1:{s:6:"object";s:15:"bx_albums_files";}', 'no', 0, 0, 0, 'BxDolTranscoderVideo', ''),
('bx_albums_proxy_preview', 'bx_albums_photos_resized', 'Proxy', 'a:4:{s:6:"object";s:15:"bx_albums_files";s:5:"image";s:17:"bx_albums_preview";s:12:"video_poster";s:30:"bx_albums_video_poster_preview";s:5:"video";a:4:{i:0;s:19:"bx_albums_video_mp4";i:1;s:20:"bx_albums_video_webm";i:2;s:29:"bx_albums_video_poster_browse";i:3;s:26:"bx_albums_video_poster_big";}}', 'no', 0, 0, 0, 'BxDolTranscoderProxy', ''),
('bx_albums_proxy_browse', 'bx_albums_photos_resized', 'Proxy', 'a:4:{s:6:"object";s:15:"bx_albums_files";s:5:"image";s:16:"bx_albums_browse";s:12:"video_poster";s:29:"bx_albums_video_poster_browse";s:5:"video";a:2:{i:0;s:19:"bx_albums_video_mp4";i:1;s:20:"bx_albums_video_webm";}}', 'no', 0, 0, 0, 'BxDolTranscoderProxy', ''),
('bx_albums_proxy_cover', 'bx_albums_photos_resized', 'Proxy', 'a:4:{s:6:"object";s:15:"bx_albums_files";s:5:"image";s:13:"bx_albums_big";s:12:"video_poster";s:26:"bx_albums_video_poster_big";s:5:"video";a:2:{i:0;s:19:"bx_albums_video_mp4";i:1;s:20:"bx_albums_video_webm";}}', 'no', 0, 0, 0, 'BxDolTranscoderProxy', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_albums_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"300";s:13:"square_resize";s:1:"1";}', '0'),
('bx_albums_browse', 'Resize', 'a:1:{s:1:"h";s:3:"180";}', '0'),
('bx_albums_big', 'Resize', 'a:2:{s:1:"w";s:4:"1280";s:1:"h";s:4:"1280";}', '0'),
('bx_albums_video_poster_browse', 'Poster', 'a:2:{s:1:"h";s:3:"180";s:10:"force_type";s:3:"jpg";}', 0),
('bx_albums_video_poster_preview', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_albums_video_poster_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"300";s:13:"square_resize";s:1:"1";}', 10),
('bx_albums_video_poster_big', 'Poster', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"jpg";}', 0),
('bx_albums_video_mp4', 'Mp4', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:3:"mp4";}', 0),
('bx_albums_video_webm', 'Webm', 'a:2:{s:1:"h";s:3:"480";s:10:"force_type";s:4:"webm";}', 0);



-- FORMS

INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_albums', 'bx_albums', '_bx_albums_form_entry', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_albums_albums', 'id', '', '', 'do_submit', '', 0, 1, 'BxAlbumsFormEntry', 'modules/boonex/albums/classes/BxAlbumsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_albums', 'bx_albums_entry_add', 'bx_albums', 0, '_bx_albums_form_entry_display_add'),
('bx_albums', 'bx_albums_entry_edit', 'bx_albums', 0, '_bx_albums_form_entry_display_edit'),
('bx_albums', 'bx_albums_entry_add_images', 'bx_albums', 0, '_bx_albums_form_entry_display_add_images'),
('bx_albums', 'bx_albums_entry_delete', 'bx_albums', 0, '_bx_albums_form_entry_display_delete'),
('bx_albums', 'bx_albums_entry_view', 'bx_albums', 1, '_bx_albums_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_albums', 'bx_albums', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_albums_form_entry_input_sys_delete_confirm', '_bx_albums_form_entry_input_delete_confirm', '_bx_albums_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_albums_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_albums', 'bx_albums', 'do_submit', '_bx_albums_form_entry_input_do_submit', '', 0, 'submit', '_bx_albums_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_albums', 'bx_albums', 'title', '', '', 0, 'text', '_bx_albums_form_entry_input_sys_title', '_bx_albums_form_entry_input_title', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_albums_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_albums', 'bx_albums', 'text', '', '', 0, 'textarea', '_bx_albums_form_entry_input_sys_text', '_bx_albums_form_entry_input_text', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_albums', 'bx_albums', 'pictures', 'a:2:{i:0;s:15:"bx_albums_html5";i:1;s:14:"bx_albums_crop";}', 'a:3:{s:16:"bx_albums_simple";s:26:"_sys_uploader_simple_title";s:15:"bx_albums_html5";s:25:"_sys_uploader_html5_title";s:14:"bx_albums_crop";s:24:"_sys_uploader_crop_title";}', 0, 'files', '_bx_albums_form_entry_input_sys_pictures', '_bx_albums_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_albums', 'bx_albums', 'allow_view_to', '', '', 0, 'custom', '_bx_albums_form_entry_input_sys_allow_view_to', '_bx_albums_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_albums', 'bx_albums', 'location', '', '', 0, 'custom', '_sys_form_input_sys_location', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0),
('bx_albums', 'bx_albums', 'added', '', '', 0, 'datetime', '_bx_albums_form_entry_input_sys_date_added', '_bx_albums_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_albums', 'bx_albums', 'changed', '', '', 0, 'datetime', '_bx_albums_form_entry_input_sys_date_changed', '_bx_albums_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


INSERT INTO `sys_form_display_inputs` (`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES
('bx_albums_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_albums_entry_add', 'title', 2147483647, 1, 2),
('bx_albums_entry_add', 'text', 2147483647, 1, 3),
('bx_albums_entry_add', 'pictures', 2147483647, 1, 4),
('bx_albums_entry_add', 'allow_view_to', 2147483647, 1, 5),
('bx_albums_entry_add', 'location', 2147483647, 1, 6),
('bx_albums_entry_add', 'do_submit', 2147483647, 1, 7),

('bx_albums_entry_edit', 'delete_confirm', 2147483647, 0, 1),
('bx_albums_entry_edit', 'title', 2147483647, 1, 2),
('bx_albums_entry_edit', 'text', 2147483647, 1, 3),
('bx_albums_entry_edit', 'pictures', 2147483647, 0, 4),
('bx_albums_entry_edit', 'allow_view_to', 2147483647, 1, 5),
('bx_albums_entry_edit', 'location', 2147483647, 1, 6),
('bx_albums_entry_edit', 'do_submit', 2147483647, 1, 7),

('bx_albums_entry_add_images', 'delete_confirm', 2147483647, 0, 1),
('bx_albums_entry_add_images', 'title', 2147483647, 0, 2),
('bx_albums_entry_add_images', 'text', 2147483647, 0, 3),
('bx_albums_entry_add_images', 'allow_view_to', 2147483647, 0, 4),
('bx_albums_entry_add_images', 'location', 2147483647, 0, 5),
('bx_albums_entry_add_images', 'pictures', 2147483647, 1, 6),
('bx_albums_entry_add_images', 'do_submit', 2147483647, 1, 7),

('bx_albums_entry_view', 'delete_confirm', 2147483647, 0, 0),
('bx_albums_entry_view', 'allow_view_to', 2147483647, 0, 0),
('bx_albums_entry_view', 'do_submit', 2147483647, 0, 0),
('bx_albums_entry_view', 'text', 2147483647, 0, 0),
('bx_albums_entry_view', 'title', 2147483647, 0, 0),
('bx_albums_entry_view', 'pictures', 2147483647, 0, 0),
('bx_albums_entry_view', 'added', 2147483647, 1, 1),
('bx_albums_entry_view', 'changed', 2147483647, 1, 2),

('bx_albums_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_albums_entry_delete', 'do_submit', 2147483647, 1, 2),
('bx_albums_entry_delete', 'allow_view_to', 2147483647, 0, 0),
('bx_albums_entry_delete', 'pictures', 2147483647, 0, 0),
('bx_albums_entry_delete', 'text', 2147483647, 0, 0),
('bx_albums_entry_delete', 'title', 2147483647, 0, 0);

-- COMMENTS

INSERT INTO `sys_objects_cmts` (`Name`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Nl2br`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_albums', 'bx_albums_cmts', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-album&id={object_id}', '', 'bx_albums_albums', 'id', 'author', 'title', 'comments', '', ''),
('bx_albums_media', 'bx_albums_cmts_media', 1, 5000, 1000, 1, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-album-media&id={object_id}', '', 'bx_albums_files2albums', 'id', '', 'title', 'comments', '', '');

-- VOTES

INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_albums', 'bx_albums_votes', 'bx_albums_votes_track', '604800', '1', '1', '0', '1', 'bx_albums_albums', 'id', 'author', 'rate', 'votes', '', ''),
('bx_albums_media', 'bx_albums_votes_media', 'bx_albums_votes_media_track', '604800', '1', '1', '0', '1', 'bx_albums_files2albums', 'id', '', 'rate', 'votes', '', '');

-- REPORTS

INSERT INTO `sys_objects_report` (`name`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_albums', 'bx_albums_reports', 'bx_albums_reports_track', '1', 'page.php?i=view-album&id={object_id}', 'bx_albums_albums', 'id', 'author', 'reports', '', '');

-- VIEWS

INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_albums', 'bx_albums_views_track', '86400', '1', 'bx_albums_albums', 'id', 'views', '', ''),
('bx_albums_media', 'bx_albums_views_media_track', '86400', '1', 'bx_albums_files2albums', 'id', 'views', '', '');

-- STUDIO: page & widget

INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_albums', '_bx_albums', '_bx_albums', 'bx_albums@modules/boonex/albums/|std-pi.png');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_albums', '{url_studio}module.php?name=bx_albums', '', 'bx_albums@modules/boonex/albums/|std-wi.png', '_bx_albums', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

