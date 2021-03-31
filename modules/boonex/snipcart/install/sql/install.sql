-- TABLE: entries
CREATE TABLE IF NOT EXISTS `bx_snipcart_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `added` int(11) NOT NULL,
  `changed` int(11) NOT NULL,
  `thumb` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `cat` int(11) NOT NULL,
  `text` text NOT NULL,
  `price` float NOT NULL default '0',
  `weight` float NOT NULL default '0',
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
  `allow_view_to` varchar(16) NOT NULL DEFAULT '3',
  `status` enum('active','hidden') NOT NULL DEFAULT 'active',
  `status_admin` enum('active','hidden','pending') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`id`),
  FULLTEXT KEY `title_text` (`title`,`text`)
);

-- TABLE: settings
CREATE TABLE IF NOT EXISTS `bx_snipcart_settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `author` int(10) unsigned NOT NULL,
  `mode` varchar(8) NOT NULL DEFAULT '',
  `api_key_test` varchar(255) NOT NULL DEFAULT '',
  `api_key_live` varchar(255) NOT NULL DEFAULT '',
  `currency` varchar(4) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `author` (`author`)
);

-- TABLE: storages & transcoders
CREATE TABLE IF NOT EXISTS `bx_snipcart_files` (
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

CREATE TABLE IF NOT EXISTS `bx_snipcart_photos_resized` (
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
CREATE TABLE IF NOT EXISTS `bx_snipcart_cmts` (
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

CREATE TABLE IF NOT EXISTS `bx_snipcart_cmts_notes` (
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
CREATE TABLE IF NOT EXISTS `bx_snipcart_votes` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_snipcart_votes_track` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `author_nip` int(11) unsigned NOT NULL default '0',
  `value` tinyint(4) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `vote` (`object_id`, `author_nip`)
);

CREATE TABLE IF NOT EXISTS `bx_snipcart_reactions` (
  `object_id` int(11) NOT NULL default '0',
  `reaction` varchar(32) NOT NULL default '',
  `count` int(11) NOT NULL default '0',
  `sum` int(11) NOT NULL default '0',
  UNIQUE KEY `reaction` (`object_id`, `reaction`)
);

CREATE TABLE IF NOT EXISTS `bx_snipcart_reactions_track` (
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
CREATE TABLE IF NOT EXISTS `bx_snipcart_views_track` (
  `object_id` int(11) NOT NULL default '0',
  `viewer_id` int(11) NOT NULL default '0',
  `viewer_nip` int(11) unsigned NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`viewer_id`,`viewer_nip`)
);

-- TABLE: metas
CREATE TABLE IF NOT EXISTS `bx_snipcart_meta_keywords` (
  `object_id` int(10) unsigned NOT NULL,
  `keyword` varchar(255) NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `keyword` (`keyword`)
);

CREATE TABLE IF NOT EXISTS `bx_snipcart_meta_locations` (
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

CREATE TABLE IF NOT EXISTS `bx_snipcart_meta_mentions` (
  `object_id` int(10) unsigned NOT NULL,
  `profile_id` int(10) unsigned NOT NULL,
  KEY `object_id` (`object_id`),
  KEY `profile_id` (`profile_id`)
);

-- TABLE: reports
CREATE TABLE IF NOT EXISTS `bx_snipcart_reports` (
  `object_id` int(11) NOT NULL default '0',
  `count` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_snipcart_reports_track` (
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
CREATE TABLE IF NOT EXISTS `bx_snipcart_favorites_track` (
  `object_id` int(11) NOT NULL default '0',
  `author_id` int(11) NOT NULL default '0',
  `date` int(11) NOT NULL default '0',
  KEY `id` (`object_id`,`author_id`)
);

-- TABLE: scores
CREATE TABLE IF NOT EXISTS `bx_snipcart_scores` (
  `object_id` int(11) NOT NULL default '0',
  `count_up` int(11) NOT NULL default '0',
  `count_down` int(11) NOT NULL default '0',
  UNIQUE KEY `object_id` (`object_id`)
);

CREATE TABLE IF NOT EXISTS `bx_snipcart_scores_track` (
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
('bx_snipcart_files', @sStorageEngine, '', 360, 2592000, 3, 'bx_snipcart_files', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0),
('bx_snipcart_photos_resized', @sStorageEngine, '', 360, 2592000, 3, 'bx_snipcart_photos_resized', 'allow-deny', 'jpg,jpeg,jpe,gif,png', '', 0, 0, 0, 0, 0, 0);

INSERT INTO `sys_objects_transcoder` (`object`, `storage_object`, `source_type`, `source_params`, `private`, `atime_tracking`, `atime_pruning`, `ts`) VALUES 
('bx_snipcart_preview', 'bx_snipcart_photos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_snipcart_files";}', 'no', '1', '2592000', '0'),
('bx_snipcart_gallery', 'bx_snipcart_photos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_snipcart_files";}', 'no', '1', '2592000', '0'), 
('bx_snipcart_cover', 'bx_snipcart_photos_resized', 'Storage', 'a:1:{s:6:"object";s:17:"bx_snipcart_files";}', 'no', '1', '2592000', '0');

INSERT INTO `sys_transcoder_filters` (`transcoder_object`, `filter`, `filter_params`, `order`) VALUES 
('bx_snipcart_preview', 'Resize', 'a:3:{s:1:"w";s:3:"300";s:1:"h";s:3:"200";s:11:"crop_resize";s:1:"1";}', '0'),
('bx_snipcart_gallery', 'Resize', 'a:1:{s:1:"w";s:3:"500";}', '0'),
('bx_snipcart_cover', 'Resize', 'a:1:{s:1:"w";s:4:"2000";}', '0');


-- FORMS
INSERT INTO `sys_objects_form`(`object`, `module`, `title`, `action`, `form_attrs`, `table`, `key`, `uri`, `uri_title`, `submit_name`, `params`, `deletable`, `active`, `override_class_name`, `override_class_file`) VALUES 
('bx_snipcart', 'bx_snipcart', '_bx_snipcart_form_entry', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_snipcart_entries', 'id', '', '', 'a:2:{i:0;s:9:"do_submit";i:1;s:10:"do_publish";}', '', 0, 1, 'BxSnipcartFormEntry', 'modules/boonex/snipcart/classes/BxSnipcartFormEntry.php'),
('bx_snipcart_settings', 'bx_snipcart', '_bx_snipcart_form_settings', '', 'a:1:{s:7:"enctype";s:19:"multipart/form-data";}', 'bx_snipcart_settings', 'id', '', '', 'do_submit', '', 0, 1, 'BxSnipcartFormSettings', 'modules/boonex/snipcart/classes/BxSnipcartFormSettings.php');

INSERT INTO `sys_form_displays`(`object`, `display_name`, `module`, `view_mode`, `title`) VALUES 
('bx_snipcart', 'bx_snipcart_entry_add', 'bx_snipcart', 0, '_bx_snipcart_form_entry_display_add'),
('bx_snipcart', 'bx_snipcart_entry_delete', 'bx_snipcart', 0, '_bx_snipcart_form_entry_display_delete'),
('bx_snipcart', 'bx_snipcart_entry_edit', 'bx_snipcart', 0, '_bx_snipcart_form_entry_display_edit'),
('bx_snipcart', 'bx_snipcart_entry_view', 'bx_snipcart', 1, '_bx_snipcart_form_entry_display_view'),

('bx_snipcart_settings', 'bx_snipcart_settings_edit', 'bx_snipcart', 0, '_bx_snipcart_form_settings_display_edit');

INSERT INTO `sys_form_inputs`(`object`, `module`, `name`, `value`, `values`, `checked`, `type`, `caption_system`, `caption`, `info`, `required`, `collapsed`, `html`, `attrs`, `attrs_tr`, `attrs_wrapper`, `checker_func`, `checker_params`, `checker_error`, `db_pass`, `db_params`, `editable`, `deletable`) VALUES 
('bx_snipcart', 'bx_snipcart', 'allow_view_to', '', '', 0, 'custom', '_bx_snipcart_form_entry_input_sys_allow_view_to', '_bx_snipcart_form_entry_input_allow_view_to', '', 1, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'delete_confirm', 1, '', 0, 'checkbox', '_bx_snipcart_form_entry_input_sys_delete_confirm', '_bx_snipcart_form_entry_input_delete_confirm', '_bx_snipcart_form_entry_input_delete_confirm_info', 1, 0, 0, '', '', '', 'Avail', '', '_bx_snipcart_form_entry_input_delete_confirm_error', '', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'do_publish', '_bx_snipcart_form_entry_input_do_publish', '', 0, 'submit', '_bx_snipcart_form_entry_input_sys_do_publish', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'do_submit', '_bx_snipcart_form_entry_input_do_submit', '', 0, 'submit', '_bx_snipcart_form_entry_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'location', '', '', 0, 'location', '_sys_form_input_sys_location', '_sys_form_input_location', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'pictures', 'a:1:{i:0;s:9:"sys_html5";}', 'a:2:{s:10:"sys_simple";s:26:"_sys_uploader_simple_title";s:9:"sys_html5";s:25:"_sys_uploader_html5_title";}', 0, 'files', '_bx_snipcart_form_entry_input_sys_pictures', '_bx_snipcart_form_entry_input_pictures', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'text', '', '', 0, 'textarea', '_bx_snipcart_form_entry_input_sys_text', '_bx_snipcart_form_entry_input_text', '', 1, 0, 2, '', '', '', 'Avail', '', '_bx_snipcart_form_entry_input_text_err', 'XssHtml', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'title', '', '', 0, 'text', '_bx_snipcart_form_entry_input_sys_title', '_bx_snipcart_form_entry_input_title', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_snipcart_form_entry_input_title_err', 'Xss', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'cat', '', '#!bx_snipcart_cats', 0, 'select', '_bx_snipcart_form_entry_input_sys_cat', '_bx_snipcart_form_entry_input_cat', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_snipcart_form_entry_input_cat_err', 'Xss', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'price', '', '', 0, 'text', '_bx_snipcart_form_entry_input_sys_price', '_bx_snipcart_form_entry_input_price', '_bx_snipcart_form_entry_input_price_inf', 1, 0, 0, '', '', '', 'Avail', '', '_bx_snipcart_form_entry_input_price_err', 'Float', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'weight', '', '', 0, 'text', '_bx_snipcart_form_entry_input_sys_weight', '_bx_snipcart_form_entry_input_weight', '_bx_snipcart_form_entry_input_weight_inf', 1, 0, 0, '', '', '', 'Avail', '', '_bx_snipcart_form_entry_input_weight_err', 'Float', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'added', '', '', 0, 'datetime', '_bx_snipcart_form_entry_input_sys_date_added', '_bx_snipcart_form_entry_input_date_added', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),
('bx_snipcart', 'bx_snipcart', 'changed', '', '', 0, 'datetime', '_bx_snipcart_form_entry_input_sys_date_changed', '_bx_snipcart_form_entry_input_date_changed', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0),

('bx_snipcart_settings', 'bx_snipcart', 'mode', '', '#!bx_snipcart_modes', 0, 'select', '_bx_snipcart_form_settings_input_sys_mode', '_bx_snipcart_form_settings_input_mode', '', 1, 0, 0, '', '', '', 'Avail', '', '_bx_snipcart_form_settings_input_mode_err', 'Xss', '', 0, 0),
('bx_snipcart_settings', 'bx_snipcart', 'api_key_test', '', '', 0, 'text', '_bx_snipcart_form_settings_input_sys_api_key_test', '_bx_snipcart_form_settings_input_api_key_test', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_snipcart_settings', 'bx_snipcart', 'api_key_live', '', '', 0, 'text', '_bx_snipcart_form_settings_input_sys_api_key_live', '_bx_snipcart_form_settings_input_api_key_live', '', 0, 0, 0, '', '', '', '', '', '', 'Xss', '', 1, 0),
('bx_snipcart_settings', 'bx_snipcart', 'currency', '', '#!bx_snipcart_currencies', 0, 'select', '_bx_snipcart_form_settings_input_sys_currency', '_bx_snipcart_form_settings_input_currency', '_bx_snipcart_form_settings_input_currency_inf', 1, 0, 0, '', '', '', 'Avail', '', '_bx_snipcart_form_settings_input_currency_err', 'Xss', '', 1, 0),
('bx_snipcart_settings', 'bx_snipcart', 'do_submit', '_bx_snipcart_form_settings_input_do_submit', '', 0, 'submit', '_bx_snipcart_form_settings_input_sys_do_submit', '', '', 0, 0, 0, '', '', '', '', '', '', '', '', 1, 0);

INSERT INTO `sys_form_display_inputs`(`display_name`, `input_name`, `visible_for_levels`, `active`, `order`) VALUES 
('bx_snipcart_entry_add', 'title', 2147483647, 1, 1),
('bx_snipcart_entry_add', 'cat', 2147483647, 1, 2),
('bx_snipcart_entry_add', 'text', 2147483647, 1, 3),
('bx_snipcart_entry_add', 'price', 2147483647, 1, 4),
('bx_snipcart_entry_add', 'weight', 2147483647, 1, 5),
('bx_snipcart_entry_add', 'pictures', 2147483647, 1, 6),
('bx_snipcart_entry_add', 'allow_view_to', 2147483647, 1, 7),
('bx_snipcart_entry_add', 'location', 2147483647, 1, 8),
('bx_snipcart_entry_add', 'do_publish', 2147483647, 1, 9),

('bx_snipcart_entry_delete', 'delete_confirm', 2147483647, 1, 1),
('bx_snipcart_entry_delete', 'do_submit', 2147483647, 1, 2),

('bx_snipcart_entry_edit', 'title', 2147483647, 1, 1),
('bx_snipcart_entry_edit', 'cat', 2147483647, 1, 2),
('bx_snipcart_entry_edit', 'text', 2147483647, 1, 3),
('bx_snipcart_entry_edit', 'price', 2147483647, 1, 4),
('bx_snipcart_entry_edit', 'weight', 2147483647, 1, 5),
('bx_snipcart_entry_edit', 'pictures', 2147483647, 1, 6),
('bx_snipcart_entry_edit', 'allow_view_to', 2147483647, 1, 7),
('bx_snipcart_entry_edit', 'location', 2147483647, 1, 8),
('bx_snipcart_entry_edit', 'do_submit', 2147483647, 1, 9),

('bx_snipcart_entry_view', 'cat', 2147483647, 1, 1),
('bx_snipcart_entry_view', 'weight', 2147483647, 1, 2),
('bx_snipcart_entry_view', 'added', 2147483647, 1, 3),
('bx_snipcart_entry_view', 'changed', 2147483647, 1, 4),

('bx_snipcart_entry_view_full', 'cat', 2147483647, 1, 1),
('bx_snipcart_entry_view_full', 'text', 2147483647, 1, 2),
('bx_snipcart_entry_view_full', 'weight', 2147483647, 1, 3),
('bx_snipcart_entry_view_full', 'added', 2147483647, 1, 4),
('bx_snipcart_entry_view_full', 'changed', 2147483647, 1, 5),

('bx_snipcart_settings_edit', 'mode', 2147483647, 1, 1),
('bx_snipcart_settings_edit', 'api_key_test', 2147483647, 1, 2),
('bx_snipcart_settings_edit', 'api_key_live', 2147483647, 1, 3),
('bx_snipcart_settings_edit', 'currency', 2147483647, 1, 4),
('bx_snipcart_settings_edit', 'do_submit', 2147483647, 1, 5);


-- PRE-VALUES
INSERT INTO `sys_form_pre_lists`(`key`, `title`, `module`, `use_for_sets`, `extendable`) VALUES
('bx_snipcart_cats', '_bx_snipcart_pre_lists_cats', 'bx_snipcart', '0', '1'),
('bx_snipcart_modes', '_bx_snipcart_pre_lists_modes', 'bx_snipcart', '0', '0'),
('bx_snipcart_currencies', '_bx_snipcart_pre_lists_currencies', 'bx_snipcart', '0', '0');

INSERT INTO `sys_form_pre_values`(`Key`, `Value`, `Order`, `LKey`, `LKey2`) VALUES
('bx_snipcart_cats', '', 0, '_sys_please_select', ''),
('bx_snipcart_cats', '1', 1, '_bx_snipcart_cat_automotive_and_industrial', ''),
('bx_snipcart_cats', '2', 2, '_bx_snipcart_cat_beauty_health_food', ''),
('bx_snipcart_cats', '3', 3, '_bx_snipcart_cat_books', ''),
('bx_snipcart_cats', '4', 4, '_bx_snipcart_cat_clothing_shoes_jewelry', ''),
('bx_snipcart_cats', '5', 5, '_bx_snipcart_cat_electronics_and_computers', ''),
('bx_snipcart_cats', '6', 6, '_bx_snipcart_cat_handmade', ''),
('bx_snipcart_cats', '7', 7, '_bx_snipcart_cat_home_garden_tools', ''),
('bx_snipcart_cats', '8', 8, '_bx_snipcart_cat_movies_music_games', ''),
('bx_snipcart_cats', '9', 9, '_bx_snipcart_cat_sports_and_outdoors', ''),
('bx_snipcart_cats', '10', 10, '_bx_snipcart_cat_toys_kids_baby', ''),

('bx_snipcart_modes', '', 0, '_sys_please_select', ''),
('bx_snipcart_modes', 'test', 1, '_bx_snipcart_mode_test', ''),
('bx_snipcart_modes', 'live', 2, '_bx_snipcart_mode_live', ''),

('bx_snipcart_currencies', '', 0, '_sys_please_select', ''),
('bx_snipcart_currencies', 'CAD', 1, 'CAD', 'C&#36;'),
('bx_snipcart_currencies', 'EUR', 2, 'EUR', '&#128;'),
('bx_snipcart_currencies', 'USD', 3, 'USD', '&#36;');


-- COMMENTS
INSERT INTO `sys_objects_cmts` (`Name`, `Module`, `Table`, `CharsPostMin`, `CharsPostMax`, `CharsDisplayMax`, `Html`, `PerView`, `PerViewReplies`, `BrowseType`, `IsBrowseSwitch`, `PostFormPosition`, `NumberOfLevels`, `IsDisplaySwitch`, `IsRatable`, `ViewingThreshold`, `IsOn`, `RootStylePrefix`, `BaseUrl`, `ObjectVote`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldTitle`, `TriggerFieldComments`, `ClassName`, `ClassFile`) VALUES
('bx_snipcart', 'bx_snipcart', 'bx_snipcart_cmts', 1, 5000, 1000, 3, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-snipcart-entry&id={object_id}', '', 'bx_snipcart_entries', 'id', 'author', 'title', 'comments', '', ''),
('bx_snipcart_notes', 'bx_snipcart', 'bx_snipcart_cmts_notes', 1, 5000, 1000, 0, 5, 3, 'tail', 1, 'bottom', 1, 1, 1, -3, 1, 'cmt', 'page.php?i=view-snipcart-entry&id={object_id}', '', 'bx_snipcart_entries', 'id', 'author', 'title', '', 'BxTemplCmtsNotes', '');


-- VOTES
INSERT INTO `sys_objects_vote` (`Name`, `TableMain`, `TableTrack`, `PostTimeout`, `MinValue`, `MaxValue`, `IsUndo`, `IsOn`, `TriggerTable`, `TriggerFieldId`, `TriggerFieldAuthor`, `TriggerFieldRate`, `TriggerFieldRateCount`, `ClassName`, `ClassFile`) VALUES 
('bx_snipcart', 'bx_snipcart_votes', 'bx_snipcart_votes_track', '604800', '1', '1', '0', '1', 'bx_snipcart_entries', 'id', 'author', 'rate', 'votes', '', ''),
('bx_snipcart_reactions', 'bx_snipcart_reactions', 'bx_snipcart_reactions_track', '604800', '1', '1', '1', '1', 'bx_snipcart_entries', 'id', 'author', 'rrate', 'rvotes', 'BxTemplVoteReactions', '');


-- SCORES
INSERT INTO `sys_objects_score` (`name`, `module`, `table_main`, `table_track`, `post_timeout`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_score`, `trigger_field_cup`, `trigger_field_cdown`, `class_name`, `class_file`) VALUES 
('bx_snipcart', 'bx_snipcart', 'bx_snipcart_scores', 'bx_snipcart_scores_track', '604800', '0', 'bx_snipcart_entries', 'id', 'author', 'score', 'sc_up', 'sc_down', '', '');


-- REPORTS
INSERT INTO `sys_objects_report` (`name`, `module`, `table_main`, `table_track`, `is_on`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_snipcart', 'bx_snipcart', 'bx_snipcart_reports', 'bx_snipcart_reports_track', '1', 'page.php?i=view-snipcart-entry&id={object_id}', 'bx_snipcart_entries', 'id', 'author', 'reports', '', '');


-- VIEWS
INSERT INTO `sys_objects_view` (`name`, `table_track`, `period`, `is_on`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_snipcart', 'bx_snipcart_views_track', '86400', '1', 'bx_snipcart_entries', 'id', 'author', 'views', '', '');


-- FAFORITES
INSERT INTO `sys_objects_favorite` (`name`, `table_track`, `is_on`, `is_undo`, `is_public`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_count`, `class_name`, `class_file`) VALUES 
('bx_snipcart', 'bx_snipcart_favorites_track', '1', '1', '1', 'page.php?i=view-snipcart-entry&id={object_id}', 'bx_snipcart_entries', 'id', 'author', 'favorites', '', '');


-- FEATURED
INSERT INTO `sys_objects_feature` (`name`, `module`, `is_on`, `is_undo`, `base_url`, `trigger_table`, `trigger_field_id`, `trigger_field_author`, `trigger_field_flag`, `class_name`, `class_file`) VALUES 
('bx_snipcart', 'bx_snipcart', '1', '1', 'page.php?i=view-snipcart-entry&id={object_id}', 'bx_snipcart_entries', 'id', 'author', 'featured', '', '');


-- CONTENT INFO
INSERT INTO `sys_objects_content_info` (`name`, `title`, `alert_unit`, `alert_action_add`, `alert_action_update`, `alert_action_delete`, `class_name`, `class_file`) VALUES
('bx_snipcart', '_bx_snipcart', 'bx_snipcart', 'added', 'edited', 'deleted', '', ''),
('bx_snipcart_cmts', '_bx_snipcart_cmts', 'bx_snipcart', 'commentPost', 'commentUpdated', 'commentRemoved', 'BxDolContentInfoCmts', '');

INSERT INTO `sys_content_info_grids` (`object`, `grid_object`, `grid_field_id`, `condition`, `selection`) VALUES
('bx_snipcart', 'bx_snipcart_administration', 'id', '', ''),
('bx_snipcart', 'bx_snipcart_common', 'id', '', '');


-- SEARCH EXTENDED
INSERT INTO `sys_objects_search_extended` (`object`, `object_content_info`, `module`, `title`, `active`, `class_name`, `class_file`) VALUES
('bx_snipcart', 'bx_snipcart', 'bx_snipcart', '_bx_snipcart_search_extended', 1, '', ''),
('bx_snipcart_cmts', 'bx_snipcart_cmts', 'bx_snipcart', '_bx_snipcart_search_extended_cmts', 1, 'BxTemplSearchExtendedCmts', '');


-- STUDIO: page & widget
INSERT INTO `sys_std_pages`(`index`, `name`, `header`, `caption`, `icon`) VALUES
(3, 'bx_snipcart', '_bx_snipcart', '_bx_snipcart', 'bx_snipcart@modules/boonex/snipcart/|std-icon.svg');
SET @iPageId = LAST_INSERT_ID();

SET @iParentPageId = (SELECT `id` FROM `sys_std_pages` WHERE `name` = 'home');
SET @iParentPageOrder = (SELECT MAX(`order`) FROM `sys_std_pages_widgets` WHERE `page_id` = @iParentPageId);
INSERT INTO `sys_std_widgets` (`page_id`, `module`, `type`, `url`, `click`, `icon`, `caption`, `cnt_notices`, `cnt_actions`) VALUES
(@iPageId, 'bx_snipcart', 'integrations', '{url_studio}module.php?name=bx_snipcart', '', 'bx_snipcart@modules/boonex/snipcart/|std-icon.svg', '_bx_snipcart', '', 'a:4:{s:6:"module";s:6:"system";s:6:"method";s:11:"get_actions";s:6:"params";a:0:{}s:5:"class";s:18:"TemplStudioModules";}');
INSERT INTO `sys_std_pages_widgets` (`page_id`, `widget_id`, `order`) VALUES
(@iParentPageId, LAST_INSERT_ID(), IF(ISNULL(@iParentPageOrder), 1, @iParentPageOrder + 1));
