
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- TABLE: EVENTS
CREATE TABLE IF NOT EXISTS `bx_events_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `picture` int(11) NOT NULL,
  `cover` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_cat` int(11) NOT NULL,
  `event_desc` text NOT NULL,
  `date_start` int(11) DEFAULT NULL,
  `date_end` int(11) DEFAULT NULL,
  `timezone` varchar(255) DEFAULT NULL,
  `views` int(11) NOT NULL default '0',
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `favorites` int(11) NOT NULL default '0',
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `join_confirmation` tinyint(4) NOT NULL DEFAULT '1',
  `reminder` int(11) NOT NULL DEFAULT '1',
  `allow_view_to` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search_fields` (`event_name`, `event_desc`)
);

-- TABLE: REPEATING INTERVALS
CREATE TABLE IF NOT EXISTS `bx_events_intervals` (
  `interval_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(10) unsigned NOT NULL,
  `repeat_year` int(11) NOT NULL,
  `repeat_month` int(11) NOT NULL,
  `repeat_week_of_month` int(11) NOT NULL,
  `repeat_day_of_month` int(11) NOT NULL,
  `repeat_day_of_week` int(11) NOT NULL,
  `repeat_stop` int(10) unsigned NOT NULL,
  PRIMARY KEY (`interval_id`),
  KEY `event_id` (`event_id`)
) AUTO_INCREMENT=1000;

-- TABLE: STORAGES & TRANSCODERS
CREATE TABLE `bx_events_pics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
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

CREATE TABLE `bx_events_pics_resized` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `profile_id` int(10) unsigned NOT NULL,
  `remote_id` varchar(128) NOT NULL,
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

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_events_cmts` (
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
  KEY `cmt_object_id` (`cmt_object_id`,`cmt_parent_id`),
  FULLTEXT KEY `search_fields` (`cmt_text`)
);

-- TABLE: VIEWS
CREATE TABLE `bx_events_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: VOTES
CREATE TABLE IF NOT EXISTS `bx_events_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_events_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

