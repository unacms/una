
SET @sStorageEngine = (SELECT `value` FROM `sys_options` WHERE `name` = 'sys_storage_default');

-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_files_main` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `file_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `desc` text NOT NULL,
  `data` text NOT NULL,
  `data_processed` tinyint(4) NOT NULL DEFAULT '0',
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
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  `type` enum('file', 'folder') NOT NULL DEFAULT 'file',
  `parent_folder_id` int(10) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search_fields` (`title`,`desc`,`data`)
);


-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_files_files` (
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

CREATE TABLE IF NOT EXISTS `bx_files_photos_resized` (
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
CREATE TABLE IF NOT EXISTS `bx_files_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_files_cmts_notes` (
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

-- TABLE: bookmarks
CREATE TABLE IF NOT EXISTS `bx_files_bookmarks` (
  `object_id` int(11) NOT NULL default '0',
  `profile_id` int(11) NOT NULL default '0',
  PRIMARY KEY (`object_id`, `profile_id`)
);

-- TABLE: downloading jobs
CREATE TABLE IF NOT EXISTS `bx_files_downloading_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `owner` int(11) NOT NULL,
  `files` MEDIUMTEXT NOT NULL,
  `result` TEXT NOT NULL,
  `started` int(11) NOT NULL,
  PRIMARY KEY (`id`)
);

-- TABLE: votes
CREATE TABLE IF NOT EXISTS `bx_files_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_files_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_files_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_files_reactions_track` (
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
CREATE TABLE `bx_files_views_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE `bx_files_meta_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE `bx_files_meta_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_files_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_files_reports_track` (
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
CREATE TABLE `bx_files_favorites_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `list_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`author_id`)
);

CREATE TABLE `bx_files_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_files_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_files_scores_track` (
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
INSERT INTO `sys_objects_storage` (`object`, `engine`, `params`, `token_life`, `cache_control`, `levels`, `table_files`, `ext_mode`, `ext_allow`, `ext_deny`, `quota_size`, `current_size`, `quota_number`, `current_number`, `max_file_size`, `ts`) VALUES
('bx_files_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_files_files', 'deny-allow', '', '{dangerous}', 0, 0, 0, 0, 0, 0),
('bx_files_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_files_photos_resized', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_files_preview', 'bx_files_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_files_files";}', 'no', '1', '2592000', '0'),
('bx_files_gallery', 'bx_files_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_files_files";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_files_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_files_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0');

-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_files', 'bx_files', '_bx_files_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_files_main', 'id', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}', '', 0, 1, 'BxFilesFormEntry', 'modules/boonex/files/classes/BxFilesFormEntry.php'),
('bx_files_upload', 'bx_files', '_bx_files_form_upload', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_files_main', 'id', '', '', 'do_submit', '', 0, 1, 'BxFilesFormUpload', 'modules/boonex/files/classes/BxFilesFormUpload.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_files', 'bx_files_entry_delete', 'bx_files', 0, '_bx_files_form_entry_display_delete'),
('bx_files', 'bx_files_entry_edit', 'bx_files', 0, '_bx_files_form_entry_display_edit'),
('bx_files', 'bx_files_entry_view', 'bx_files', 1, '_bx_files_form_entry_display_view'),
('bx_files_upload', 'bx_files_entry_upload', 'bx_files', 0, '_bx_files_form_entry_display_add');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_files', 'bx_files', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_files', 'bx_files', 'allow_view_to', '', '', 0, 'custom', '_bx_files_form_entry_input_sys_allow_view_to', '_bx_files_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_files', 'bx_files', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_files_form_entry_input_sys_delete_confirm', '_bx_files_form_entry_input_delete_confirm', '_bx_files_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_files_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_files', 'bx_files', 'do_publish', '_bx_files_form_entry_input_do_publish', '', 0, 'submit', '_bx_files_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_files', 'bx_files', 'do_submit', '_bx_files_form_entry_input_do_submit', '', 0, 'submit', '_bx_files_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_files', 'bx_files', 'attachments', 'a:1:{i:0;s:14:"bx_files_html5";}', 'a:2:{s:15:"bx_files_simple";s:26:"_sys_uploader_simple_title";s:14:"bx_files_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_files_form_entry_input_sys_attachment', '_bx_files_form_entry_input_attachment', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_files_form_entry_input_attachment', '', '', 1, 0),
('bx_files', 'bx_files', 'desc', '', '', 0, 'textarea', '_bx_files_form_entry_input_sys_desc', '_bx_files_form_entry_input_desc', '', 0, 0, 2, '', '', '', '', '', '', 'XssHtml', '', 1, 0),
('bx_files', 'bx_files', 'data', '', '', 0, 'textarea', '_bx_files_form_entry_input_sys_data', '_bx_files_form_entry_input_data', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 0, 0),
('bx_files', 'bx_files', 'title', '', '', 0, 'text', '_bx_files_form_entry_input_sys_title', '_bx_files_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_files_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_files', 'bx_files', 'cat', '', '#!bx_files_cats', 0, 'select', '_bx_files_form_entry_input_sys_cat', '_bx_files_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_files_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_files', 'bx_files', 'added', '', '', 0, 'datetime', '_bx_files_form_entry_input_sys_date_added', '_bx_files_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_files', 'bx_files', 'changed', '', '', 0, 'datetime', '_bx_files_form_entry_input_sys_date_changed', '_bx_files_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_files', 'bx_files', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),

('bx_files_upload', 'bx_files', 'attachments', 'a:1:{i:0;s:14:"bx_files_html5";}', 'a:2:{s:15:"bx_files_simple";s:26:"_sys_uploader_simple_title";s:14:"bx_files_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_files_form_entry_input_sys_attachments', '_bx_files_form_entry_input_attachments', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_files_form_entry_input_attachments', '', '', 1, 0),
('bx_files_upload', 'bx_files', 'cat', '', '#!bx_files_cats', 0, 'select', '_bx_files_form_entry_input_sys_cat', '_bx_files_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_files_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_files_upload', 'bx_files', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_files_upload', 'bx_files', 'allow_view_to', '', '', 0, 'custom', '_bx_files_form_entry_input_sys_allow_view_to', '_bx_files_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_files_upload', 'bx_files', 'do_submit', '_bx_files_form_entry_input_do_submit', '', 0, 'submit', '_bx_files_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_files_upload', 'bx_files', 'profile_id', '0', '', 0, 'hidden', '_bx_files_form_entry_input_sys_profile_id', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 0, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_files_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_files_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_files_entry_edit', 'title', 2147483647, 1, 1),
('bx_files_entry_edit', 'cat', 2147483647, 1, 2),
('bx_files_entry_edit', 'desc', 2147483647, 1, 3),
('bx_files_entry_edit', 'data', 0, 1, 4),
('bx_files_entry_edit', 'attachments', 2147483647, 1, 5),
('bx_files_entry_edit', 'allow_view_to', 2147483647, 1, 6),
('bx_files_entry_edit', 'cf', 2147483647, 1, 7),
('bx_files_entry_edit', 'do_submit', 2147483647, 1, 8),

('bx_files_entry_view', 'cat', 2147483647, 1, 1),
('bx_files_entry_view', 'added', 2147483647, 1, 2),
('bx_files_entry_view', 'changed', 2147483647, 1, 3),

('bx_files_entry_upload', 'profile_id', 2147483647, 1, 1),
('bx_files_entry_upload', 'attachments', 2147483647, 1, 2),
('bx_files_entry_upload', 'cat', 2147483647, 1, 3),
('bx_files_entry_upload', 'allow_view_to', 2147483647, 1, 4),
('bx_files_entry_upload', 'cf', 2147483647, 1, 5),
('bx_files_entry_upload', 'do_submit', 2147483647, 1, 6);

-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_files_cats', '_bx_files_pre_lists_cats', 'bx_files', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_files_cats', '', 0, '_sys_please_select', ''),
('bx_files_cats', '1', 1, '_bx_files_cat_documentation', ''),
('bx_files_cats', '2', 2, '_bx_files_cat_presentation', ''),
('bx_files_cats', '3', 3, '_bx_files_cat_report', ''),
('bx_files_cats', '4', 4, '_bx_files_cat_proposal', ''),
('bx_files_cats', '5', 5, '_bx_files_cat_list', ''),
('bx_files_cats', '6', 6, '_bx_files_cat_record', ''),
('bx_files_cats', '7', 7, '_bx_files_cat_screencast', ''),
('bx_files_cats', '8', 8, '_bx_files_cat_screenshot', ''),
('bx_files_cats', '9', 9, '_bx_files_cat_sketch', ''),
('bx_files_cats', '10', 10, '_bx_files_cat_archive', ''),
('bx_files_cats', '11', 11, '_bx_files_cat_backup', '');

-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_files', 'bx_files', 'bx_files_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-file&id={object_id}', '', 'bx_files_main', 'id', 'author', 'title', 'comments', '', ''),
('bx_files_notes', 'bx_files', 'bx_files_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-file&id={object_id}', '', 'bx_files_main', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');

-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_files', 'bx_files_votes', 'bx_files_votes_track', '604800', '1', '1', '0', '1', 'bx_files_main', 'id', 'author', 'rate', 'votes', '', ''),
('bx_files_reactions', 'bx_files_reactions', 'bx_files_reactions_track', '604800', '1', '1', '1', '1', 'bx_files_main', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');

-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_files', 'bx_files', 'bx_files_scores', 'bx_files_scores_track', '604800', '0', 'bx_files_main', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');

-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_files', 'bx_files', 'bx_files_reports', 'bx_files_reports_track', '1', 'page.php?i=view-file&id={object_id}', 'bx_files_notes', 'bx_files_main', 'id', 'author', 'reports', '', '');

-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_files', 'bx_files_views_track', '86400', '1', 'bx_files_main', 'id', 'author', 'views', '', '');

-- FAVORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `table_lists`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_files', 'bx_files_favorites_track', 'bx_files_favorites_lists', '1', '1', '1', 'page.php?i=view-file&id={object_id}', 'bx_files_main', 'id', 'author', 'favorites', '', '');

-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_files', 'bx_files', '1', '1', 'page.php?i=view-file&id={object_id}', 'bx_files_main', 'id', 'author', 'featured', '', '');

-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_files', '_bx_files', 'bx_files', 'added', 'edited', 'deleted', '', ''),
('bx_files_cmts', '_bx_files_cmts', 'bx_files', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_files', 'bx_files_administration', 'id', '', ''),
('bx_files', 'bx_files_common', 'id', '', '');

-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_files', 'bx_files', 'bx_files', '_bx_files_search_extended', 1, '', ''),
('bx_files_cmts', 'bx_files_cmts', 'bx_files', '_bx_files_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');

-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_files', '_bx_files', '_bx_files', 'bx_files@modules/boonex/files/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_files', 'content', '{url_studio}module.php?name=bx_files', '', 'bx_files@modules/boonex/files/|std-icon.svg', '_bx_files', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));

