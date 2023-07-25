-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_polls_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `text` text NOT NULL,
  `cat` int(11) NOT NULL,
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
  `anonymous` tinyint(4) NOT NULL DEFAULT '0',
  `hidden_results` tinyint(4) NOT NULL DEFAULT '0',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `search_fields` (`text`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_subentries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `entry_id` int(11) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `rate` float NOT NULL default '0',
  `votes` int(11) NOT NULL default '0',
  `order` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title` (`title`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_polls_files` (
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

CREATE TABLE IF NOT EXISTS `bx_polls_photos_resized` (
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
CREATE TABLE IF NOT EXISTS `bx_polls_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_polls_cmts_notes` (
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
CREATE TABLE IF NOT EXISTS `bx_polls_votes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_votes_subentries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_votes_subentries_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_reactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_reactions_track` (
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
CREATE TABLE `bx_polls_views_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE `bx_polls_meta_keywords` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE `bx_polls_meta_locations` (
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

CREATE TABLE `bx_polls_meta_mentions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_polls_reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_reports_track` (
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
CREATE TABLE `bx_polls_favorites_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `list_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `id` (`object_id`,`author_id`)
);

CREATE TABLE `bx_polls_favorites_lists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  `allow_view_favorite_list_to` varchar(16) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_polls_scores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_polls_scores_track` (
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
('bx_polls_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_polls_files', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0),
('bx_polls_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_polls_photos_resized', 'allow-deny', '{image}', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_polls_preview', 'bx_polls_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_polls_files";}', 'no', '1', '2592000', '0'),
('bx_polls_gallery', 'bx_polls_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_polls_files";}', 'no', '1', '2592000', '0'),
('bx_polls_cover', 'bx_polls_photos_resized', 'Storage', 'a:1:{s:6:"object";s:14:"bx_polls_files";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_polls_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_polls_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_polls_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0');


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_polls', 'bx_polls', '_bx_polls_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_polls_entries', 'id', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}', 'a:1:{s:14:"checker_helper";s:29:"BxPollsFormEntryCheckerHelper";}', 0, 1, 'BxPollsFormEntry', 'modules/boonex/polls/classes/BxPollsFormEntry.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_polls', 'bx_polls_entry_add', 'bx_polls', 0, '_bx_polls_form_entry_display_add'),
('bx_polls', 'bx_polls_entry_delete', 'bx_polls', 0, '_bx_polls_form_entry_display_delete'),
('bx_polls', 'bx_polls_entry_edit', 'bx_polls', 0, '_bx_polls_form_entry_display_edit'),
('bx_polls', 'bx_polls_entry_view', 'bx_polls', 1, '_bx_polls_form_entry_display_view');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_polls', 'bx_polls', 'cf', '1', '#!sys_content_filter', 0, 'select', '_sys_form_entry_input_sys_cf', '_sys_form_entry_input_cf', '', 0, 0, 0, '', '', '', '', '', '', 'Int', '', 1, 0),
('bx_polls', 'bx_polls', 'allow_view_to', '', '', 0, 'custom', '_bx_polls_form_entry_input_sys_allow_view_to', '_bx_polls_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_polls_form_entry_input_sys_delete_confirm', '_bx_polls_form_entry_input_delete_confirm', '_bx_polls_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_polls_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_polls', 'bx_polls', 'do_publish', '_bx_polls_form_entry_input_do_publish', '', 0, 'submit', '_bx_polls_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'do_submit', '_bx_polls_form_entry_input_do_submit', '', 0, 'submit', '_bx_polls_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'pictures', 'a:1:{i:0;s:9:"sys_html5";}', 'a:1:{s:9:"sys_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_polls_form_entry_input_sys_pictures', '_bx_polls_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'text', '', '', 0, 'textarea', '_bx_polls_form_entry_input_sys_text', '_bx_polls_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_polls_form_entry_input_text_err', 'Xss', '', 1, 0),
('bx_polls', 'bx_polls', 'subentries', '', '', 0, 'custom', '_bx_polls_form_entry_input_sys_subentries', '_bx_polls_form_entry_input_subentries', '', 1, 0, 0, '', '', '', 'AvailSubentries', '', '_bx_polls_form_entry_input_subentries_err', '', '', 1, 0),
('bx_polls', 'bx_polls', 'cat', '', '#!bx_polls_cats', 0, 'select', '_bx_polls_form_entry_input_sys_cat', '_bx_polls_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'avail', '', '_bx_polls_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_polls', 'bx_polls', 'anonymous', 1, '', 1, 'switcher', '_bx_polls_form_profile_input_sys_anonymous', '_bx_polls_form_profile_input_anonymous', '_bx_polls_form_profile_input_anonymous_info', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_polls', 'bx_polls', 'hidden_results', 1, '', 0, 'switcher', '_bx_polls_form_profile_input_sys_hidden_results', '_bx_polls_form_profile_input_hidden_results', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_polls', 'bx_polls', 'added', '', '', 0, 'datetime', '_bx_polls_form_entry_input_sys_date_added', '_bx_polls_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'changed', '', '', 0, 'datetime', '_bx_polls_form_entry_input_sys_date_changed', '_bx_polls_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_polls', 'bx_polls', 'labels', '', '', 0, 'custom', '_sys_form_input_sys_labels', '_sys_form_input_labels', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);


INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_polls_entry_add', 'cat', 2147483647, 1, 1),
('bx_polls_entry_add', 'text', 2147483647, 1, 2),
('bx_polls_entry_add', 'subentries', 2147483647, 1, 3),
('bx_polls_entry_add', 'pictures', 2147483647, 1, 4),
('bx_polls_entry_add', 'allow_view_to', 2147483647, 1, 5),
('bx_polls_entry_add', 'cf', 2147483647, 1, 6),
('bx_polls_entry_add', 'anonymous', 2147483647, 1, 7),
('bx_polls_entry_add', 'hidden_results', 2147483647, 1, 8),
('bx_polls_entry_add', 'location', 2147483647, 1, 9),
('bx_polls_entry_add', 'do_publish', 2147483647, 1, 10),

('bx_polls_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_polls_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_polls_entry_edit', 'cat', 2147483647, 1, 1),
('bx_polls_entry_edit', 'text', 2147483647, 1, 2),
('bx_polls_entry_edit', 'subentries', 2147483647, 1, 3),
('bx_polls_entry_edit', 'pictures', 2147483647, 1, 4),
('bx_polls_entry_edit', 'allow_view_to', 2147483647, 1, 5),
('bx_polls_entry_edit', 'cf', 2147483647, 1, 6),
('bx_polls_entry_edit', 'hidden_results', 2147483647, 1, 7),
('bx_polls_entry_edit', 'location', 2147483647, 1, 8),
('bx_polls_entry_edit', 'do_submit', 2147483647, 1, 9),

('bx_polls_entry_view', 'cat', 2147483647, 1, 1),
('bx_polls_entry_view', 'added', 2147483647, 1, 2),
('bx_polls_entry_view', 'changed', 2147483647, 1, 3);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`) VALUES
('bx_polls_cats', '_bx_polls_pre_lists_cats', 'bx_polls', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_polls_cats', '', 0, '_sys_please_select', ''),
('bx_polls_cats', '1', 1, '_bx_polls_cat_beauty_and_style', ''),
('bx_polls_cats', '2', 2, '_bx_polls_cat_business_and_money', ''),
('bx_polls_cats', '3', 3, '_bx_polls_cat_cars_and_transportation', ''),
('bx_polls_cats', '4', 4, '_bx_polls_cat_computers_and_internet', ''),
('bx_polls_cats', '5', 5, '_bx_polls_cat_food_and_dining', ''),
('bx_polls_cats', '6', 6, '_bx_polls_cat_education', ''),
('bx_polls_cats', '7', 7, '_bx_polls_cat_electronics', ''),
('bx_polls_cats', '8', 8, '_bx_polls_cat_entertainment', ''),
('bx_polls_cats', '9', 9, '_bx_polls_cat_family_and_relationships', ''),
('bx_polls_cats', '10', 10, '_bx_polls_cat_games', ''),
('bx_polls_cats', '11', 11, '_bx_polls_cat_health_and_fitness', ''),
('bx_polls_cats', '12', 12, '_bx_polls_cat_home_and_garden', ''),
('bx_polls_cats', '13', 13, '_bx_polls_cat_news_and_events', ''),
('bx_polls_cats', '14', 14, '_bx_polls_cat_pets', ''),
('bx_polls_cats', '15', 15, '_bx_polls_cat_politics_and_government', ''),
('bx_polls_cats', '16', 16, '_bx_polls_cat_pregnancy_and_parenting', ''),
('bx_polls_cats', '17', 17, '_bx_polls_cat_sports', ''),
('bx_polls_cats', '18', 18, '_bx_polls_cat_misc', ''),
('bx_polls_cats', '19', 19, '_bx_polls_cat_would_you_rather', ''),
('bx_polls_cats', '20', 20, '_bx_polls_cat_yes_or_no', '');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_polls', 'bx_polls', 'bx_polls_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-poll&id={object_id}', '', 'bx_polls_entries', 'id', 'author', 'text', 'comments', '', ''),
('bx_polls_notes', 'bx_polls', 'bx_polls_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-post&id={object_id}', '', 'bx_polls_entries', 'id', 'author', 'text', '', 'BxTemplCmtsNotes', '');


-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `Module`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_polls', 'bx_polls', 'bx_polls_votes', 'bx_polls_votes_track', '604800', '1', '1', '0', '1', 'bx_polls_entries', 'id', 'author', 'rate', 'votes', '', ''),
('bx_polls_subentries', 'bx_polls', 'bx_polls_votes_subentries', 'bx_polls_votes_subentries_track', '604800', '1', '1', '0', '1', 'bx_polls_subentries', 'id', 'author', 'rate', 'votes', 'BxPollsVoteSubentries', 'modules/boonex/polls/classes/BxPollsVoteSubentries.php'),

('bx_polls_reactions', 'bx_polls', 'bx_polls_reactions', 'bx_polls_reactions_track', '604800', '1', '1', '1', '1', 'bx_polls_entries', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');


-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_polls', 'bx_polls', 'bx_polls_scores', 'bx_polls_scores_track', '604800', '0', 'bx_polls_entries', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `object_comment`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_polls', 'bx_polls', 'bx_polls_reports', 'bx_polls_reports_track', '1', 'page.php?i=view-poll&id={object_id}', 'bx_polls_notes', 'bx_polls_entries', 'id', 'author', 'reports', '', '');


-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `module`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_polls', 'bx_polls', 'bx_polls_views_track', '86400', '1', 'bx_polls_entries', 'id', 'author', 'views', '', '');


-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `table_lists`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_polls', 'bx_polls_favorites_track', 'bx_polls_favorites_lists', '1', '1', '1', 'page.php?i=view-poll&id={object_id}', 'bx_polls_entries', 'id', 'author', 'favorites', '', '');


-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_polls', 'bx_polls', '1', '1', 'page.php?i=view-poll&id={object_id}', 'bx_polls_entries', 'id', 'author', 'featured', '', '');


-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_polls', '_bx_polls', 'bx_polls', 'added', 'edited', 'deleted', '', ''),
('bx_polls_cmts', '_bx_polls_cmts', 'bx_polls', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_polls', 'bx_polls_administration', 'id', '', ''),
('bx_polls', 'bx_polls_common', 'id', '', '');


-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_polls', 'bx_polls', 'bx_polls', '_bx_polls_search_extended', 1, '', ''),
('bx_polls_cmts', 'bx_polls_cmts', 'bx_polls', '_bx_polls_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_polls', '_bx_polls', '_bx_polls', 'bx_polls@modules/boonex/polls/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_polls', 'content', '{url_studio}module.php?name=bx_polls', '', 'bx_polls@modules/boonex/polls/|std-icon.svg', '_bx_polls', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