-- TABLE: REPORTS
CREATE TABLE IF NOT EXISTS `bx_events_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
) ENGINE=MYISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_events_reports_track` (
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

-- TABLE: metas
CREATE TABLE IF NOT EXISTS `bx_events_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `bx_events_meta_locations` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: fans
CREATE TABLE IF NOT EXISTS `bx_events_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initiator` int(11) NOT NULL,
  `content` int(11) NOT NULL,
  `mutual` tinyint(4) NOT NULL,
  `added` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `initiator` (`initiator`,`content`),
  KEY `content` (`content`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: admins
CREATE TABLE IF NOT EXISTS `bx_events_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `group_profile_id` int(10) unsigned NOT NULL,
  `fan_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin` (`group_profile_id`,`fan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- TABLE: favorites
CREATE TABLE `bx_events_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- STORAGES & TRANSCODERS
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_events_pics', @sStorageEngine, '', 360, 2592000, 3, 'bx_events_pics', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_events_pics_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_events_pics_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_events_icon', 'bx_events_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_events_pics";}', 'no', '1', '2592000', '0'),
('bx_events_thumb', 'bx_events_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_events_pics";}', 'no', '1', '2592000', '0'),
('bx_events_avatar', 'bx_events_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_events_pics";}', 'no', '1', '2592000', '0'),
('bx_events_picture', 'bx_events_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_events_pics";}', 'no', '1', '2592000', '0'),
('bx_events_cover', 'bx_events_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_events_pics";}', 'no', '1', '2592000', '0'),
('bx_events_cover_thumb', 'bx_events_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_events_pics";}', 'no', '1', '2592000', '0'),
('bx_events_gallery', 'bx_events_pics_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_events_pics";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_events_icon', 'Resize', 'a:3:{s:1:"w";s:2:"32";s:1:"h";s:2:"32";s:13:"square_resize";s:1:"1";}', '0'),
('bx_events_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0'),
('bx_events_avatar', 'Resize', 'a:3:{s:1:"w";s:2:"96";s:1:"h";s:2:"96";s:13:"square_resize";s:1:"1";}', '0'),
('bx_events_picture', 'Resize', 'a:3:{s:1:"w";s:4:"1024";s:1:"h";s:4:"1024";s:13:"square_resize";s:1:"0";}', '0'),
('bx_events_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0'),
('bx_events_cover_thumb', 'Resize', 'a:3:{s:1:"w";s:2:"48";s:1:"h";s:2:"48";s:13:"square_resize";s:1:"1";}', '0'),
('bx_events_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');

-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_event', 'bx_events', '_bx_events_form_profile', '', 'a:1:{s:7:\"enctype\";s:19:\"multipart/form-data\";}', 'bx_events_data', 'id', '', '', 'do_submit', '', 0, 1, 'BxEventsFormEntry', 'modules/boonex/events/classes/BxEventsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_event', 'bx_event_add', 'bx_events', 0, '_bx_events_form_profile_display_add'),
('bx_event', 'bx_event_delete', 'bx_events', 0, '_bx_events_form_profile_display_delete'),
('bx_event', 'bx_event_edit', 'bx_events', 0, '_bx_events_form_profile_display_edit'),
('bx_event', 'bx_event_edit_cover', 'bx_events', 0, '_bx_events_form_profile_display_edit_cover'),
('bx_event', 'bx_event_view', 'bx_events', 1, '_bx_events_form_profile_display_view'),
('bx_event', 'bx_event_view_full', 'bx_events', 1, '_bx_events_form_profile_display_view_full'),
('bx_event', 'bx_event_invite', 'bx_events', 0, '_bx_events_form_profile_display_invite');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_event', 'bx_events', 'allow_view_to', 3, '', 0, 'custom', '_bx_events_form_profile_input_sys_allow_view_to', '_bx_events_form_profile_input_allow_view_to', '_bx_events_form_profile_input_allow_view_to_desc', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_event', 'bx_events', 'cover', 'a:1:{i:0;s:20:\"bx_events_cover_crop\";}', 'a:1:{s:20:\"bx_events_cover_crop\";s:24:\"_sys_uploader_crop_title\";}', 0, 'files', '_bx_events_form_profile_input_sys_cover', '_bx_events_form_profile_input_cover', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_event', 'bx_events', 'date_end', 0, '', 0, 'datetime', '_bx_events_form_profile_input_sys_date_end', '_bx_events_form_profile_input_date_end', '', 1, 0, 0, '', '', '', 'date_time', '', '_bx_events_form_profile_input_date_end_err', 'DateTimeUtc', '', 1, 0),
('bx_event', 'bx_events', 'date_start', 0, '', 0, 'datetime', '_bx_events_form_profile_input_sys_date_start', '_bx_events_form_profile_input_date_start', '', 1, 0, 0, '', '', '', 'date_time', '', '_bx_events_form_profile_input_date_start_err', 'DateTimeUtc', '', 1, 0),
('bx_event', 'bx_events', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_events_form_profile_input_sys_delete_confirm', '_bx_events_form_profile_input_delete_confirm', '_bx_events_form_profile_input_delete_confirm_info', 1, 0, 0, '', '', '', 'avail', '', '_bx_events_form_profile_input_delete_confirm_error', '', '', 1, 0),
('bx_event', 'bx_events', 'do_submit', '_sys_form_account_input_submit', '', 0, 'submit', '_bx_events_form_profile_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_event', 'bx_events', 'event_cat', '', '#!bx_events_cats', 0, 'select', '_bx_events_form_profile_input_sys_event_cat', '_bx_events_form_profile_input_event_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_events_form_profile_input_event_cat_err', 'Xss', '', 1, 1),
('bx_event', 'bx_events', 'reminder', '', '#!bx_events_reminder', 0, 'select', '_bx_events_form_profile_input_sys_reminder', '_bx_events_form_profile_input_reminder', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 1),
('bx_event', 'bx_events', 'event_desc', '', '', 0, 'textarea', '_bx_events_form_profile_input_sys_event_desc', '_bx_events_form_profile_input_event_desc', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 1),
('bx_event', 'bx_events', 'event_name', '', '', 0, 'text', '_bx_events_form_profile_input_sys_event_name', '_bx_events_form_profile_input_event_name', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_events_form_profile_input_event_name_err', 'Xss', '', 1, 0),
('bx_event', 'bx_events', 'initial_members', '', '', 0, 'custom', '_bx_events_form_profile_input_sys_initial_members', '_bx_events_form_profile_input_initial_members', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('bx_event', 'bx_events', 'join_confirmation', 1, '', 1, 'switcher', '_bx_events_form_profile_input_sys_join_confirm', '_bx_events_form_profile_input_join_confirm', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_event', 'bx_events', 'picture', 'a:1:{i:0;s:22:\"bx_events_picture_crop\";}', 'a:1:{s:22:\"bx_events_picture_crop\";s:24:\"_sys_uploader_crop_title\";}', 0, 'files', '_bx_events_form_profile_input_sys_picture', '_bx_events_form_profile_input_picture', '', 0, 0, 0, '', '', '', '', '', '_bx_events_form_profile_input_picture_err', '', '', 1, 0),
('bx_event', 'bx_events', 'reoccurring', '', '', 0, 'custom', '_bx_events_form_profile_input_sys_reoccurring', '_bx_events_form_profile_input_reoccurring', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 1),
('bx_event', 'bx_events', 'time', '', '', 0, 'custom', '_bx_events_form_profile_input_sys_time', '_bx_events_form_profile_input_time', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_event', 'bx_events', 'timezone', 'UTC', '', 0, 'select', '_bx_events_form_profile_input_sys_timezone', '_bx_events_form_profile_input_timezone', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_event', 'bx_events', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_event_add', 'time', 2147483647, 0, 1),
('bx_event_add', 'delete_confirm', 2147483647, 0, 2),
('bx_event_add', 'cover', 2147483647, 0, 3),
('bx_event_add', 'initial_members', 2147483647, 1, 4),
('bx_event_add', 'picture', 2147483647, 1, 5),
('bx_event_add', 'event_name', 2147483647, 1, 6),
('bx_event_add', 'event_cat', 2147483647, 1, 7),
('bx_event_add', 'event_desc', 2147483647, 1, 8),
('bx_event_add', 'location', 2147483647, 1, 9),
('bx_event_add', 'date_start', 2147483647, 1, 10),
('bx_event_add', 'date_end', 2147483647, 1, 11),
('bx_event_add', 'timezone', 2147483647, 1, 12),
('bx_event_add', 'reoccurring', 2147483647, 1, 13),
('bx_event_add', 'join_confirmation', 2147483647, 1, 14),
('bx_event_add', 'reminder', 2147483647, 1, 15),
('bx_event_add', 'allow_view_to', 2147483647, 1, 16),
('bx_event_add', 'do_submit', 2147483647, 1, 17),

('bx_event_invite', 'initial_members', 2147483647, 1, 1),
('bx_event_invite', 'do_submit', 2147483647, 1, 2),

('bx_event_delete', 'cover', 2147483647, 0, 0),
('bx_event_delete', 'picture', 2147483647, 0, 0),
('bx_event_delete', 'delete_confirm', 2147483647, 1, 0),
('bx_event_delete', 'do_submit', 2147483647, 1, 1),
('bx_event_delete', 'event_name', 2147483647, 0, 2),
('bx_event_delete', 'event_cat', 2147483647, 0, 3),

('bx_event_edit', 'time', 2147483647, 0, 1),
('bx_event_edit', 'initial_members', 2147483647, 0, 2),
('bx_event_edit', 'delete_confirm', 2147483647, 0, 3),
('bx_event_edit', 'cover', 2147483647, 0, 4),
('bx_event_edit', 'picture', 2147483647, 1, 5),
('bx_event_edit', 'event_name', 2147483647, 1, 6),
('bx_event_edit', 'event_cat', 2147483647, 1, 7),
('bx_event_edit', 'event_desc', 2147483647, 1, 8),
('bx_event_edit', 'location', 2147483647, 1, 9),
('bx_event_edit', 'date_start', 2147483647, 1, 10),
('bx_event_edit', 'date_end', 2147483647, 1, 11),
('bx_event_edit', 'timezone', 2147483647, 1, 12),
('bx_event_edit', 'reoccurring', 2147483647, 1, 13),
('bx_event_edit', 'join_confirmation', 2147483647, 1, 14),
('bx_event_edit', 'reminder', 2147483647, 1, 15),
('bx_event_edit', 'allow_view_to', 2147483647, 1, 16),
('bx_event_edit', 'do_submit', 2147483647, 1, 17),

('bx_event_edit_cover', 'allow_view_to', 2147483647, 0, 1),
('bx_event_edit_cover', 'time', 2147483647, 0, 2),
('bx_event_edit_cover', 'reoccurring', 2147483647, 0, 3),
('bx_event_edit_cover', 'join_confirmation', 2147483647, 0, 4),
('bx_event_edit_cover', 'initial_members', 2147483647, 0, 5),
('bx_event_edit_cover', 'timezone', 2147483647, 0, 6),
('bx_event_edit_cover', 'event_desc', 2147483647, 0, 7),
('bx_event_edit_cover', 'date_start', 2147483647, 0, 8),
('bx_event_edit_cover', 'date_end', 2147483647, 0, 9),
('bx_event_edit_cover', 'delete_confirm', 2147483647, 0, 10),
('bx_event_edit_cover', 'event_name', 2147483647, 0, 11),
('bx_event_edit_cover', 'location', 2147483647, 0, 12),
('bx_event_edit_cover', 'picture', 2147483647, 0, 13),
('bx_event_edit_cover', 'event_cat', 2147483647, 0, 14),
('bx_event_edit_cover', 'cover', 2147483647, 1, 15),
('bx_event_edit_cover', 'do_submit', 2147483647, 1, 16),

('bx_event_view', 'allow_view_to', 2147483647, 0, 1),
('bx_event_view', 'reoccurring', 2147483647, 0, 2),
('bx_event_view', 'join_confirmation', 2147483647, 0, 3),
('bx_event_view', 'initial_members', 2147483647, 0, 4),
('bx_event_view', 'delete_confirm', 2147483647, 0, 5),
('bx_event_view', 'picture', 2147483647, 0, 6),
('bx_event_view', 'cover', 2147483647, 0, 7),
('bx_event_view', 'do_submit', 2147483647, 0, 8),
('bx_event_view', 'event_name', 2147483647, 1, 9),
('bx_event_view', 'event_cat', 2147483647, 1, 10),
('bx_event_view', 'date_start', 2147483647, 1, 11),
('bx_event_view', 'date_end', 2147483647, 1, 12),
('bx_event_view', 'time', 2147483647, 1, 13),
('bx_event_view', 'timezone', 0, 1, 14),
('bx_event_view', 'event_desc', 2147483647, 0, 15),

('bx_event_view_full', 'allow_view_to', 2147483647, 0, 1),
('bx_event_view_full', 'reoccurring', 2147483647, 0, 2),
('bx_event_view_full', 'picture', 2147483647, 0, 3),
('bx_event_view_full', 'join_confirmation', 2147483647, 0, 4),
('bx_event_view_full', 'initial_members', 2147483647, 0, 5),
('bx_event_view_full', 'do_submit', 2147483647, 0, 6),
('bx_event_view_full', 'delete_confirm', 2147483647, 0, 7),
('bx_event_view_full', 'cover', 2147483647, 0, 8),
('bx_event_view_full', 'event_name', 2147483647, 1, 9),
('bx_event_view_full', 'event_cat', 2147483647, 1, 10),
('bx_event_view_full', 'date_start', 2147483647, 1, 11),
('bx_event_view_full', 'date_end', 2147483647, 1, 12),
('bx_event_view_full', 'time', 2147483647, 1, 13),
('bx_event_view_full', 'timezone', 0, 1, 14),
('bx_event_view_full', 'event_desc', 2147483647, 0, 15);

-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_reminder', '_bx_events_pre_lists_reminder', 'bx_events', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_reminder', '0', 0, '_bx_events_reminder_none', ''),
('bx_events_reminder', '1', 1, '_bx_events_reminder_1h', ''),
('bx_events_reminder', '2', 2, '_bx_events_reminder_2h', ''),
('bx_events_reminder', '3', 3, '_bx_events_reminder_3h', ''),
('bx_events_reminder', '6', 4, '_bx_events_reminder_6h', ''),
('bx_events_reminder', '12', 5, '_bx_events_reminder_12h', ''),
('bx_events_reminder', '24', 6, '_bx_events_reminder_24h', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_cats', '_bx_events_pre_lists_cats', 'bx_events', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_cats', '', 0, '_sys_please_select', ''),
('bx_events_cats', '1', 1, '_bx_events_cat_Conference', ''),
('bx_events_cats', '2', 2, '_bx_events_cat_Festival', ''),
('bx_events_cats', '3', 3, '_bx_events_cat_Fundraiser', ''),
('bx_events_cats', '4', 4, '_bx_events_cat_Lecture', ''),
('bx_events_cats', '5', 5, '_bx_events_cat_Market', ''),
('bx_events_cats', '6', 6, '_bx_events_cat_Meal', ''),
('bx_events_cats', '7', 7, '_bx_events_cat_Social_Mixer', ''),
('bx_events_cats', '8', 8, '_bx_events_cat_Tour', ''),
('bx_events_cats', '9', 9, '_bx_events_cat_Volunteering', ''),
('bx_events_cats', '10', 10, '_bx_events_cat_Workshop', ''),
('bx_events_cats', '11', 11, '_bx_events_cat_Other', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_repeat_year', '_bx_events_pre_lists_repeat_year', 'bx_events', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_repeat_year', '1', 1, '_bx_events_cat_repeat_year_every_year', ''),
('bx_events_repeat_year', '2', 2, '_bx_events_cat_repeat_year_every_2_years', ''),
('bx_events_repeat_year', '3', 3, '_bx_events_cat_repeat_year_every_3_years', ''),
('bx_events_repeat_year', '4', 4, '_bx_events_cat_repeat_year_every_4_years', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_repeat_month', '_bx_events_pre_lists_repeat_month', 'bx_events', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_repeat_month', '0', 0, '_bx_events_cat_repeat_month_every_month', ''),
('bx_events_repeat_month', '1', 1, '_bx_events_cat_repeat_month_jan', ''),
('bx_events_repeat_month', '2', 2, '_bx_events_cat_repeat_month_feb', ''),
('bx_events_repeat_month', '3', 3, '_bx_events_cat_repeat_month_mar', ''),
('bx_events_repeat_month', '4', 4, '_bx_events_cat_repeat_month_apr', ''),
('bx_events_repeat_month', '5', 5, '_bx_events_cat_repeat_month_may', ''),
('bx_events_repeat_month', '6', 6, '_bx_events_cat_repeat_month_jun', ''),
('bx_events_repeat_month', '7', 7, '_bx_events_cat_repeat_month_jul', ''),
('bx_events_repeat_month', '8', 8, '_bx_events_cat_repeat_month_aug', ''),
('bx_events_repeat_month', '9', 9, '_bx_events_cat_repeat_month_sep', ''),
('bx_events_repeat_month', '10', 10, '_bx_events_cat_repeat_month_oct', ''),
('bx_events_repeat_month', '11', 11, '_bx_events_cat_repeat_month_nov', ''),
('bx_events_repeat_month', '12', 12, '_bx_events_cat_repeat_month_dec', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_repeat_week_of_month', '_bx_events_pre_lists_repeat_week_of_month', 'bx_events', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_repeat_week_of_month', '0', 0, '_bx_events_cat_repeat_week_of_month_every_week', ''),
('bx_events_repeat_week_of_month', '1', 1, '_bx_events_cat_repeat_week_of_month_first_week_of_month', ''),
('bx_events_repeat_week_of_month', '2', 2, '_bx_events_cat_repeat_week_of_month_second_week_of_month', ''),
('bx_events_repeat_week_of_month', '3', 3, '_bx_events_cat_repeat_week_of_month_third_week_of_month', ''),
('bx_events_repeat_week_of_month', '4', 4, '_bx_events_cat_repeat_week_of_month_fourth_week_of_month', ''),
('bx_events_repeat_week_of_month', '5', 5, '_bx_events_cat_repeat_week_of_month_fifth_week_of_month', ''),
('bx_events_repeat_week_of_month', '6', 6, '_bx_events_cat_repeat_week_of_month_sixth_week_of_month', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_repeat_day_of_month', '_bx_events_pre_lists_repeat_day_of_month', 'bx_events', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_repeat_day_of_month', '0', 0, '_bx_events_cat_repeat_day_of_month_every_day', ''),
('bx_events_repeat_day_of_month', '1', 1, '1', ''),
('bx_events_repeat_day_of_month', '2', 2, '2', ''),
('bx_events_repeat_day_of_month', '3', 3, '3', ''),
('bx_events_repeat_day_of_month', '4', 4, '4', ''),
('bx_events_repeat_day_of_month', '5', 5, '5', ''),
('bx_events_repeat_day_of_month', '6', 6, '6', ''),
('bx_events_repeat_day_of_month', '7', 7, '7', ''),
('bx_events_repeat_day_of_month', '8', 8, '8', ''),
('bx_events_repeat_day_of_month', '9', 9, '9', ''),
('bx_events_repeat_day_of_month', '10', 10, '10', ''),
('bx_events_repeat_day_of_month', '11', 11, '11', ''),
('bx_events_repeat_day_of_month', '12', 12, '12', ''),
('bx_events_repeat_day_of_month', '13', 13, '13', ''),
('bx_events_repeat_day_of_month', '14', 14, '14', ''),
('bx_events_repeat_day_of_month', '15', 15, '15', ''),
('bx_events_repeat_day_of_month', '16', 16, '16', ''),
('bx_events_repeat_day_of_month', '17', 17, '17', ''),
('bx_events_repeat_day_of_month', '18', 18, '18', ''),
('bx_events_repeat_day_of_month', '19', 19, '19', ''),
('bx_events_repeat_day_of_month', '20', 20, '20', ''),
('bx_events_repeat_day_of_month', '21', 21, '21', ''),
('bx_events_repeat_day_of_month', '22', 22, '22', ''),
('bx_events_repeat_day_of_month', '23', 23, '23', ''),
('bx_events_repeat_day_of_month', '24', 24, '24', ''),
('bx_events_repeat_day_of_month', '25', 25, '25', ''),
('bx_events_repeat_day_of_month', '26', 26, '26', ''),
('bx_events_repeat_day_of_month', '27', 27, '27', ''),
('bx_events_repeat_day_of_month', '28', 28, '28', ''),
('bx_events_repeat_day_of_month', '29', 29, '29', ''),
('bx_events_repeat_day_of_month', '30', 30, '30', ''),
('bx_events_repeat_day_of_month', '31', 31, '31', '');

INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_events_repeat_day_of_week', '_bx_events_pre_lists_repeat_day_of_week', 'bx_events', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_events_repeat_day_of_week', '0', 0, '_bx_events_cat_repeat_day_of_week_every_day', ''),
('bx_events_repeat_day_of_week', '1', 1, '_bx_events_cat_repeat_day_of_week_mon', ''),
('bx_events_repeat_day_of_week', '2', 2, '_bx_events_cat_repeat_day_of_week_tue', ''),
('bx_events_repeat_day_of_week', '3', 3, '_bx_events_cat_repeat_day_of_week_wed', ''),
('bx_events_repeat_day_of_week', '4', 4, '_bx_events_cat_repeat_day_of_week_thu', ''),
('bx_events_repeat_day_of_week', '5', 5, '_bx_events_cat_repeat_day_of_week_fri', ''),
('bx_events_repeat_day_of_week', '6', 6, '_bx_events_cat_repeat_day_of_week_sat', ''),
('bx_events_repeat_day_of_week', '7', 7, '_bx_events_cat_repeat_day_of_week_sun', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_events', '_bx_events', 'bx_events', 'added', 'edited', 'deleted', '', ''),
('bx_events_cmts', '_bx_events_cmts', 'bx_events', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_events', 'bx_events_administration', 'td`.`id', '', ''),
('bx_events', 'bx_events_common', 'td`.`id', '', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_events', 'bx_events', 'bx_events', '_bx_events_search_extended', 1, '', ''),
('bx_events_cmts', 'bx_events_cmts', 'bx_events', '_bx_events_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');

-- STUDIO PAGE & WIDGET
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_events', '_bx_events', '_bx_events', 'bx_events@modules/boonex/events/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_events', '{url_studio}module.php?name=bx_events', '', 'bx_events@modules/boonex/events/|std-icon.svg', '_bx_events', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

