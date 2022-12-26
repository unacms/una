-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_stream_streams` (
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
  `key` varchar(12) NOT NULL,
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
  `comments` int(11) NOT NULL default '0',
  `reports` int(11) NOT NULL default '0',
  `featured` int(11) NOT NULL default '0',
  `cf` int(11) NOT NULL default '1',
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `allow_comments` tinyint(4) NOT NULL DEFAULT '1',
  `status` enum('active','awaiting','failed','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_stream_covers` (
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

CREATE TABLE IF NOT EXISTS `bx_stream_photos_resized` (
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

CREATE TABLE IF NOT EXISTS `bx_stream_recordings` (
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

CREATE TABLE IF NOT EXISTS `bx_stream_recordings_seq` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `tries` tinyint(4) NOT NULL DEFAULT 0,
  `added` int(10) unsigned NOT NULL,
  `status` enum('pending','processing') NOT NULL DEFAULT 'pending',
  PRIMARY KEY (`id`),
  KEY `content_id` (`content_id`)
);

-- TABLE: comments
CREATE TABLE IF NOT EXISTS `bx_stream_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_stream_cmts_notes` (
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
CREATE TABLE IF NOT EXISTS `bx_stream_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_stream_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_stream_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_stream_reactions_track` (
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
CREATE TABLE `bx_stream_views_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE `bx_stream_meta_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE `bx_stream_meta_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

CREATE TABLE `bx_stream_meta_locations` (
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
CREATE TABLE IF NOT EXISTS `bx_stream_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_stream_reports_track` (
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

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_stream_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_stream_scores_track` (
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
('bx_stream_covers', @sStorageEngine, '', 360, 2592000, 3, 'bx_stream_covers', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),

('bx_stream_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_stream_photos_resized', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),

('bx_stream_recordings', @sStorageEngine, '', 360, 2592000, 3, 'bx_stream_recordings', 'allow-deny', '{video}', '', 0, 0, 0, 0, 0, 0);


INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`, `override_class_name`, `override_class_file`) VALUES 
('bx_stream_preview', 'bx_stream_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_stream_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_stream_gallery', 'bx_stream_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_stream_covers";}', 'no', '1', '2592000', '0', '', ''),
('bx_stream_cover', 'bx_stream_photos_resized', 'Storage', 'a:1:{s:6:"object";s:16:"bx_stream_covers";}', 'no', '1', '2592000', '0', '', '');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_stream_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_stream_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_stream_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0');


-- FORMS: entry (post)
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_stream', 'bx_stream', '_bx_stream_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_stream_streams', 'id', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}', '', 0, 1, 'BxStrmFormEntry', 'modules/boonex/stream/classes/BxStrmFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_stream', 'bx_stream_entry_add', 'bx_stream', 0, '_bx_stream_form_entry_display_add'),
('bx_stream', 'bx_stream_entry_delete', 'bx_stream', 0, '_bx_stream_form_entry_display_delete'),
('bx_stream', 'bx_stream_entry_edit', 'bx_stream', 0, '_bx_stream_form_entry_display_edit'),
('bx_stream', 'bx_stream_entry_view', 'bx_stream', 1, '_bx_stream_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_stream', 'bx_stream', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_stream', 'bx_stream', 'allow_view_to', '', '', 0, 'custom', '_bx_stream_form_entry_input_sys_allow_view_to', '_bx_stream_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_stream_form_entry_input_sys_delete_confirm', '_bx_stream_form_entry_input_delete_confirm', '_bx_stream_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_stream_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_stream', 'bx_stream', 'do_publish', '_bx_stream_form_entry_input_do_publish', '', 0, 'submit', '_bx_stream_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'do_submit', '_bx_stream_form_entry_input_do_submit', '', 0, 'submit', '_bx_stream_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'covers', 'a:1:{i:0;s:15:"bx_stream_html5";}', 'a:1:{s:15:"bx_stream_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_stream_form_entry_input_sys_covers', '_bx_stream_form_entry_input_covers', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'text', '', '', 0, 'textarea', '_bx_stream_form_entry_input_sys_text', '_bx_stream_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_stream_form_entry_input_text_err', 'XssHtml', '', 1, 0),
('bx_stream', 'bx_stream', 'title', '', '', 0, 'text', '_bx_stream_form_entry_input_sys_title', '_bx_stream_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_stream_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_stream', 'bx_stream', 'cat', '', '#!bx_stream_cats', 0, 'select', '_bx_stream_form_entry_input_sys_cat', '_bx_stream_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_stream_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_stream', 'bx_stream', 'multicat', '', '', 0, 'custom', '_bx_stream_form_entry_input_sys_multicat', '_bx_stream_form_entry_input_multicat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_stream_form_entry_input_multicat_err', 'Xss', '', 1, 0),
('bx_stream', 'bx_stream', 'added', '', '', 0, 'datetime', '_bx_stream_form_entry_input_sys_date_added', '_bx_stream_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'changed', '', '', 0, 'datetime', '_bx_stream_form_entry_input_sys_date_changed', '_bx_stream_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'anonymous', '', '', 0, 'switcher', '_sys_form_input_sys_anonymous', '_sys_form_input_anonymous', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_stream', 'bx_stream', 'allow_comments', '1', '', 1, 'switcher', '_bx_stream_form_entry_input_sys_allow_comments', '_bx_stream_form_entry_input_allow_comments', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0);



INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_stream_entry_add', 'delete_confirm', 2147483647, 0, 1),
('bx_stream_entry_add', 'title', 2147483647, 1, 2),
('bx_stream_entry_add', 'cat', 2147483647, 1, 3),
('bx_stream_entry_add', 'text', 2147483647, 1, 4),
('bx_stream_entry_add', 'covers', 2147483647, 1, 5),
('bx_stream_entry_add', 'allow_view_to', 2147483647, 1, 6),
('bx_stream_entry_add', 'cf', 2147483647, 1, 7),
('bx_stream_entry_add', 'location', 2147483647, 1, 8),
('bx_stream_entry_add', 'allow_comments', 192, 1, 9),
('bx_stream_entry_add', 'do_publish', 2147483647, 1, 10),

('bx_stream_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_stream_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_stream_entry_edit', 'do_publish', 2147483647, 0, 1),
('bx_stream_entry_edit', 'delete_confirm', 2147483647, 0, 2),
('bx_stream_entry_edit', 'title', 2147483647, 1, 3),
('bx_stream_entry_edit', 'cat', 2147483647, 1, 4),
('bx_stream_entry_edit', 'text', 2147483647, 1, 5),
('bx_stream_entry_edit', 'covers', 2147483647, 1, 6),
('bx_stream_entry_edit', 'allow_view_to', 2147483647, 1, 7),
('bx_stream_entry_edit', 'cf', 2147483647, 1, 8),
('bx_stream_entry_edit', 'location', 2147483647, 1, 9),
('bx_stream_entry_edit', 'allow_comments', 192, 1, 10),
('bx_stream_entry_edit', 'do_submit', 2147483647, 1, 11),

('bx_stream_entry_view', 'cat', 2147483647, 1, 1),
('bx_stream_entry_view', 'added', 2147483647, 1, 2),
('bx_stream_entry_view', 'changed', 2147483647, 1, 3),
('bx_stream_entry_view', 'published', 192, 1, 4);

-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_stream_cats', '_bx_stream_pre_lists_cats', 'bx_stream', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_stream_cats', '', 0, '_sys_please_select', ''),
('bx_stream_cats', '1', 1, '_bx_stream_cat_Animals_Pets', ''),
('bx_stream_cats', '2', 2, '_bx_stream_cat_Architecture', ''),
('bx_stream_cats', '3', 3, '_bx_stream_cat_Art', ''),
('bx_stream_cats', '4', 4, '_bx_stream_cat_Cars_Motorcycles', ''),
('bx_stream_cats', '5', 5, '_bx_stream_cat_Celebrities', ''),
('bx_stream_cats', '6', 6, '_bx_stream_cat_Design', ''),
('bx_stream_cats', '7', 7, '_bx_stream_cat_DIY_Crafts', ''),
('bx_stream_cats', '8', 8, '_bx_stream_cat_Education', ''),
('bx_stream_cats', '9', 9, '_bx_stream_cat_Film_Music_Books', ''),
('bx_stream_cats', '10', 10, '_bx_stream_cat_Food_Drink', ''),
('bx_stream_cats', '11', 11, '_bx_stream_cat_Gardening', ''),
('bx_stream_cats', '12', 12, '_bx_stream_cat_Geek', ''),
('bx_stream_cats', '13', 13, '_bx_stream_cat_Hair_Beauty', ''),
('bx_stream_cats', '14', 14, '_bx_stream_cat_Health_Fitness', ''),
('bx_stream_cats', '15', 15, '_bx_stream_cat_History', ''),
('bx_stream_cats', '16', 16, '_bx_stream_cat_Holidays_Events', ''),
('bx_stream_cats', '17', 17, '_bx_stream_cat_Home_Decor', ''),
('bx_stream_cats', '18', 18, '_bx_stream_cat_Humor', ''),
('bx_stream_cats', '19', 19, '_bx_stream_cat_Illustrations_Posters', ''),
('bx_stream_cats', '20', 20, '_bx_stream_cat_Kids_Parenting', ''),
('bx_stream_cats', '21', 21, '_bx_stream_cat_Mens_Fashion', ''),
('bx_stream_cats', '22', 22, '_bx_stream_cat_Outdoors', ''),
('bx_stream_cats', '23', 23, '_bx_stream_cat_Photography', ''),
('bx_stream_cats', '24', 24, '_bx_stream_cat_Products', ''),
('bx_stream_cats', '25', 25, '_bx_stream_cat_Quotes', ''),
('bx_stream_cats', '26', 26, '_bx_stream_cat_Science_Nature', ''),
('bx_stream_cats', '27', 27, '_bx_stream_cat_Sports', ''),
('bx_stream_cats', '28', 28, '_bx_stream_cat_Tattoos', ''),
('bx_stream_cats', '29', 29, '_bx_stream_cat_Technology', ''),
('bx_stream_cats', '30', 30, '_bx_stream_cat_Travel', ''),
('bx_stream_cats', '31', 31, '_bx_stream_cat_Weddings', ''),
('bx_stream_cats', '32', 32, '_bx_stream_cat_Womens_Fashion', '');

-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_stream', 'bx_stream', 'bx_stream_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-stream&id={object_id}', '', 'bx_stream_streams', 'id', 'author', 'title', 'comments', '', ''),
('bx_stream_notes', 'bx_stream', 'bx_stream_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-stream&id={object_id}', '', 'bx_stream_streams', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_stream', 'bx_stream_votes', 'bx_stream_votes_track', '604800', '1', '1', '0', '1', 'bx_stream_streams', 'id', 'author', 'rate', 'votes', '', ''),
('bx_stream_reactions', 'bx_stream_reactions', 'bx_stream_reactions_track', '604800', '1', '1', '1', '1', 'bx_stream_streams', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');

-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_stream', 'bx_stream', 'bx_stream_scores', 'bx_stream_scores_track', '604800', '0', 'bx_stream_streams', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_stream', 'bx_stream', 'bx_stream_reports', 'bx_stream_reports_track', '1', 'page.php?i=view-stream&id={object_id}', 'bx_stream_notes', 'bx_stream_streams', 'id', 'author', 'reports', '', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_stream', 'bx_stream_views_track', '86400', '1', 'bx_stream_streams', 'id', 'author', 'views', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_stream', 'bx_stream', '1', '1', 'page.php?i=view-stream&id={object_id}', 'bx_stream_streams', 'id', 'author', 'featured', '', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_stream', '_bx_stream', 'bx_stream', 'added', 'edited', 'deleted', '', ''),
('bx_stream_cmts', '_bx_stream_cmts', 'bx_stream', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_stream', 'bx_stream_administration', 'id', '', ''),
('bx_stream', 'bx_stream_common', 'id', '', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_stream', 'bx_stream', 'bx_stream', '_bx_stream_search_extended', 1, '', ''),
('bx_stream_cmts', 'bx_stream_cmts', 'bx_stream', '_bx_stream_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');

-- LOGS

INSERT INTO `sys_objects_logs` (`object`, `module`, `logs_storage`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_stream_ome_api', 'bx_stream', 'Auto', '_bx_stream_logs_ome_api', 0, '', '');

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_stream', '_bx_stream', '_bx_stream', 'bx_stream@modules/boonex/stream/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_stream', 'content', '{url_studio}module.php?name=bx_stream', '', 'bx_stream@modules/boonex/stream/|std-icon.svg', '_bx_stream', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

